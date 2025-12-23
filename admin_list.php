<?php
require 'db_connect.php';
session_start();

// 1. ตรวจสอบสิทธิ์ Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// 2. ฟังก์ชัน: อัปเดตสถานะการจ่ายเงิน
if (isset($_POST['update_status'])) {
    $reg_id = $_POST['reg_id'];
    $status = $_POST['status'];
    
    $sql = "UPDATE `การลงทะเบียน` SET `สถานะ` = '$status' WHERE `รหัสใบสมัคร` = '$reg_id'";
    
    if ($conn->query($sql)) {
        $_SESSION['swal'] = [
            'title' => 'บันทึกสำเร็จ!',
            'text' => 'อัปเดตสถานะเรียบร้อยแล้ว',
            'icon' => 'success'
        ];
    } else {
        $_SESSION['swal'] = [
            'title' => 'เกิดข้อผิดพลาด',
            'text' => 'ไม่สามารถอัปเดตข้อมูลได้',
            'icon' => 'error'
        ];
    }
    // Redirect กลับไปที่เดิมเพื่อล้างค่า POST (กันกด F5 แล้วส่งซ้ำ)
    header("Location: " . $_SERVER['PHP_SELF'] . "#regs");
    exit();
}

// 3. ฟังก์ชัน: ลบใบสมัคร
if (isset($_POST['delete_reg'])) {
    $del_id = $_POST['reg_id'];
    
    $sql_del = "DELETE FROM `การลงทะเบียน` WHERE `รหัสใบสมัคร` = '$del_id'";
    
    if ($conn->query($sql_del)) {
        $_SESSION['swal'] = [
            'title' => 'ลบข้อมูลสำเร็จ!',
            'text' => 'ข้อมูลถูกลบออกจากระบบแล้ว',
            'icon' => 'success'
        ];
    } else {
        $_SESSION['swal'] = [
            'title' => 'ลบข้อมูลไม่สำเร็จ',
            'text' => 'อาจมีข้อมูลที่เกี่ยวข้องในตารางอื่น',
            'icon' => 'error'
        ];
    }
    header("Location: " . $_SERVER['PHP_SELF'] . "#regs");
    exit();
}

