<?php
// ดึงข้อมูล Admin ที่ล็อกอินอยู่ เพื่อเอามาโชว์รูปและชื่อ
$aid = $_SESSION['admin_id'];
$sql = "SELECT AdminName, UserName, Email FROM tb_admin WHERE ID=:aid";
$query = $dbh->prepare($sql);
$query->bindParam(':aid', $aid, PDO::PARAM_STR);
$query->execute();
$row = $query->fetch(PDO::FETCH_OBJ);
$adminName = $row->AdminName; // หรือใช้ UserName ก็ได้
$adminRole = "Administrator"; 
?>

<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <a href="profile.php" class="nav-link">
                <div class="profile-image">
                    <img class="img-xs rounded-circle" src="images/faces/face8.jpg" alt="profile image">
                    <div class="dot-indicator bg-success"></div>
                </div>
                <div class="text-wrapper">
                    <p class="profile-name"><?php echo htmlentities($adminName); ?></p>
                    <p class="designation"><?php echo $adminRole; ?></p>
                </div>
            </a>
        </li>
        
        <li class="nav-item nav-category">
            <span class="nav-link">SHOP MANAGEMENT</span>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="dashboard.php">
                <span class="menu-title">Dashboard</span>
                <i class="icon-screen-desktop menu-icon"></i>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#auth1" aria-expanded="false" aria-controls="auth">
                <span class="menu-title">Users</span>
                <i class="icon-people menu-icon"></i>
            </a>
            <div class="collapse" id="auth1">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> 
                        <a class="nav-link" href="add-user.php">Add Users</a>
                    </li>
                    <li class="nav-item"> 
                        <a class="nav-link" href="manage_users.php">Manage Users</a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#auth2" aria-expanded="false" aria-controls="auth">
                <span class="menu-title">Categories</span>
                <i class="icon-layers menu-icon"></i>
            </a>
            <div class="collapse" id="auth2">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> 
                        <a class="nav-link" href="add-catagory.php">เพิ่มประเภทสินค้า</a>
                    </li>
                    <li class="nav-item"> 
                        <a class="nav-link" href="manage_catagory.php">รายการประเภทสินค้า</a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#auth3" aria-expanded="false" aria-controls="auth">
                <span class="menu-title">Products</span>
                <i class="icon-handbag menu-icon"></i>
            </a>
            <div class="collapse" id="auth3">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> 
                        <a class="nav-link" href="add-product.php">เพิ่มสินค้า</a>
                    </li>
                    <li class="nav-item"> 
                        <a class="nav-link" href="manage_product.php">รายการสินค้า</a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="manage_orders.php">
                <span class="menu-title">Orders</span>
                <i class="icon-basket-loaded menu-icon"></i>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="../index.php" target="_blank">
                <span class="menu-title">Visit Website</span>
                <i class="icon-globe menu-icon"></i>
            </a>
        </li>

    </ul>
</nav>