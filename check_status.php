<?php
session_start();
require 'db_connect.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=please_login");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจสอบสถานะ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>

<body>

    <?php include 'navbar.php'; ?>

    <div class="container mt-5 mb-5 flex-grow-1">
        <div class="card card-custom mx-auto border-0" style="max-width: 700px;">
            <div class="card-header bg-white border-0 pt-4 text-center">
                <h3 class="fw-bold text-primary"><i class="bi bi-search"></i> ตรวจสอบสถานะการสมัคร</h3>
                <p class="text-muted small">กรอกเลขบัตรประชาชนเพื่อค้นหาข้อมูลของท่าน</p>
            </div>
            <div class="card-body p-4">
                <form method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-lg border-2"
                        placeholder="เลขบัตรประชาชน 13 หลัก" required>
                    <button class="btn btn-primary px-4 fw-bold" type="submit">ค้นหา</button>
                </form>

                <?php
                if (isset($_GET['search'])) {
                    $s = $_GET['search'];
                    $r = $conn->query("SELECT * FROM `การลงทะเบียน` JOIN `นักวิ่ง` ON `การลงทะเบียน`.`รหัสนักวิ่ง`=`นักวิ่ง`.`รหัสนักวิ่ง` JOIN `ประเภทการแข่งขัน` ON `การลงทะเบียน`.`รหัสประเภท`=`ประเภทการแข่งขัน`.`รหัสประเภท` WHERE `นักวิ่ง`.`เลขบัตรประชาชน`='$s'");
                    if ($r->num_rows > 0) {
                        while ($row = $r->fetch_assoc()) {
                            $st = $row['สถานะ'];
                            $bg = $st == 'ชำระแล้ว' ? 'bg-success' : 'bg-warning text-dark';
                            echo "<div class='alert border mt-4 bg-light shadow-sm'>";
                            echo "<div class='d-flex justify-content-between align-items-center mb-2'>";
                            echo "<h5 class='mb-0 fw-bold text-dark'>{$row['ชื่อจริง']} {$row['นามสกุล']}</h5>";
                            echo "<span class='badge $bg rounded-pill px-3'>$st</span>";
                            echo "</div>";
                            echo "<hr>";
                            echo "<div class='row text-muted small'>";
                            echo "<div class='col-6'>รายการ: <strong class='text-dark'>{$row['ชื่อรายการ']}</strong></div>";
                            echo "<div class='col-6 text-end'>Bib: <strong class='text-dark'>" . ($row['หมายเลข_bib'] ?: '-') . "</strong></div>";
                            echo "</div></div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger mt-4 text-center'><i class='bi bi-x-circle'></i> ไม่พบข้อมูลการสมัคร</div>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>