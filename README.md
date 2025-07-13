
# Simione – Collaborative Todo List App

**Simione** is a collaborative todo list web application built using **PHP**, **MySQL**, and **XAMPP**. It allows users to create multiple task lists, manage tasks with deadlines, and invite others to collaborate on lists using a shareable link. This project was developed as part of a PHP school requirement.

---

## Features

- User registration and login
- Create, view, and manage multiple todo lists
- Add tasks with **due date** and **time**
- Mark tasks as done (checkbox with line-through effect)
- Highlight overdue and upcoming tasks
- Delete tasks or entire lists
- Invite collaborators using a shareable link
- Collaborators can view, complete, and leave shared lists
- Leave button for collaborators only
- Clickable logo to return to dashboard
- Copy-to-clipboard functionality for invite links
- Error page for unauthorized or invalid actions

---

## Tech Stack

- **Backend:** PHP 8.2
- **Frontend:** HTML5, CSS3, minimal JS
- **Database:** MySQL
- **Local Server:** XAMPP / Apache

---

## 🛠️ Setup Instructions

1. **Clone or download** this repository into your XAMPP `htdocs` folder:
- C:\xampp\htdocs\todo-app
2. **Import the SQL database**:
- Open **phpMyAdmin**
- Create a new database with the name "todo_app".
- Run the provided SQL query(sql/query.sql)
3. **Start Apache and MySQL** in XAMPP
4. Open your browser and go to: [localhost](http://localhost/todo/)
