<?php
require_once 'settings.php';
$conn = mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("<p>Database connection failed. Please try again later.</p>");
}

$title       = 'Job Description';
$description = 'Available job positions at LearnBridge Technologies.';
$active      = 'jobs.php';
include 'includes/header.inc';

function print_list($text, $tag = 'ul') {
    $items = array_filter(array_map('trim', explode("\n", $text)));
    echo "<$tag>";
    foreach ($items as $item) {
        echo '<li>' . htmlspecialchars($item) . '</li>';
    }
    echo "</$tag>";
}

$result = mysqli_query($conn, "SELECT * FROM jobs ORDER BY position_no");
?>

  <main class="main">
    <h1 class="page-title">Available Jobs</h1>
    <p class="page-note">Join LearnBridge Technologies and help us make online education accessible to everyone.</p>

<?php if ($result && mysqli_num_rows($result) > 0): ?>
  <?php while ($job = mysqli_fetch_assoc($result)): ?>
    <section class="job">
      <h2>Job Position <?php echo (int)$job['position_no']; ?></h2>
      <h3><?php echo htmlspecialchars($job['title']); ?></h3>
      <p><strong>Reference Number:</strong> <?php echo htmlspecialchars($job['job_ref']); ?></p>
      <p><?php echo htmlspecialchars($job['description']); ?></p>
      <p><strong>Salary:</strong> <?php echo htmlspecialchars($job['salary']); ?></p>
      <p><strong>Reports to:</strong> <?php echo htmlspecialchars($job['reports_to']); ?></p>

      <section>
        <h4>Key Responsibilities</h4>
        <?php print_list($job['responsibilities'], 'ol'); ?>
      </section>

      <section>
        <h4>Requirements</h4>
        <p><strong>Essential:</strong></p>
        <?php print_list($job['essential']); ?>
        <p><strong>Preferable:</strong></p>
        <?php print_list($job['preferable']); ?>
      </section>

      <p><a href="apply.php?job=<?php echo urlencode($job['job_ref']); ?>" class="apply-button">Apply for this job</a></p>
    </section>
  <?php endwhile; ?>
<?php else: ?>
    <p>No jobs are available at the moment.</p>
<?php endif; ?>

    <aside>
      <h3>Why Join Us?</h3>
      <ul>
        <li>Flexible working arrangements.</li>
        <li>Friendly and supportive team.</li>
        <li>Opportunities to learn new skills.</li>
        <li>Help improve online education.</li>
      </ul>
    </aside>
  </main>
<?php mysqli_close($conn); ?>
<?php include 'includes/footer.inc'; ?>