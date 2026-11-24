<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="<?php echo $description; ?>">
  <title><?php echo $title; ?> | LearnBridge Technologies</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header class="navbar">
    <a class="logo" href="index.php">LearnBridge Technologies</a>
    <nav class="link" aria-label="Main navigation">
      <a href="index.php" class="button" <?php if ($active == "index.php") echo 'aria-current="page"'; ?>>Home Page</a>
      <a href="about.php" class="button" <?php if ($active == "about.php") echo 'aria-current="page"'; ?>>About Us</a>
      <a href="jobs.php" class="button" <?php if ($active == "jobs.php") echo 'aria-current="page"'; ?>>Job Description</a>
      <a href="apply.php" class="button" <?php if ($active == "apply.php") echo 'aria-current="page"'; ?>>Apply</a>
    </nav>
  </header>