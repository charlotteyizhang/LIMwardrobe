<?php include("connect.php");

$mysqli = getConnect();
$stmt = $mysqli->stmt_init();
$user_id = isset($_POST['user_id'])? $_POST['user_id'] : null;
$query = "SELECT id, times, price FROM wardrobe WHERE user_id = ?";
if($stmt->prepare($query)){
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($clothes_id, $times, $price);
    $stmt->store_result();
    while($stmt->fetch()){
    	$row['clothes_id'] = (int)$clothes_id;
        $row['times'] = (int)$times;
        $row['PT'] = (double)($price/$times);
        $row_set[] = $row;//build an array
        
    }
    $stmt->close();
}
echo json_encode($row_set);
$mysqli->close();

?>