<?php
/**
 * Database layer: SQLite via PDO. The schema and the starter content
 * (content/*.php) are installed automatically on first run.
 */
require_once __DIR__ . '/config.php';

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }
    $dir = dirname(DB_PATH);
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
    $pdo = new PDO('sqlite:' . DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec('PRAGMA foreign_keys = ON');
    install_schema($pdo);
    seed_data($pdo);
    return $pdo;
}

function install_schema(PDO $pdo): void
{
    $pdo->exec("
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        role TEXT NOT NULL DEFAULT 'student',
        created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
    );
    CREATE TABLE IF NOT EXISTS courses (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        slug TEXT NOT NULL UNIQUE,
        title TEXT NOT NULL,
        icon TEXT NOT NULL DEFAULT '📘',
        color TEXT NOT NULL DEFAULT '#4f46e5',
        level TEXT NOT NULL DEFAULT 'Beginner',
        description TEXT NOT NULL DEFAULT '',
        sort INTEGER NOT NULL DEFAULT 0
    );
    CREATE TABLE IF NOT EXISTS lessons (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        course_id INTEGER NOT NULL REFERENCES courses(id) ON DELETE CASCADE,
        title TEXT NOT NULL,
        content TEXT NOT NULL DEFAULT '',
        code TEXT NOT NULL DEFAULT '',
        try_it INTEGER NOT NULL DEFAULT 0,
        position INTEGER NOT NULL DEFAULT 0
    );
    CREATE TABLE IF NOT EXISTS questions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        course_id INTEGER NOT NULL REFERENCES courses(id) ON DELETE CASCADE,
        question TEXT NOT NULL,
        opt_a TEXT NOT NULL,
        opt_b TEXT NOT NULL,
        opt_c TEXT NOT NULL,
        opt_d TEXT NOT NULL,
        answer INTEGER NOT NULL,
        explanation TEXT NOT NULL DEFAULT ''
    );
    CREATE TABLE IF NOT EXISTS enrollments (
        user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
        course_id INTEGER NOT NULL REFERENCES courses(id) ON DELETE CASCADE,
        enrolled_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (user_id, course_id)
    );
    CREATE TABLE IF NOT EXISTS progress (
        user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
        lesson_id INTEGER NOT NULL REFERENCES lessons(id) ON DELETE CASCADE,
        completed_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (user_id, lesson_id)
    );
    CREATE TABLE IF NOT EXISTS quiz_attempts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
        course_id INTEGER NOT NULL REFERENCES courses(id) ON DELETE CASCADE,
        score INTEGER NOT NULL,
        total INTEGER NOT NULL,
        taken_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
    );
    ");
}

function seed_data(PDO $pdo): void
{
    if ((int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn() === 0) {
        $st = $pdo->prepare('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)');
        $st->execute(['Administrator', ADMIN_EMAIL, password_hash(ADMIN_PASSWORD, PASSWORD_DEFAULT), 'admin']);
    }
    if ((int) $pdo->query('SELECT COUNT(*) FROM courses')->fetchColumn() === 0) {
        $files = glob(__DIR__ . '/content/*.php');
        sort($files);
        $order = 1;
        foreach ($files as $file) {
            $course = require $file;
            import_course($pdo, $course, $order++);
        }
    }
}

function import_course(PDO $pdo, array $c, int $sort): void
{
    $pdo->beginTransaction();
    $st = $pdo->prepare('INSERT INTO courses (slug, title, icon, color, level, description, sort) VALUES (?,?,?,?,?,?,?)');
    $st->execute([$c['slug'], $c['title'], $c['icon'], $c['color'], $c['level'], $c['description'], $sort]);
    $cid = (int) $pdo->lastInsertId();

    $ls = $pdo->prepare('INSERT INTO lessons (course_id, title, content, code, try_it, position) VALUES (?,?,?,?,?,?)');
    foreach ($c['lessons'] as $i => $l) {
        $ls->execute([$cid, $l['title'], $l['content'], $l['code'] ?? '', !empty($l['try']) ? 1 : 0, $i + 1]);
    }

    $qs = $pdo->prepare('INSERT INTO questions (course_id, question, opt_a, opt_b, opt_c, opt_d, answer, explanation) VALUES (?,?,?,?,?,?,?,?)');
    foreach ($c['questions'] as $q) {
        $qs->execute([$cid, $q['q'], $q['options'][0], $q['options'][1], $q['options'][2], $q['options'][3], $q['answer'], $q['explain'] ?? '']);
    }
    $pdo->commit();
}
