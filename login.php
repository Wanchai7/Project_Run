<?php
session_start();
require 'db_connect.php';
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: admin_list.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "รหัสผ่านไม่ถูกต้อง";
        }
    } else {
        $error = "ไม่พบชื่อผู้ใช้นี้";
    }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - City Marathon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600;700&display=swap');

        body {
            font-family: 'Sarabun', sans-serif;
            /* พื้นหลังเดียวกับธีมหลัก */
            background: linear-gradient(rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.8)),
                url('https://images.unsplash.com/photo-1476480862126-209bfaa8edc8?q=80&w=2070');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* จัดกึ่งกลางเฉพาะส่วน Content */
        .login-wrapper {
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>

<body>

    <?php include 'navbar.php'; ?>

    <div class="login-wrapper">
        <div class="card card-custom p-4">
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 60px; height: 60px;">
                        <i class="bi bi-person-lock fs-2"></i>
                    </div>
                    <h3 class="fw-bold">เข้าสู่ระบบ</h3>
                    <p class="text-muted small">City Marathon System</p>
                </div>

                <?php
                if (isset($_GET['error']) && $_GET['error'] == 'please_login') {
                    echo '<div class="alert alert-warning text-center small"><i class="bi bi-lock-fill"></i> กรุณาเข้าสู่ระบบก่อนใช้งาน</div>';
                }
                ?>
                <?php if (isset($_GET['registered']))
                    echo "<div class='alert alert-success text-center small'>สมัครสมาชิกสำเร็จ! กรุณาล็อคอิน</div>"; ?>
                <?php if ($error)
                    echo "<div class='alert alert-danger text-center small'>$error</div>"; ?>

                <form method="POST">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username"
                            required>
                        <label for="username">ชื่อผู้ใช้ (Username)</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                            required>
                        <label for="password">รหัสผ่าน (Password)</label>
                    </div>
                    <button type="submit"
                        class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm text-uppercase ls-1">
                        Log In
                    </button>
                </form>

                <div class="text-center mt-4 small text-secondary">
                    ยังไม่มีบัญชี? <a href="register_member.php"
                        class="text-decoration-none fw-bold text-primary">สมัครสมาชิกใหม่</a>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>

</html>