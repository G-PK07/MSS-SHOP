<?php
session_start();
error_reporting(0); // ปิด Error ชั่วคราวเพื่อให้หน้าเว็บสวยงาม (ควรเปิดตอน Dev)
include("includes/dbconnection.php");

// 1. เช็คว่าล็อกอินยัง? (ใช้ตัวแปร adid ของลูกค้า)
if (strlen($_SESSION['adid']) == 0) {
    header('location:login.php');
    exit();
} else {
    // --- ส่วนบันทึกข้อมูล ---
    if (isset($_POST['submit'])) {
        $uid = $_SESSION['adid'];
        $fname = $_POST['fullname']; 
        $newpassword = $_POST['newpassword']; 

        try {
            // กรณี A: เปลี่ยนรหัสผ่านด้วย
            if (!empty($newpassword)) {
                $password_hash = md5($newpassword); 
                $sql = "UPDATE tb_admin SET AdminName=:fname, Password=:pass WHERE ID=:uid";
                $query = $dbh->prepare($sql);
                $query->bindParam(':fname', $fname, PDO::PARAM_STR);
                $query->bindParam(':pass', $password_hash, PDO::PARAM_STR);
                $query->bindParam(':uid', $uid, PDO::PARAM_STR);
                $_SESSION['adname'] = $fname; 
            } 
            // กรณี B: ไม่เปลี่ยนรหัส
            else {
                $sql = "UPDATE tb_admin SET AdminName=:fname WHERE ID=:uid";
                $query = $dbh->prepare($sql);
                $query->bindParam(':fname', $fname, PDO::PARAM_STR);
                $query->bindParam(':uid', $uid, PDO::PARAM_STR);
                $_SESSION['adname'] = $fname; 
            }

            $query->execute();
            echo '<script>alert("แก้ไขข้อมูลเรียบร้อยแล้ว");</script>';
            echo "<script>window.location.href='user_profile.php'</script>";

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>แก้ไขข้อมูลส่วนตัว | Mitchaship Shop</title>
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
      background-color: #f8f9fa; /* สีพื้นหลังเดียวกับ index */
    }
    .profile-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        background: white;
    }
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    .main-footer {
      background-color: #212529;
      color: #aaa;
      padding-top: 40px;
      padding-bottom: 20px;
      margin-top: 80px;
    }
    .main-footer a { color: #aaa; text-decoration: none; transition: 0.3s; }
    .main-footer a:hover { color: white; }
  </style>
</head>
<body>

  <nav class="navbar navbar-expand-sm bg-white navbar-light shadow-sm sticky-top">
    <div class="container">
      <a class="navbar-brand fw-bold text-primary" href="index.php">
        <i class="bi bi-shop"></i> Mitchaship<span class="text-dark">Shop</span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.php">หน้าแรก</a>
          </li>
        </ul>

        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item me-2">
             <a href="cart.php" class="btn btn-outline-primary position-relative rounded-pill px-3">
                <i class="bi bi-cart3"></i> 
                <?php 
                $cart_count = 0;
                if(isset($_SESSION['cart'])){
                    foreach($_SESSION['cart'] as $qty){
                        $cart_count += $qty;
                    }
                }
                if($cart_count > 0){
                ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <?php echo $cart_count; ?>
                </span>
                <?php } ?>
             </a>
          </li>

          <li class="nav-item dropdown ms-2">
            <a class="nav-link dropdown-toggle fw-bold text-dark" href="#" role="button" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle text-primary"></i> <?php echo $_SESSION['adname']; ?> 
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                <li><a class="dropdown-item" href="user_profile.php"><i class="bi bi-gear me-2"></i> ข้อมูลส่วนตัว</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="logout.php" onclick="return confirm('ต้องการออกจากระบบ?');"><i class="bi bi-box-arrow-right me-2"></i> ออกจากระบบ</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            
            <div class="profile-card p-4 p-md-5">
                
                <div class="text-center mb-4">
                    <div class="d-inline-block p-3 rounded-circle bg-primary bg-opacity-10 mb-3">
                        <i class="bi bi-person-lines-fill display-4 text-primary"></i>
                    </div>
                    <h3 class="fw-bold">แก้ไขข้อมูลส่วนตัว</h3>
                    <p class="text-muted">อัปเดตข้อมูลบัญชีของคุณได้ที่นี่</p>
                </div>

                <form method="post">
                    <?php
                    $uid = $_SESSION['adid'];
                    $sql = "SELECT * FROM tb_admin WHERE ID=:uid";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':uid', $uid, PDO::PARAM_STR);
                    $query->execute();
                    $row = $query->fetch(PDO::FETCH_OBJ);
                    
                    if($row) {
                    ?>
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary">Username (ชื่อผู้ใช้)</label>
                            <input type="text" class="form-control bg-light" value="<?php echo $row->UserName; ?>" readonly disabled>
                            <div class="form-text"><i class="bi bi-info-circle"></i> ชื่อผู้ใช้ไม่สามารถเปลี่ยนแปลงได้</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">ชื่อ-นามสกุล</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" name="fullname" value="<?php echo $row->AdminName; ?>" required placeholder="กรอกชื่อ-นามสกุลของคุณ">
                            </div>
                        </div>
                        
                        <div class="p-3 bg-light rounded-3 mb-4">
                            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-key-fill text-warning me-2"></i>เปลี่ยนรหัสผ่าน</h5>
                            
                            <div class="mb-3">
                                <label class="form-label">รหัสผ่านใหม่</label>
                                <input type="password" class="form-control" name="newpassword" placeholder="กรอกรหัสผ่านใหม่ (ถ้าต้องการเปลี่ยน)">
                            </div>
                            <div class="form-text text-danger">* หากไม่ต้องการเปลี่ยนรหัสผ่าน ให้เว้นว่างไว้</div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="index.php" class="btn btn-outline-secondary rounded-pill px-4">
                                <i class="bi bi-arrow-left me-1"></i> ย้อนกลับ
                            </a>
                            <button type="submit" name="submit" class="btn btn-primary rounded-pill px-5 shadow-sm">
                                <i class="bi bi-check-circle-fill me-1"></i> บันทึกข้อมูล
                            </button>
                        </div>

                    <?php } ?>
                </form>

            </div>
        </div>
    </div>
  </div>

  <footer class="main-footer">
      <div class="container text-center">
          <div class="row justify-content-center">
              <div class="col-md-6">
                <h5 class="fw-bold text-white mb-3">MitchashipShop</h5>
                <p class="small mb-3">ร้านค้าออนไลน์จำลองเพื่อการศึกษา รายวิชา Web Programming 2/2568</p>
                <div class="text-white-50 small">
                   &copy; 2026 Mitchaship Shop. All Rights Reserved.
                </div>
              </div>
          </div>
      </div>
  </footer>

</body>
</html>
<?php } ?>