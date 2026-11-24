<?php
require_once 'settings.php';

$conn = mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("<p>Database connection failed. Please try again later.</p>");
}
mysqli_set_charset($conn, 'utf8mb4');

$members = [];
$result = mysqli_query($conn, "SELECT * FROM about ORDER BY member_no");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $members[] = $row;
    }
}
mysqli_close($conn);

$title       = 'About Us';
$description = 'Meet our team, learn about our contributions and discover our interests.';
$active      = 'about.php';
include 'includes/header.inc';
?>

  <main class="main">
    <h1 class="page-title">Meet Our Team</h1>

    <section class="panel">
      <h2>01. Our Group Information</h2>
      <h3>Group Details</h3>
      <ul class="group-list">
        <li>
          <strong>Group Name:</strong> DNB Boys
          <ul>
            <li>Group Code: G02</li>
            <li>Industry: Educational Technology</li>
          </ul>
        </li>
        <li>
          <strong>Project:</strong>
          <ul>
            <li>LearnBridge Technologies Recruitment Website</li>
            <li>Unit: COS10026 Web Technology</li>
          </ul>
        </li>
      </ul>
    </section>

    <section class="panel">
      <h2>02. Our Team Members</h2>
      <p class="section-intro">
        Every member contributes different skills and ideas to help our team complete this project.
      </p>

<?php if ($members): ?>
      <dl class="member-list">
  <?php foreach ($members as $m): ?>
        <div class="member">
          <dt>Member <?php echo (int)$m['member_no']; ?> – <?php echo htmlspecialchars($m['full_name']); ?></dt>
          <dd>
            <span class="member-id">Student ID: <?php echo htmlspecialchars($m['student_id']); ?></span>
            <p><strong>Contribution (Part 1):</strong> <?php echo htmlspecialchars($m['contribution_p1']); ?></p>
            <p><strong>Contribution (Part 2):</strong> <?php echo htmlspecialchars($m['contribution_p2']); ?></p>
            <blockquote class="team-quote">
              "<?php echo htmlspecialchars($m['quote_vi']); ?>"<br>
              <strong>English translation:</strong>
              "<?php echo htmlspecialchars($m['quote_en']); ?>"
            </blockquote>
          </dd>
        </div>
  <?php endforeach; ?>
      </dl>
<?php else: ?>
      <p>Team information is not available at the moment.</p>
<?php endif; ?>
    </section>

    <section class="panel">
      <h2>03. Our Group Photo</h2>
      <p class="section-intro">
        A picture of our team working together and sharing ideas to create a better learning experience.
      </p>
      <figure class="group-photo">
        <img src="Groupimg.jpg" alt="The three members of our team together">
      </figure>
    </section>

    <section class="panel">
      <h2>04. Fun Facts About Our Team</h2>
      <p class="section-intro">Discover some interesting facts about our team members.</p>

      <div class="table-container">
        <table class="fun-facts">
          <caption>Table 1: Interesting Facts About Our Members</caption>
          <thead>
            <tr>
              <th scope="col">Member</th>
              <th scope="col">Dream Job</th>
              <th scope="col">Hobby</th>
              <th scope="col">Hometown</th>
            </tr>
          </thead>
          <tbody>
<?php foreach ($members as $m): ?>
            <tr>
              <td>Member <?php echo (int)$m['member_no']; ?>: <?php echo htmlspecialchars($m['full_name']); ?></td>
              <td><?php echo htmlspecialchars($m['dream_job']); ?></td>
              <td><?php echo htmlspecialchars($m['hobby']); ?></td>
              <td><?php echo htmlspecialchars($m['hometown']); ?></td>
            </tr>
<?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>

    <section class="acknowledgement">
      <h2>Acknowledgement of Country</h2>
      <p>
        We acknowledge the Traditional Custodians of the lands on which we live, learn and work.
        We pay our respects to Aboriginal and Torres Strait Islander peoples, their Elders past
        and present, and recognise their continuing connection to land, waters and community.
      </p>
    </section>
  </main>

<?php include 'includes/footer.inc'; ?>