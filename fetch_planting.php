<?php
// ไฟล์นี้จะถูกเรียกโดย JavaScript (Fetch API) จาก dashboard.php
// หน้าที่ของมันคือดึงข้อมูลแล้วสร้างเป็น HTML Fragment เพื่อส่งกลับไปแสดงผล

session_start();
include 'db.php'; // เรียกใช้ไฟล์เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่าผู้ใช้ล็อกอินอยู่หรือไม่ (เพื่อความปลอดภัย)
if (!isset($_SESSION['user_id'])) {
    echo "<p style='color:red;'>ข้อผิดพลาด: ไม่ได้รับอนุญาตให้เข้าถึงข้อมูล</p>";
    exit();
}

// เตรียมคำสั่ง SQL เพื่อดึงข้อมูลการเพาะปลูก
// ใช้ JOIN เพื่อดึงชื่อเกษตรกร (fullname) จากตาราง farmers มาแสดงด้วย
$sql = "SELECT 
            p.id, 
            p.crop_name, 
            p.planting_date, 
            p.harvest_date, 
            p.status,
            f.fullname AS farmer_name 
        FROM planting p
        JOIN farmers f ON p.farmer_id = f.id
        ORDER BY p.planting_date DESC";

$result = $conn->query($sql);

?>

<style>
    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        font-size: 14px;
    }
    .data-table th, .data-table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }
    .data-table th {
        background-color: #4CAF50;
        color: white;
        text-align: center;
    }
    .data-table tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    .data-table tr:hover {
        background-color: #ddd;
    }
    .no-data {
        text-align: center;
        padding: 20px;
        color: #888;
    }
</style>

<h2>🌱 ข้อมูลการเพาะปลูกทั้งหมด</h2>

<?php if ($result && $result->num_rows > 0): ?>
    <table class="data-table">
        <thead>
            <tr>
                <th>ลำดับ</th>
                <th>ชื่อเกษตรกร</th>
                <th>พืชที่ปลูก</th>
                <th>วันที่ปลูก</th>
                <th>วันที่คาดว่าจะเก็บเกี่ยว</th>
                <th>สถานะ</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $count = 1;
            // วนลูปแสดงข้อมูลทีละแถว
            while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td style="text-align:center;"><?php echo $count++; ?></td>
                    <td><?php echo htmlspecialchars($row['farmer_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['crop_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['planting_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['harvest_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p class="no-data">ไม่พบข้อมูลการเพาะปลูก</p>
<?php endif; ?>

<?php
// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>