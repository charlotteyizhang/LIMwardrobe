<?php include("connect.php");
    $mysqli = getConnect();
    $sensor_weight = isset($_POST['weight']) ? $_POST['weight'] : null;
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;
    echo $sensor_weight.",".$user_id;
    /* create a prepared statement */
    $stmt = $mysqli->stmt_init();
    if($sensor_weight != null && $user_id != null){
        // $query = "INSERT INTO histories (donation_id) VALUES (?)";
        //select weight from the wardrobe to compare with the sensor weight
        $query = "SELECT id, weight FROM wardrobe WHERE user_id = ?";
        if ($stmt->prepare($query)) {
           $stmt->bind_param("i",$user_id);
            $stmt->execute();
            $stmt->bind_result($clothes_id, $weight);
            $stmt->store_result();
            while($stmt->fetch()){
                //allow the tolerance scope 0.8 
                if(abs($sensor_weight - $weight)< 0.8){
                    update($clothes_id, $user_id);
                    $query = "SELECT times, price FROM wardrobe WHERE id=? AND user_id = ?";
                    if($stmt->prepare($query)){
                        $stmt->bind_param("ii", $clothes_id, $user_id);
                        $stmt->execute();
                        $stmt->bind_result($times, $price);
                        $stmt->store_result();
                        while($stmt->fetch()){
                            $row['user_id'] = $user_id;
                            $row['clothes_id'] = (int)$clothes_id;
                            $row['times'] = (int)$times;
                            $row['PT'] = (float)($price/$times);
                            $row_set[] = $row;//build an array
                        }
                    }
                }
            }

            /* free result set */
            $stmt->close();
        }
    }
    function update($clothes_id, $user_id){
        $mysqli = getConnect();
        $update = "UPDATE wardrobe SET times = times + 1 WHERE id=? AND user_id = ?";
        $stmt = $mysqli->stmt_init();
        if($stmt->prepare($update)){
            $stmt->bind_param("ii", $clothes_id, $user_id);
            $stmt->execute();
        }
    }
    $stmt = $mysqli->stmt_init();
    $insert = "INSERT INTO histories (donation_id) VALUES (?)";
    if($stmt->prepare($insert)) {
        $stmt->bind_param("i", $sensor_weight);
        $stmt->execute();
        $stmt->close();
    }
    echo json_encode($row_set);
    $mysqli->close();
    

?>