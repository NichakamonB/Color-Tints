<?php
// การตั้งค่าการเชื่อมต่อฐานข้อมูล
$servername = "localhost";  // หรือ IP ของเซิร์ฟเวอร์ MySQL
$username = "root";         // ชื่อผู้ใช้ MySQL (ปรับตามที่คุณใช้)
$password = "";             // รหัสผ่าน MySQL (หากมี)
$dbname = "user_registration"; // ชื่อฐานข้อมูลที่สร้าง

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

        // สร้างคำสั่ง SQL เพื่อบันทึกข้อมูลลงในตาราง `users`
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

        // ตรวจสอบและดำเนินการบันทึกข้อมูล
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
