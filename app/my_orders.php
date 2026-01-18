<?php
session_start();
include("includes/dbconnection.php");

// เช็คล็อกอิน (ถ้าไม่ได้ล็อกอิน ให้เด้งไปหน้า Login)
if (empty($_SESSION['adid'])) {
    header("location: login.php");
    exit();
}

$uid = $_SESSION['adid'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>ประวัติการสั่งซื้อ | Mitchaship Shop</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body { font-family: 'Prompt', sans-serif; background-color: #f8f9fa; }
        .order-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            background: white;
            transition: 0.3s;
        }
        .order-card:hover { transform: translateY(-3px); }
        .main-footer { background-color: #212529; color: #aaa; padding: 30px 0; margin-top: 50px; }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-sm bg-white navbar-light shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="index.php"><i class="bi bi-shop"></i> MitchashipShop</a>
            <div class="ms-auto">
                <a href="index.php" class="btn btn-outline-secondary rounded-pill btn-sm px-3">กลับหน้าหลัก</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4 fw-bold"><i class="bi bi-clock-history text-primary"></i> ประวัติการสั่งซื้อของฉัน</h2>

        <?php
        // ดึงข้อมูลออเดอร์ (ดึงแค่ตาราง tb_order เดิมที่มีอยู่)
        $sql = "SELECT * FROM tb_order WHERE ID_User = :uid ORDER BY OrderID DESC";
        $query = $dbh->prepare($sql);
        $query->bindParam(':uid', $uid);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        if ($query->rowCount() > 0) {
            foreach ($results as $row) {
        ?>
            <div class="order-card p-4">
                <div class="row align-items-center">
                    
                    <div class="col-md-4 mb-2">
                        <small class="text-muted d-block">เลขที่คำสั่งซื้อ</small>
                        <span class="fw-bold fs-5 text-primary">#<?php echo $row->OrderID; ?></span>
                    </div>

                    <div class="col-md-4 mb-2">
                        <small class="text-muted d-block">วันที่สั่งซื้อ</small>
                        <span><?php echo date("d/m/Y H:i", strtotime($row->OrderDate)); ?></span>
                    </div>

                    <div class="col-md-4 mb-2 text-md-end">
                        <small class="text-muted d-block">ยอดสุทธิ</small>
                        <span class="fw-bold text-danger fs-4"><?php echo number_format($row->TotalPrice, 2); ?> ฿</span>
                    </div>

                </div>
            </div>
        <?php 
            }
        } else {
            // กรณีไม่มีออเดอร์
            echo "<div class='alert alert-secondary text-center py-5'>";
            echo "<i class='bi bi-basket display-4 d-block mb-3'></i>";
            echo "ยังไม่มีประวัติการสั่งซื้อ <br>";
            echo "<a href='index.php' class='btn btn-primary mt-3 rounded-pill px-4'>ไปเลือกซื้อสินค้า</a>";
            echo "</div>";
        } 
        ?>
    </div>

    <footer class="main-footer text-center">
        <div class="container">
            <p class="mb-0 small text-white-50">&copy; 2026 Mitchaship Shop. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>