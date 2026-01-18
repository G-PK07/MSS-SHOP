<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');

// เช็คว่าล็อกอินหรือยัง
if (strlen($_SESSION['adid']) == 0) {
    header('location:../login.php');
} else {
    // --- ส่วนบันทึกข้อมูล เมื่อกดปุ่ม Submit ---
    if (isset($_POST['submit'])) {
        $adminid = $_SESSION['adid'];
        $AName = $_POST['adminname'];
        $email = $_POST['email']; // ต้องตรวจสอบว่าในตาราง tb_admin มีคอลัมน์ Email ไหม (จากรูปก่อนหน้านี้มี)
        
        // อัปเดตข้อมูล
        $sql = "UPDATE tb_admin SET AdminName=:name, Email=:email WHERE ID=:aid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':name', $AName, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':aid', $adminid, PDO::PARAM_STR);
        $query->execute();

        echo '<script>alert("แก้ไขข้อมูลส่วนตัวเรียบร้อยแล้ว");</script>';
        echo "<script>window.location.href='profile.php'</script>";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Profile</title>
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="vendors/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="vendors/chartist/chartist.min.css">
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
                        <h3 class="page-title"> จัดการข้อมูลส่วนตัว </h3>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">แก้ไขโปรไฟล์ (Admin Profile)</h4>
                                    
                                    <form class="forms-sample" method="post">
                                        <?php
                                        // ดึงข้อมูลเก่ามาแสดงในฟอร์ม
                                        $adminid = $_SESSION['adid'];
                                        $sql = "SELECT * from tb_admin where ID=:aid";
                                        $query = $dbh->prepare($sql);
                                        $query->bindParam(':aid', $adminid, PDO::PARAM_STR);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        
                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $row) {
                                        ?>
                                            <div class="form-group">
                                                <label>ชื่อผู้ดูแลระบบ (Name)</label>
                                                <input type="text" name="adminname" value="<?php echo $row->AdminName; ?>" class="form-control" required>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Username (ไม่สามารถแก้ไขได้)</label>
                                                <input type="text" name="username" value="<?php echo $row->UserName; ?>" class="form-control" readonly>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>อีเมล (Email)</label>
                                                <input type="email" name="email" value="<?php echo $row->Email; ?>" class="form-control" required>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>วันที่ลงทะเบียน</label>
                                                <input type="text" value="<?php echo $row->AdminRegdate; ?>" class="form-control" readonly>
                                            </div>
                                            
                                            <button type="submit" name="submit" class="btn btn-primary mr-2">บันทึกข้อมูล</button>
                                        <?php 
                                            }
                                        } 
                                        ?>
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
    <script src="./js/dashboard.js"></script>
</body>
</html>
<?php } ?>