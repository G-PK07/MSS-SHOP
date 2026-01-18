<?php
session_start();
error_reporting(0);
include("../includes/dbconnection.php");

if (isset($_POST['submit'])) {
    try {
        // 1. รับค่า ID สินค้าที่ต้องการแก้
        $eid = $_GET['editid'];

        // 2. รับค่าจากฟอร์ม
        $ID_catagory = $_POST['ID_catagory'];
        $Pro_name = $_POST['Pro_name'];
        $Pro_price = $_POST['Pro_price'];
        $Pro_cost = $_POST['Pro_cost'];
        $Pro_total = $_POST['Pro_total'];
        
        // 3. จัดการรูปภาพ (เช็คว่ามีการอัปโหลดรูปใหม่มาไหม)
        $Pro_image = $_FILES['Pro_image']['name'];

        if ($Pro_image != "") {
            // --- กรณีมีการเปลี่ยนรูปภาพ ---
            
            // ตรวจสอบนามสกุลไฟล์
            $extension = strtolower(pathinfo($Pro_image, PATHINFO_EXTENSION));
            $allowed_extensions = array("jpg", "jpeg", "png", "gif");

            if (!in_array($extension, $allowed_extensions)) {
                echo "<script>alert('ประเภทไฟล์รูปภาพไม่ถูกต้อง');</script>";
            } else {
                // ตั้งชื่อไฟล์ใหม่
                $new_image = md5($Pro_image . time()) . "." . $extension;
                // อัปโหลดไฟล์ไปที่โฟลเดอร์เดิม
                move_uploaded_file($_FILES['Pro_image']['tmp_name'], "productimages/" . $new_image);

                // SQL Update แบบ "เปลี่ยนรูป"
                $sql = "UPDATE tb_product SET 
                        ID_catagory=:idcat, 
                        Pro_name=:pname, 
                        Pro_price=:pprice, 
                        Pro_cost=:pcost, 
                        Pro_total=:ptotal, 
                        Pro_image=:pimage 
                        WHERE ID=:eid";

                $query = $dbh->prepare($sql);
                $query->bindParam(':pimage', $new_image, PDO::PARAM_STR); // ผูกตัวแปรรูป
                
                // (ผูกตัวแปรอื่นๆ ทำด้านล่างรวบยอด)
            }
        } else {
            // --- กรณี "ไม่เปลี่ยน" รูปภาพ (ใช้รูปเดิม) ---
            $sql = "UPDATE tb_product SET 
                    ID_catagory=:idcat, 
                    Pro_name=:pname, 
                    Pro_price=:pprice, 
                    Pro_cost=:pcost, 
                    Pro_total=:ptotal 
                    WHERE ID=:eid";
            
            $query = $dbh->prepare($sql);
        }

        // 4. ผูกตัวแปรที่ใช้ร่วมกัน (ทั้งเปลี่ยนรูปและไม่เปลี่ยนรูป)
        if (isset($query)) { // เช็คว่า Query ถูกสร้างขึ้นแล้ว
            $query->bindParam(':idcat', $ID_catagory, PDO::PARAM_INT);
            $query->bindParam(':pname', $Pro_name, PDO::PARAM_STR);
            $query->bindParam(':pprice', $Pro_price, PDO::PARAM_STR);
            $query->bindParam(':pcost', $Pro_cost, PDO::PARAM_STR);
            $query->bindParam(':ptotal', $Pro_total, PDO::PARAM_INT);
            $query->bindParam(':eid', $eid, PDO::PARAM_STR);

            $query->execute();

            echo "<script>alert('แก้ไขข้อมูลสินค้าเรียบร้อยแล้ว');</script>";
            echo "<script>window.location.href='manage_product.php'</script>";
        }

    } catch (PDOException $e) {
        exit("Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Product</title>
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
                        <h3 class="page-title"> Edit Product </h3>
                    </div>

                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">แก้ไขข้อมูลสินค้า</h4>
                                    
                                    <form class="forms-sample" method="post" action="" enctype="multipart/form-data">
                                        
                                        <?php
                                        // ดึงข้อมูลสินค้าเดิมออกมาแสดง
                                        $eid = $_GET['editid'];
                                        $sql = "SELECT * FROM tb_product WHERE ID=:eid";
                                        $query = $dbh->prepare($sql);
                                        $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);

                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $row) {
                                        ?>
                                                <div class="form-group">
                                                    <label for="ID_catagory">หมวดหมู่สินค้า</label>
                                                    <select class="form-control" name="ID_catagory" required>
                                                        <option value="">เลือกหมวดหมู่</option>
                                                        <?php
                                                        // ดึงหมวดหมู่ทั้งหมดมาแสดงใน Dropdown
                                                        $sql2 = "SELECT ID, NameCatagory FROM tb_catagory";
                                                        $query2 = $dbh->prepare($sql2);
                                                        $query2->execute();
                                                        $result2 = $query2->fetchAll(PDO::FETCH_OBJ);
                                                        
                                                        foreach ($result2 as $row2) {
                                                            // เช็คว่าหมวดหมู่ไหนตรงกับของเดิม ให้เติมคำว่า selected
                                                            $selected = ($row->ID_catagory == $row2->ID) ? "selected" : "";
                                                        ?>
                                                            <option value="<?php echo $row2->ID; ?>" <?php echo $selected; ?>>
                                                                <?php echo $row2->NameCatagory; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="Pro_name">ชื่อสินค้า</label>
                                                    <input type="text" class="form-control" name="Pro_name" value="<?php echo $row->Pro_name; ?>" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="Pro_price">ราคาขาย</label>
                                                    <input type="text" class="form-control" name="Pro_price" value="<?php echo $row->Pro_price; ?>" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="Pro_cost">ราคาทุน</label>
                                                    <input type="text" class="form-control" name="Pro_cost" value="<?php echo $row->Pro_cost; ?>" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="Pro_total">จำนวนคงเหลือ</label>
                                                    <input type="number" class="form-control" name="Pro_total" value="<?php echo $row->Pro_total; ?>" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>รูปภาพปัจจุบัน</label><br>
                                                    <?php if($row->Pro_image != ""): ?>
                                                        <img src="productimages/<?php echo $row->Pro_image;?>" width="150" height="150" style="border-radius:10px; object-fit:cover;">
                                                    <?php else: ?>
                                                        <p>ไม่มีรูปภาพ</p>
                                                    <?php endif; ?>
                                                </div>

                                                <div class="form-group">
                                                    <label for="Pro_image">เปลี่ยนรูปภาพ (ถ้าไม่เปลี่ยนให้เว้นว่างไว้)</label>
                                                    <input type="file" class="form-control" name="Pro_image">
                                                </div>
                                                
                                                <button type="submit" class="btn btn-primary mr-2" name="submit">บันทึกการแก้ไข</button>
                                                <button type="button" class="btn btn-light" onclick="window.location.href='manage_product.php'">ยกเลิก</button>

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
    <script src="js/off-canvas.js"></script>
    <script src="js/misc.js"></script>
    <script src="./js/dashboard.js"></script>
</body>
</html>