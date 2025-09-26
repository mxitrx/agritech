<?php
$servername = "localhost";
$username   = "root";      // ชื่อผู้ใช้ MySQL
$password   = "";          // รหัสผ่าน MySQL
$dbname     = "farmbook";  // ชื่อฐานข้อมูล

$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

// ตั้งค่า charset ให้รองรับภาษาไทย
$conn->set_charset("utf8");
?>
