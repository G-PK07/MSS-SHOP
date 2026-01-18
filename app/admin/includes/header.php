<?php
// 1. ตรวจสอบการเชื่อมต่อฐานข้อมูล
if (!isset($dbh)) {
    include("../includes/dbconnection.php"); 
}

// *** แก้ไขจุดสำคัญ: เช็คว่าเป็น Admin จริงไหม และใช้ตัวแปร admin_id ***
if (empty($_SESSION['admin_id'])) {
    // ถ้าไม่มี Session ของแอดมิน ให้เด้งออกไปหน้า Login ทันที (ป้องกันคนนอกเข้า)
    header('location:../login.php');
    exit();
}

// ใช้ ID จาก Session เฉพาะของ Admin (ไม่ใช่ adid ที่ใช้ร่วมกับ user)
$aid = $_SESSION['admin_id']; 

// 2. เขียนคำสั่ง SQL เพื่อดึง UserName, AdminName, Email
$sql = "SELECT AdminName, Email, UserName FROM tb_admin WHERE ID=:aid";
$query = $dbh->prepare($sql);
$query->bindParam(':aid', $aid, PDO::PARAM_STR);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);

// 3. กำหนดค่าเริ่มต้นตัวแปร
$showUsername = "Admin"; 
$showEmail = "";

// 4. เอาข้อมูลที่ดึงได้มาใส่ตัวแปร
if ($query->rowCount() > 0) {
    foreach ($results as $row) {
        $showUsername = $row->UserName; 
        $showEmail = $row->Email;
    }
}
?>

<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    
    <div class="navbar-brand-wrapper d-flex align-items-center">
        <a class="navbar-brand brand-logo" href="dashboard.php">
            <strong style="color: white; font-size: 1.5rem;">Menu</strong>
        </a>
        <a class="navbar-brand brand-logo-mini" href="dashboard.php">
            <img src="images/logo-mini.svg" alt="logo" />
        </a>
    </div>

    <div class="navbar-menu-wrapper d-flex align-items-center flex-grow-1">
        <h5 class="mb-0 font-weight-medium d-none d-lg-flex">mitchachip SHOP</h5>
        
        <ul class="navbar-nav navbar-nav-right ml-auto">
            <li class="nav-item dropdown d-none d-xl-inline-flex user-dropdown">
                <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                    <img class="img-xs rounded-circle ml-2" src="images/faces/face8.jpg" alt="Profile image"> 
                    <span class="font-weight-normal"> <?php echo htmlentities($showUsername); ?> </span>
                </a>
                
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                    <div class="dropdown-header text-center">
                        <img class="img-md rounded-circle" src="images/faces/face8.jpg" alt="Profile image">
                        <p class="mb-1 mt-3 font-weight-bold"><?php echo htmlentities($showUsername); ?></p>
                        <p class="font-weight-light text-muted mb-0"><?php echo htmlentities($showEmail); ?></p>
                    </div>
                    
                    <a class="dropdown-item" href="profile.php">
                        <i class="dropdown-item-icon icon-user text-primary"></i> ข้อมูลส่วนตัว
                    </a>
                    
                    <a class="dropdown-item" href="../logout.php" onclick="return confirm('ต้องการออกจากระบบหรือไม่?');">
                        <i class="dropdown-item-icon icon-power text-primary"></i> ออกจากระบบ
                    </a>
                </div>
            </li>
        </ul>
        
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="icon-menu"></span>
        </button>
    </div>
</nav>