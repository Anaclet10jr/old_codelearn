# CodeLearn – E-Learning System

A complete, ready-to-run e-learning platform for learning programming.
Built with **PHP + SQLite** (no database server or Composer needed).

## Courses included (8 languages)

| Course | Lessons | Live code editor |
|---|---|---|
| HTML | 5 | yes |
| CSS | 4 | yes |
| JavaScript | 5 | yes |
| PHP | 4 | – |
| Python | 5 | – |
| Java | 4 | – |
| C | 5 | – |
| SQL | 4 | – |

Each course has a final quiz (5 questions, pass mark 60%). Students who finish every lesson **and** pass the quiz can print a certificate.

## Features

- Student registration, login, logout (passwords hashed with `password_hash`)
- Course catalogue with search and level filter, dark / light theme
- Lessons with copy-able code samples; HTML/CSS/JS lessons have an in-browser **"Try it yourself"** editor (sandboxed iframe)
- Progress tracking per lesson, "continue where you left off"
- Auto-graded quizzes with explanations and retakes
- Student dashboard (courses, lessons done, quiz history, certificates)
- Printable certificate of completion
- **Admin panel**: statistics, manage users and roles, add/delete courses, lessons and quiz questions
- Security: PDO prepared statements, CSRF tokens on every form, output escaping, session ID regeneration, open-redirect protection

## Requirements

- PHP 7.4 or newer with the `pdo_sqlite` extension (enabled by default in most installs)
- Optional: Python 3 (for `tools/export_report.py`), a C compiler and a JDK (to run the code in `examples/`)

## Run it

### Option 1 – PHP built-in server (quickest)

```bash
cd codelearn
php -S localhost:8000
```

Open <http://localhost:8000>.

### Option 2 – XAMPP / WAMP / Laragon / Apache

Copy the `codelearn` folder into `htdocs` (XAMPP) or `www` (WAMP) and open
`http://localhost/codelearn/`.

On first run the database `data/elearning.sqlite` is created and filled with all courses automatically.
Make sure the `data/` folder is writable by the web server.

## Default admin account

| Email | Password |
|---|---|
| `admin@codelearn.local` | `admin123` |

**Change the password** (edit `config.php` *before* the first run, or update the user in the database) before using this on a real server.

## Folder structure

```
codelearn/
├── index.php            course catalogue
├── course.php           course overview, lessons, quiz link
├── lesson.php           lesson viewer + live editor
├── quiz.php             quiz and results
├── dashboard.php        student dashboard
├── certificate.php      printable certificate
├── login.php  register.php  logout.php
├── config.php           settings (app name, pass mark, admin account)
├── db.php               SQLite schema + automatic seeding
├── includes/            header, footer, helper functions
├── assets/              css/style.css, js/app.js
├── content/             course content files (one per language)
├── admin/               dashboard, users, course/lesson/quiz manager
├── data/                SQLite database (created at runtime, web access blocked)
├── examples/            runnable sample programs: C, Java, Python, PHP, JS, HTML, CSS, SQL
└── tools/export_report.py   Python script: export student progress to CSV
```

## Adding a new course

**From the browser:** log in as admin → *Admin* → *Manage courses* → *Add a new course*, then add lessons and questions.

**From code:** copy any file in `content/`, rename it (e.g. `09-typescript.php`), edit it and delete `data/elearning.sqlite` to re-seed.
(Re-seeding wipes users and progress, so prefer the admin panel on a live system.)

## Export progress to CSV (Python)

```bash
python tools/export_report.py report.csv
```

## Production checklist

- Change the admin password and use HTTPS
- Move `data/` outside the web root and update `DB_PATH` in `config.php`
- Set `display_errors = Off` in `php.ini`
- Back up `elearning.sqlite` regularly
- Consider adding login rate limiting if the site is public

## Languages used in this project

PHP (back end) · SQL/SQLite (data) · HTML · CSS · JavaScript (front end) · Python (reporting tool) · C and Java (example programs) – and the 8 courses teach HTML, CSS, JavaScript, PHP, Python, Java, C and SQL.
