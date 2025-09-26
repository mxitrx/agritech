<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $crop_name  = trim($_POST['crop_name']);
    $start_date = $_POST['start_date'];
    $area       = trim($_POST['area']);

    $stmt = $conn->prepare("INSERT INTO planting (crop_name, start_date, area, user_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $crop_name, $start_date, $area, $_SESSION['user_id']);

    if ($stmt->execute()) {
        echo "<script>alert('เพิ่มข้อมูลการเพาะปลูกสำเร็จ'); window.location='dashboard.php';</script>";
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
