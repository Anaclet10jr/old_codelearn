<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
    session_start();
}

/* ---------- small helpers ---------- */

function h($s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $url)
{
    header('Location: ' . $url);
    exit;
}

/** Run a prepared statement and return it. */
function q(string $sql, array $params = []): PDOStatement
{
    $st = db()->prepare($sql);
    $st->execute($params);
    return $st;
}

/** Only allow relative redirect targets (prevents open redirects). */
function safe_next(?string $next, string $default = 'dashboard.php'): string
{
    if ($next && preg_match('~^[a-zA-Z0-9_./?=&-]+$~', $next) && strpos($next, '//') === false && $next[0] !== '/') {
        return $next;
    }
    return $default;
}

/* ---------- flash messages ---------- */

function flash(string $type, string $msg): void
{
    $_SESSION['flash'][] = ['type' => $type, 'msg' => $msg];
}

function take_flash(): array
{
    $f = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $f;
}

/* ---------- CSRF ---------- */

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . h(csrf_token()) . '">';
}

function csrf_check(): void
{
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(419);
        exit('Invalid or expired form token. Please go back, refresh the page and try again.');
    }
}

/* ---------- authentication ---------- */

function current_user(): ?array
{
    static $cache = false;
    if ($cache !== false) {
        return $cache;
    }
    $cache = null;
    if (!empty($_SESSION['uid'])) {
        $row = q('SELECT id, name, email, role FROM users WHERE id = ?', [$_SESSION['uid']])->fetch();
        $cache = $row ?: null;
    }
    return $cache;
}

function require_login(string $base = ''): array
{
    $u = current_user();
    if (!$u) {
        flash('warn', 'Please log in to continue.');
        $qs   = $_SERVER['QUERY_STRING'] ?? '';
        $next = basename($_SERVER['SCRIPT_NAME']) . ($qs !== '' ? '?' . $qs : '');
        redirect($base . 'login.php?next=' . urlencode($next));
    }
    return $u;
}

function require_admin(string $base = ''): array
{
    $u = require_login($base);
    if ($u['role'] !== 'admin') {
        http_response_code(403);
        exit('403 – Administrators only.');
    }
    return $u;
}

/* ---------- course helpers ---------- */

function get_course(string $slug): ?array
{
    $r = q('SELECT * FROM courses WHERE slug = ?', [$slug])->fetch();
    return $r ?: null;
}

function course_lessons(int $courseId): array
{
    return q('SELECT * FROM lessons WHERE course_id = ? ORDER BY position, id', [$courseId])->fetchAll();
}

function done_lessons(int $uid, int $courseId): array
{
    $rows = q('SELECT p.lesson_id FROM progress p JOIN lessons l ON l.id = p.lesson_id
               WHERE p.user_id = ? AND l.course_id = ?', [$uid, $courseId])->fetchAll(PDO::FETCH_COLUMN);
    return array_map('intval', $rows);
}

function course_progress(int $uid, int $courseId): array
{
    $total = (int) q('SELECT COUNT(*) FROM lessons WHERE course_id = ?', [$courseId])->fetchColumn();
    $done  = count(done_lessons($uid, $courseId));
    return ['done' => $done, 'total' => $total, 'pct' => $total ? (int) round($done * 100 / $total) : 0];
}

function best_attempt(int $uid, int $courseId): ?array
{
    $r = q('SELECT * FROM quiz_attempts WHERE user_id = ? AND course_id = ?
            ORDER BY (score * 100.0 / total) DESC, id DESC LIMIT 1', [$uid, $courseId])->fetch();
    return $r ?: null;
}

/** Certified = every lesson completed AND best quiz score >= PASS_MARK. */
function is_certified(int $uid, int $courseId): bool
{
    $p = course_progress($uid, $courseId);
    if ($p['total'] === 0 || $p['done'] < $p['total']) {
        return false;
    }
    $b = best_attempt($uid, $courseId);
    return $b && $b['total'] > 0 && ($b['score'] * 100 / $b['total']) >= PASS_MARK;
}

function enroll(int $uid, int $courseId): void
{
    q('INSERT OR IGNORE INTO enrollments (user_id, course_id) VALUES (?, ?)', [$uid, $courseId]);
}

function not_found(string $base = '')
{
    http_response_code(404);
    $pageTitle = 'Not found';
    include __DIR__ . '/header.php';
    echo '<main class="container"><div class="card center"><h1>Not found</h1><p>That page does not exist.</p>'
       . '<a class="btn" href="' . h($base) . 'index.php">Back to courses</a></div></main>';
    include __DIR__ . '/footer.php';
    exit;
}
