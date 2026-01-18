<?php
session_start();
error_reporting(0);
include("includes/dbconnection.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Mitchaship Shop | ช้อปปิ้งออนไลน์</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600;800&display=swap" rel="stylesheet">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <style>
    /* ใช้ฟอนต์ Prompt ทั้งเว็บ */
    body {
      font-family: 'Prompt', sans-serif;
      background-color: #f8f9fa; /* พื้นหลังสีเทาอ่อน สบายตา */
    }

    /* Hero Banner สไตล์ Modern */
    .hero-banner {
      background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
      color: white;
      padding: 70px 0;
      margin-bottom: 30px;
      border-radius: 0 0 30px 30px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    /* ป้ายเตือนมิจฉาชีพ */
    .mitcha-badge {
        background-color: #ffc107; /* สีเหลืองเตือนภัย */
        color: #000;
        font-weight: 800; /* ตัวหนาพิเศษ */
        font-size: 1.6rem;
        padding: 15px 30px;
        border-radius: 15px;
        border: 4px solid #000; /* ขอบดำหนาๆ */
        box-shadow: 5px 5px 0px #000; /* เงาแบบการ์ตูน */
        display: inline-block;
        transform: rotate(-2deg); /* เอียงนิดๆ ให้ดูขี้เล่น */
        transition: transform 0.3s;
    }
    .mitcha-badge:hover {
        transform: rotate(0deg) scale(1.05); /* ชี้แล้วเด้ง */
    }

    /* ปรับแต่งเมนูหมวดหมู่ด้านซ้าย */
    .category-list .nav-link {
      color: #555;
      border-radius: 10px;
      margin-bottom: 5px;
      padding: 10px 15px;
      transition: all 0.3s;
    }
    .category-list .nav-link:hover {
      background-color: #e9ecef;
      color: #0d6efd;
      transform: translateX(5px); /* ขยับขวาเล็กน้อยเมื่อชี้ */
    }
    .category-list .nav-link.active {
      background-color: #0d6efd;
      color: white;
      box-shadow: 0 4px 6px rgba(13, 110, 253, 0.3);
    }

    /* ปรับแต่งการ์ดสินค้า */
    .product-card {
      border: none;
      border-radius: 15px;
      background: white;
      transition: all 0.3s ease;
      overflow: hidden;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .product-card:hover {
      transform: translateY(-5px); /* ลอยขึ้นเมื่อชี้ */
      box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }
    .card-img-wrapper {
      height: 200px;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      background-color: white;
    }
    .card-img-top {
      max-height: 100%;
      max-width: 100%;
      object-fit: contain;
    }
    .price-tag {
      font-size: 1.2rem;
      font-weight: 600;
      color: #dc3545;
    }

    /* Footer สวยๆ */
    .main-footer {
      background-color: #212529;
      color: #aaa;
      padding-top: 40px;
      padding-bottom: 20px;
      margin-top: 60px;
    }
    .main-footer h5 {
      color: white;
      margin-bottom: 20px;
    }
    .main-footer a {
      color: #aaa;
      text-decoration: none;
      transition: 0.3s;
    }
    .main-footer a:hover {
      color: white;
    }
  </style>
</head>

<body>

  <nav class="navbar navbar-expand-sm bg-white navbar-light shadow-sm sticky-top">
    <div class="container"> <a class="navbar-brand fw-bold text-primary" href="index.php">
        <i class="bi bi-shop"></i> Mitchaship<span class="text-dark">Shop</span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link active" href="index.php">หน้าแรก</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">โปรโมชั่น (ปรับปรุงอยู่นะจ๊ะ)</a> </li>
          <li class="nav-item">
            <a class="nav-link" href="#">วิธีการสั่งซื้อ (ปรับปรุงอยู่นะจ๊ะ)</a> </li>
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

          <?php if (strlen($_SESSION['adid']) == 0) { ?>
            <li class="nav-item ms-2">
              <a class="btn btn-primary rounded-pill px-4" href="login.php">เข้าสู่ระบบ</a>
            </li>
          <?php } else { ?>
            <li class="nav-item dropdown ms-2">
              <a class="nav-link dropdown-toggle fw-bold text-dark" href="#" role="button" data-bs-toggle="dropdown">
                 <i class="bi bi-person-circle text-primary"></i> <?php echo $_SESSION['adname']; ?> 
              </a>
              <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                <li><a class="dropdown-item" href="user_profile.php"><i class="bi bi-gear me-2"></i> ข้อมูลส่วนตัว</a></li>
                <li>
                    <a class="dropdown-item" href="my_orders.php">
                        <i class="bi bi-clock-history me-2"></i> ประวัติการสั่งซื้อ
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="logout.php" onclick="return confirm('ต้องการออกจากระบบ?');"><i class="bi bi-box-arrow-right me-2"></i> ออกจากระบบ</a></li>
              </ul>
            </li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </nav>

  <div class="hero-banner">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-4">ยินดีต้อนรับสู่ Mitchaship Shop</h1>
        
        <div class="mb-4">
             <span class="mitcha-badge">
                <i class="bi bi-exclamation-triangle-fill"></i> ศูนย์รวมสินค้าคุณภาพจีน ราคาโดนดุจมิจ ไว้ใจด้าย 👻
             </span>
        </div>
        
        <a href="#products" class="btn btn-light btn-lg rounded-pill px-5 mt-2 fw-bold text-primary shadow">ช้อปเลย!</a>
    </div>
  </div>

  <div class="container" id="products">
    <div class="row">

      <div class="col-lg-3 col-md-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold border-bottom-0 pt-3">
                <i class="bi bi-list-ul me-2"></i> หมวดหมู่สินค้า
            </div>
            <div class="card-body p-2">
                <ul class="nav flex-column category-list">
                  <li class="nav-item">
                     <a class="nav-link <?php if(!isset($_GET['cid'])){ echo 'active'; } ?>" href="index.php">
                        <i class="bi bi-grid-fill me-2"></i> สินค้าทั้งหมด
                     </a>
                  </li>
                  <?php
                  $sql = "SELECT * FROM tb_catagory";
                  $query = $dbh->prepare($sql);
                  $query->execute();
                  $results = $query->fetchAll(PDO::FETCH_OBJ);

                  if ($query->rowCount() > 0) {
                    foreach ($results as $row) {
                      $isActive = (isset($_GET['cid']) && $_GET['cid'] == $row->ID) ? 'active' : '';
                      ?>
                      <li class="nav-item">
                        <a class="nav-link <?php echo $isActive; ?>" href="index.php?cid=<?php echo $row->ID; ?>">
                           <i class="bi bi-tag me-2"></i> <?php echo htmlentities($row->NameCatagory); ?>
                        </a>
                      </li>
                    <?php
                    }
                  }
                  ?>
                </ul>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-4 bg-warning text-dark overflow-hidden">
            <div class="card-body text-center p-4">
                <h4 class="fw-bold"><i class="bi bi-lightning-fill"></i> Flash Sale เหมือนแจกฟรี !!</h4>
                <p class="mb-2">ลดกระหน่ำ 100%</p>
                <small>เฉพาะวันที่ 30 กุมภาพันธ์ เท่านั้น!</small>
            </div>
        </div>
      </div>

      <div class="col-lg-9 col-md-8">
        <?php
        try {
          if (isset($_GET['cid'])) {
            $cid = $_GET['cid'];
            $sql_cat_name = "SELECT NameCatagory FROM tb_catagory WHERE ID = :cid";
            $stmt_cat = $dbh->prepare($sql_cat_name);
            $stmt_cat->bindParam(':cid', $cid);
            $stmt_cat->execute();
            $cat_name = $stmt_cat->fetch(PDO::FETCH_OBJ);
            
            echo "<h3 class='mb-4 border-start border-5 border-primary ps-3'>หมวดหมู่: " . ($cat_name ? $cat_name->NameCatagory : 'ไม่ระบุ') . "</h3>";
            
            $sql_prod = "SELECT * FROM tb_product WHERE ID_catagory = :cid";
            $query_prod = $dbh->prepare($sql_prod);
            $query_prod->bindParam(':cid', $cid, PDO::PARAM_STR);

          } else {
            echo "<h3 class='mb-4 border-start border-5 border-primary ps-3'>สินค้าแนะนำ</h3>";
            $sql_prod = "SELECT * FROM tb_product"; 
            $query_prod = $dbh->prepare($sql_prod);
          }

          $query_prod->execute();
          $results_prod = $query_prod->fetchAll(PDO::FETCH_OBJ);
          ?>

          <div class="row">
            <?php
            if ($query_prod->rowCount() > 0) {
              foreach ($results_prod as $row_prod) {
                ?>
                <div class="col-lg-4 col-md-6 mb-4">
                  <div class="card h-100 product-card">
                    <div class="card-img-wrapper position-relative">
                        <img src="admin/productimages/<?php echo htmlentities($row_prod->Pro_image); ?>" class="card-img-top" alt="Product">
                        <?php if($row_prod->Pro_total < 5) { ?>
                            <span class="position-absolute top-0 end-0 badge bg-danger m-2">เหลือรับน้อย!</span>
                        <?php } ?>
                    </div>

                    <div class="card-body d-flex flex-column pt-2">
                      <h5 class="card-title text-truncate" title="<?php echo htmlentities($row_prod->Pro_name); ?>">
                          <?php echo htmlentities($row_prod->Pro_name); ?>
                      </h5>
                      
                      <div class="d-flex justify-content-between align-items-center mt-2 mb-3">
                          <span class="price-tag"><?php echo number_format($row_prod->Pro_price); ?> ฿</span>
                          <span class="text-muted small">คงเหลือ <?php echo htmlentities($row_prod->Pro_total); ?> ชิ้น</span>
                      </div>
                      
                      <div class="mt-auto">
                          <a href="cart_act.php?action=add&p_id=<?php echo $row_prod->ID; ?>" class="btn btn-primary w-100 rounded-pill shadow-sm"
                            onclick="return confirm('หยิบสินค้าใส่ตะกร้า?');">
                            <i class="bi bi-cart-plus-fill me-1"></i> หยิบใส่ตะกร้า
                          </a>
                      </div>
                    </div>
                  </div>
                </div>
                <?php
              }
            } else {
              echo "<div class='col-12'><div class='alert alert-warning text-center py-5'><i class='bi bi-search display-4 d-block mb-3'></i>ไม่พบสินค้าในหมวดหมู่นี้</div></div>";
            }
            ?>
          </div>

          <?php
        } catch (PDOException $e) {
          echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
        }
        ?>

      </div>
    </div>
  </div>

  <footer class="main-footer">
      <div class="container">
          <div class="row">
              <div class="col-md-4 mb-3">
                  <h5 class="fw-bold text-white">MitchashipShop</h5>
                  <p class="small">ร้านค้าออนไลน์จำลองเพื่อการศึกษา รายวิชา Web Programming 2/2568 มหาวิทยาลัยเทคโนโลยีราชมงคลล้านนา (RMUTL)</p>
              </div>
              <div class="col-md-4 mb-3">
                  <h5 class="fw-bold text-white">เมนูลัด</h5>
                  <ul class="list-unstyled">
                      <li><a href="index.php">หน้าแรก</a></li>
                      <li><a href="cart.php">ตะกร้าสินค้า</a></li>
                      <li><a href="login.php">เข้าสู่ระบบ</a></li>
                  </ul>
              </div>
              <div class="col-md-4 mb-3">
                  <h5 class="fw-bold text-white">ติดต่อเรา</h5>
                  <p class="small">
                      <i class="bi bi-geo-alt-fill me-2"></i> 123 มหาวิทยาลัยเทคโนโลยีราชมงคลล้านนา<br>
                      <i class="bi bi-telephone-fill me-2"></i> 099-xxx-xxx<br>
                      <i class="bi bi-envelope-fill me-2"></i> gorrakod_ph67@live.rmutl.ac.th
                  </p>
                  <div>
                      <a href="#" class="me-2 fs-5"><i class="bi bi-facebook"></i></a>
                      <a href="#" class="me-2 fs-5"><i class="bi bi-line"></i></a>
                      <a href="#" class="fs-5"><i class="bi bi-instagram"></i></a>
                  </div>
              </div>
          </div>
          <hr class="border-secondary">
          <div class="text-center small">
              &copy; 2026 Mitchaship Shop. All Rights Reserved.
          </div>
      </div>
  </footer>

</body>
</html>