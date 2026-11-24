<?php
$title       = 'Home';
$description = 'LearnBridge Technologies builds digital learning tools for everyone.';
$active      = 'index.php';
include 'includes/header.inc';
?>

  <main class="main">
    <form class="search-container" action="search.php" method="get" role="search">
      <input type="search" name="q" placeholder="Search..." class="search-input" aria-label="Search">
      <button type="submit" class="search-input">Search</button>
    </form>

    <header class="hero">
      <img src="anhvscode.png" alt="LearnBridge logo">
      <div>
        <h1>LearnBridge Technologies</h1>
        <p class="slogan">Learning for everyone, everywhere</p>
      </div>
    </header>

    <hr class="hr">

    <section class="panel">
      <h2>Who we are</h2>
      <p>
        LearnBridge Technologies is an educational technology company that designs
        and builds digital learning tools and online platforms. Founded on the belief
        that good education should not depend on where a student lives, what device
        they own or how they learn best, we create services that schools, teachers
        and students can rely on every day.
      </p>
      <a href="jobs.php" class="join-button">Join our team</a>
    </section>

    <section class="cards">
      <div class="card">
        <h3>Our Mission</h3>
        <p>Make quality education reachable for every learner.</p>
      </div>
      <div class="card">
        <h3>Our Products</h3>
        <p>Digital learning tools and online platforms for schools.</p>
      </div>
      <div class="card">
        <h3>Our Team</h3>
        <p>Developers, designers and educators working together.</p>
      </div>
    </section>
  </main>

<?php include 'includes/footer.inc'; ?>