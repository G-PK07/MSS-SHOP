<?php
session_start();
include('../includes/dbconnection.php');

// ตรวจสอบ Admin
if (strlen($_SESSION['adid']) == 0) {
    header('location:../login.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Order Details</title>
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <div class="container-scroller">
        <?php include_once('includes/header.php'); ?>
        
        <div class="container-fluid page-body-wrapper">
            <?php include_once('includes/sidebar.php'); ?>
            
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h3 class="page-title"> รายละเอียดคำสั่งซื้อ </h3>
                        <a href="manage_orders.php" class="btn btn-secondary btn-sm">
                            <i class="icon-arrow-left"></i> ย้อนกลับ
                        </a>
                    </div>
                    
                    <?php
                    // รับค่า OrderID จาก URL
                    $oid = intval($_GET['oid']);

                    // 1. ดึงข้อมูลหัวบิล (Header)
                    $sql_head = "SELECT tb_order.*, tb_admin.AdminName, tb_admin.Email 
                                 FROM tb_order 
                                 LEFT JOIN tb_admin ON tb_order.ID_User = tb_admin.ID 
                                 WHERE tb_order.OrderID=:oid";
                    $query_head = $dbh->prepare($sql_head);
                    $query_head->bindParam(':oid', $oid, PDO::PARAM_STR);
                    $query_head->execute();
                    $head = $query_head->fetch(PDO::FETCH_OBJ);
                    
                    if($query_head->rowCount() > 0) {
                    ?>

                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">รหัสใบสั่งซื้อ: #<?php echo $head->OrderID; ?></h4>
                                    <p class="mb-1"><strong>วันที่สั่งซื้อ:</strong> <?php echo $head->OrderDate; ?></p>
                                    <p class="mb-1"><strong>ลูกค้า:</strong> <?php echo $head->AdminName; ?> (Email: <?php echo $head->Email; ?>)</p>
                                    <p class="mb-4"><strong>ยอดสุทธิ:</strong> <span class="text-danger fw-bold" style="font-size: 1.2rem;"><?php echo number_format($head->TotalPrice, 2); ?> บาท</span></p>
                                    
                                    <h5 class="mt-4 mb-3">รายการสินค้าที่สั่ง</h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th> ลำดับ </th>
                                                    <th> สินค้า </th>
                                                    <th> ราคาต่อชิ้น </th>
                                                    <th> จำนวน </th>
                                                    <th> รวม </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // 2. ดึงรายละเอียดสินค้า (Detail) + Join กับตารางสินค้าเพื่อเอารูปและชื่อ
                                                $sql_detail = "SELECT tb_order_detail.*, tb_product.Pro_name, tb_product.Pro_image 
                                                               FROM tb_order_detail 
                                                               LEFT JOIN tb_product ON tb_order_detail.ProductID = tb_product.ID 
                                                               WHERE tb_order_detail.OrderID = :oid";
                                                $query_detail = $dbh->prepare($sql_detail);
                                                $query_detail->bindParam(':oid', $oid, PDO::PARAM_STR);
                                                $query_detail->execute();
                                                $results = $query_detail->fetchAll(PDO::FETCH_OBJ);
                                                
                                                $cnt = 1;
                                                foreach($results as $row) {
                                                    $total_item = $row->Qty * $row->PricePerUnit;
                                                ?>
                                                <tr>
                                                    <td><?php echo $cnt; ?></td>
                                                    <td>
                                                        <img src="productimages/<?php echo $row->Pro_image; ?>" style="width:50px; height:50px; object-fit:cover; border-radius:5px;"> 
                                                        &nbsp; <?php echo $row->Pro_name; ?>
                                                    </td>
                                                    <td><?php echo number_format($row->PricePerUnit, 2); ?></td>
                                                    <td><?php echo $row->Qty; ?></td>
                                                    <td><?php echo number_format($total_item, 2); ?></td>
                                                </tr>
                                                <?php 
                                                    $cnt++; 
                                                } 
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } else { echo "<div class='alert alert-danger'>ไม่พบข้อมูลออเดอร์นี้</div>"; } ?>

                </div>
                <?php include_once('includes/footer.php'); ?>
            </div>
        </div>
    </div>
    
    <script src="vendors/js/vendor.bundle.base.js"></script>
    <script src="./js/dashboard.js"></script>
</body>
</html>