<?php
$base = '';
require_once __DIR__ . '/includes/functions.php';

$lesson = q('SELECT * FROM lessons WHERE id = ?', [(int) ($_GET['id'] ?? 0)])->fetch();
if (!$lesson) {
    not_found();
}
$course  = q('SELECT * FROM courses WHERE id = ?', [$lesson['course_id']])->fetch();
$cid     = (int) $course['id'];
$lessons = course_lessons($cid);
$user    = current_user();

// locate previous / next lessons
$idx = 0;
foreach ($lessons as $i => $l) {
    if ((int) $l['id'] === (int) $lesson['id']) { $idx = $i; break; }
}
$prev = $lessons[$idx - 1] ?? null;
$next = $lessons[$idx + 1] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $u = require_login();
    q('INSERT OR IGNORE INTO progress (user_id, lesson_id) VALUES (?, ?)', [$u['id'], $lesson['id']]);
    enroll((int) $u['id'], $cid);
    if ($next) {
        redirect('lesson.php?id=' . (int) $next['id']);
    }
    flash('ok', 'Course lessons complete! Take the final quiz to earn your certificate.');
    redirect('course.php?slug=' . urlencode($course['slug']));
}

$done = [];
if ($user) {
    enroll((int) $user['id'], $cid);
    $done = done_lessons((int) $user['id'], $cid);
}
$isDone = in_array((int) $lesson['id'], $done, true);

$pageTitle = $lesson['title'] . ' – ' . $course['title'];
include __DIR__ . '/includes/header.php';
?>
<main class="container">
  <p class="muted"><a href="index.php">Courses</a> › <a href="course.php?slug=<?= h($course['slug']) ?>"><?= h($course['title']) ?></a> › Lesson <?= $idx + 1 ?></p>
  <div class="lesson-layout">
    <aside class="card side no-print">
      <h4><?= h($course['icon']) ?> <?= h($course['title']) ?></h4>
      <ul class="lesson-list">
        <?php foreach ($lessons as $i => $l): $d = in_array((int) $l['id'], $done, true); ?>
          <li><a class="<?= (int) $l['id'] === (int) $lesson['id'] ? 'current' : '' ?>" href="lesson.php?id=<?= (int) $l['id'] ?>">
            <span class="num <?= $d ? 'done' : '' ?>"><?= $d ? '✓' : $i + 1 ?></span><span><?= h($l['title']) ?></span></a></li>
        <?php endforeach; ?>
      </ul>
    </aside>

    <article class="card lesson-body">
      <h1><?= h($lesson['title']) ?></h1>
      <?= $lesson['content'] /* trusted: written by course authors / admins */ ?>

      <?php if ($lesson['try_it']): ?>
        <div class="tryit" data-tryit>
          <div class="tryit-bar">
            <strong>✏️ Try it yourself</strong>
            <button type="button" class="btn small" data-run>▶ Run</button>
            <button type="button" class="btn small ghost" data-reset>Reset</button>
          </div>
          <div class="tryit-grid">
            <textarea spellcheck="false" data-editor aria-label="Code editor"><?= h($lesson['code']) ?></textarea>
            <iframe sandbox="allow-scripts" data-preview title="Live preview"></iframe>
          </div>
        </div>
      <?php elseif (trim($lesson['code']) !== ''): ?>
        <div class="codewrap">
          <span class="lang"><?= h($course['title']) ?> example</span>
          <button type="button" class="copy" data-copy="#code-<?= (int) $lesson['id'] ?>">Copy</button>
          <pre><code id="code-<?= (int) $lesson['id'] ?>"><?= h($lesson['code']) ?></code></pre>
        </div>
      <?php endif; ?>

      <div class="nav-lessons no-print">
        <div><?php if ($prev): ?><a class="btn ghost" href="lesson.php?id=<?= (int) $prev['id'] ?>">← <?= h($prev['title']) ?></a><?php endif; ?></div>
        <div>
          <?php if ($user): ?>
            <form method="post" class="inline"><?= csrf_field() ?>
              <button class="btn <?= $isDone ? 'ghost' : 'ok' ?>">
                <?= $isDone ? 'Completed ✓ – ' : 'Mark complete – ' ?><?= $next ? 'next lesson →' : 'finish course' ?>
              </button>
            </form>
          <?php else: ?>
            <a class="btn" href="login.php?next=<?= urlencode('lesson.php?id=' . $lesson['id']) ?>">Log in to track progress</a>
          <?php endif; ?>
        </div>
      </div>
    </article>
  </div>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
