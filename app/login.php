<?php
session_start();
// error_reporting(0);
include("includes/dbconnection.php");

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = md5($_POST['password']);

  $sql = "SELECT ID,UserName,Password,Status from tb_admin WHERE UserName = :adname AND Password = :adpass";
  $query = $dbh->prepare($sql);
  $query->bindParam(':adname', $username, PDO::PARAM_STR);
  $query->bindParam(':adpass', $password, PDO::PARAM_STR);
  $query->execute();

  $result = $query->fetchAll(PDO::FETCH_OBJ);

  if ($query->rowCount() > 0) {
    $user = $result[0];

    // 1. Session พื้นฐาน
    $_SESSION['adname'] = $user->UserName;
    $_SESSION['adid'] = $user->ID;
    $_SESSION['user_status'] = $user->Status; 

    // 2. Session แยกสำหรับ Admin
    if ($user->Status != 1) { 
        $_SESSION['admin_id'] = $user->ID;       
        $_SESSION['admin_name'] = $user->UserName; 
    }

    // --- Redirect ---
    if($user->Status == 1) {
        echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
    } else {
        echo "<script type='text/javascript'> document.location = 'admin/dashboard.php'; </script>";
    }

  } else {
    echo "<script>alert('ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง'); </script>";
  } 
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>เข้าสู่ระบบ | Mitchaship Shop</title>
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
    .login-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    .login-header {
        background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
        color: white;
        padding: 30px 20px;
        text-align: center;
    }
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    .btn-login {
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
      <a class="navbar-brand fw-bold text-primary" href="index.php">
        <i class="bi bi-shop"></i> Mitchaship<span class="text-dark">Shop</span>
      </a>
      
      <div class="ms-auto">
         <a href="index.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3 me-2">
             <i class="bi bi-house-door"></i> หน้าแรก
         </a>
         <a href="register.php" class="btn btn-primary btn-sm rounded-pill px-3">
             <i class="bi bi-person-plus"></i> สมัครสมาชิก
         </a>
      </div>
    </div>
  </nav>

  <div class="container mt-5 flex-grow-1">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            
            <div class="card login-card">
                <div class="login-header">
                    <i class="bi bi-person-circle display-4 mb-2 d-block"></i>
                    <h3 class="fw-bold m-0">เข้าสู่ระบบ</h3>
                    <small>Welcome Back!</small>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form action="" method="post" name="login">
                        
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">USERNAME</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-primary"></i></span>
                                <input type="text" class="form-control bg-light border-start-0" placeholder="ชื่อผู้ใช้" name="username" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold">PASSWORD</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-primary"></i></span>
                                <input type="password" class="form-control bg-light border-start-0" placeholder="รหัสผ่าน" name="password" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"> 
                                <label class="form-check-label small text-muted" for="remember">จดจำฉันไว้</label>
                            </div>
                            </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill btn-login shadow-sm" name="login">
                            เข้าสู่ระบบ <i class="bi bi-arrow-right-circle ms-1"></i>
                        </button>

                        <div class="text-center mt-4 pt-3 border-top">
                            <span class="small text-muted">ยังไม่มีบัญชีสมาชิก?</span>
                            <a href="register.php" class="small fw-bold text-decoration-none">สมัครสมาชิกใหม่</a>
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