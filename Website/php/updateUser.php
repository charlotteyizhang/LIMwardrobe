<?php include("connect.php");

$mysqli = getConnect();
$user_limit = isset($_POST['user_limit'])? $_POST['user_limit'] : null;
$user_id = $_SESSION['user_id'];
$stmt = $mysqli->stmt_init();
$update = "UPDATE users SET user_limit = ? WHERE user_id = ?";
if($stmt->prepare($update)){
	$stmt->bind_param("ii", $user_limit, $user_id);
	$stmt->execute();
	echo $user_limit;
}
?>