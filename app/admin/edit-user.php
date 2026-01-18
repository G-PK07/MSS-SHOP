<?php
session_start();
// ปิด error reporting ชั่วคราวเพื่อให้หน้าจอสะอาดตามที่คุณต้องการ
error_reporting(0);
include("../includes/dbconnection.php");

if (isset($_POST['submit'])) {
    try {
        // รับค่า ID จาก URL (เพราะ form action="" ค่า editid จะยังอยู่บน URL)
        $eid = $_GET['editid'];
        $adminname = $_POST['adminname'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // เช็คว่ามีการเปลี่ยนรหัสผ่านไหม
        if (!empty($password)) {
            // กรณีเปลี่ยนรหัสผ่าน (แก้ SQL ให้ถูกต้อง: ใช้ SET ไม่ใช่วงเล็บ)
            $sql = "UPDATE tb_admin SET AdminName=:adminname, UserName=:username, Password=:password, Email=:email WHERE ID=:eid";
            $password_hash = md5($password);
        } else {
            // กรณีไม่เปลี่ยนรหัสผ่าน (อัปเดตแค่ข้อมูลทั่วไป)
            $sql = "UPDATE tb_admin SET AdminName=:adminname, UserName=:username, Email=:email WHERE ID=:eid";
        }

        $query = $dbh->prepare($sql);
        
        // ผูกตัวแปร (Bind Param)
        $query->bindParam(':adminname', $adminname, PDO::PARAM_STR);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':eid', $eid, PDO::PARAM_STR); // **เพิ่มตัวนี้ที่เคยหายไป**

        // ถ้ามีการเปลี่ยนรหัสผ่าน ต้อง Bind Password ด้วย
        if (!empty($password)) {
            $query->bindParam(':password', $password_hash, PDO::PARAM_STR);
        }

        $query->execute();

        echo "<script>alert('แก้ไขข้อมูลเรียบร้อยแล้ว');</script>";
        echo "<script>window.location.href='manage_users.php'</script>";

    } catch (PDOException $e) {
        exit("Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit User</title>
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
                        <h3 class="page-title"> Edit Users </h3>
                    </div>

                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">แก้ไขข้อมูล</h4>
                                    
                                    <form class="forms-sample" method="post" action="">
                                        
                                        <?php
                                        $eid = $_GET['editid'];
                                        // แก้ SQL ตรงนี้ให้มี FROM tb_admin
                                        $sql = "SELECT * FROM tb_admin WHERE ID=:eid";
                                        $query = $dbh->prepare($sql);
                                        $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);

                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $row) {
                                        ?>
                                                <div class="form-group">
                                                    <label for="adminName">ชื่อ-นามสกุล (Name)</label>
                                                    <input type="text" class="form-control" name="adminname" value="<?php echo $row->AdminName; ?>" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="username">ชื่อผู้ใช้ (Username)</label>
                                                    <input type="text" class="form-control" name="username" value="<?php echo $row->UserName; ?>" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="password">รหัสผ่านใหม่</label>
                                                    <input type="password" class="form-control" name="password" placeholder="กรอกเฉพาะเมื่อต้องการเปลี่ยนรหัสผ่าน">
                                                </div>

                                                <div class="form-group">
                                                    <label for="email">อีเมล (Email)</label>
                                                    <input type="email" class="form-control" name="email" value="<?php echo $row->Email; ?>" required>
                                                </div>
                                                
                                                <button type="submit" class="btn btn-primary mr-2" name="submit">บันทึกการแก้ไข</button>
                                                <button type="button" class="btn btn-light" onclick="window.location.href='manage_users.php'">ยกเลิก</button>

                                        <?php 
                                            } // ปิด loop foreach
                                        } // ปิด if
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
    <script src="js/off-canvas.js"></script>
    <script src="js/misc.js"></script>
    <script src="./js/dashboard.js"></script>
</body>
</html>