<?php
session_start();
error_reporting(0);
include("../includes/dbconnection.php");

if (isset($_POST['submit'])) {
    try {
        // รับค่าจากฟอร์ม
        $ID_catagory = $_POST['ID_catagory'];
        $Pro_name = $_POST['Pro_name'];
        $Pro_price = $_POST['Pro_price'];
        $Pro_cost = $_POST['Pro_cost'];
        $Pro_total = $_POST['Pro_total']; // จำนวนสินค้า

        // --- ส่วนจัดการรูปภาพ ---
        $Pro_image = $_FILES['Pro_image']['name'];
        
        // ตรวจสอบว่ามีการเลือกไฟล์มาหรือไม่
        if ($Pro_image != "") {
            // หา Extension นามสกุลไฟล์
            $extension = strtolower(pathinfo($Pro_image, PATHINFO_EXTENSION));
            $allowed_extensions = array("jpg", "jpeg", "png", "gif");

            // ตรวจสอบนามสกุลไฟล์
            if (!in_array($extension, $allowed_extensions)) {
                echo "<script>alert('ประเภทไฟล์ไม่ถูกต้อง (อนุญาตเฉพาะ .jpg, .jpeg, .png, .gif)');</script>";
            } else {
                // ตั้งชื่อไฟล์ใหม่เพื่อป้องกันชื่อซ้ำ
                $new_image = md5($Pro_image . time()) . "." . $extension;
                
                // ย้ายไฟล์ไปยังโฟลเดอร์ (ต้องสร้างโฟลเดอร์ productimages ไว้ก่อน)
                move_uploaded_file($_FILES['Pro_image']['tmp_name'], "productimages/" . $new_image);

                // --- ส่วนบันทึกข้อมูลลงฐานข้อมูล (แก้ไข SQL ให้ตรงกับตารางสินค้า) ---
                // สมมติว่าตารางชื่อ tb_product (คุณต้องแก้ชื่อตารางให้ตรงกับ DB ของคุณ)
                $sql = "INSERT INTO tb_product (ID_catagory, Pro_name, Pro_price, Pro_cost, Pro_total, Pro_image) 
                        VALUES (:idcat, :pname, :pprice, :pcost, :ptotal, :pimage)";
                
                $query = $dbh->prepare($sql);
                $query->bindParam(':idcat', $ID_catagory, PDO::PARAM_INT);
                $query->bindParam(':pname', $Pro_name, PDO::PARAM_STR);
                $query->bindParam(':pprice', $Pro_price, PDO::PARAM_STR);
                $query->bindParam(':pcost', $Pro_cost, PDO::PARAM_STR);
                $query->bindParam(':ptotal', $Pro_total, PDO::PARAM_INT);
                $query->bindParam(':pimage', $new_image, PDO::PARAM_STR); // เก็บชื่อไฟล์ใหม่ลง DB
                
                $query->execute();
                $LastInsertId = $dbh->lastInsertId();

                if ($LastInsertId > 0) {
                    echo "<script>alert('เพิ่มสินค้าเรียบร้อยแล้ว');</script>";
                    echo "<script>window.location.href='manage_product.php';</script>"; // แก้ลิงก์ไปหน้าที่ต้องการ
                } else {
                    echo "<script>alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล');</script>";
                }
            }
        } else {
             echo "<script>alert('กรุณาเลือกรูปภาพสินค้า');</script>";
        }

    } catch (PDOException $e) {
        exit("Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Product Management</title>
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
                        <h3 class="page-title"> Add Product </h3>
                    </div>
                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">เพิ่มสินค้าใหม่</h4>
                                    
                                    <form class="forms-sample" method="post" enctype="multipart/form-data">
                                        
                                        <div class="form-group">
                                            <select class="form-control" id="ID-catagory" name="ID_catagory" required>
                                                <option value="">เลือกประเภทสินค้า</option>
                                                <?php
                                                $sql = "SELECT ID,NameCatagory FROM tb_catagory";
                                                $query = $dbh->prepare($sql);
                                                $query->execute();
                                                $result = $query->fetchAll(PDO::FETCH_OBJ);
                                                if ($query->rowCount() > 0) {
                                                    foreach ($result as $row) { ?>
                                                        <option value="<?php echo htmlentities($row->ID) ?>"><?php echo htmlentities($row->NameCatagory) ?></option>
                                                    <?php }
                                                } ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="Pro_name">ชื่อสินค้า</label>
                                            <input type="text" class="form-control" id="Pro_name" name="Pro_name" placeholder="ระบุชื่อ" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="Pro_price">ราคาขาย</label>
                                            <input type="text" class="form-control" id="Pro_price" name="Pro_price" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="Pro_cost">ราคาทุน</label>
                                            <input type="text" class="form-control" id="Pro_cost" name="Pro_cost" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="Pro_image">รูปภาพสินค้า (Picture)</label>
                                            <input type="file" class="form-control" id="Pro_image" name="Pro_image" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="Pro_total">จำนวนสินค้า</label>
                                            <input type="number" class="form-control" id="Pro_total" name="Pro_total" placeholder="ระบุจำนวน" required>
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