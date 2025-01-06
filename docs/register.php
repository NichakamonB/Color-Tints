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
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // ตรวจสอบให้แน่ใจว่ารหัสผ่านและยืนยันรหัสผ่านตรงกัน
    if ($password != $confirm_password) {
        echo "Password and confirm password do not match.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
    } elseif (strlen($password) < 8) {
        echo "Password must be at least 8 characters long.";
    } else {
        // ตรวจสอบว่าอีเมลมีในฐานข้อมูลหรือไม่
        $email_check = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $email_check->bind_param("s", $email);
        $email_check->execute();
        $result = $email_check->get_result();
        
        if ($result->num_rows > 0) {
            echo "Email is already registered.";
        } else {
            // เข้ารหัสรหัสผ่านก่อนบันทึก
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // สร้างคำสั่ง SQL เพื่อบันทึกข้อมูลลงในตาราง `users`
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            // ดำเนินการบันทึกข้อมูล
            if ($stmt->execute()) {
                echo "New record created successfully";
                // Redirect after success
                header("Location: success_page.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }

            // ปิด statement
            $stmt->close();
        }

        // ปิด email_check statement
        $email_check->close();
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
