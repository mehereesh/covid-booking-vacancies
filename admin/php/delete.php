<?php 
if(isset($_GET["cid"])){
    $id=$_GET["cid"];

    $servername="localhost";
    $username="root";
    $password="";
    $database="mydb";

    $connection = new mysqli($servername,$username,$password,$database);

    $sql = "DELETE FROM centre WHERE id=$id";
    $connection->query($sql);
}

header("Location: index.php");
exit;
?>