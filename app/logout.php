<?php
session_start();

// 1. ล้างค่าตัวแปร
session_unset();

// 2. ทำลาย Session ทิ้งถาวร
session_destroy();

// 3. สั่งเด้งไปหน้า index.php (แก้จาก ../index.php เป็น index.php เฉยๆ)
echo '<script type="text/javascript">';
echo 'window.location.href="index.php";'; // <--- แก้ตรงนี้ครับ
echo '</script>';
?>