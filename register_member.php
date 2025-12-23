<?php
require 'db_connect.php';
$msg = "";
$msg_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    // สูตรลับ: ถ้าตั้งชื่อ admin ให้เป็นแอดมิน (เอาออกได้ถ้าใช้งานจริง)
    $role = ($username == 'admin') ? 'admin' : 'user';

    try {
        $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $password, $role);
        $stmt->execute();
        header("Location: login.php?registered=1");
        exit();
    } catch (Exception $e) {
        $msg = "ชื่อผู้ใช้นี้ถูกใช้งานแล้ว กรุณาใช้ชื่ออื่น";
        $msg_type = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก - City Marathon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600;700&display=swap');

        body {
            font-family: 'Sarabun', sans-serif;
            background: linear-gradient(rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.8)),
                url('https://images.unsplash.com/photo-1551966775-a4ddc8df052b?q=80&w=2070');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .register-wrapper {
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

    <div class="register-wrapper">
        <div class="card card-custom p-4">
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 60px; height: 60px;">
                        <i class="bi bi-person-plus-fill fs-2"></i>
                    </div>
                    <h3 class="fw-bold">สมัครสมาชิกใหม่</h3>
                    <p class="text-muted small">สร้างบัญชีเพื่อลงทะเบียนวิ่ง</p>
                </div>

                <?php if ($msg)
                    echo "<div class='alert alert-$msg_type text-center small'>$msg</div>"; ?>

                <form method="POST">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="reg_username" name="username" placeholder="Username"
                            required>
                        <label for="reg_username">ตั้งชื่อผู้ใช้ (Username)</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" id="reg_password" name="password"
                            placeholder="Password" required>
                        <label for="reg_password">ตั้งรหัสผ่าน (Password)</label>
                    </div>
                    <button type="submit"
                        class="btn btn-success w-100 py-3 rounded-pill fw-bold shadow-sm text-uppercase ls-1">
                        ลงทะเบียน
                    </button>
                </form>

                <div class="text-center mt-4 small text-secondary">
                    มีบัญชีอยู่แล้ว? <a href="login.php"
                        class="text-decoration-none fw-bold text-success">เข้าสู่ระบบ</a>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>

</html>