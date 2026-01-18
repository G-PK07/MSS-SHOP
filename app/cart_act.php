<?php
session_start();

if(!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// ใช้ $_REQUEST เพื่อรับค่าได้ทั้งแบบ GET (จากลิ้งค์) และ POST (จากฟอร์ม)
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$p_id = isset($_REQUEST['p_id']) ? $_REQUEST['p_id'] : '';

// 1. เพิ่มสินค้า (Add)
if ($action == 'add' && !empty($p_id)) {
    if (isset($_SESSION['cart'][$p_id])) {
        $_SESSION['cart'][$p_id]++;
    } else {
        $_SESSION['cart'][$p_id] = 1;
    }
}

// 2. ลบสินค้า (Remove)
if ($action == 'remove' && !empty($p_id)) {
    unset($_SESSION['cart'][$p_id]);
}

// 3. อัปเดตจำนวน (Update)
if ($action == 'update') {
    // รับค่า Array 'amount' จากฟอร์ม
    $amount_array = $_POST['amount'];
    
    if(is_array($amount_array)){
        foreach ($amount_array as $p_id => $qty) {
            // เช็คว่าเป็นตัวเลข และมากกว่า 0
            if (is_numeric($qty) && $qty > 0) {
                $_SESSION['cart'][$p_id] = $qty;
            } else {
                // ถ้าใส่เลข 0 หรือมั่วมา ให้ปรับเป็น 1
                $_SESSION['cart'][$p_id] = 1;
            }
        }
    }
}

// กลับไปหน้าตะกร้า
header("Location: cart.php");
exit();
?>