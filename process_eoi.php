<?php
// Chặn truy cập trực tiếp: chỉ chấp nhận dữ liệu gửi từ form (POST)
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: apply.php");
    exit();
}

require_once "settings.php";

// Làm sạch dữ liệu: bỏ khoảng trắng thừa và dấu \
function clean($value) {
    return trim(stripslashes($value));
}

// Lấy dữ liệu từ form (tên trong $_POST[...] phải trùng thuộc tính name trong apply.php)
$ref      = strtoupper(clean($_POST["ref"] ?? ""));
$first    = clean($_POST["first"] ?? "");
$last     = clean($_POST["last"] ?? "");
$dobInput = clean($_POST["dob"] ?? "");
$gender   = clean($_POST["gender"] ?? "");
$street   = clean($_POST["street"] ?? "");
$suburb   = clean($_POST["suburb"] ?? "");
$state    = clean($_POST["state"] ?? "");
$postcode = clean($_POST["postcode"] ?? "");
$email    = clean($_POST["email"] ?? "");
$phone    = clean($_POST["phone"] ?? "");
$other    = clean($_POST["other_skills"] ?? "");

// Skills là mảng (do name="skills[]"), có thể rỗng nếu không tick ô nào
$skillsIn = (isset($_POST["skills"]) && is_array($_POST["skills"])) ? $_POST["skills"] : [];

$errors = [];

// Job reference: đúng 5 ký tự chữ hoặc số
if (!preg_match('/^[A-Za-z0-9]{5}$/', $ref)) {
    $errors[] = "Job reference number must be exactly 5 letters or numbers.";
}

// Họ và tên: 1–20 chữ cái
if (!preg_match('/^[A-Za-z]{1,20}$/', $first)) {
    $errors[] = "First name must be 1–20 letters only.";
}
if (!preg_match('/^[A-Za-z]{1,20}$/', $last)) {
    $errors[] = "Last name must be 1–20 letters only.";
}

// Ngày sinh dd/mm/yyyy -> yyyy-mm-dd
$dob = "";
$d = DateTime::createFromFormat("d/m/Y", $dobInput);
if ($d && $d->format("d/m/Y") === $dobInput) {
    $age = $d->diff(new DateTime("today"))->y;
    if ($d > new DateTime("today") || $age < 15 || $age > 80) {
        $errors[] = "Applicants must be between 15 and 80 years old.";
    } else {
        $dob = $d->format("Y-m-d");
    }
} else {
    $errors[] = "Date of birth must be a valid date in dd/mm/yyyy format.";
}

// Giới tính: chỉ chấp nhận 3 giá trị của form
if (!in_array($gender, ["female", "male", "other"], true)) {
    $errors[] = "Please select a gender.";
}

// Địa chỉ: không rỗng, tối đa 40 ký tự
if ($street === "" || strlen($street) > 40) {
    $errors[] = "Street address is required (max 40 characters).";
}
if ($suburb === "" || strlen($suburb) > 40) {
    $errors[] = "Suburb/Town is required (max 40 characters).";
}

// State và postcode: chữ số đầu của postcode phải đúng với state
$stateMap = [
    "VIC" => ["3", "8"], "NSW" => ["1", "2"], "QLD" => ["4", "9"], "NT" => ["0"],
    "WA"  => ["6"],      "SA"  => ["5"],      "TAS" => ["7"],      "ACT" => ["0"]
];
if (!array_key_exists($state, $stateMap)) {
    $errors[] = "Please select a valid state.";
} elseif (!preg_match('/^[0-9]{4}$/', $postcode)) {
    $errors[] = "Postcode must be exactly 4 digits.";
} elseif (!in_array($postcode[0], $stateMap[$state], true)) {
    $errors[] = "Postcode $postcode does not match the state $state.";
}

// Email và số điện thoại
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
    $errors[] = "Please enter a valid email address.";
}
if (!preg_match('/^[0-9]{8,12}$/', $phone)) {
    $errors[] = "Phone number must be 8–12 digits.";
}

// Skills: chỉ nhận giá trị nằm trong danh sách cho phép
$allowedSkills = ["html", "css", "design", "accessibility"];
$skills = [];
foreach ($skillsIn as $s) {
    if (in_array($s, $allowedSkills, true)) {
        $skills[] = $s;
    }
}
$skills = implode(", ", array_unique($skills));

// ===== Lưu vào database nếu không có lỗi =====
$eoiNumber = null;

if (empty($errors)) {
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

    if (!$conn) {
        $errors[] = "Cannot connect to the database. Please try again later.";
    } else {
        // Prepared statement: dấu ? là chỗ điền dữ liệu, chống SQL injection
        $sql = "INSERT INTO eoi (JobRef, FirstName, LastName, DOB, Gender, StreetAddress,
                                 Suburb, State, Postcode, Email, Phone, Skills, OtherSkills)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = mysqli_prepare($conn, $sql);

        // 13 dấu ? tương ứng 13 chữ "s" (string) và 13 biến, đúng thứ tự cột
        mysqli_stmt_bind_param($stmt, "sssssssssssss",
            $ref, $first, $last, $dob, $gender, $street,
            $suburb, $state, $postcode, $email, $phone, $skills, $other);

        if (mysqli_stmt_execute($stmt)) {
            $eoiNumber = mysqli_insert_id($conn);   // mã EOI vừa được tạo
        } else {
            $errors[] = "Could not save your application. Please try again.";
        }
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
}
// ===== Hiển thị kết quả =====
$title = "Application Result";
$description = "Result of your job application.";
$active = "apply.php";
include "includes/header.inc";
?>

  <main class="main">
    <?php if (empty($errors)): ?>
      <h1 class="page-title">Application received</h1>
      <p>Thank you, <?php echo htmlspecialchars($first); ?>. Your EOI number is
         <strong><?php echo (int)$eoiNumber; ?></strong>. Please keep it for your records.</p>
      <p><a href="index.php">Back to home</a></p>
    <?php else: ?>
      <h1 class="page-title">Please fix the following</h1>
      <ul>
        <?php foreach ($errors as $e): ?>
          <li><?php echo htmlspecialchars($e); ?></li>
        <?php endforeach; ?>
      </ul>
      <p><a href="apply.php">Back to the application form</a></p>
    <?php endif; ?>
  </main>

<?php include "includes/footer.inc"; ?>