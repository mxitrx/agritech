<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name   = trim($_POST['name']);
    $age    = intval($_POST['age']);
    $address = trim($_POST['address']);

    $stmt = $conn->prepare("INSERT INTO farmers (name, age, address, user_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sisi", $name, $age, $address, $_SESSION['user_id']);

    if ($stmt->execute()) {
        echo "<script>alert('เพิ่มข้อมูลเกษตรกรสำเร็จ'); window.location='dashboard.php';</script>";
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
