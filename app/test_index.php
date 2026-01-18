<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" >
  </head>
  <body>
 
<?php
//$servername = "localhost";
$servername = "mysql-database";//เปลี่ยนไปใช้ชื่อ database container ของ Docker
$username = "root";
$password = "123456";
$dbname = "mydb_Projectweb";
 
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
 
$sql = "SELECT id, firstname, lastname FROM user";
$result = mysqli_query($conn, $sql);
 
if (mysqli_num_rows($result) > 0) {
   
  // output data of each row
  echo "<h3>User Table</h3>";
  echo "<table class='table'>";
    echo "<tr class='table-dark'>";
            echo "<th>Firstname</th>";
            echo "<th>Lastname</th>";
    echo "</tr>";
  while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
            echo "<td>".$row["firstname"]."</td>";
            echo "<td>".$row["lastname"]."</td>";
        echo "</tr>";
  }
  echo "</table>";
} else {
  echo "0 results";
}
 
mysqli_close($conn);
?>
  </body>
  </html>