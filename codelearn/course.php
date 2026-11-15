<?php
$base = '';
require_once __DIR__ . '/includes/functions.php';

$course = get_course($_GET['slug'] ?? '');
if (!$course) {
    not_found();
}
$cid  = (int) $course['id'];
$user = current_user();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'enroll') {
    csrf_check();
    $u = require_login();
    enroll((int) $u['id'], $cid);
    flash('ok', 'You are enrolled in ' . $course['title'] . '. Happy learning!');
    redirect('course.php?slug=' . urlencode($course['slug']));
}

$lessons = course_lessons($cid);
$qCount  = (int) q('SELECT COUNT(*) FROM questions WHERE course_id = ?', [$cid])->fetchColumn();
$done = $prog = $best = null;
$certified = false;
$enrolled = false;
if ($user) {
    $uid       = (int) $user['id'];
    $done      = done_lessons($uid, $cid);
    $prog      = course_progress($uid, $cid);
    $best      = best_attempt($uid, $cid);
    $certified = is_certified($uid, $cid);
    $enrolled  = (bool) q('SELECT 1 FROM enrollments WHERE user_id = ? AND course_id = ?', [$uid, $cid])->fetch();
}
$done = $done ?? [];

// first lesson not yet completed (or the first lesson)
$resume = $lessons[0]['id'] ?? null;
foreach ($lessons as $l) {
    if (!in_array((int) $l['id'], $done, true)) { $resume = $l['id']; break; }
}

$pageTitle = $course['title'] . ' course';
include __DIR__ . '/includes/header.php';
?>
<main class="container">
  <div class="course-head">
    <div class="big" style="background:<?= h($course['color']) ?>"><?= h($course['icon']) ?></div>
    <div>
      <span class="badge"><?= h($course['level']) ?></span>
      <h1 style="margin:6px 0"><?= h($course['title']) ?></h1>
      <p class="muted" style="margin:0;max-width:640px"><?= h($course['description']) ?></p>
    </div>
  </div>

  <div class="two-col">
    <section class="card">
      <h2>Lessons</h2>
      <ul class="lesson-list">
        <?php foreach ($lessons as $i => $l): $isDone = in_array((int) $l['id'], $done, true); ?>
          <li><a href="lesson.php?id=<?= (int) $l['id'] ?>">
            <span class="num <?= $isDone ? 'done' : '' ?>"><?= $isDone ? '✓' : $i + 1 ?></span>
            <span><?= h($l['title']) ?><?php if ($l['try_it']): ?> <span class="badge">Live editor</span><?php endif; ?></span>
          </a></li>
        <?php endforeach; ?>
      </ul>
    </section>

    <aside class="card">
      <?php if ($user && $prog): ?>
        <h3 style="margin-top:0">Your progress</h3>
        <div class="progress"><i style="width:<?= $prog['pct'] ?>%"></i></div>
        <p class="muted"><?= $prog['done'] ?> of <?= $prog['total'] ?> lessons complete (<?= $prog['pct'] ?>%)</p>
        <?php if ($best): ?>
          <p>Best quiz score: <strong><?= (int) $best['score'] ?>/<?= (int) $best['total'] ?></strong></p>
        <?php endif; ?>
        <?php if ($resume): ?>
          <p><a class="btn block" href="lesson.php?id=<?= (int) $resume ?>"><?= $prog['done'] ? 'Continue' : 'Start' ?> course →</a></p>
        <?php endif; ?>
      <?php else: ?>
        <h3 style="margin-top:0">Ready to start?</h3>
        <p class="muted">Log in to track progress, take the quiz and earn a certificate.</p>
        <p><a class="btn block" href="login.php?next=<?= urlencode('course.php?slug=' . $course['slug']) ?>">Log in</a></p>
        <?php if ($resume): ?><p class="center"><a href="lesson.php?id=<?= (int) $resume ?>">or preview the first lesson</a></p><?php endif; ?>
      <?php endif; ?>

      <?php if ($user && !$enrolled): ?>
        <form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="enroll">
          <button class="btn ghost block">Enrol in this course</button></form>
      <?php endif; ?>

      <hr style="border:0;border-top:1px solid var(--border);margin:20px 0">
      <h3 style="margin-top:0">Final quiz</h3>
      <p class="muted"><?= $qCount ?> questions · pass mark <?= PASS_MARK ?>%</p>
      <?php if ($qCount > 0): ?>
        <p><a class="btn <?= $user ? '' : 'ghost' ?> block" href="quiz.php?slug=<?= h($course['slug']) ?>">Take the quiz</a></p>
      <?php endif; ?>
      <?php if ($certified): ?>
        <p><a class="btn ok block" href="certificate.php?slug=<?= h($course['slug']) ?>">🎓 View certificate</a></p>
      <?php elseif ($user): ?>
        <p class="muted" style="font-size:.9rem">Complete every lesson and pass the quiz to earn your certificate.</p>
      <?php endif; ?>
    </aside>
  </div>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
