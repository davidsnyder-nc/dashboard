<?php
// Set the content type to JSON for all responses.
header('Content-Type: application/json');

// --- Handle POST requests for saving settings ---
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['action']) && $input['action'] === 'save_settings') {
    $settings_file = __DIR__ . '/settings.json';
    
    // Attempt to write the settings to the file.
    if (file_put_contents($settings_file, json_encode($input['settings'], JSON_PRETTY_PRINT))) {
        echo json_encode(['success' => true]);
    } else {
        // If writing fails, send a server error response.
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['success' => false, 'error' => 'Unable to write to settings.json. Check file permissions.']);
    }
    // Stop the script after handling the save action.
    exit;
}


// --- Handle GET requests for file browsing ---

// Get the requested path and action from the frontend.
$relativePath = isset($_GET['path']) ? $_GET['path'] : '/';
$action = isset($_GET['action']) ? $_GET['action'] : 'list';

// --- Security and Path Setup ---
$rootDir = __DIR__;
// Create the absolute path on the server.
$relativePath = urldecode($relativePath); // Decode the URL-encoded path
$absolutePath = realpath($rootDir . '/' . $relativePath);

// Prevent browsing outside the root directory (directory traversal attack).
if (!$absolutePath || strpos($absolutePath, $rootDir) !== 0) {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['error' => 'Access Denied']);
    exit;
}

// Check if the file/directory exists.
if (!file_exists($absolutePath)) {
    header('HTTP/1.1 404 Not Found');
    echo json_encode(['error' => 'File or directory not found']);
    exit;
}

// --- Logic ---
switch ($action) {
    case 'backup_all':
        backupAllProjects($rootDir);
        break;
    case 'list_recursive':
        listRecursive($absolutePath);
        break;
    case 'get_content':
        getFileContentAsJson($absolutePath);
        break;
    case 'list':
        listFilesAsJson($absolutePath);
        break;
    default:
        listFilesAsJson($absolutePath);
        break;
}

/**
 * Creates a zip archive of all project folders and sends it for download.
 * @param string $rootDir The root directory of the projects.
 */
function backupAllProjects($rootDir) {
    if (!class_exists('ZipArchive')) {
        header('HTTP/1.1 500 Internal Server Error');
        echo 'ZipArchive class not found. Please ensure the PHP zip extension is enabled.';
        exit;
    }

    $zipFileName = 'web.server_backup_' . date('Y-m-d') . '.zip';
    $zipFilePath = sys_get_temp_dir() . '/' . $zipFileName;
    $zip = new ZipArchive();

    if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => 'Cannot create zip archive.']);
        exit;
    }

    // Get all directories in the root
    $directories = scandir($rootDir);
    $excluded_dirs = ['.', '..', 'phpmyadmin', 'images']; // Add any other folders to exclude

    foreach ($directories as $dir) {
        $dirPath = $rootDir . '/' . $dir;
        if (is_dir($dirPath) && !in_array($dir, $excluded_dirs)) {
            // Add directory and its contents recursively
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($dirPath, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST
            );

            $zip->addEmptyDir($dir);

            foreach ($files as $file) {
                $filePath = $file->getRealPath();
                $relativePath = $dir . '/' . str_replace($dirPath . '/', '', $filePath);
                
                if ($file->isDir()) {
                    $zip->addEmptyDir($relativePath);
                } else if ($file->isFile()) {
                    $zip->addFile($filePath, $relativePath);
                }
            }
        }
    }

    $zip->close();

    // Send the file for download
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
    header('Content-Length: ' . filesize($zipFilePath));
    header('Pragma: no-cache'); 
    header('Expires: 0');
    readfile($zipFilePath);

    // Clean up the temporary file
    unlink($zipFilePath);
    exit;
}


/**
 * Lists the contents of a directory recursively and returns a JSON response.
 * @param string $directoryPath The absolute path to the directory.
 */
function listRecursive($directoryPath) {
    if (!is_dir($directoryPath)) {
        echo json_encode(['error' => 'Not a directory']);
        exit;
    }
    $items = [];
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directoryPath, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            // Get path relative to the initial directory to keep it clean.
            $relativePath = str_replace($directoryPath . DIRECTORY_SEPARATOR, '', $file->getRealPath());
            $items[] = [
                'name' => $relativePath,
                'type' => 'file'
            ];
        }
    }
    echo json_encode($items);
}

/**
 * Lists the contents of a directory (non-recursively) as a JSON response.
 * @param string $directoryPath The absolute path to the directory.
 */
function listFilesAsJson($directoryPath) {
    if (!is_dir($directoryPath)) {
        echo json_encode(['error' => 'Not a directory']);
        exit;
    }

    $items = [];
    $files = scandir($directoryPath);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
             continue;
        }
        $itemPath = $directoryPath . '/' . $file;
        if (is_readable($itemPath)) {
            $items[] = [
                'name' => $file,
                'type' => is_dir($itemPath) ? 'folder' : 'file',
            ];
        }
    }
    echo json_encode($items);
}

/**
 * Gets the content of a file and returns it within a JSON object.
 * @param string $filePath The absolute path to the file.
 */
function getFileContentAsJson($filePath) {
    if (!is_file($filePath) || !is_readable($filePath)) {
        header('HTTP/1.1 404 Not Found');
        echo json_encode(['error' => 'File not found or not readable']);
        return;
    }

    $content = file_get_contents($filePath);

    if ($content === false) {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => 'Could not read file content.']);
        return;
    }
    
    // Ensure content is valid UTF-8, as json_encode requires it.
    if (!mb_check_encoding($content, 'UTF-8')) {
        // Attempt to convert from a common encoding.
        $content = mb_convert_encoding($content, 'UTF-8', 'ISO-8859-1');
        // If it's still not valid, it might be a binary file.
        if (!mb_check_encoding($content, 'UTF-8')) {
             echo json_encode(['success' => true, 'content' => '[Binary file content not displayed]']);
             return;
        }
    }

    echo json_encode(['success' => true, 'content' => $content]);
}
?>
