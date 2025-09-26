<?php
session_start();
include 'db.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// ดึงข้อมูลผู้ใช้
$stmt = $conn->prepare("SELECT fullname, email, created_at FROM users WHERE id = ?");
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
  <title>Dashboard - FarmBook</title>
  <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <style>
    .menu {
      margin-top: 20px;
    }
    .menu a {
      display: block;
      margin: 10px 0;
      padding: 10px;
      background: #4CAF50;
      color: white;
      text-align: center;
      text-decoration: none;
      border-radius: 5px;
    }
    .menu a:hover {
      background: #45a049;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>ยินดีต้อนรับ, <?php echo htmlspecialchars($user['fullname']); ?> 🎉</h1>
    <p><strong>อีเมล:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    <p><strong>สมัครใช้งานเมื่อ:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>

    <div class="menu">
      <a href="profile.php">👤 โปรไฟล์ส่วนตัว</a>
      <a href="fetch_farmers.php">🌾 ข้อมูลเกษตรกร</a>
      <a href="fetch_planting.php">🌱 ข้อมูลการเพาะปลูก</a>
      <a href="add_farmer_form.html">➕ เพิ่มเกษตรกร</a>
      <a href="add_planting_form.html">➕ เพิ่มการเพาะปลูก</a>
      <a href="logout.php">🚪 ออกจากระบบ</a>
    </div>
  </div>
</body>
</html>
