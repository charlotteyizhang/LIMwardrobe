<?php
require_once("connect.php");

$name = $_POST['name'];
$weight = $_POST['weight'];
$pic_id = $_POST['pic_id'];
$price = $_POST['price'];
$user_id = $_POST['user_id'];
$birthday = date("Y-m-d");

$mysqli = getConnect();
$insert = "INSERT INTO wardrobe (name, weight, pic_id, price, birthday, user_id) VALUES (?,?,?,?,?,?)";	
$stmt = $mysqli->stmt_init();
if($stmt->prepare($insert)){
	$stmt->bind_param("siidsi",$name, $weight, $pic_id, $price, $birthday, $user_id);
	$stmt->execute();
	echo "ok".$name.$birthday;
}

?>