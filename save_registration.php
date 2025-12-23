<?php
require 'db_connect.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fn = $_POST['ชื่อจริง'];
    $ln = $_POST['นามสกุล'];
    $id = $_POST['เลขบัตรประชาชน'];
    $bd = $_POST['วันเกิด'];
    $gd = $_POST['เพศ'];
    $ph = $_POST['เบอร์โทรศัพท์'];
    $em = $_POST['อีเมล'];
    $md = $_POST['โรคประจำตัว'];
    $cid = $_POST['รหัสประเภท'];
    $rt = $_POST['ประเภทนักวิ่ง'];
    $pid = ($rt == 'ผู้สูงอายุ') ? 2 : ($rt == 'ผู้พิการ' ? 3 : 1);
    $sid = $_POST['รหัสขนส่ง'] ?? 1;
    $sz = $_POST['ไซส์เสื้อ'];

    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare("INSERT INTO `นักวิ่ง` (`ชื่อจริง`, `นามสกุล`, `เลขบัตรประชาชน`, `วันเกิด`, `เพศ`, `เบอร์โทรศัพท์`, `อีเมล`, `โรคประจำตัว`) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->bind_param("ssssssss", $fn, $ln, $id, $bd, $gd, $ph, $em, $md);
        $stmt->execute();
        $rid = $conn->insert_id;

        $stmt2 = $conn->prepare("INSERT INTO `การลงทะเบียน` (`รหัสนักวิ่ง`, `รหัสประเภท`, `รหัสราคา`, `รหัสขนส่ง`, `ไซส์เสื้อ`, `สถานะ`) VALUES (?,?,?,?,?,'รอชำระเงิน')");
        $stmt2->bind_param("iiiis", $rid, $cid, $pid, $sid, $sz);
        $stmt2->execute();

        $conn->commit();
        echo "<script>alert('✅ ลงทะเบียนสำเร็จ! กรุณาชำระเงินในขั้นตอนถัดไป'); window.location.href='index.php';</script>";
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}
?>