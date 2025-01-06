<?php
// การตั้งค่าการเชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_registration";

// สร้างการเชื่อมต่อกับฐานข้อมูล
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่าได้ส่งข้อมูลมาในฟอร์มแล้วหรือยัง
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับข้อมูลจากฟอร์ม
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // ตรวจสอบให้แน่ใจว่ารหัสผ่านและยืนยันรหัสผ่านตรงกัน
    if ($password != $confirm_password) {
        echo "Password and confirm password do not match.";
    } else {
        // เข้ารหัสรหัสผ่านก่อนบันทึก
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // ใช้คำสั่ง SQL แบบ prepared statement เพื่อป้องกัน SQL Injection
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        // ดำเนินการบันทึกข้อมูล
        if ($stmt->execute()) {
            echo "New record created successfully";
            // Redirect หลังจากบันทึกสำเร็จ
            header("Location: success_page.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        // ปิด statement
        $stmt->close();
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
