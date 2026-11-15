<?php
$base = '';
require_once __DIR__ . '/includes/functions.php';

if (current_user()) {
    redirect('dashboard.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $email = strtolower(trim($_POST['email'] ?? ''));
    $pass  = $_POST['password'] ?? '';
    $u = q('SELECT * FROM users WHERE email = ?', [$email])->fetch();
    if ($u && password_verify($pass, $u['password'])) {
        session_regenerate_id(true);
        $_SESSION['uid'] = (int) $u['id'];
        redirect(safe_next($_GET['next'] ?? null));
    }
    $error = 'Invalid email or password.';
}

$pageTitle = 'Log in';
include __DIR__ . '/includes/header.php';
?>
<main class="container">
  <div class="card form-card">
    <h1>Welcome back</h1>
    <?php if ($error): ?><div class="alert err"><?= h($error) ?></div><?php endif; ?>
    <form method="post" autocomplete="on">
      <?= csrf_field() ?>
      <label for="email">Email</label>
      <input id="email" type="email" name="email" required value="<?= h($_POST['email'] ?? '') ?>">
      <label for="password">Password</label>
      <input id="password" type="password" name="password" required>
      <p style="margin-top:20px"><button class="btn block">Log in</button></p>
    </form>
    <p class="muted center">New here? <a href="register.php">Create an account</a></p>
  </div>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
