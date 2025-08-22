# Localhost Dashboard

A web-based dashboard for managing and exploring projects on your local Debian server.  
Built with **Tailwind CSS**, **JavaScript**, and a lightweight PHP backend.

---

## Features

### 📂 Project Browser
- Automatically scans server folders (excluding system directories like `phpmyadmin` and `images`).
- Displays projects as interactive cards with:
  - Logo or placeholder
  - Name and description
  - Technology tags
  - Links (Local, External, GitHub)

### ⚙️ Dashboard Settings
- Drag-and-drop ordering of projects (SortableJS).
- Show/hide specific project folders.
- Edit project metadata: display name, description, logo, external link, GitHub link.
- Restore default settings with one click.
- Dark/light theme toggle.
- Settings saved to `settings.json` automatically on the server.

### 🤖 AI Integrations
- **Gemini Chatbot Assistant**
  - General server Q&A.
  - Project-specific "Ask Codebase" chat with file browser and content viewer.
- **Code Refactoring**
  - Analyze and refactor individual project files with AI.
  - Provides improved code and explanations in a side-by-side modal.
- **Project Summarization**
  - AI generates concise project descriptions and tags across all folders.

### 💾 Backup System
- One-click backup of all project folders into a `.zip` archive.
- Excludes server config files and hidden directories.

### 🖥️ UI/UX
- Modern, responsive interface with Tailwind CSS.
- Smooth animations and transitions.
- Card-based layout optimized for desktop and mobile.
- Custom scrollbars for a cleaner look.

---

## Requirements

- PHP-enabled web server (tested on Debian).
- Browser with modern JavaScript support.
- Google Gemini API key (optional, for AI features).

---

## Setup

1. Place files into your server’s web root.
2. Open the dashboard in your browser (e.g., `http://localhost/`).
3. Configure projects and settings via the **Settings** panel.
4. (Optional) Enter your Gemini API key for AI-powered features.

---

## Notes

- `settings.json` is created automatically when saving settings.  
- Do **not** commit `settings.json` to GitHub if it contains your API key — add it to `.gitignore`.  
- Safe to publish `index.html` and `browser.php` without credentials.

---
