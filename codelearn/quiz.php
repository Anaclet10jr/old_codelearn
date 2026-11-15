<?php
$base = '';
require_once __DIR__ . '/includes/functions.php';

$user   = require_login();
$course = get_course($_GET['slug'] ?? '');
if (!$course) {
    not_found();
}
$cid = (int) $course['id'];
$questions = q('SELECT * FROM questions WHERE course_id = ? ORDER BY id', [$cid])->fetchAll();
if (!$questions) {
    flash('warn', 'This course has no quiz yet.');
    redirect('course.php?slug=' . urlencode($course['slug']));
}

$result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $given = $_POST['a'] ?? [];
    $score = 0;
    $detail = [];
    foreach ($questions as $qu) {
        $pick = isset($given[$qu['id']]) && ctype_digit((string) $given[$qu['id']]) ? (int) $given[$qu['id']] : -1;
        $ok = $pick === (int) $qu['answer'];
        if ($ok) $score++;
        $detail[$qu['id']] = ['pick' => $pick, 'ok' => $ok];
    }
    $total = count($questions);
    q('INSERT INTO quiz_attempts (user_id, course_id, score, total) VALUES (?, ?, ?, ?)', [$user['id'], $cid, $score, $total]);
    enroll((int) $user['id'], $cid);
    $pct = (int) round($score * 100 / $total);
    $result = ['score' => $score, 'total' => $total, 'pct' => $pct, 'pass' => $pct >= PASS_MARK, 'detail' => $detail];
    $result['certified'] = is_certified((int) $user['id'], $cid);
}

$pageTitle = $course['title'] . ' quiz';
include __DIR__ . '/includes/header.php';
$letters = ['A', 'B', 'C', 'D'];
?>
<main class="container" style="max-width:820px">
  <p class="muted"><a href="course.php?slug=<?= h($course['slug']) ?>">← Back to <?= h($course['title']) ?></a></p>
  <h1><?= h($course['icon']) ?> <?= h($course['title']) ?> quiz</h1>

  <?php if ($result): ?>
    <div class="card center" style="margin-bottom:24px">
      <div class="score <?= $result['pass'] ? 'pass' : 'fail' ?>"><?= $result['score'] ?>/<?= $result['total'] ?> · <?= $result['pct'] ?>%</div>
      <p><strong><?= $result['pass'] ? '🎉 You passed!' : 'Not quite there yet.' ?></strong>
         Pass mark is <?= PASS_MARK ?>%.</p>
      <?php if ($result['certified']): ?>
        <a class="btn ok" href="certificate.php?slug=<?= h($course['slug']) ?>">🎓 Get your certificate</a>
      <?php elseif ($result['pass']): ?>
        <p class="muted">Finish all lessons to unlock your certificate.</p>
        <a class="btn" href="course.php?slug=<?= h($course['slug']) ?>">Go to lessons</a>
      <?php else: ?>
        <p class="muted">Review the explanations below, revisit the lessons and try again.</p>
      <?php endif; ?>
      <a class="btn ghost" href="quiz.php?slug=<?= h($course['slug']) ?>">Retake quiz</a>
    </div>
  <?php endif; ?>

  <form method="post" id="quizForm" class="card">
    <?= csrf_field() ?>
    <?php foreach ($questions as $n => $qu): $opts = [$qu['opt_a'], $qu['opt_b'], $qu['opt_c'], $qu['opt_d']]; ?>
      <div class="question">
        <h3><?= $n + 1 ?>. <?= h($qu['question']) ?></h3>
        <?php foreach ($opts as $i => $text):
            $cls = '';
            if ($result) {
                if ($i === (int) $qu['answer']) $cls = 'correct';
                elseif ($i === $result['detail'][$qu['id']]['pick']) $cls = 'wrong';
            } ?>
          <label class="option <?= $cls ?>">
            <input type="radio" name="a[<?= (int) $qu['id'] ?>]" value="<?= $i ?>"
              <?= $result ? 'disabled' : '' ?> <?= ($result && $i === $result['detail'][$qu['id']]['pick']) ? 'checked' : '' ?>>
            <span><strong><?= $letters[$i] ?>.</strong> <?= h($text) ?></span>
          </label>
        <?php endforeach; ?>
        <?php if ($result && $qu['explanation']): ?>
          <p class="explain">💡 <?= h($qu['explanation']) ?></p>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
    <?php if (!$result): ?><button class="btn">Submit answers</button><?php endif; ?>
  </form>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
