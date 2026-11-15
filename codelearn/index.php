<?php
$base = '';
require_once __DIR__ . '/includes/functions.php';

$user = current_user();
$courses = q('SELECT c.*,
        (SELECT COUNT(*) FROM lessons   WHERE course_id = c.id) AS lesson_count,
        (SELECT COUNT(*) FROM questions WHERE course_id = c.id) AS q_count
      FROM courses c ORDER BY sort, id')->fetchAll();

$totalLessons   = array_sum(array_column($courses, 'lesson_count'));
$totalQuestions = array_sum(array_column($courses, 'q_count'));
$levels = array_values(array_unique(array_column($courses, 'level')));

$pageTitle = 'Learn to code';
include __DIR__ . '/includes/header.php';
?>
<main class="container">
  <section class="hero">
    <h1>Learn to code, one lesson at a time.</h1>
    <p>Free interactive courses in HTML, CSS, JavaScript, PHP, Python, Java, C and SQL — with live code editors, quizzes, progress tracking and certificates.</p>
    <?php if ($user): ?>
      <a class="btn" href="dashboard.php">Continue learning</a>
    <?php else: ?>
      <a class="btn" href="register.php">Get started free</a>
      <a class="btn ghost" href="login.php">I have an account</a>
    <?php endif; ?>
    <div class="stats">
      <div><b><?= count($courses) ?></b><span>Courses</span></div>
      <div><b><?= (int) $totalLessons ?></b><span>Lessons</span></div>
      <div><b><?= (int) $totalQuestions ?></b><span>Quiz questions</span></div>
    </div>
  </section>

  <div class="toolbar" id="courses">
    <h2 style="margin:0">All courses</h2>
    <div style="display:flex;gap:10px;flex-wrap:wrap">
      <select id="levelFilter" style="width:auto" aria-label="Filter by level">
        <option value="">All levels</option>
        <?php foreach ($levels as $lv): ?><option><?= h($lv) ?></option><?php endforeach; ?>
      </select>
      <input class="search" id="courseSearch" type="search" placeholder="Search courses…" aria-label="Search courses">
    </div>
  </div>

  <div class="grid">
    <?php foreach ($courses as $c):
        $prog = $user ? course_progress((int) $user['id'], (int) $c['id']) : null; ?>
      <a class="card course-card" href="course.php?slug=<?= h($c['slug']) ?>" style="color:inherit;text-decoration:none"
         data-course="<?= h(strtolower($c['title'] . ' ' . $c['description'])) ?>" data-level="<?= h($c['level']) ?>">
        <div class="top" style="background:<?= h($c['color']) ?>"><?= h($c['icon']) ?></div>
        <div class="body">
          <div><span class="badge"><?= h($c['level']) ?></span></div>
          <h3><?= h($c['title']) ?></h3>
          <p><?= h($c['description']) ?></p>
          <div class="meta"><span><?= (int) $c['lesson_count'] ?> lessons</span><span><?= (int) $c['q_count'] ?> quiz questions</span></div>
          <?php if ($prog && $prog['done'] > 0): ?>
            <div class="progress" title="<?= $prog['pct'] ?>% complete"><i style="width:<?= $prog['pct'] ?>%"></i></div>
          <?php endif; ?>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
