<?php
// เริ่มต้น Session เพื่อใช้จัดการข้อมูลผู้ใช้งานที่ Login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// นำเข้าไฟล์เชื่อมต่อฐานข้อมูลและ Model
require_once 'config/db.php';
require_once 'models/Registration.php';

class RegistrationController
{
    private $db;
    private $registrationModel;

    public function __construct()
    {
        global $conn;
        $this->db = $conn;
        $this->registrationModel = new Registration($this->db);
    }

    /**
     * ฟังก์ชันหลักสำหรับจัดการ Request
     */
    public function handleRequest()
    {
        // รับค่า action จาก URL ถ้าไม่มีให้ไปหน้า list เป็นค่าเริ่มต้น
        $action = isset($_GET['action']) ? $_GET['action'] : 'list';

        switch ($action) {
            case 'create':
                $this->checkAuth(); // เช็ค Login ก่อนเข้าหน้าฟอร์ม
                $this->showRegistrationForm();
                break;
            case 'store':
                $this->checkAuth();
                $this->storeRegistration();
                break;
            case 'list':
            default:
                $this->index();
                break;
        }
    }

    /**
     * ตรวจสอบสิทธิ์การเข้าใช้งาน (ตัวอย่างเบื้องต้น)
     */
    private function checkAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: views/login.php");
            exit();
        }
    }

    /**
     * 1. แสดงรายการผู้สมัครทั้งหมด (หน้าหลังบ้าน)
     */
    public function index()
    {
        $registrations = $this->registrationModel->getAllRegistrations();

        // กำหนดหัวข้อหน้าเว็บ
        $pageTitle = "รายชื่อผู้สมัครทั้งหมด";

        // เรียกใช้ View โดยรวมไฟล์ส่วนประกอบตามโครงสร้างภาพของคุณ
        include 'views/navbar.php';
        include 'views/registration_list.php';
        include 'views/footer.php';
    }

    /**
     * 2. แสดงฟอร์มลงทะเบียน (หน้าบ้าน)
     */
    public function showRegistrationForm()
    {
        // ดึงข้อมูลประเภทการวิ่งจากตาราง RACE_CATEGORY
        $categories = $this->db->query("SELECT * FROM RACE_CATEGORY");

        // ดึงข้อมูลการจัดส่งจากตาราง SHIPPING_OPTION
        $shippingOptions = $this->db->query("SELECT * FROM SHIPPING_OPTION");

        $pageTitle = "ลงทะเบียนสมัครวิ่ง";

        include 'views/navbar.php';
        include 'views/register.php'; // ปรับตามชื่อไฟล์ในภาพของคุณ
        include 'views/footer.php';
    }

    /**
     * 3. รับค่าจากฟอร์มและบันทึกลงฐานข้อมูล
     */
    public function storeRegistration()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // ป้องกัน XSS เบื้องต้น
            $data = [
                'runner_id' => htmlspecialchars($_POST['runner_id']),
                'category_id' => htmlspecialchars($_POST['category_id']),
                'price_id' => htmlspecialchars($_POST['price_id']),
                'shipping_id' => htmlspecialchars($_POST['shipping_id']),
                'shirt_size' => htmlspecialchars($_POST['shirt_size']),
                'bib_number' => $this->generateBibNumber($_POST['category_id'])
            ];

            // เรียกใช้ Method ใน Model เพื่อบันทึก
            $result = $this->registrationModel->save($data);

            if ($result) {
                // บันทึกสำเร็จ ส่งไปหน้าตรวจสอบสถานะ
                $_SESSION['message'] = "ลงทะเบียนสมัครวิ่งสำเร็จแล้ว!";
                header("Location: index.php?action=list&status=success");
                exit();
            } else {
                $_SESSION['error'] = "เกิดข้อผิดพลาด: ไม่สามารถบันทึกข้อมูลได้";
                header("Location: index.php?action=create");
                exit();
            }
        }
    }

    /**
     * ฟังก์ชันช่วยสร้างเลข BIB อัตโนมัติ
     */
    private function generateBibNumber($categoryId)
    {
        $prefix = "";
        switch ($categoryId) {
            case 1:
                $prefix = "M";
                break;    // Marathon
            case 2:
                $prefix = "H";
                break;    // Half
            case 3:
                $prefix = "MINI";
                break; // Mini
            default:
                $prefix = "R";
        }
        // สุ่มตัวเลข 4 หลัก และเติม 0 ข้างหน้าให้ครบ (เช่น M0045)
        return $prefix . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }
}

// เริ่มต้นการทำงาน
$controller = new RegistrationController();
$controller->handleRequest();