<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600;700&display=swap');

    :root {
        --primary-orange: #ff512f;
        --secondary-orange: #f09819;
        --primary-navy: #1e3c72;
        --secondary-navy: #2a5298;
    }

    /* Navbar styling */
    .navbar-custom {
        background: linear-gradient(to right, var(--primary-navy), var(--secondary-navy)) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 1rem 0;
    }

    .navbar-brand {
        font-weight: 700;
        letter-spacing: 1px;
        font-size: 1.5rem;
    }

    .nav-link {
        font-weight: 500;
        transition: color 0.3s;
    }

    .nav-link:hover {
        color: var(--secondary-orange) !important;
    }

    /* Mobile Adjustments */
    @media (max-width: 768px) {
        h1.display-1 {
            font-size: 2.5rem !important;
        }

        .hero-section {
            height: 350px !important;
        }
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="bi bi-person-running me-2"></i>Chombueng Marathon
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="index.php">หน้าแรก</a></li>
                <li class="nav-item"><a class="nav-link" href="register.php">สมัครวิ่ง</a></li>
                <li class="nav-item"><a class="nav-link" href="check_status.php">เช็คสถานะ</a></li>

                <li class="nav-item">
                    <a class="nav-link fw-bold text-warning" href="payment.php">
                        <i class="bi bi-cash-coin me-1"></i> แจ้งชำระเงิน
                    </a>
                </li>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                        <li class="nav-item ms-lg-2">
                            <a class="nav-link text-warning fw-bold border border-warning rounded px-3 py-1"
                                href="admin_list.php">
                                <i class="bi bi-speedometer2"></i> Admin Panel
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item ms-lg-3"><span class="text-white-50 small">สวัสดี,</span> <span
                                class="text-white fw-bold"><?php echo htmlspecialchars($_SESSION['username']); ?></span></li>
                    <?php endif; ?>

                    <li class="nav-item ms-2">
                        <a class="btn btn-outline-danger btn-sm rounded-pill px-3 mt-2 mt-lg-0" href="logout.php">
                            <i class="bi bi-box-arrow-right"></i> ออกจากระบบ
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-outline-light btn-sm rounded-pill px-4 mt-2 mt-lg-0"
                            href="login.php">เข้าสู่ระบบ</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-light text-primary btn-sm rounded-pill px-4 mt-2 mt-lg-0 fw-bold"
                            href="register_member.php">สมัครสมาชิก</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<div style="height: 76px;"></div>