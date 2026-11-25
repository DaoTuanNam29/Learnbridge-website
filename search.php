<?php
require_once 'settings.php';

$conn = mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("<p>Database connection failed. Please try again later.</p>");
}
mysqli_set_charset($conn, 'utf8mb4');


$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$q = mb_substr($q, 0, 100);

$jobs = [];
if ($q !== '') {

    $kw   = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $q);
    $like = '%' . $kw . '%';


    $sql = "SELECT job_ref, title, description, salary
            FROM jobs
            WHERE title LIKE ? OR job_ref LIKE ? OR description LIKE ?
               OR responsibilities LIKE ? OR essential LIKE ? OR preferable LIKE ?
            ORDER BY position_no";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssss', $like, $like, $like, $like, $like, $like);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $jobs[] = $row;
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);

$title       = 'Search';
$description = 'Search job positions at LearnBridge Technologies.';
$active      = '';
include 'includes/header.inc';
?>

  <main class="main">
    <h1 class="page-title">Search Jobs</h1>

    <form class="search-container" action="search.php" method="get" role="search">
      <input type="search" name="q" class="search-input" aria-label="Search"
             placeholder="Search..." value="<?php echo htmlspecialchars($q); ?>">
      <button type="submit" class="search-input">Search</button>
    </form>

<?php if ($q === ''): ?>
    <p class="page-note">Please enter a keyword to search for jobs.</p>

<?php elseif ($jobs): ?>
    <p class="page-note">
      <?php echo count($jobs); ?> result(s) for "<?php echo htmlspecialchars($q); ?>"
    </p>
  <?php foreach ($jobs as $job): ?>
    <section class="search-result">
      <h2><a href="jobs.php#<?php echo urlencode($job['job_ref']); ?>">
        <?php echo htmlspecialchars($job['title']); ?></a></h2>
      <p><strong>Reference Number:</strong> <?php echo htmlspecialchars($job['job_ref']); ?></p>
      <p><?php echo htmlspecialchars($job['description']); ?></p>
      <p><strong>Salary:</strong> <?php echo htmlspecialchars($job['salary']); ?></p>
    </section>
  <?php endforeach; ?>

<?php else: ?>
    <p class="page-note">No jobs found for "<?php echo htmlspecialchars($q); ?>".</p>
<?php endif; ?>
  </main>

<?php include 'includes/footer.inc'; ?>