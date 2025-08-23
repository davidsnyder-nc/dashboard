# Local Server Dashboard

A modern, feature-rich dashboard for managing your local web development projects. This dashboard provides a clean and intuitive interface to view, manage, and interact with your local websites, integrating powerful features like GitHub data synchronization, AI-powered codebase analysis, and comprehensive backup utilities.

![Dashboard Screenshot](https://placehold.co/800x500/1e293b/ffffff?text=Dashboard+UI)

## ✨ Features

* **Project Discovery:** Automatically scans your local server's root directory to find and display all your project folders.
* **Dynamic Project Cards:** Each project is displayed with a logo, name, description, and technology tags for easy identification.
* **GitHub Integration:**
    * **Sync Descriptions & Topics:** Automatically fetches repository descriptions and topics (used as tags) from GitHub for linked projects.
    * **Repository Backup:** Download a complete backup of all your personal GitHub repositories as a single `.zip` file, powered by [GitVault](https://gitvault.app/).
* **AI-Powered Code Assistant:**
    * **Codebase Chat:** Open a chat interface to ask questions about a specific project's codebase using the Gemini API.
    * **Code Refactoring:** Analyze and refactor individual files with AI-powered suggestions and explanations.
* **Comprehensive Settings Panel:**
    * **Drag-and-Drop Reordering:** Easily customize the order of your projects.
    * **Hide/Show Projects:** Toggle the visibility of projects in the dashboard.
    * **Customization:** Set custom display names, descriptions, logo paths, and external links for each project.
    * **API Key Management:** Securely store your Gemini and GitHub API tokens.
* **Local Backup:** Create and download a `.zip` archive of all your local project folders.
* **Responsive Design:** A clean, modern UI built with Tailwind CSS that looks great on all devices.
* **Dark/Light Mode:** Switch between themes to suit your preference.

## 🛠️ Tech Stack

* **Frontend:** HTML5, Tailwind CSS, Vanilla JavaScript
* **Libraries:**
    * [SortableJS](https://github.com/SortableJS/Sortable) for drag-and-drop functionality.
    * [JSZip](https://stuk.github.io/jszip/) for creating `.zip` archives in the browser.
    * [FileSaver.js](https://github.com/eligrey/FileSaver.js/) for saving generated files.
* **Backend (Required):** A simple PHP backend (`browser.php`) is required to handle file system operations.

## 🚀 Getting Started

### Prerequisites

* A local web server environment (e.g., XAMPP, MAMP, WAMP, or a simple PHP server).
* PHP installed on your server.

### Installation

1.  **Clone the repository** or download the files to the root directory of your local web server.
    ```bash
    git clone [https://github.com/your-username/your-repo-name.git](https://github.com/your-username/your-repo-name.git) /path/to/your/www/root
    ```
2.  **Place your projects** in the same root directory. The dashboard will automatically detect them.
3.  **Open the dashboard** in your web browser by navigating to your localhost address (e.g., `http://localhost/`).

### Configuration

The dashboard uses a `settings.json` file in the root directory to store your preferences. You can manage most settings through the UI, but here is an overview of the structure:

```json
{
  "theme": "dark",
  "geminiApiKey": "YOUR_GEMINI_API_KEY",
  "githubToken": "YOUR_GITHUB_PERSONAL_ACCESS_TOKEN",
  "chatbotContext": "Custom context for the server assistant...",
  "websitesOrder": [
    "project-alpha",
    "project-gamma",
    "project-beta"
  ],
  "websites": {
    "project-alpha": {
      "displayName": "Project Alpha",
      "description": "A custom description for this project.",
      "logoPath": "/project-alpha/images/logo.svg",
      "githubLink": "[https://github.com/user/project-alpha](https://github.com/user/project-alpha)",
      "isHidden": false
    },
    "project-beta": {
      "isHidden": true
    }
  }
}
```

* **API Keys:** To use the GitHub integration and AI features, you must add your **GitHub Personal Access Token** and **Google Gemini API Key** in the `Settings > General` tab. These are saved to `settings.json` and also stored in your browser's local storage.

## ⚙️ Backend API (`browser.php`)

This dashboard requires a simple PHP backend script named `browser.php` to be present in the same directory. This script is responsible for securely interacting with the server's file system.

The script must handle the following GET requests:

* `?action=list&path=/`: Lists folders in the root directory.
* `?action=list_recursive&path=/project-name/`: Lists all files recursively within a specific project.
* `?action=get_content&path=/project-name/file.js`: Retrieves the content of a specific file.
* `?action=backup_all`: Creates a `.zip` archive of all project folders and initiates a download.

The script must also handle a POST request to save the `settings.json` file.

## 🤝 Contributing

Contributions, issues, and feature requests are welcome! Feel free to check the [issues page](https://github.com/your-username/your-repo-name/issues).

## 📄 License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
