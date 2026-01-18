<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');

// ตรวจสอบว่าเป็น Admin หรือไม่
if (strlen($_SESSION['adid']) == 0) {
    header('location:../login.php');
}

// --- โค้ดสำหรับลบออเดอร์ ---
if (isset($_GET['delid'])) {
    $oid = intval($_GET['delid']);
    try {
        // 1. ลบรายละเอียดสินค้าในออเดอร์นี้ก่อน (tb_order_detail)
        $sql1 = "DELETE FROM tb_order_detail WHERE OrderID=:oid";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':oid', $oid, PDO::PARAM_STR);
        $query1->execute();

        // 2. ลบหัวบิล (tb_order)
        $sql2 = "DELETE FROM tb_order WHERE OrderID=:oid";
        $query2 = $dbh->prepare($sql2);
        $query2->bindParam(':oid', $oid, PDO::PARAM_STR);
        $query2->execute();

        echo "<script>alert('ลบคำสั่งซื้อเรียบร้อยแล้ว');</script>";
        echo "<script>window.location.href='manage_orders.php'</script>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Orders</title>
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="vendors/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="vendors/chartist/chartist.min.css">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
</head>

<body>
    <div class="container-scroller">
        <?php include_once('includes/header.php'); ?>
        
        <div class="container-fluid page-body-wrapper">
            <?php include_once('includes/sidebar.php'); ?>
            
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h3 class="page-title"> จัดการคำสั่งซื้อ (Orders) </h3>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">รายการคำสั่งซื้อทั้งหมด</h4>
                                    
                                    <div class="table-responsive">
                                        <table id="example1" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th> # </th>
                                                    <th> เลขที่บิล </th>
                                                    <th> วันที่สั่งซื้อ </th>
                                                    <th> ลูกค้า </th>
                                                    <th> ยอดรวม </th>
                                                    <th> จัดการ </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // ดึงข้อมูล Order เชื่อมกับ tb_admin (เพื่อเอาชื่อคนซื้อ)
                                                // *หมายเหตุ: ระบบของคุณใช้ tb_admin เก็บทั้ง Admin และ User
                                                $sql = "SELECT tb_order.*, tb_admin.AdminName 
                                                        FROM tb_order 
                                                        LEFT JOIN tb_admin ON tb_order.ID_User = tb_admin.ID 
                                                        ORDER BY tb_order.OrderDate DESC";
                                                $query = $dbh->prepare($sql);
                                                $query->execute();
                                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                
                                                $cnt = 1;
                                                if ($query->rowCount() > 0) {
                                                    foreach ($results as $row) {
                                                ?>
                                                    <tr>
                                                        <td> <?php echo $cnt; ?> </td>
                                                        <td> <?php echo $row->OrderID; ?> </td>
                                                        <td> <?php echo $row->OrderDate; ?> </td>
                                                        <td> <?php echo $row->AdminName; ?> </td>
                                                        <td class="text-danger fw-bold"> <?php echo number_format($row->TotalPrice, 2); ?> </td>
                                                        <td>
                                                            <a href="order_details.php?oid=<?php echo $row->OrderID; ?>" class="btn btn-info btn-sm">
                                                                <i class="icon-eye"></i> รายละเอียด
                                                            </a>
                                                            
                                                            <a href="manage_orders.php?delid=<?php echo $row->OrderID; ?>" onclick="return confirm('ต้องการลบออเดอร์นี้หรือไม่?');" class="btn btn-danger btn-sm">
                                                                <i class="icon-trash"></i> ลบ
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php
                                                        $cnt++;
                                                    }
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
                <?php include_once('includes/footer.php'); ?>
            </div>
        </div>
    </div>

    <script src="vendors/js/vendor.bundle.base.js"></script>
    <script src="./js/dashboard.js"></script>
    <script src="../plugins/jquery/jquery.min.js"></script>
    <script src="../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    
    <script>
        $(function () {
            $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "order": [[ 1, "desc" ]] // เรียงตามเลขที่บิล ล่าสุดขึ้นก่อน
            });
        });
    </script>
</body>
</html>