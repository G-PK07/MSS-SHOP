<?php
session_start();
error_reporting(0);
include("../includes/dbconnection.php");

if (isset($_POST['submit'])) {
    try {
        // 1. รับค่า ID จาก URL
        $eid = $_GET['editid'];
        
        // 2. รับค่าชื่อหมวดหมู่จากฟอร์ม (ตั้งชื่อตัวแปรให้ตรงกัน)
        $NameCatagory = $_POST['NameCatagory'];

        // 3. SQL สำหรับอัปเดตหมวดหมู่ (ลบเรื่อง Password/Email ของ Admin ออกให้หมด)
        $sql = "UPDATE tb_catagory SET NameCatagory=:NameCatagory WHERE ID=:eid";
        
        $query = $dbh->prepare($sql);
        
        // 4. ผูกตัวแปร (Bind) ให้ครบตาม SQL ข้างบน (มีแค่ 2 ตัว)
        $query->bindParam(':NameCatagory', $NameCatagory, PDO::PARAM_STR);
        $query->bindParam(':eid', $eid, PDO::PARAM_STR);

        // 5. รันคำสั่ง
        $query->execute();

        echo "<script>alert('แก้ไขข้อมูลเรียบร้อยแล้ว');</script>";
        echo "<script>window.location.href='manage_catagory.php'</script>";

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
                        <h3 class="page-title"> Edit Catagory </h3>
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
                                        $sql = "SELECT * FROM tb_catagory WHERE ID=:eid";
                                        $query = $dbh->prepare($sql);
                                        $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);

                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $row) {
                                        ?>
                                                <div class="form-group">
                                                    <label for="NameCatagory">ชื่อ</label>
                                                    <input type="text" class="form-control" name="NameCatagory" value="<?php echo $row->NameCatagory; ?>" required>
                                                </div>
                                                
                                                <button type="submit" class="btn btn-primary mr-2" name="submit">บันทึกการแก้ไข</button>
                                                <button type="button" class="btn btn-light" onclick="window.location.href='manage_catagory.php'">ยกเลิก</button>

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