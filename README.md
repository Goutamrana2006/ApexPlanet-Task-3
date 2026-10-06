# ApexPlanet Task 2 - CRUD Application

## Project Overview

A PHP and MySQL-based web application with user authentication and CRUD functionality.

## Technologies Used

- PHP
- MySQL
- HTML5
- CSS3
- XAMPP
- Git & GitHub

## Features

- User Registration
- Secure Password Hashing
- User Login and Logout
- Session Management
- Create Posts
- View Posts
- Update Posts
- Delete Posts

## Database

### Database Name
`blog`

### Users Table

| Column | Type |
|---|---|
| id | INT |
| username | VARCHAR(100) |
| password | VARCHAR(255) |

### Posts Table

| Column | Type |
|---|---|
| id | INT |
| title | VARCHAR(255) |
| content | TEXT |
| created_at | TIMESTAMP |

## Project Structure

```text
ApexPlanet-Task-2/
├── config/
│   └── database.php
├── auth/
│   ├── register.php
│   ├── login.php
│   └── logout.php
├── posts/
│   ├── create.php
│   ├── index.php
│   ├── edit.php
│   └── delete.php
├── index.php
├── README.md
└── style.css