<?php
require_once __DIR__ . '/functions.php';
$base      = $base ?? '';
$user      = current_user();
$pageTitle = $pageTitle ?? APP_NAME;
?><!doctype html>
<html lang="en" data-theme="light">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= h($pageTitle) ?> · <?= h(APP_NAME) ?></title>
<link rel="stylesheet" href="<?= $base ?>assets/css/style.css">
<script>try{var t=localStorage.getItem('theme');if(t)document.documentElement.setAttribute('data-theme',t);}catch(e){}</script>
</head>
<body>
<header class="site-header">
  <div class="container nav">
    <a class="brand" href="<?= $base ?>index.php"><span class="logo">&lt;/&gt;</span> <?= h(APP_NAME) ?></a>
    <button class="nav-toggle" id="navToggle" aria-label="Menu">☰</button>
    <nav class="nav-links" id="navLinks">
      <a href="<?= $base ?>index.php">Courses</a>
      <?php if ($user): ?>
        <a href="<?= $base ?>dashboard.php">My Learning</a>
        <?php if ($user['role'] === 'admin'): ?><a href="<?= $base ?>admin/index.php">Admin</a><?php endif; ?>
        <span class="who">Hi, <?= h(explode(' ', $user['name'])[0]) ?></span>
        <a class="btn small ghost" href="<?= $base ?>logout.php">Log out</a>
      <?php else: ?>
        <a href="<?= $base ?>login.php">Log in</a>
        <a class="btn small" href="<?= $base ?>register.php">Sign up free</a>
      <?php endif; ?>
      <button class="icon-btn" id="themeToggle" title="Toggle dark mode" aria-label="Toggle dark mode">◐</button>
    </nav>
  </div>
</header>
<?php $flashes = take_flash(); if ($flashes): ?>
<div class="container">
  <?php foreach ($flashes as $f): ?>
    <div class="alert <?= h($f['type']) ?>"><?= h($f['msg']) ?></div>
  <?php endforeach; ?>
</div>
<?php endif; ?>