// 4. ฟังก์ชัน: เพิ่มรายการวิ่ง (API Add Race) - ถ้าต้องการให้เด้งในหน้านี้ต้องรวมโค้ด insert มาไว้หน้านี้
// แต่ถ้าใช้ api_add_race.php แยก ให้ใช้วิธีส่ง session กลับมา
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบจัดการหลังบ้าน - City Marathon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { background-color: #f8f9fa; font-family: 'Sarabun', sans-serif; }
        .nav-pills .nav-link { border-radius: 10px; color: #6c757d; font-weight: 500; }
        .nav-pills .nav-link.active { background-color: #0d6efd; color: white; box-shadow: 0 4px 6px rgba(13, 110, 253, 0.2); }
        .nav-pills .nav-link:hover:not(.active) { background-color: #e9ecef; }
        .card-custom { border-radius: 15px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); }
        .table-action-col { width: 180px; }
    </style>
</head>

<body>

    <?php include 'navbar.php'; ?>

    <div class="container mt-5 mb-5">
        <div class="row">
            
            <div class="col-lg-3 mb-4">
                <div class="card card-custom border-0 p-3 sticky-top" style="top: 20px; z-index: 1;">
                    <h5 class="fw-bold mb-4 ps-2 border-start border-4 border-primary">&nbsp;เมนูจัดการ</h5>
                    <div class="nav flex-column nav-pills" id="adminTab" role="tablist">
                        <button class="nav-link active text-start mb-2" id="summary-tab" data-bs-toggle="pill" data-bs-target="#summary" type="button">
                            <i class="bi bi-speedometer2 me-2"></i> สรุปภาพรวม
                        </button>
                        <button class="nav-link text-start mb-2" id="regs-tab" data-bs-toggle="pill" data-bs-target="#regs" type="button">
                            <i class="bi bi-people me-2"></i> รายชื่อผู้สมัคร
                        </button>
                        <button class="nav-link text-start mb-2" id="race-tab" data-bs-toggle="pill" data-bs-target="#race" type="button">
                            <i class="bi bi-trophy me-2"></i> จัดการรายการวิ่ง
                        </button>
                        <hr>
                        <button class="nav-link text-start text-danger" onclick="confirmLogout()">
                            <i class="bi bi-box-arrow-right me-2"></i> ออกจากระบบ
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="tab-content" id="adminTabContent">

                    <div class="tab-pane fade show active" id="summary" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card card-custom border-0 bg-primary text-white p-4 h-100">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">นักวิ่งทั้งหมด</h6>
                                            <h2 class="fw-bold display-5 mb-0">
                                                <?php echo $conn->query("SELECT COUNT(*) FROM นักวิ่ง")->fetch_row()[0]; ?>
                                            </h2>
                                            <small>คน</small>
                                        </div>
                                        <i class="bi bi-people-fill display-4 opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card card-custom border-0 bg-success text-white p-4 h-100">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">ยอดเงินรวม (เฉพาะที่จ่ายแล้ว)</h6>
                                            <h2 class="fw-bold display-5 mb-0">
                                                <?php
                                                $total = $conn->query("SELECT SUM(ราคา) FROM การลงทะเบียน JOIN เรทราคา ON การลงทะเบียน.รหัสราคา=เรทราคา.รหัสราคา WHERE สถานะ='ชำระแล้ว'")->fetch_row()[0];
                                                echo number_format($total ?? 0);
                                                ?>
                                            </h2>
                                            <small>บาท</small>
                                        </div>
                                        <i class="bi bi-currency-bitcoin display-4 opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="regs" role="tabpanel">
                        <div class="card card-custom border-0 shadow-sm overflow-hidden">
                            <div class="p-3 bg-white border-bottom d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-list-ul"></i> รายชื่อผู้สมัครล่าสุด</h5>
                                <button class="btn btn-sm btn-outline-success"><i class="bi bi-file-earmark-excel"></i> Export Excel</button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-3">ID</th>
                                            <th>ชื่อ-นามสกุล</th>
                                            <th>รายการ</th>
                                            <th>สถานะ</th>
                                            <th class="table-action-col">จัดการ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql_regs = "SELECT * FROM `การลงทะเบียน` 
                                                     JOIN `นักวิ่ง` ON `การลงทะเบียน`.`รหัสนักวิ่ง`=`นักวิ่ง`.`รหัสนักวิ่ง` 
                                                     JOIN `ประเภทการแข่งขัน` ON `การลงทะเบียน`.`รหัสประเภท`=`ประเภทการแข่งขัน`.`รหัสประเภท` 
                                                     JOIN `เรทราคา` ON `การลงทะเบียน`.`รหัสราคา`=`เรทราคา`.`รหัสราคา` 
                                                     ORDER BY `รหัสใบสมัคร` DESC";
                                        $r = $conn->query($sql_regs);
                                        
                                        while ($row = $r->fetch_assoc()):
                                            $badgeClass = 'bg-secondary';
                                            if ($row['สถานะ'] == 'รอชำระเงิน') $badgeClass = 'bg-warning text-dark';
                                            if ($row['สถานะ'] == 'ชำระแล้ว') $badgeClass = 'bg-success';
                                            if ($row['สถานะ'] == 'ยกเลิก') $badgeClass = 'bg-danger';
                                        ?>
                                            <tr>
                                                <td class="ps-3 text-muted">#<?= $row['รหัสใบสมัคร'] ?></td>
                                                <td>
                                                    <div class="fw-bold"><?= htmlspecialchars($row['ชื่อจริง']) . " " . htmlspecialchars($row['นามสกุล']) ?></div>
                                                    <small class="text-muted"><i class="bi bi-tag"></i> <?= number_format($row['ราคา']) ?> บ.</small>
                                                </td>
                                                <td><span class="badge bg-info text-dark"><?= htmlspecialchars($row['ชื่อรายการ']) ?></span></td>
                                                <td><span class="badge rounded-pill <?= $badgeClass ?>"><?= $row['สถานะ'] ?></span></td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        
                                                        <form method="POST" action="" class="d-flex">
                                                            <input type="hidden" name="reg_id" value="<?= $row['รหัสใบสมัคร'] ?>">
                                                            <input type="hidden" name="update_status" value="1">
                                                            
                                                            <select name="status" class="form-select form-select-sm me-1" style="width: 100px;">
                                                                <option value="รอชำระเงิน" <?= $row['สถานะ'] == 'รอชำระเงิน' ? 'selected' : '' ?>>รอโอน</option>
                                                                <option value="ชำระแล้ว" <?= $row['สถานะ'] == 'ชำระแล้ว' ? 'selected' : '' ?>>ชำระแล้ว</option>
                                                                <option value="ยกเลิก" <?= $row['สถานะ'] == 'ยกเลิก' ? 'selected' : '' ?>>ยกเลิก</option>
                                                            </select>
                                                            
                                                            <button type="submit" class="btn btn-sm btn-outline-primary" title="บันทึก">
                                                                <i class="bi bi-check-lg"></i>
                                                            </button>
                                                        </form>

                                                        <form method="POST" action="" class="form-delete">
                                                            <input type="hidden" name="reg_id" value="<?= $row['รหัสใบสมัคร'] ?>">
                                                            <input type="hidden" name="delete_reg" value="1">
                                                            <button type="button" onclick="confirmDelete(this.form)" class="btn btn-sm btn-outline-danger" title="ลบ">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>

                                                    </div>
                                                </td>
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
                                <div class="col-md-6">
                                    <label class="form-label text-muted small">ชื่อรายการ</label>
                                    <input type="text" name="name" class="form-control" placeholder="เช่น Fun Run 5KM" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted small">ระยะทาง (กม.)</label>
                                    <input type="number" step="0.1" name="dist" class="form-control" placeholder="0.0" required>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button class="btn btn-primary w-100"><i class="bi bi-plus-circle me-1"></i> บันทึก</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // 1. ฟังก์ชันยืนยันการลบแบบ SweetAlert
        function confirmDelete(form) {
            Swal.fire({
                title: 'ยืนยันการลบ?',
                text: "ข้อมูลใบสมัครนี้จะหายไปถาวรและกู้คืนไม่ได้!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ใช่, ลบเลย!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // สั่ง submit ฟอร์มเมื่อกดยืนยัน
                }
            })
        }

        // 2. ฟังก์ชันยืนยัน Logout
        function confirmLogout() {
            Swal.fire({
                title: 'ออกจากระบบ?',
                text: "คุณต้องการออกจากระบบใช่หรือไม่",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'ใช่, ออกจากระบบ',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'logout.php';
                }
            })
        }

        // 3. แสดง Alert จาก Session (PHP ส่งมา)
        <?php if (isset($_SESSION['swal'])): ?>
            Swal.fire({
                title: '<?= $_SESSION['swal']['title'] ?>',
                text: '<?= $_SESSION['swal']['text'] ?>',
                icon: '<?= $_SESSION['swal']['icon'] ?>',
                timer: 3000,
                showConfirmButton: false
            });
            <?php unset($_SESSION['swal']); // ลบ session ทิ้งเมื่อแสดงแล้ว ?>
        <?php endif; ?>

        // 4. Script จัดการ Tab (ให้หน้าเว็บจำว่าอยู่ Tab ไหนหลังรีเฟรช)
        document.addEventListener("DOMContentLoaded", function(){
            var hash = window.location.hash;
            if (hash) {
                var triggerEl = document.querySelector('#adminTab button[data-bs-target="' + hash + '"]');
                if (triggerEl) {
                    var tab = new bootstrap.Tab(triggerEl);
                    tab.show();
                }
            }
            var tabList = [].slice.call(document.querySelectorAll('#adminTab button'));
            tabList.forEach(function (triggerEl) {
                triggerEl.addEventListener('shown.bs.tab', function (event) {
                    history.pushState(null, null, event.target.getAttribute('data-bs-target'));
                });
            });
        });
    </script>
</body>
</html>