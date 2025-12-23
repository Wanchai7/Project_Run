<?php
require 'db_connect.php';
session_start();

// ตรวจสอบสิทธิ์ Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// โค้ดสำหรับจัดการลบรายการวิ่ง (ถ้ามีคนกดลบ)
if (isset($_GET['delete_race'])) {
    $id = $_GET['delete_race'];
    $conn->query("DELETE FROM `ประเภทการแข่งขัน` WHERE `รหัสประเภท` = '$id'");
    header("Location: admin_dashboard.php#race"); // กลับมาที่แท็บเดิม
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบจัดการหลังบ้าน - City Marathon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Sarabun', sans-serif;
        }

        .nav-pills .nav-link {
            border-radius: 10px;
            color: #6c757d;
        }

        .nav-pills .nav-link.active {
            background-color: #0d6efd;
            color: white;
        }

        .card-custom {
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body>

    <?php include 'navbar.php'; ?>

    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-lg-3 mb-4">
                <div class="card card-custom border-0 p-3">
                    <h5 class="fw-bold mb-4 ps-2">เมนูจัดการ</h5>
                    <div class="nav flex-column nav-pills" id="adminTab" role="tablist" aria-orientation="vertical">
                        <button class="nav-link active text-start mb-2" id="summary-tab" data-bs-toggle="pill"
                            data-bs-target="#summary" type="button">
                            <i class="bi bi-speedometer2 me-2"></i> สรุปภาพรวม
                        </button>
                        <button class="nav-link text-start mb-2" id="regs-tab" data-bs-toggle="pill"
                            data-bs-target="#regs" type="button">
                            <i class="bi bi-people me-2"></i> รายชื่อผู้สมัคร
                        </button>
                        <button class="nav-link text-start mb-2" id="race-tab" data-bs-toggle="pill"
                            data-bs-target="#race" type="button">
                            <i class="bi bi-trophy me-2"></i> จัดการรายการวิ่ง
                        </button>
                        <button class="nav-link text-start mb-2 text-danger" onclick="location.href='logout.php'">
                            <i class="bi bi-box-arrow-right me-2"></i> ออกจากระบบ
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="tab-content" id="adminTabContent">

                    <div class="tab-pane fade show active" id="summary" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="card card-custom border-0 bg-primary text-white p-4">
                                    <h6>นักวิ่งทั้งหมด</h6>
                                    <h2 class="fw-bold">
                                        <?php echo $conn->query("SELECT COUNT(*) FROM นักวิ่ง")->fetch_row()[0]; ?>
                                    </h2>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-custom border-0 bg-success text-white p-4">
                                    <h6>ยอดเงินรวม (฿)</h6>
                                    <h2 class="fw-bold">
                                        <?php
                                        $total = $conn->query("SELECT SUM(ราคา) FROM การลงทะเบียน JOIN เรทราคา ON การลงทะเบียน.รหัสราคา=เรทราคา.รหัสราคา WHERE สถานะ='ชำระแล้ว'")->fetch_row()[0];
                                        echo number_format($total ?? 0);
                                        ?>
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="regs" role="tabpanel">
                        <div class="card card-custom border-0 shadow-sm overflow-hidden">
                            <div class="p-3 bg-white border-bottom d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold">รายชื่อนักวิ่งล่าสุด</h5>
                                <button class="btn btn-sm btn-success"><i class="bi bi-download"></i></button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-3">ID</th>
                                            <th>ชื่อ-นามสกุล</th>
                                            <th>ระยะทาง</th>
                                            <th>สถานะ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $r = $conn->query("SELECT * FROM `การลงทะเบียน` JOIN `นักวิ่ง` ON `การลงทะเบียน`.`รหัสนักวิ่ง`=`นักวิ่ง`.`รหัสนักวิ่ง` JOIN `ประเภทการแข่งขัน` ON `การลงทะเบียน`.`รหัสประเภท`=`ประเภทการแข่งขัน`.`รหัสประเภท` JOIN `เรทราคา` ON `การลงทะเบียน`.`รหัสราคา`=`เรทราคา`.`รหัสราคา` ORDER BY `รหัสใบสมัคร` DESC");
                                        while ($row = $r->fetch_assoc()):
                                            $stClass = ($row['สถานะ'] == 'ชำระแล้ว') ? 'text-success' : 'text-warning';
                                            ?>
                                            <tr>
                                                <td class="ps-3 text-muted">#<?= $row['รหัสใบสมัคร'] ?></td>
                                                <td><strong><?= $row['ชื่อจริง'] ?></strong></td>
                                                <td><span class="badge bg-info text-dark"><?= $row['ชื่อรายการ'] ?></span>
                                                </td>
                                                <td class="<?= $stClass ?> fw-bold"><?= $row['สถานะ'] ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="race" role="tabpanel">
                        <div class="card card-custom border-0 p-4 mb-4">
                            <h5 class="fw-bold mb-3">เพิ่มระยะการแข่งขันใหม่</h5>
                            <form action="api_add_race.php" method="POST" class="row g-2">
                                <div class="col-md-6"><input type="text" name="name" class="form-control"
                                        placeholder="ชื่อรายการ (เช่น 5KM)"></div>
                                <div class="col-md-4"><input type="number" name="dist" class="form-control"
                                        placeholder="ระยะทาง (กม.)"></div>
                                <div class="col-md-2"><button class="btn btn-primary w-100">บันทึก</button></div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>