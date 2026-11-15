<?php
$base = '';
require_once __DIR__ . '/includes/functions.php';

$user   = require_login();
$course = get_course($_GET['slug'] ?? '');
if (!$course) {
    not_found();
}
$uid = (int) $user['id'];
$cid = (int) $course['id'];

if (!is_certified($uid, $cid)) {
    flash('warn', 'Complete every lesson and pass the quiz (' . PASS_MARK . '%) to unlock this certificate.');
    redirect('course.php?slug=' . urlencode($course['slug']));
}

$best   = best_attempt($uid, $cid);
$date   = date('F j, Y', strtotime($best['taken_at']));
$pct    = (int) round($best['score'] * 100 / $best['total']);
$certId = sprintf('CL-%04d-%s-%s', $uid, strtoupper($course['slug']), date('Ymd', strtotime($best['taken_at'])));

$pageTitle = 'Certificate – ' . $course['title'];
include __DIR__ . '/includes/header.php';
?>
<main class="container">
  <p class="no-print"><a href="course.php?slug=<?= h($course['slug']) ?>">← Back to course</a>
     &nbsp; <button class="btn small" onclick="window.print()">🖨 Print / Save as PDF</button></p>
  <div class="certificate">
    <small>CERTIFICATE OF COMPLETION</small>
    <h1><?= h(APP_NAME) ?></h1>
    <p>This is to certify that</p>
    <div class="name"><?= h($user['name']) ?></div>
    <p>has successfully completed the course</p>
    <h2 style="font-size:2rem;margin:.2em 0"><?= h($course['icon']) ?> <?= h($course['title']) ?></h2>
    <p>with a final quiz score of <strong><?= $pct ?>%</strong> on <?= h($date) ?>.</p>
    <p style="margin-top:34px"><small>Certificate ID: <?= h($certId) ?></small></p>
  </div>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
