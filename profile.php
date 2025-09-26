<?php
session_start();
include 'db.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// ถ้ามีการอัปโหลดรูปใหม่
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['profile_pic'])) {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_name = basename($_FILES["profile_pic"]["name"]);
    $target_file = $target_dir . time() . "_" . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // ตรวจสอบว่าเป็นไฟล์รูป
    $check = getimagesize($_FILES["profile_pic"]["tmp_name"]);
    if ($check !== false) {
        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
            // อัปเดต path รูปลง DB
            $stmt = $conn->prepare("UPDATE users SET profile_pic = ? WHERE id = ?");
            $stmt->bind_param("si", $target_file, $user_id);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// ดึงข้อมูลผู้ใช้
$stmt = $conn->prepare("SELECT fullname, email, created_at, profile_pic FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>โปรไฟล์ส่วนตัว - FarmBook</title>
  <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <style>
    .profile-pic {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #4CAF50;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>โปรไฟล์ส่วนตัว</h1>

    <div class="profile-box" style="text-align:center;">
      <?php if (!empty($user['profile_pic'])): ?>
        <img src="<?php echo htmlspecialchars($user['profile_pic']); ?>" alt="Profile Picture" class="profile-pic">
      <?php else: ?>
        <img src="default.png" alt="Default Profile" class="profile-pic">
      <?php endif; ?>

      <form action="profile.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="profile_pic" accept="image/*" required>
        <button type="submit">อัปโหลดรูป</button>
      </form>

      <p><strong>ชื่อ-นามสกุล:</strong> <?php echo htmlspecialchars($user['fullname']); ?></p>
      <p><strong>อีเมล:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
      <p><strong>สมัครใช้งานเมื่อ:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>
    </div>

    <br>
    <a href="dashboard.php">⬅ กลับไปหน้า Dashboard</a>
  </div>
</body>
</html>
