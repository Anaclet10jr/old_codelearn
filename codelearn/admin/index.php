<?php
$base = '../';
require_once __DIR__ . '/../includes/functions.php';
$admin = require_admin($base);

$stats = [
    'Students'      => (int) q("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn(),
    'Courses'       => (int) q('SELECT COUNT(*) FROM courses')->fetchColumn(),
    'Lessons'       => (int) q('SELECT COUNT(*) FROM lessons')->fetchColumn(),
    'Quiz questions'=> (int) q('SELECT COUNT(*) FROM questions')->fetchColumn(),
    'Quiz attempts' => (int) q('SELECT COUNT(*) FROM quiz_attempts')->fetchColumn(),
    'Lessons completed' => (int) q('SELECT COUNT(*) FROM progress')->fetchColumn(),
];

$popular = q('SELECT c.title, c.icon, COUNT(e.user_id) AS n FROM courses c
              LEFT JOIN enrollments e ON e.course_id = c.id GROUP BY c.id ORDER BY n DESC, c.sort LIMIT 8')->fetchAll();
$newUsers = q('SELECT name, email, role, created_at FROM users ORDER BY id DESC LIMIT 6')->fetchAll();

$pageTitle = 'Admin dashboard';
include __DIR__ . '/../includes/header.php';
?>
<main class="container">
  <h1>Admin dashboard</h1>
  <p class="row-actions">
    <a class="btn small" href="courses.php">Manage courses</a>
    <a class="btn small ghost" href="users.php">Manage users</a>
  </p>
  <div class="stat-grid">
    <?php foreach ($stats as $label => $n): ?>
      <div class="card stat"><b><?= $n ?></b><?= h($label) ?></div>
    <?php endforeach; ?>
  </div>

  <div class="two-col">
    <section>
      <h2>Enrolments per course</h2>
      <div class="table-wrap"><table class="table">
        <tr><th>Course</th><th>Students</th></tr>
        <?php foreach ($popular as $p): ?><tr><td><?= h($p['icon']) ?> <?= h($p['title']) ?></td><td><?= (int) $p['n'] ?></td></tr><?php endforeach; ?>
      </table></div>
    </section>
    <section>
      <h2>Newest users</h2>
      <div class="table-wrap"><table class="table">
        <tr><th>Name</th><th>Role</th></tr>
        <?php foreach ($newUsers as $u): ?>
          <tr><td><?= h($u['name']) ?><br><span class="muted"><?= h($u['email']) ?></span></td><td><?= h($u['role']) ?></td></tr>
        <?php endforeach; ?>
      </table></div>
    </section>
  </div>
</main>
<?php include __DIR__ . '/../includes/footer.php'; ?>
