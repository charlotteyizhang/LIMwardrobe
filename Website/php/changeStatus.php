<?php
include ("connect.php");

$id = $_POST['id'];
if(isset($_SESSION['userurl'])){
    $url = $_SESSION['userurl'];
}else{
    $url = "../index.php";
}
$mysqli = getConnect();
$stmt = $mysqli->stmt_init();
$update = "UPDATE wardrobe SET status=1 WHERE id = ?";
if($stmt->prepare($update)){
	$stmt->bind_param("i", $id);
	$stmt->execute();
	echo"<p>Buy the new one</p>";
}
?>