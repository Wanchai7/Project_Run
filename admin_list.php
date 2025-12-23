<?php
require 'db_connect.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-5 mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold"><i class="bi bi-speedometer2 text-primary"></i> แผงควบคุมผู้ดูแลระบบ</h4>
            <button class="btn btn-success btn-sm"><i class="bi bi-file-earmark-spreadsheet"></i> Export Excel</button>
        </div>

        <div class="card card-custom border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-nowrap">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th class="py-3 ps-4">ID</th>
                                <th>ชื่อนักวิ่ง</th>
                                <th>สุขภาพ/โรค</th>
                                <th>ระยะทาง</th>
                                <th>ราคา</th>
                                <th>สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $r = $conn->query("SELECT * FROM `การลงทะเบียน` JOIN `นักวิ่ง` ON `การลงทะเบียน`.`รหัสนักวิ่ง`=`นักวิ่ง`.`รหัสนักวิ่ง` JOIN `ประเภทการแข่งขัน` ON `การลงทะเบียน`.`รหัสประเภท`=`ประเภทการแข่งขัน`.`รหัสประเภท` JOIN `เรทราคา` ON `การลงทะเบียน`.`รหัสราคา`=`เรทราคา`.`รหัสราคา` ORDER BY `รหัสใบสมัคร` DESC");
                            while ($row = $r->fetch_assoc()) {
                                $med = $row['โรคประจำตัว'];
                                $medBadge = ($med == 'ไม่มี' || $med == '-') ? '<span class="badge bg-secondary opacity-25">ปกติ</span>' : "<span class='badge bg-danger'>$med</span>";
                                $st = $row['สถานะ'];
                                $stClass = $st == 'ชำระแล้ว' ? 'text-success' : 'text-warning';
                                echo "<tr>";
                                echo "<td class='ps-4 fw-bold text-muted'>#{$row['รหัสใบสมัคร']}</td>";
                                echo "<td><div class='fw-bold'>{$row['ชื่อจริง']} {$row['นามสกุล']}</div><small class='text-muted'>{$row['เบอร์โทรศัพท์']}</small></td>";
                                echo "<td>$medBadge</td>";
                                echo "<td><span class='badge bg-info text-dark'>{$row['ชื่อรายการ']}</span></td>";
                                echo "<td>" . number_format($row['ราคา']) . " ฿</td>";
                                echo "<td class='fw-bold $stClass'><i class='bi bi-circle-fill small me-1'></i> $st</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>