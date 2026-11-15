<?php
$base = '../';
require_once __DIR__ . '/../includes/functions.php';
$admin = require_admin($base);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id = (int) ($_POST['id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($id === (int) $admin['id']) {
        flash('err', 'You cannot change or delete your own account here.');
    } elseif ($action === 'toggle_role') {
        q("UPDATE users SET role = CASE role WHEN 'admin' THEN 'student' ELSE 'admin' END WHERE id = ?", [$id]);
        flash('ok', 'Role updated.');
    } elseif ($action === 'delete') {
        q('DELETE FROM users WHERE id = ?', [$id]);
        flash('ok', 'User deleted along with their progress.');
    }
    redirect('users.php');
}

$users = q('SELECT u.*,
    (SELECT COUNT(*) FROM progress p WHERE p.user_id = u.id) AS lessons_done,
    (SELECT COUNT(*) FROM quiz_attempts a WHERE a.user_id = u.id) AS quizzes
  FROM users u ORDER BY u.id')->fetchAll();

$pageTitle = 'Users';
include __DIR__ . '/../includes/header.php';
?>
<main class="container">
  <p><a href="index.php">← Admin</a></p>
  <h1>Users (<?= count($users) ?>)</h1>
  <div class="table-wrap"><table class="table">
    <tr><th>#</th><th>Name / email</th><th>Role</th><th>Lessons</th><th>Quizzes</th><th>Joined</th><th></th></tr>
    <?php foreach ($users as $u): ?>
      <tr>
        <td><?= (int) $u['id'] ?></td>
        <td><?= h($u['name']) ?><br><span class="muted"><?= h($u['email']) ?></span></td>
        <td><span class="badge"><?= h($u['role']) ?></span></td>
        <td><?= (int) $u['lessons_done'] ?></td>
        <td><?= (int) $u['quizzes'] ?></td>
        <td><?= h(substr($u['created_at'], 0, 10)) ?></td>
        <td>
          <?php if ((int) $u['id'] !== (int) $admin['id']): ?>
          <div class="row-actions">
            <form method="post" class="inline"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $u['id'] ?>">
              <button class="btn small ghost" name="action" value="toggle_role">Make <?= $u['role'] === 'admin' ? 'student' : 'admin' ?></button></form>
            <form method="post" class="inline" onsubmit="return confirm('Delete this user and all their progress?')"><?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int) $u['id'] ?>">
              <button class="btn small danger" name="action" value="delete">Delete</button></form>
          </div>
          <?php else: ?><span class="muted">(you)</span><?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </table></div>
</main>
<?php include __DIR__ . '/../includes/footer.php'; ?>
