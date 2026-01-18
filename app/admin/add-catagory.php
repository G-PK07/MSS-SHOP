<?php
session_start();
error_reporting(0);
include("../includes/dbconnection.php");
if (isset($_POST['submit'])) {
    try {
        $NameCatagory = $_POST['NameCatagory'];
        //print_r($_POST);
        //die('xx');


        $sql = 'INSERT INTO tb_catagory (NameCatagory)
VALUES (:NameCatagory);';
        $query = $dbh->prepare($sql);
        $query->bindParam(':NameCatagory', $NameCatagory, PDO::PARAM_STR);
        $query->execute();
        $LastInsertId = $dbh->lastInsertId();
        if ($LastInsertId > 0) {
            echo "<script>alert('เพิ่มประเภทสินค้าแล้ว')</script>";
            echo "<script>window.location.href='manage_catagory.php'</script>";
        } else {
            echo "<script>alert('พลาดจ้า')</script>";
        }
        //print_r($_POST);
        //die();

        //$sql = "delete from tb_admin where ID=:rid";
        //$query = $dbh->prepare($sql);
        //$query->bindParam(':rid', $rid, PDO::PARAM_STR);

        //echo "<script>alert('ลบล้า')</script>";
        //echo "<script>window.location.href='manage_users.php'</script>";
    } catch (PDOException $e) {
        exit("Error" . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Catagory Management</title>
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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
                        <h3 class="page-title"> Add Category </h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Add Category</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">เพิ่มสินค้าใหม่</h4>
                                    <p class="card-description"> กรอกข้อมูลสินค้า </p>
                                    
                                    <form class="forms-sample" method="post" action="add-catagory.php">
                                        
                                        <div class="form-group">
                                            <label for="NameCatagory">ชื่อประเภทสินค้า</label>
                                            <input type="text" class="form-control" id="NameCatagory" name="NameCatagory" placeholder="ระบุชื่อ" required>
                                        </div>

                                        <button type="submit" class="btn btn-primary mr-2" name="submit">บันทึกข้อมูล</button>
                                        <button type="button" class="btn btn-light" onclick="window.history.back()">ยกเลิก</button>
                                    </form>
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
    <script src="js/off-canvas.js"></script>
    <script src="js/misc.js"></script>
    <script src="./js/dashboard.js"></script>
</body>

</html>