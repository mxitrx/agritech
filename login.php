<?php
session_start();

include 'db.php';

// การเชื่อมต่อฐานข้อมูล (ปรับค่าให้ตรงกับของคุณ)
$servername = "localhost";
$username   = "root";     // ชื่อผู้ใช้ MySQL
$password   = "";         // รหัสผ่าน MySQL
$dbname     = "farmbook"; // ชื่อฐานข้อมูล

$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    // ตรวจสอบว่ามี email นี้อยู่ในระบบหรือไม่
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // ดึงข้อมูลรหัสผ่านจากฐานข้อมูล
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        // ตรวจสอบรหัสผ่าน
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['email']   = $email;

            header("Location: dashboard.php"); // ไปหน้า dashboard หลัง login สำเร็จ
            exit();
        } else {
            echo "<script>alert('รหัสผ่านไม่ถูกต้อง'); window.location='index.html';</script>";
        }
    } else {
        echo "<script>alert('ไม่พบบัญชีนี้ในระบบ'); window.location='index.html';</script>";
    }

    $stmt->close();
}
$conn->close();
?>
