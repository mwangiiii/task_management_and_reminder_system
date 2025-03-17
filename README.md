# Task Management and Reminder System

A robust and user-friendly **Task Management and Reminder System** built with **Laravel** and **Tailwind CSS**. This system allows users to create, manage, and track tasks with features like task categorization, priority settings, alerts, and file uploads. It also supports parent-child task relationships and budget tracking.

---

## Features

- **Task Creation**: Create tasks with details like name, description, due date, category, priority, and alerts.
- **Parent-Child Tasks**: Organize tasks hierarchically with parent-child relationships.
- **Budget Tracking**: Set budgets for parent tasks and track costs for child tasks.
- **Alerts**: Set multiple alerts for tasks to receive timely reminders.
- **File Uploads**: Attach files to tasks for better organization.
- **Task Status**: Track task progress with statuses like "Pending," "In Progress," and "Completed."
- **Responsive Design**: Built with Tailwind CSS for a seamless experience across devices.

---

## Installation

Follow these steps to set up the project locally:

### Prerequisites

- PHP >= 8.0
- Composer
- MySQL
- Node.js and NPM

### Steps

1. **Clone the repository**:
   ```bash
   git clone https://github.com/your-username/task-management-system.git
   cd task-management-system
   
2. **Install PHP dependencies:**:
   ```bash
   composer install
   
3. **Install JavaScript dependencies**:
   ```bash
   npm install
   
4. **Set up the environment file**:
   ``bash
   cp .env.example .env

5. **Update the .env file with your database credentials:**
   ```bash
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=task_management
    DB_USERNAME=root
    DB_PASSWORD=your_password

6. **Run migrations:**
   ```bash
   php artisan migrate

7. **Seed the database**
   ```bash
   php artisan db:seed

8. **Compile assets:**
   ```bash
   npm run dev

9. **Start the development server:**
   ```bash
   php artisan serve

10. **Access the application:**
-    Open your browser and navigate to http://localhost:8000.

  # Usage

## Creating a Task
- Navigate to the **Tasks** page.
- Click on **Create Task**.
- Fill in the task details (name, description, due date, category, priority, etc.).
- Add alerts and upload files if needed.
- Click **Save**.

## Starting a Task
- Click the **Start Task** button on the task details page. The task status will change to "In Progress," and the start time will be recorded.

## Completing a Task
- Click the **Complete Task** button on the task details page. The task status will change to "Completed," and the completion time will be recorded.

## Managing Parent-Child Tasks
- Create a parent task and add child tasks under it. The system will track the budget and costs for parent tasks.

# Contributing
We welcome contributions! Here’s how you can contribute:

1. **Fork the repository**.
2. Create a new branch:
   ```bash
   git checkout -b feature/your-feature-name
