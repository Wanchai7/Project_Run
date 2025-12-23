<?php
session_start();
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
    <title>ลงทะเบียนวิ่ง</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .page-header {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://cdn.britannica.com/93/253593-138-0DD5803B/History-of-the-marathon.jpg?w=800&h=450&c=crop');
            background-size: cover;
            background-position: center;
            height: 350px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .form-wrapper {
            max-width: 900px;
            margin: -80px auto 50px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            padding: 40px;
            position: relative;
            z-index: 10;
        }

        .section-head {
            color: #1e3c72;
            border-left: 5px solid #ff512f;
            padding-left: 15px;
            margin: 30px 0 20px;
            font-weight: 700;
        }

        /* เพิ่มสไตล์ปุ่ม Action ให้เด่นขึ้น */
        .btn-action {
            background: linear-gradient(45deg, #ff512f, #dd2476);
            border: none;
            color: white;
            transition: all 0.3s;
        }

        .btn-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(221, 36, 118, 0.3);
            color: white;
        }
    </style>
</head>

<body>

    <?php include 'navbar.php'; ?>

    <header class="page-header">
        <div class="text-center">
            <h1 class="fw-bold display-4">ลงทะเบียนแข่งขัน</h1>
            <p class="fs-5 text-white-50">กรอกข้อมูลของท่านให้ครบถ้วนเพื่อเข้าร่วมกิจกรรม</p>
        </div>
    </header>

    <div class="container">
        <div class="form-wrapper">
            <form id="regisForm" action="save_registration.php" method="POST">

                <h4 class="section-head">1. ข้อมูลส่วนตัว (Personal Info)</h4>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label text-muted small">ชื่อจริง</label><input type="text"
                            class="form-control form-control-lg bg-light border-0" name="ชื่อจริง" required></div>
                    <div class="col-md-6"><label class="form-label text-muted small">นามสกุล</label><input type="text"
                            class="form-control form-control-lg bg-light border-0" name="นามสกุล" required></div>
                    <div class="col-md-6"><label class="form-label text-muted small">เลขบัตรประชาชน</label><input
                            type="text" class="form-control form-control-lg bg-light border-0" name="เลขบัตรประชาชน"
                            maxlength="13" required></div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">วันเกิด</label>
                        <input type="date" class="form-control form-control-lg bg-light border-0" id="birthDate"
                            name="วันเกิด" onchange="checkAge()" required>
                        <small id="ageDisplay" class="text-primary fw-bold ms-1"></small>
                    </div>
                    <div class="col-md-6"><label class="form-label text-muted small">เพศ</label>
                        <select class="form-select form-select-lg bg-light border-0" name="เพศ">
                            <option value="ชาย">ชาย</option>
                            <option value="หญิง">หญิง</option>
                        </select>
                    </div>
                    <div class="col-md-6"><label class="form-label text-muted small">เบอร์โทรศัพท์</label><input
                            type="tel" class="form-control form-control-lg bg-light border-0" name="เบอร์โทรศัพท์">
                    </div>
                    <div class="col-md-12"><label class="form-label text-muted small">อีเมล</label><input type="email"
                            class="form-control form-control-lg bg-light border-0" name="อีเมล" required></div>

                    <div class="col-12 mt-4">
                        <div class="p-3 rounded border border-danger bg-danger-subtle bg-opacity-10">
                            <label class="fw-bold text-danger mb-2"><i class="bi bi-heart-pulse"></i> ข้อมูลทางการแพทย์
                                (สำคัญ)</label>
                            <div class="d-flex gap-3 mb-2">
                                <div class="form-check"><input class="form-check-input" type="radio" name="med_option"
                                        id="medNo" value="no" checked onchange="toggleMed()"><label
                                        class="form-check-label" for="medNo">สุขภาพแข็งแรง (ไม่มีโรค)</label></div>
                                <div class="form-check"><input class="form-check-input" type="radio" name="med_option"
                                        id="medYes" value="yes" onchange="toggleMed()"><label class="form-check-label"
                                        for="medYes">มีโรคประจำตัว</label></div>
                            </div>
                            <input type="text" class="form-control" id="medInput" name="โรคประจำตัว" value="ไม่มี"
                                readonly placeholder="โปรดระบุโรค...">
                        </div>
                    </div>
                </div>

                <h4 class="section-head">2. รายละเอียดการวิ่ง (Race Details)</h4>
                <div class="row g-4">
                    <div class="col-md-8">
                        <label class="form-label fw-bold">ระยะทางที่ต้องการวิ่ง</label>
                        <select class="form-select form-select-lg border-2 border-primary" id="raceCategory"
                            name="รหัสประเภท" onchange="calculatePrice()" required>
                            <option value="" data-price="0" selected disabled>-- เลือกการแข่งขัน --</option>
                            <option value="1" data-price="1200">🏁 Full Marathon (42 km) - 1,200 บ.</option>
                            <option value="2" data-price="900">🥇 Half Marathon (21 km) - 900 บ.</option>
                            <option value="3" data-price="600">🥈 Mini Marathon (10.5 km) - 600 บ.</option>
                            <option value="4" data-price="400">🥉 Fun Run (5 km) - 400 บ.</option>
                        </select>

                        <label class="form-label mt-3 fw-bold">ประเภทผู้สมัคร</label>
                        <select class="form-select" id="runnerType" name="ประเภทนักวิ่ง" onchange="calculatePrice()">
                            <option value="บุคคลทั่วไป" data-discount="0">บุคคลทั่วไป</option>
                            <option value="ผู้สูงอายุ" data-discount="200" id="optionSenior">ผู้สูงอายุ 60+ (ลด 200 บ.)
                            </option>
                            <option value="ผู้พิการ" data-discount="500">ผู้พิการ (ลด 500 บ.)</option>
                        </select>
                        <small id="ageWarning" class="text-danger" style="display:none;">* อายุไม่ถึง 60 ปี
                            ไม่สามารถเลือกเรทผู้สูงอายุได้</small>
                    </div>

                    <div class="col-md-4">
                        <div class="p-3 border rounded bg-light text-center h-100">
                            <i class="bi bi-person-arms-up fs-1 text-secondary"></i>
                            <label class="d-block mt-2 fw-bold">ไซส์เสื้อวิ่ง</label>
                            <select class="form-select mt-2 mb-3" name="ไซส์เสื้อ">
                                <option value="S">S (36")</option>
                                <option value="M">M (38")</option>
                                <option value="L">L (40")</option>
                                <option value="XL">XL (42")</option>
                            </select>

                            <hr>

                            <i class="bi bi-gift fs-3 text-secondary"></i>
                            <label class="d-block mt-1 fw-bold">ของที่ระลึก (ซื้อเพิ่ม)</label>
                            <select class="form-select mt-2 border-warning" id="souvenirSelect" name="ของที่ระลึก"
                                onchange="calculatePrice()">
                                <option value="ไม่รับ" data-price="0">ไม่รับ</option>
                                <option value="หมวกวิ่ง" data-price="150">หมวกวิ่ง (+150 บ.)</option>
                                <option value="ผ้าขนหนู" data-price="100">ผ้าขนหนู (+100 บ.)</option>
                                <option value="กระบอกน้ำ" data-price="250">กระบอกน้ำ (+250 บ.)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <h4 class="section-head">3. การจัดส่ง & ชำระเงิน</h4>
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="รหัสขนส่ง" id="ship1" value="1" data-cost="0"
                                onchange="syncShip(this)" checked>
                            <label class="btn btn-outline-secondary py-3" for="ship1">รับเอง (ฟรี)</label>
                            <input type="radio" class="btn-check" name="รหัสขนส่ง" id="ship2" value="2" data-cost="60"
                                onchange="syncShip(this)">
                            <label class="btn btn-outline-secondary py-3" for="ship2">EMS (+60บ.)</label>
                        </div>
                        <select id="shippingOption" style="display:none;">
                            <option value="1" data-cost="0"></option>
                            <option value="2" data-cost="60"></option>
                        </select>
                    </div>
                    <div class="col-md-6 mt-3 mt-md-0">
                        <div class="bg-dark text-white p-4 rounded-4 text-center position-relative overflow-hidden">
                            <div
                                style="position:absolute; top:-10px; right:-10px; font-size:5rem; color:rgba(255,255,255,0.1);">
                                <i class="bi bi-cash-coin"></i>
                            </div>
                            <span class="text-white-50">ยอดชำระสุทธิ</span>
                            <h2 class="display-4 fw-bold mb-0"><span id="totalDisplay">0</span> <small
                                    class="fs-4">฿</small></h2>
                            <input type="hidden" id="totalAmount" name="ยอดรวม" value="0">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-action w-100 py-3 mt-4 rounded-pill fw-bold fs-5 shadow">
                    ยืนยันการสมัครวิ่ง <i class="bi bi-arrow-right-circle ms-2"></i>
                </button>
            </form>
        </div>
    </div>

    <script>
        function syncShip(r) {
            document.getElementById('shippingOption').value = r.value;
            calculatePrice();
        }

        function toggleMed() {
            let y = document.getElementById('medYes').checked;
            let i = document.getElementById('medInput');
            if (y) {
                i.readOnly = false;
                i.value = "";
                i.focus();
            } else {
                i.readOnly = true;
                i.value = "ไม่มี";
            }
        }

        function checkAge() {
            let bd = document.getElementById('birthDate').value;
            if (bd) {
                let d = new Date(bd);
                let now = new Date();
                let age = now.getFullYear() - d.getFullYear();
                if (now.getMonth() < d.getMonth() || (now.getMonth() == d.getMonth() && now.getDate() < d.getDate())) age--;
                document.getElementById('ageDisplay').innerText = "อายุ: " + age + " ปี";
                let s = document.getElementById('optionSenior');
                if (age < 60) {
                    s.disabled = true;
                    if (document.getElementById('runnerType').value == 'ผู้สูงอายุ') {
                        document.getElementById('runnerType').value = 'บุคคลทั่วไป';
                        calculatePrice();
                        document.getElementById('ageWarning').style.display = 'block';
                    }
                } else {
                    s.disabled = false;
                    document.getElementById('ageWarning').style.display = 'none';
                }
            }
        }

        function calculatePrice() {
            // 1. ราคาค่าวิ่ง
            let rs = document.getElementById('raceCategory');
            let bp = parseFloat(rs.options[rs.selectedIndex].getAttribute('data-price')) || 0;

            // 2. ส่วนลด
            let ts = document.getElementById('runnerType');
            let dc = parseFloat(ts.options[ts.selectedIndex].getAttribute('data-discount')) || 0;

            // 3. ค่าส่ง
            let ss = document.getElementById('shippingOption');
            let sc = parseFloat(ss.options[ss.selectedIndex].getAttribute('data-cost')) || 0;

            // 4. [เพิ่ม] ราคาของที่ระลึก
            let sv = document.getElementById('souvenirSelect');
            let sv_price = parseFloat(sv.options[sv.selectedIndex].getAttribute('data-price')) || 0;

            // คำนวณรวม
            let t = (bp - dc) + sc + sv_price;

            if (t < 0) t = 0;

            // แสดงผล
            document.getElementById('totalDisplay').innerText = t.toLocaleString();
            document.getElementById('totalAmount').value = t;
        }
    </script>
    <?php include 'footer.php'; ?>
</body>

</html>