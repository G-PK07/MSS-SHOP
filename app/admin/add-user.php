<?php
session_start();
error_reporting(0);
include("../includes/dbconnection.php");
if (isset($_POST['submit'])) {
    try {
        $adminname = $_POST['adminname'];
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $email = $_POST['email'];
        //print_r($_POST);
        //die('xx');


        $sql = 'INSERT INTO tb_admin (AdminName, UserName, Password, Email)
VALUES (:adminname, :username, :password, :email);';
        $query = $dbh->prepare($sql);
        $query->bindParam(':adminname', $adminname, PDO::PARAM_STR);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();
        $LastInsertId = $dbh->lastInsertId();
        if ($LastInsertId > 0) {
            echo "<script>alert('เพิ่มล้า')</script>";
            echo "<script>window.location.href='manage_users.php'</script>";
        } else {
            echo "<script>alert('พลาด')</script>";
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
    <title>User Management</title>
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
                        <h3 class="page-title"> Add Users </h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Add User</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">เพิ่มผู้ใช้งานใหม่</h4>
                                    <p class="card-description"> กรอกข้อมูลสมาชิก </p>
                                    
                                    <form class="forms-sample" method="post" action="add-user.php">
                                        
                                        <div class="form-group">
                                            <label for="adminName">ชื่อ-นามสกุล (Name)</label>
                                            <input type="text" class="form-control" id="adminName" name="adminname" placeholder="ระบุชื่อ-นามสกุล" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="username">ชื่อผู้ใช้ (Username)</label>
                                            <input type="text" class="form-control" id="username" name="username" placeholder="ระบุชื่อผู้ใช้" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="password">รหัสผ่าน (Password)</label>
                                            <input type="password" class="form-control" id="password" name="password" placeholder="ตั้งรหัสผ่าน" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="email">อีเมล (Email)</label>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="example@email.com" required>
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