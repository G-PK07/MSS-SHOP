<?php
session_start();
// error_reporting(0); // ปิด error ในหน้าผลิตจริง
include("includes/dbconnection.php");

if (isset($_POST['submit'])) {
    try {
        $usname = $_POST['usname'];
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $email = $_POST['email'];

        // เช็คว่าซ้ำไหม
        $sql_check = "SELECT ID FROM tb_admin WHERE UserName = :username OR Email = :email";
        $query_check = $dbh->prepare($sql_check);
        $query_check->bindParam(':username', $username, PDO::PARAM_STR);
        $query_check->bindParam(':email', $email, PDO::PARAM_STR);
        $query_check->execute();

        if ($query_check->rowCount() > 0) {
            echo "<script>alert('Username หรือ Email นี้มีผู้ใช้งานแล้ว กรุณาใช้ชื่ออื่น');</script>";
            echo "<script>window.history.back();</script>"; 
            exit; 
        }
        
        // บันทึกข้อมูล (ค่าเริ่มต้น Status อาจจะต้องกำหนดใน Database หรือเพิ่ม Default Value)
        $sql = "INSERT INTO tb_admin (AdminName, UserName, Password, Email, Status) VALUES (:adminname, :username, :password, :email, 1)";
        // *หมายเหตุ: ผมเติม Status = 1 (User ทั่วไป) ไปให้เลย เพื่อความชัวร์
        
        $query = $dbh->prepare($sql);
        $query->bindParam(':adminname', $usname, PDO::PARAM_STR);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        
        $query->execute();
        
        $LastInsertId = $dbh->lastInsertId();
        
        if ($LastInsertId > 0) {
            echo "<script>alert('ลงทะเบียนเรียบร้อยแล้ว! กรุณาเข้าสู่ระบบ');</script>";
            echo "<script>window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('เกิดข้อผิดพลาด ลองใหม่อีกครั้ง');</script>";
        }

    } catch (PDOException $e) {
        exit("Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>สมัครสมาชิก | Mitchaship Shop</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600&display=swap" rel="stylesheet">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    body {
      font-family: 'Prompt', sans-serif;
      background-color: #f8f9fa;
    }
    .register-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    .register-header {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%); /* สีเขียวไล่เฉดให้ต่างจาก Login นิดหน่อย หรือจะใช้สีฟ้าก็ได้ */
        color: white;
        padding: 30px 20px;
        text-align: center;
    }
    .form-control:focus {
        border-color: #198754;
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
    }
    .btn-register {
        padding: 10px 20px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    .main-footer {
      background-color: #212529;
      color: #aaa;
      padding-top: 30px;
      padding-bottom: 20px;
      margin-top: 60px;
    }
  </style>
</head>

<body class="d-flex flex-column min-vh-100">

  <nav class="navbar navbar-expand-sm bg-white navbar-light shadow-sm sticky-top">
    <div class="container">
      <a class="navbar-brand fw-bold text-success" href="index.php">
        <i class="bi bi-shop"></i> Mitchaship<span class="text-dark">Shop</span>
      </a>
      
      <div class="ms-auto">
         <a href="index.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3 me-2">
             <i class="bi bi-house-door"></i> หน้าแรก
         </a>
         <a href="login.php" class="btn btn-success btn-sm rounded-pill px-3">
             <i class="bi bi-box-arrow-in-right"></i> เข้าสู่ระบบ
         </a>
      </div>
    </div>
  </nav>

  <div class="container mt-5 flex-grow-1">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            
            <div class="card register-card">
                <div class="register-header">
                    <i class="bi bi-person-plus-fill display-4 mb-2 d-block"></i>
                    <h3 class="fw-bold m-0">สมัครสมาชิกใหม่</h3>
                    <small>Create your account</small>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form action="register.php" method="post">
                        
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">ชื่อ-นามสกุล</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person-vcard text-success"></i></span>
                                <input type="text" class="form-control bg-light border-start-0" placeholder="กรอกชื่อ-นามสกุล" name="usname" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">อีเมล</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-success"></i></span>
                                <input type="email" class="form-control bg-light border-start-0" placeholder="ตัวอย่าง: name@example.com" name="email" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">ชื่อผู้ใช้ (Username)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-success"></i></span>
                                <input type="text" class="form-control bg-light border-start-0" placeholder="ตั้งชื่อผู้ใช้สำหรับเข้าระบบ" name="username" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold">รหัสผ่าน</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-success"></i></span>
                                <input type="password" class="form-control bg-light border-start-0" placeholder="กำหนดรหัสผ่าน" name="password" required>
                            </div>
                        </div>
                        
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" required id="agree">
                            <label class="form-check-label small text-muted" for="agree">
                                ฉันยอมรับ <a href="#" class="text-success text-decoration-none">เงื่อนไขและนโยบายความเป็นส่วนตัว</a>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-success w-100 rounded-pill btn-register shadow-sm" name="submit">
                            ลงทะเบียน <i class="bi bi-check-circle ms-1"></i>
                        </button>

                        <div class="text-center mt-4 pt-3 border-top">
                            <span class="small text-muted">มีบัญชีอยู่แล้ว?</span>
                            <a href="login.php" class="small fw-bold text-decoration-none text-success">เข้าสู่ระบบที่นี่</a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
  </div>

  <footer class="main-footer text-center">
      <div class="container">
          <p class="mb-0 small text-white-50">&copy; 2026 Mitchaship Shop. All Rights Reserved.</p>
      </div>
  </footer>

</body>
</html>