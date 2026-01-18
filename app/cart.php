<?php
session_start();
include("includes/dbconnection.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>ตะกร้าสินค้า</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        .quantity-input {
            max-width: 60px;
            text-align: center;
        }
        /* ลบลูกศรขึ้นลง default ของ browser ออก เพื่อความสวยงาม */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1>ตะกร้าสินค้าของคุณ</h1>
    
    <div class="mb-3">
        <a href="index.php" class="btn btn-secondary">< กลับไปเลือกสินค้าต่อ</a>
    </div>

    <form action="cart_act.php?action=update" method="post">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>รูปภาพ</th>
                    <th>ชื่อสินค้า</th>
                    <th>ราคา/ชิ้น</th>
                    <th style="width: 180px;">จำนวน</th> <th>รวม (บาท)</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $total_price = 0;
            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $p_id => $qty) {
                    $sql = "SELECT * FROM tb_product WHERE ID = :pid";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':pid', $p_id);
                    $query->execute();
                    $row = $query->fetch(PDO::FETCH_OBJ);

                    $sum_row = $row->Pro_price * $qty;
                    $total_price += $sum_row;
            ?>
                <tr>
                    <td>
                        <img src="admin/productimages/<?php echo $row->Pro_image; ?>" width="60" style="border-radius:5px;">
                    </td>
                    <td><?php echo $row->Pro_name; ?></td>
                    <td><?php echo number_format($row->Pro_price); ?></td>
                    <td>
                        <div class="input-group">
                            <button class="btn btn-outline-secondary" type="button" onclick="decreaseValue('qty_<?php echo $p_id; ?>')">-</button>
                            
                            <input type="number" id="qty_<?php echo $p_id; ?>" name="amount[<?php echo $p_id; ?>]" value="<?php echo $qty; ?>" class="form-control quantity-input" min="1">
                            
                            <button class="btn btn-outline-secondary" type="button" onclick="increaseValue('qty_<?php echo $p_id; ?>')">+</button>
                        </div>
                    </td> 
                    <td><?php echo number_format($sum_row); ?></td>
                    <td>
                        <a href="cart_act.php?action=remove&p_id=<?php echo $p_id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('ลบสินค้านี้?');">ลบ</a>
                    </td>
                </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='6' class='text-center text-danger'>ยังไม่มีสินค้าในตะกร้า</td></tr>";
            }
            ?>
                <tr>
                    <td colspan="4" class="text-end fw-bold">ราคารวมทั้งสิ้น</td>
                    <td colspan="2" class="fw-bold bg-warning"><?php echo number_format($total_price, 2); ?> บาท</td>
                </tr>
            </tbody>
        </table>

        <?php if ($total_price > 0) { ?>
            <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded border">
                <div>
                    <span class="text-danger fw-bold">* หมายเหตุ:</span> หากกด + หรือ - แล้ว <br>
                    กรุณากดปุ่ม <strong class="text-primary">"คำนวณยอดเงินใหม่"</strong> เพื่ออัปเดตราคารวม
                </div>
                <div>
                    <button type="submit" class="btn btn-primary me-2">🔄 คำนวณยอดเงินใหม่</button>
                    
                    <a href="save_order.php" class="btn btn-success btn-lg">ยืนยันการสั่งซื้อ ></a>
                </div>
            </div>
        <?php } ?>
    </form>
</div>

<script>
    function increaseValue(inputId) {
        var value = parseInt(document.getElementById(inputId).value, 10);
        value = isNaN(value) ? 0 : value;
        value++; // เพิ่มค่า
        document.getElementById(inputId).value = value;
    }

    function decreaseValue(inputId) {
        var value = parseInt(document.getElementById(inputId).value, 10);
        value = isNaN(value) ? 0 : value;
        if (value > 1) { // ห้ามต่ำกว่า 1
            value--; // ลดค่า
            document.getElementById(inputId).value = value;
        }
    }
</script>

</body>
</html>