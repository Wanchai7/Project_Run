<?php
session_start();
require 'db_connect.php'; //

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reg_id = $_POST['reg_id'];
    $amount = $_POST['amount'];
    $bank = $_POST['bank'];
    $pay_time = $_POST['pay_time'];

    // จัดการอัปโหลดไฟล์
    $target_dir = "uploads/slips/";
    // สร้างโฟลเดอร์ถ้ายังไม่มี
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $filename = time() . "_" . basename($_FILES["slip_file"]["name"]);
    $target_file = $target_dir . $filename;
    $uploadOk = 1;

    // ตรวจสอบว่าเป็นไฟล์รูปภาพจริงไหม
    $check = getimagesize($_FILES["slip_file"]["tmp_name"]);
    if ($check !== false) {
        if (move_uploaded_file($_FILES["slip_file"]["tmp_name"], $target_file)) {
            // 1. บันทึกลงตาราง การชำระเงิน
            $sql_pay = "INSERT INTO `การชำระเงิน` (`รหัสใบสมัคร`, `จำนวนเงิน`, `ธนาคารที่โอน`, `วันเวลาที่โอน`, `รูปสลิป`) 
                        VALUES ('$reg_id', '$amount', '$bank', '$pay_time', '$filename')";

            // 2. อัปเดตสถานะในตาราง การลงทะเบียน เป็น 'รอตรวจสอบ'
            $sql_update = "UPDATE `การลงทะเบียน` SET `สถานะ` = 'รอตรวจสอบ' WHERE `รหัสใบสมัคร` = '$reg_id'";

            if ($conn->query($sql_pay) && $conn->query($sql_update)) {
                echo "<script>
                    alert('บันทึกการแจ้งโอนเรียบร้อย! กรุณารอเจ้าหน้าที่ตรวจสอบ');
                    window.location.href = 'check_status.php';
                </script>";
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            echo "ขออภัย, เกิดข้อผิดพลาดในการอัปโหลดรูปภาพ";
        }
    } else {
        echo "ไฟล์ไม่ใช่รูปภาพ";
    }
}
?>