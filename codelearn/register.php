<?php
$base = '';
require_once __DIR__ . '/includes/functions.php';

if (current_user()) {
    redirect('dashboard.php');
}

$errors = [];
$name = $email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $name  = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $pass  = $_POST['password'] ?? '';
    $pass2 = $_POST['password2'] ?? '';

    if ($name === '' || mb_strlen($name) > 80)              $errors[] = 'Please enter your name (max 80 characters).';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))        $errors[] = 'Please enter a valid email address.';
    if (strlen($pass) < 6)                                  $errors[] = 'Password must be at least 6 characters.';
    if ($pass !== $pass2)                                   $errors[] = 'Passwords do not match.';
    if (!$errors && q('SELECT 1 FROM users WHERE email = ?', [$email])->fetch()) {
        $errors[] = 'That email is already registered. Try logging in.';
    }

    if (!$errors) {
        q('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)',
          [$name, $email, password_hash($pass, PASSWORD_DEFAULT), 'student']);
        session_regenerate_id(true);
        $_SESSION['uid'] = (int) db()->lastInsertId();
        flash('ok', 'Account created. Welcome aboard!');
        redirect('index.php#courses');
    }
}

$pageTitle = 'Create account';
include __DIR__ . '/includes/header.php';
?>
<main class="container">
  <div class="card form-card">
    <h1>Create your account</h1>
    <?php foreach ($errors as $e): ?><div class="alert err"><?= h($e) ?></div><?php endforeach; ?>
    <form method="post">
      <?= csrf_field() ?>
      <label for="name">Full name</label>
      <input id="name" type="text" name="name" required value="<?= h($name) ?>">
      <label for="email">Email</label>
      <input id="email" type="email" name="email" required value="<?= h($email) ?>">
      <label for="password">Password (min 6 characters)</label>
      <input id="password" type="password" name="password" required minlength="6">
      <label for="password2">Confirm password</label>
      <input id="password2" type="password" name="password2" required minlength="6">
      <p style="margin-top:20px"><button class="btn block">Sign up</button></p>
    </form>
    <p class="muted center">Already registered? <a href="login.php">Log in</a></p>
  </div>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
