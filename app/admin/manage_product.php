<?php
session_start();
error_reporting(0);
include("../includes/dbconnection.php");

// --- ส่วนที่ 1: ระบบลบสินค้า (รวมการลบรูปภาพ) ---
if (isset($_GET["delid"])) {
    try {
        $rid = intval($_GET["delid"]);

        // 1.1 ค้นหาชื่อรูปภาพก่อน เพื่อลบไฟล์
        $sql_get_img = "SELECT Pro_image FROM tb_product WHERE ID=:rid";
        $query_img = $dbh->prepare($sql_get_img);
        $query_img->bindParam(':rid', $rid, PDO::PARAM_STR);
        $query_img->execute();
        $row_img = $query_img->fetch(PDO::FETCH_OBJ);

        // 1.2 ลบไฟล์รูปออกจากโฟลเดอร์ productimages (ถ้ามี)
        if ($row_img->Pro_image != "" && file_exists("productimages/" . $row_img->Pro_image)) {
            unlink("productimages/" . $row_img->Pro_image);
        }

        // 1.3 ลบข้อมูลจากฐานข้อมูล
        $sql = "DELETE FROM tb_product WHERE ID=:rid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':rid', $rid, PDO::PARAM_STR);
        $query->execute();

        echo "<script>alert('ลบข้อมูลและรูปภาพเรียบร้อยแล้ว')</script>";
        echo "<script>window.location.href='manage_product.php'</script>";
    } catch (PDOException $e) {
        exit("Error" . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Manage Product</title>
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

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
                        <h3 class="page-title"> Manage Product </h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page"> Product</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-sm-flex align-items-center mb-4">
                                        <h4 class="card-title mb-sm-0">รายการสินค้า</h4>
                                        <a href="add-product.php" class="btn btn-primary ml-auto mb-3 mb-sm-0">
                                            <i class="icon-plus"></i> เพิ่มสินค้าใหม่
                                        </a>
                                    </div>
                                    <div class="table-responsive border rounded p-1">
                                        <table id="example1" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="font-weight-bold">ลำดับ</th>
                                                    <th class="font-weight-bold">รูปภาพ</th>
                                                    <th class="font-weight-bold">ชื่อสินค้า</th>
                                                    <th class="font-weight-bold">หมวดหมู่</th>
                                                    <th class="font-weight-bold">ราคาขาย</th>
                                                    <th class="font-weight-bold">ต้นทุน</th>
                                                    <th class="font-weight-bold">คงเหลือ</th>
                                                    <th class="font-weight-bold">จัดการ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // SQL JOIN ตารางสินค้ากับหมวดหมู่
                                                $sql = "SELECT tb_product.*, tb_catagory.NameCatagory 
                                                        FROM tb_product 
                                                        LEFT JOIN tb_catagory ON tb_product.ID_catagory = tb_catagory.ID";
                                                $query = $dbh->prepare($sql);
                                                $query->execute();
                                                $result = $query->fetchAll(PDO::FETCH_OBJ);
                                                $cnt = 1;
                                                
                                                if ($query->rowCount() > 0) {
                                                    foreach ($result as $row) {
                                                ?>
                                                        <tr>
                                                            <td><?php echo $cnt; ?></td>
                                                            <td align="center">
                                                                <?php if($row->Pro_image != ""): ?>
                                                                    <img src="productimages/<?php echo htmlentities($row->Pro_image); ?>" 
                                                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                                                <?php else: ?>
                                                                    <span>ไม่มีรูป</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td><?php echo htmlentities($row->Pro_name); ?></td>
                                                            <td><?php echo htmlentities($row->NameCatagory); ?></td>
                                                            <td><?php echo htmlentities($row->Pro_price); ?></td>
                                                            <td><?php echo htmlentities($row->Pro_cost); ?></td>
                                                            <td><?php echo htmlentities($row->Pro_total); ?></td>
                                                            <td>
                                                                <div>
                                                                    <a href="edit-product.php?editid=<?php echo $row->ID; ?>" class="btn btn-sm btn-success">
                                                                        <i class="icon-pencil"></i>
                                                                    </a> 
                                                                    | 
                                                                    <a href="manage_product.php?delid=<?php echo $row->ID; ?>" 
                                                                       onclick="return confirm('ยืนยันการลบข้อมูลนี้? (รูปภาพจะหายไปด้วย)');" 
                                                                       class="btn btn-sm btn-danger"> 
                                                                        <i class="icon-trash"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                <?php
                                                        $cnt = $cnt + 1;
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
    <script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>

    <script>
        $(function () {
            $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "print"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>
</body>
</html>