<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marathon 2025 - หน้าหลัก</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600;800&display=swap');

        /* ตั้งค่าสีหลัก */
        :root {
            --primary-orange: #ff512f;
            --secondary-orange: #f09819;
            --primary-navy: #1e3c72;
            --secondary-navy: #2a5298;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Hero Section (Banner หลัก) */
        .hero-section {
            background: linear-gradient(rgba(15, 23, 42, 0.75), rgba(15, 23, 42, 0.75)),
                url('https://img.nmcdn.io/e1/w:2000,h:964,v:1/marathonsports/wp-content/uploads/2024/10/Large-group-of-runners-running-a-marathon.webp?s=b791c95a');
            background-size: cover;
            background-position: center;
            height: 90vh;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
        }

        /* ปุ่ม Action สีส้มไล่เฉด */
        .btn-action {
            background: linear-gradient(45deg, var(--primary-orange), var(--secondary-orange));
            border: none;
            color: white;
            box-shadow: 0 4px 15px rgba(240, 152, 25, 0.4);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(240, 152, 25, 0.6);
            color: white;
        }

        /* ปุ่ม Outline ขาวใส */
        .btn-outline-custom {
            border: 2px solid rgba(255, 255, 255, 0.8);
            color: white;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            transition: all 0.3s;
        }

        .btn-outline-custom:hover {
            background: white;
            color: var(--primary-navy);
            transform: translateY(-3px);
        }

        /* การ์ดไฮไลท์ */
        .feature-card {
            transition: all 0.3s;
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            background: white;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        }

        .icon-box {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #f8f9fa;
        }
    </style>
</head>

<body>

    <?php include 'navbar.php'; ?>

    <section class="hero-section">
        <div class="container">
            <span class="badge bg-warning text-dark mb-4 px-4 py-2 rounded-pill fw-bold fs-6 shadow-sm">
                🚀 เปิดรับสมัครแล้ววันนี้!
            </span>
            <h1 class="display-1 fw-bold mb-3" style="text-shadow: 0 5px 25px rgba(0,0,0,0.5); letter-spacing: 2px;">
                Marathon

            </h1>
            <p class="fs-3 fw-light mb-5 text-white-50" style="max-width: 700px; margin: 0 auto;">
                ปลุกพลังในตัวคุณ ท้าทายขีดจำกัด สู่เส้นชัยแห่งความภาคภูมิใจ <br>
                <small class="fs-6 mt-2 d-block text-white-50">15 ธันวาคม 2568 | สวนสาธารณะใจกลางเมือง</small>
            </p>

            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="register.php" class="btn btn-action btn-lg px-5 py-3 rounded-pill fw-bold fs-5">
                    <i class="bi bi-person-running me-2"></i> สมัครวิ่งเลย
                </a>
                <a href="check_status.php" class="btn btn-outline-custom btn-lg px-5 py-3 rounded-pill fw-bold fs-5">
                    <i class="bi bi-search me-2"></i> เช็คสถานะ
                </a>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h6 class="text-primary fw-bold text-uppercase ls-2">Highlights</h6>
                <h2 class="fw-bold text-dark display-5">ทำไมต้องงานนี้?</h2>
                <p class="text-muted lead">สิ่งที่นักวิ่งจะได้รับจากประสบการณ์ครั้งนี้</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card h-100 p-4 text-center">
                        <div class="icon-box text-primary bg-primary bg-opacity-10">
                            <i class="bi bi-map fs-1"></i>
                        </div>
                        <h4 class="fw-bold mb-3">เส้นทางธรรมชาติ</h4>
                        <p class="text-muted">วิ่งผ่านสวนป่าใจกลางเมือง สูดอากาศบริสุทธิ์ตลอดเส้นทาง
                            ออกแบบเส้นทางมาตรฐานสากล</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card feature-card h-100 p-4 text-center">
                        <div class="icon-box text-warning bg-warning bg-opacity-10">
                            <i class="bi bi-award fs-1"></i>
                        </div>
                        <h4 class="fw-bold mb-3">เหรียญ Finisher</h4>
                        <p class="text-muted">เหรียญดีไซน์พิเศษ Limited Edition สำหรับผู้พิชิตเส้นชัย
                            ผลิตจากวัสดุคุณภาพสูง</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card feature-card h-100 p-4 text-center">
                        <div class="icon-box text-danger bg-danger bg-opacity-10">
                            <i class="bi bi-heart-pulse fs-1"></i>
                        </div>
                        <h4 class="fw-bold mb-3">ความปลอดภัยสูงสุด</h4>
                        <p class="text-muted">ทีมแพทย์และจุดพยาบาลตลอดเส้นทาง พร้อมประกันอุบัติเหตุสำหรับนักวิ่งทุกท่าน
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>