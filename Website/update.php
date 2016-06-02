
<?php include("php/connect.php");
	$mysqli = getConnect();
    //get weight and user id from arduino
    $sensor_weight = isset($_GET['weight']) ? $_GET['weight'] : null;
    $user_id = isset($_GET['user']) ? $_GET['user'] : null;

    /* create a prepared statement */
    $stmt =  $mysqli->stmt_init();
    if($sensor_weight != null && $user_id != null){
        // $query = "INSERT INTO histories (donation_id) VALUES (?)";
        $query = "SELECT id, weight FROM wardrobe WHERE user_id = ?";
        if ($stmt->prepare($query)) {
           $stmt->bind_param("i",$user_id);
            $stmt->execute();
            $stmt->bind_result($id, $weight);
            $stmt->store_result();
            while($stmt->fetch()){
                //allow the tolerance scope 0.8 
                if(abs($sensor_weight - $weight)< 150){
                    update($id, $user_id);
                }
            }

            /* free result set */
            $stmt->close();
        }
    }

    $stmt = $mysqli->stmt_init();
    $insert = "INSERT INTO histories (donation_id) VALUES (?)";
    if($stmt->prepare($insert)) {
        $stmt->bind_param("i", $sensor_weight);
        $stmt->execute();
        $stmt->close();
        echo "insert!";
    }
    $mysqli->close();
    function update($clothes_id, $user_id){
        $mysqli = getConnect();
        $update = "UPDATE wardrobe SET times = times + 1 WHERE id=? AND user_id = ?";
        $stmt = $mysqli->stmt_init();
        if($stmt->prepare($update)){
            $stmt->bind_param("ii", $clothes_id, $user_id);
            $stmt->execute();
            echo "updated!";
        }
    }
?>