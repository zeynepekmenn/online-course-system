# Student Course Management System

A web-based course management system developed using PHP and MySQL.

## Features

### Admin Features
- Admin login system
- Add new courses
- Edit courses
- Delete courses
- Dashboard statistics
- Manage all course information

### Student Features
- Student registration and login
- View available courses
- Enroll in courses
- View enrolled courses
- Unenroll from courses

## Technologies Used

- PHP
- MySQL
- HTML5
- CSS3
- XAMPP / AMMPS
- Git & GitHub

## Project Structure

```bash
online_course/
│
├── index.php
├── login.php
├── register.php
├── logout.php
├── admin_dashboard.php
├── add_course.php
├── edit_course.php
├── delete_course.php
├── courses.php
├── my_courses.php
├── enroll.php
├── unenroll.php
├── db.php
├── style.css
└── README.md
```

## Installation

1. Clone the repository

```bash
git clone https://github.com/YOUR_USERNAME/YOUR_REPOSITORY.git
```

2. Move the project folder into:

```bash
htdocs
```

or

```bash
www
```

3. Create a MySQL database named:

```bash
online_course
```

4. Import the SQL tables using phpMyAdmin.

5. Start Apache and MySQL.

6. Open in browser:

```bash
http://localhost/online_course
```

## User Roles

### Admin
Can manage courses and monitor the system.

### Student
Can enroll and manage enrolled courses.

## Future Improvements

- Course search
- Responsive mobile design
- Profile page
- Course details page
- Email verification