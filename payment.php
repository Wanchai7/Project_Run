<?php
session_start();
require 'db_connect.php'; //
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แจ้งชำระเงิน - City Marathon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>

<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <div class="container mt-5 mb-5">
        <div class="card shadow border-0 mx-auto" style="max-width: 800px;">
            <div class="card-header bg-primary text-white p-4 text-center">
                <h3 class="fw-bold mb-0"><i class="bi bi-cash-coin"></i> แจ้งชำระเงิน (Payment)</h3>
            </div>
            <div class="card-body p-4">

                <form method="GET" class="mb-4">
                    <label class="form-label fw-bold">ค้นหาด้วยเลขบัตรประชาชน</label>
                    <div class="input-group">
                        <input type="text" name="search_id" class="form-control form-control-lg"
                            placeholder="กรอกเลขบัตรประชาชน 13 หลัก" required
                            value="<?php echo $_GET['search_id'] ?? ''; ?>">
                        <button class="btn btn-primary px-4" type="submit">ค้นหา</button>
                    </div>
                </form>

                <?php
                if (isset($_GET['search_id'])) {
                    $id_card = $_GET['search_id'];
                    // ค้นหาใบสมัครที่สถานะเป็น 'รอชำระเงิน'
                    $sql = "SELECT * FROM `การลงทะเบียน` 
                            JOIN `นักวิ่ง` ON `การลงทะเบียน`.`รหัสนักวิ่ง` = `นักวิ่ง`.`รหัสนักวิ่ง`
                            JOIN `เรทราคา` ON `การลงทะเบียน`.`รหัสราคา` = `เรทราคา`.`รหัสราคา`
                            WHERE `นักวิ่ง`.`เลขบัตรประชาชน` = '$id_card' AND `การลงทะเบียน`.`สถานะ` = 'รอชำระเงิน'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $total_price = $row['ราคา']; // สมมติว่าดึงราคามา (ต้องบวกค่าส่งเพิ่มถ้ามีในตรรกะ DB ของคุณ)
                        ?>

                        <div class="alert alert-info">
                            <h5 class="fw-bold"><i class="bi bi-person-check"></i> ข้อมูลผู้สมัคร</h5>
                            <p class="mb-1">ชื่อ: <strong><?php echo $row['ชื่อจริง'] . " " . $row['นามสกุล']; ?></strong></p>
                            <p class="mb-0">ยอดที่ต้องชำระ: <strong
                                    class="text-danger fs-4"><?php echo number_format($total_price); ?> บาท</strong></p>
                        </div>

                        <div class="row">
                            <div class="col-md-5 text-center mb-3">
                                <div class="p-3 border rounded bg-white">
                                    <h6 class="fw-bold text-primary">สแกนจ่ายที่นี่</h6>
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg"
                                        alt="QR Code" class="img-fluid" style="max-width: 150px;">
                                    <p class="small text-muted mt-2">ธนาคารกสิกรไทย<br>000-0-00000-0<br>ชื่อบัญชี: City Marathon
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-7">
                                <form action="save_payment.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="reg_id" value="<?php echo $row['รหัสใบสมัคร']; ?>">
                                    <input type="hidden" name="amount" value="<?php echo $total_price; ?>">

                                    <div class="mb-3">
                                        <label class="form-label">ธนาคารที่โอน</label>
                                        <select name="bank" class="form-select" required>
                                            <option value="KBank">กสิกรไทย (KBank)</option>
                                            <option value="SCB">ไทยพาณิชย์ (SCB)</option>
                                            <option value="KTB">กรุงไทย (KTB)</option>
                                            <option value="BBL">กรุงเทพ (BBL)</option>
                                            <option value="Other">อื่นๆ</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">วัน-เวลา ที่โอน (ตามสลิป)</label>
                                        <input type="datetime-local" name="pay_time" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">แนบหลักฐานการโอน (สลิป)</label>
                                        <input type="file" name="slip_file" class="form-control" accept="image/*" required>
                                    </div>

                                    <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                                        <i class="bi bi-send"></i> ยืนยันการแจ้งโอน
                                    </button>
                                </form>
                            </div>
                        </div>

                        <?php
                    } else {
                        echo '<div class="alert alert-warning text-center">ไม่พบข้อมูลที่ต้องชำระ หรือท่านได้ชำระเงินไปแล้ว</div>';
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>

</html>