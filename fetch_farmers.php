<?php
session_start();
include 'db.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// ดึงข้อมูลเกษตรกรที่ผูกกับ user_id นี้
$stmt = $conn->prepare("SELECT id, name, age, address, created_at FROM farmers WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>รายการเกษตรกร</title>
  <link rel="stylesheet" href="style.css">
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 8px;
      text-align: left;
    }
    th {
      background: #f2f2f2;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>ข้อมูลเกษตรกรของคุณ</h1>
    <?php if ($result->num_rows > 0): ?>
      <table>
        <tr>
          <th>ชื่อ</th>
          <th>อายุ</th>
          <th>ที่อยู่</th>
          <th>เพิ่มเมื่อ</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?php echo htmlspecialchars($row['name']); ?></td>
          <td><?php echo htmlspecialchars($row['age']); ?></td>
          <td><?php echo htmlspecialchars($row['address']); ?></td>
          <td><?php echo htmlspecialchars($row['created_at']); ?></td>
        </tr>
        <?php endwhile; ?>
      </table>
    <?php else: ?>
      <p>ยังไม่มีข้อมูลเกษตรกร</p>
    <?php endif; ?>

    <br>
    <a href="dashboard.php">⬅ กลับไป Dashboard</a>
  </div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
