<?php
$base = '';
require_once __DIR__ . '/includes/functions.php';

$user = require_login();
$uid  = (int) $user['id'];

$enrolled = q('SELECT c.* FROM enrollments e JOIN courses c ON c.id = e.course_id
               WHERE e.user_id = ? ORDER BY e.enrolled_at DESC', [$uid])->fetchAll();

$lessonsDone = (int) q('SELECT COUNT(*) FROM progress WHERE user_id = ?', [$uid])->fetchColumn();
$quizzes     = (int) q('SELECT COUNT(*) FROM quiz_attempts WHERE user_id = ?', [$uid])->fetchColumn();
$certs = 0;
$rows = [];
foreach ($enrolled as $c) {
    $cid  = (int) $c['id'];
    $prog = course_progress($uid, $cid);
    $best = best_attempt($uid, $cid);
    $cert = is_certified($uid, $cid);
    if ($cert) $certs++;
    $rows[] = ['c' => $c, 'prog' => $prog, 'best' => $best, 'cert' => $cert];
}

$recent = q('SELECT a.*, c.title, c.slug FROM quiz_attempts a JOIN courses c ON c.id = a.course_id
             WHERE a.user_id = ? ORDER BY a.id DESC LIMIT 8', [$uid])->fetchAll();

$pageTitle = 'My learning';
include __DIR__ . '/includes/header.php';
?>
<main class="container">
  <h1>Hi <?= h(explode(' ', $user['name'])[0]) ?> 👋</h1>
  <div class="stat-grid">
    <div class="card stat"><b><?= count($enrolled) ?></b>Courses</div>
    <div class="card stat"><b><?= $lessonsDone ?></b>Lessons done</div>
    <div class="card stat"><b><?= $quizzes ?></b>Quizzes taken</div>
    <div class="card stat"><b><?= $certs ?></b>Certificates</div>
  </div>

  <h2>My courses</h2>
  <?php if (!$rows): ?>
    <div class="card center"><p>You haven't started a course yet.</p><a class="btn" href="index.php#courses">Browse courses</a></div>
  <?php else: ?>
    <div class="grid">
      <?php foreach ($rows as $r): $c = $r['c']; ?>
        <div class="card course-card">
          <div class="top" style="background:<?= h($c['color']) ?>"><?= h($c['icon']) ?></div>
          <div class="body">
            <h3><?= h($c['title']) ?></h3>
            <div class="progress"><i style="width:<?= $r['prog']['pct'] ?>%"></i></div>
            <div class="meta"><span><?= $r['prog']['done'] ?>/<?= $r['prog']['total'] ?> lessons</span>
              <?php if ($r['best']): ?><span>Best quiz: <?= (int) $r['best']['score'] ?>/<?= (int) $r['best']['total'] ?></span><?php endif; ?></div>
            <div class="row-actions" style="margin-top:8px">
              <a class="btn small" href="course.php?slug=<?= h($c['slug']) ?>">Open</a>
              <?php if ($r['cert']): ?><a class="btn small ok" href="certificate.php?slug=<?= h($c['slug']) ?>">🎓 Certificate</a><?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if ($recent): ?>
    <h2 style="margin-top:34px">Recent quiz attempts</h2>
    <div class="table-wrap"><table class="table">
      <tr><th>Course</th><th>Score</th><th>Result</th><th>Date</th></tr>
      <?php foreach ($recent as $a): $pct = $a['total'] ? round($a['score'] * 100 / $a['total']) : 0; ?>
        <tr>
          <td><a href="course.php?slug=<?= h($a['slug']) ?>"><?= h($a['title']) ?></a></td>
          <td><?= (int) $a['score'] ?>/<?= (int) $a['total'] ?> (<?= $pct ?>%)</td>
          <td><?= $pct >= PASS_MARK ? '✅ Passed' : '❌ Try again' ?></td>
          <td><?= h(substr($a['taken_at'], 0, 16)) ?></td>
        </tr>
      <?php endforeach; ?>
    </table></div>
  <?php endif; ?>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
