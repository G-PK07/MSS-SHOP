<?php
session_start();
// error_reporting(0);
// แก้ไขบรรทัดนี้: ถอยกลับไป 1 โฟลเดอร์เพื่อหา dbconnection.php
include('../includes/dbconnection.php');

// เช็ค Admin
if (empty($_SESSION['admin_id'])) {
    header('location:../login.php');
    exit();
}

// --- ดึงข้อมูลสรุป (Stats) มาโชว์ ---
// 1. ยอดขายรวม
$sql_rev = "SELECT SUM(TotalPrice) as total_rev FROM tb_order";
$q_rev = $dbh->prepare($sql_rev);
$q_rev->execute();
$rev = $q_rev->fetch(PDO::FETCH_OBJ);
$total_revenue = $rev->total_rev;

// 2. จำนวนคำสั่งซื้อ
$sql_ord = "SELECT COUNT(OrderID) as total_ord FROM tb_order";
$q_ord = $dbh->prepare($sql_ord);
$q_ord->execute();
$ord = $q_ord->fetch(PDO::FETCH_OBJ);
$total_orders = $ord->total_ord;

// 3. จำนวนสินค้า
$sql_prod = "SELECT COUNT(ID) as total_prod FROM tb_product";
$q_prod = $dbh->prepare($sql_prod);
$q_prod->execute();
$prod = $q_prod->fetch(PDO::FETCH_OBJ);
$total_products = $prod->total_prod;

// 4. จำนวนสมาชิก (User)
$sql_user = "SELECT COUNT(ID) as total_users FROM tb_admin WHERE Status = 1"; 
$q_user = $dbh->prepare($sql_user);
$q_user->execute();
$users = $q_user->fetch(PDO::FETCH_OBJ);
$total_users = $users->total_users;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard | Mitchaship Shop</title>
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="vendors/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="vendors/chartist/chartist.min.css">
    <link rel="stylesheet" href="css/style.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        body, .h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
            font-family: 'Prompt', sans-serif;
        }
        /* การ์ดสถิติ */
        .stats-card {
            border-radius: 15px;
            color: white;
            overflow: hidden;
            position: relative;
            transition: transform 0.3s;
            border: none;
        }
        .stats-card:hover {
            transform: translateY(-5px);
        }
        .bg-gradient-primary-new { background: linear-gradient(45deg, #0d6efd, #0dcaf0); }
        .bg-gradient-success-new { background: linear-gradient(45deg, #198754, #20c997); }
        .bg-gradient-danger-new { background: linear-gradient(45deg, #dc3545, #f77f00); }
        .bg-gradient-warning-new { background: linear-gradient(45deg, #ffc107, #ffca2c); color: #333; }

        .stats-icon {
            font-size: 3rem;
            opacity: 0.3;
            position: absolute;
            right: 20px;
            top: 20px;
        }
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }
    </style>
</head>

<body>
    <div class="container-scroller">
        <?php include_once 'includes/header.php'; ?>
        
        <div class="container-fluid page-body-wrapper">
            <?php include_once 'includes/sidebar.php'; ?>
            
            <div class="main-panel">
                <div class="content-wrapper">
                    
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h3 class="font-weight-bold">Dashboard <small class="text-muted" style="font-size: 1rem;">ภาพรวมร้านค้า</small></h3>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 grid-margin stretch-card">
                            <div class="card stats-card bg-gradient-primary-new">
                                <div class="card-body">
                                    <h4 class="font-weight-normal mb-3">ยอดขายรวม</h4>
                                    <h2 class="mb-0"><?php echo number_format($total_revenue); ?> ฿</h2>
                                    <i class="icon-wallet stats-icon"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 grid-margin stretch-card">
                            <div class="card stats-card bg-gradient-success-new">
                                <div class="card-body">
                                    <h4 class="font-weight-normal mb-3">คำสั่งซื้อทั้งหมด</h4>
                                    <h2 class="mb-0"><?php echo number_format($total_orders); ?></h2>
                                    <i class="icon-basket-loaded stats-icon"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 grid-margin stretch-card">
                            <div class="card stats-card bg-gradient-danger-new">
                                <div class="card-body">
                                    <h4 class="font-weight-normal mb-3">จำนวนสินค้า</h4>
                                    <h2 class="mb-0"><?php echo number_format($total_products); ?></h2>
                                    <i class="icon-handbag stats-icon"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 grid-margin stretch-card">
                            <div class="card stats-card bg-gradient-warning-new">
                                <div class="card-body">
                                    <h4 class="font-weight-normal mb-3" style="color:#333">สมาชิก (User)</h4>
                                    <h2 class="mb-0" style="color:#333"><?php echo number_format($total_users); ?></h2>
                                    <i class="icon-people stats-icon" style="color:#000; opacity:0.1"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="card-title mb-0">คำสั่งซื้อล่าสุด (Recent Orders)</h4>
                                        <a href="manage_orders.php" class="btn btn-outline-primary btn-sm">ดูทั้งหมด</a>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead class="bg-dark text-white">
                                                <tr>
                                                    <th>เลขที่บิล</th>
                                                    <th>วันที่</th>
                                                    <th>ลูกค้า</th>
                                                    <th>ยอดเงิน</th>
                                                    <th>จัดการ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // ดึง 5 ออเดอร์ล่าสุด
                                                $sql = "SELECT tb_order.*, tb_admin.AdminName 
                                                        FROM tb_order 
                                                        LEFT JOIN tb_admin ON tb_order.ID_User = tb_admin.ID 
                                                        ORDER BY tb_order.OrderDate DESC LIMIT 5";
                                                $query = $dbh->prepare($sql);
                                                $query->execute();
                                                $results = $query->fetchAll(PDO::FETCH_OBJ);

                                                if($query->rowCount() > 0){
                                                    foreach($results as $row){
                                                ?>
                                                <tr>
                                                    <td>#<?php echo $row->OrderID; ?></td>
                                                    <td><?php echo $row->OrderDate; ?></td>
                                                    <td><?php echo $row->AdminName; ?></td>
                                                    <td class="text-success fw-bold"><?php echo number_format($row->TotalPrice,2); ?></td>
                                                    <td>
                                                        <a href="order_details.php?oid=<?php echo $row->OrderID; ?>" class="btn btn-primary btn-sm btn-rounded">
                                                            ตรวจสอบ
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php 
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='5' class='text-center'>ยังไม่มีคำสั่งซื้อ</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <?php include_once 'includes/footer.php'; ?>
                
            </div>
            </div>
        </div>

    <script src="vendors/js/vendor.bundle.base.js"></script>
    <script src="vendors/chart.js/Chart.min.js"></script>
    <script src="vendors/moment/moment.min.js"></script>
    <script src="vendors/daterangepicker/daterangepicker.js"></script>
    <script src="vendors/chartist/chartist.min.js"></script>
    <script src="js/off-canvas.js"></script>
    <script src="js/misc.js"></script>
    <script src="js/dashboard.js"></script>
</body>
</html>