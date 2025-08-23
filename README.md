# Web Server Dashboard

A modern, feature-rich dashboard for managing local web development projects. This dashboard provides an elegant interface to view, manage, and interact with your projects, complete with system monitoring, GitHub integration, and powerful AI-driven code analysis features.

![Dashboard Screenshot](https://placehold.co/800x400/DBEAFE/3B82F6?text=Dashboard+UI)

## ✨ Key Features

This dashboard is more than just a list of folders. It's a comprehensive toolkit designed to streamline your local development workflow.

### 🗂️ Dynamic Project Cards
- **Automatic Discovery:** Automatically detects and displays all project folders in your web root.
- **Custom Logos:** Displays a project-specific logo (`logo.png`) if available, otherwise creates a stylish placeholder.
- **GitHub Integration:** Fetches and displays repository descriptions and topics (tags) directly on the project card.
- **Flexible Links:** Provides buttons to visit the local site, an external live URL, and the GitHub repository.

### 🤖 AI-Powered Code Assistant (Powered by Gemini)
- **Codebase Chat:** Open a chat interface to ask questions about a specific project's codebase. The AI has context of the project's file structure and the content of key files.
- **Code Analysis & Refactoring:** Select any file within a project and click "Analyze & Refactor File." The AI will perform a code review, suggest improvements, and provide a refactored version of the code alongside a clear explanation of the changes.

### 🖥️ System Monitoring with Glances
- **On-Page Stats:** An optional, clean display of real-time server stats (CPU, Memory, Disk Usage) at the top of the dashboard.
- **Full Glances Modal:** If you run a full Glances instance, you can add its URL in the settings to open the complete Glances web UI in a convenient pop-up modal directly from the dashboard header.

### ⚙️ Comprehensive Settings Panel
- **Drag-and-Drop Reordering:** Easily reorder your projects by dragging and dropping them in the settings panel.
- **Hide Projects:** Declutter your view by hiding specific projects from the main grid.
- **Custom Metadata:** Set custom display names, descriptions, logos, and links for each project.
- **API Key Management:** Securely store your Gemini and GitHub API keys.

### 🗄️ Backups
- **Local Project Backup:** Create and download a `.zip` archive of all your project folders with a single click.
- **GitHub Repositories Backup:** Back up all of your personal GitHub repositories into a single `.zip` file (requires a GitHub Personal Access Token).

---

## 🚀 Setup and Requirements

The dashboard is designed to be simple to set up. It consists of a main `index.html` file and two server-side PHP scripts.

### File Structure
```
/
├── index.html            # The main dashboard file
├── browser.php           # REQUIRED: Handles server-side logic
├── glances.php           # OPTIONAL: For on-page system stats
├── settings.json         # Automatically created to store your settings
├── your-project-1/
│   └── ...
└── your-project-2/
    └── ...
```

### `browser.php` (Required)
This is the core server-side engine of the dashboard. It is **required** for the dashboard to function. Its responsibilities include:
- Listing project folders and files.
- Reading file contents for the AI assistant.
- Saving your settings to `settings.json`.
- Creating local project backups.

**You must have this file in the same directory as `index.html`.**

### `glances.php` (Optional)
This script acts as a simple proxy to a running [Glances](https://nicolargo.github.io/glances/) instance on your server. It is **only required if you want to see the on-page system statistics** (CPU, Memory, Disk).

To use it:
1.  Ensure you have Glances installed and running in web server mode (`glances -w`).
2.  Place the `glances.php` file in the same directory as `index.html`.
3.  Enable the "On-Page System Monitor" in the dashboard's settings.

If you do not need the on-page stats, you do not need this file. You can still use the "External Glances URL" feature to link to your Glances instance.

---

## 🔧 Configuration

All configuration is handled through the user-friendly settings panel. Click the **gear icon** in the header to get started.

1.  **General Settings:**
    - **Glances:** Enable or disable the on-page monitor and provide the URL for your external Glances instance.
    - **Gemini API Key:** Required for all AI features ("Ask Codebase", "Analyze & Refactor"). Your key is saved to `settings.json` on the server and also stored in your browser's local storage.
    - **GitHub Token:** A GitHub Personal Access Token is required for fetching repository details (to avoid rate limits) and for the GitHub repositories backup feature.

2.  **Website Settings:**
    - Drag and drop projects to set your preferred order.
    - Use the input fields for each project to customize its name, logo, description, and links.
    - Check the "Hide this folder" box to remove a project from the dashboard view without deleting it.

3.  **Backup Settings:**
    - No configuration is needed here, but the GitHub backup feature requires the GitHub Token to be set in the General tab.

Click **"Save and Close"** to apply your changes. Your configuration will be saved in a `settings.json` file in the root directory.
