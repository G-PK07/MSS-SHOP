<?php
session_start();
include("includes/dbconnection.php");

// 1. เช็คว่าล็อกอินหรือยัง? (ต้องมีรหัสสมาชิกถึงจะซื้อได้)
if (strlen($_SESSION['adid']) == 0) {
    echo "<script>alert('กรุณาล็อกอินก่อนทำการสั่งซื้อ!'); window.location='login.php';</script>";
    exit();
}

// 2. เช็คว่ามีของในตะกร้าไหม
if (empty($_SESSION['cart'])) {
    echo "<script>alert('ไม่มีสินค้าในตะกร้า'); window.location='index.php';</script>";
    exit();
}

try {
    // --- เริ่มต้นกระบวนการบันทึก (Transaction) ---
    // การใช้ Transaction คือถ้ามีอะไรผิดพลาดระหว่างทาง มันจะยกเลิกทั้งหมด (เงินไม่หาย ของไม่ตัดมั่ว)
    $dbh->beginTransaction(); 

    // A. คำนวณยอดรวมทั้งหมดอีกครั้ง (เพื่อความปลอดภัย ดึงราคาจริงจาก DB)
    $grand_total = 0;
    foreach ($_SESSION['cart'] as $p_id => $qty) {
        $sql = "SELECT Pro_price FROM tb_product WHERE ID = :pid";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':pid', $p_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        $grand_total += ($row->Pro_price * $qty);
    }

    // B. บันทึกลงตาราง tb_order (หัวบิล)
    // ID_User ใช้ค่าจาก Session 'adid' ที่เก็บตอนล็อกอิน
    $sql_order = "INSERT INTO tb_order (OrderDate, ID_User, TotalPrice) VALUES (NOW(), :uid, :total)";
    $stmt_order = $dbh->prepare($sql_order);
    $stmt_order->bindParam(':uid', $_SESSION['adid']); 
    $stmt_order->bindParam(':total', $grand_total);
    $stmt_order->execute();

    // ดึงเลขที่ใบสั่งซื้อล่าสุดที่เพิ่งสร้าง (OrderID)
    $last_order_id = $dbh->lastInsertId();

    // C. วนลูปสินค้าในตะกร้า เพื่อบันทึกรายละเอียด + ตัดสต็อก
    foreach ($_SESSION['cart'] as $p_id => $qty) {
        
        // ดึงข้อมูลสินค้า (เพื่อเอาราคา และเช็คสต็อกปัจจุบัน)
        $sql_prod = "SELECT Pro_price, Pro_total FROM tb_product WHERE ID = :pid";
        $stmt_prod = $dbh->prepare($sql_prod);
        $stmt_prod->bindParam(':pid', $p_id);
        $stmt_prod->execute();
        $product = $stmt_prod->fetch(PDO::FETCH_OBJ);

        // (แถม) เช็คก่อนว่าของพอให้ตัดไหม
        if ($product->Pro_total < $qty) {
            // ถ้าของไม่พอ ให้ยกเลิกทั้งหมด (Rollback) แล้วแจ้งเตือน
            $dbh->rollBack();
            echo "<script>alert('สินค้าบางรายการหมด หรือมีไม่พอจำหน่าย!'); window.location='cart.php';</script>";
            exit();
        }

        // C.1 บันทึกลง tb_order_detail
        $sql_detail = "INSERT INTO tb_order_detail (OrderID, ProductID, Qty, PricePerUnit) 
                       VALUES (:oid, :pid, :qty, :price)";
        $stmt_detail = $dbh->prepare($sql_detail);
        $stmt_detail->bindParam(':oid', $last_order_id);
        $stmt_detail->bindParam(':pid', $p_id);
        $stmt_detail->bindParam(':qty', $qty);
        $stmt_detail->bindParam(':price', $product->Pro_price);
        $stmt_detail->execute();

        // C.2 *** ตัดสต็อกสินค้า (ลดจำนวน Pro_total) ***
        $sql_stock = "UPDATE tb_product SET Pro_total = Pro_total - :qty WHERE ID = :pid";
        $stmt_stock = $dbh->prepare($sql_stock);
        $stmt_stock->bindParam(':qty', $qty);
        $stmt_stock->bindParam(':pid', $p_id);
        $stmt_stock->execute();
    }

    // --- ถ้าทำงานครบทุกขั้นตอน ให้บันทึกจริง (Commit) ---
    $dbh->commit();

    // ล้างตะกร้าสินค้า
    unset($_SESSION['cart']);

    echo "<script>alert('สั่งซื้อสำเร็จ! ขอบคุณที่ใช้บริการ'); window.location='index.php';</script>";

} catch (Exception $e) {
    // ถ้ามี Error แม้แต่จุดเดียว ให้ยกเลิกการบันทึกทั้งหมด (Rollback)
    $dbh->rollBack();
    echo "เกิดข้อผิดพลาด: " . $e->getMessage();
}
?>