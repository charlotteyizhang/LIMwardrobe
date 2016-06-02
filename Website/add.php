<!DOCTYPE html>
<?php include "header.php";?>
<div class="container-fluid text-center center">
    <?php
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    if(!isset($_SESSION['user_id'])){
        echo "please log in your wardrobe";
        exit();
    }else{
        $user_id = $_SESSION['user_id'];
    }
        $mysqli = getConnect();
        $stmt = $mysqli->stmt_init();
        $user_limit = 0;
        $query = "SELECT user_limit FROM users WHERE user_id = ? ";
        if($stmt->prepare($query)){
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($limit);
            $stmt->store_result();
            while ($stmt->fetch()){
                $user_limit = $limit;
            }
        }
        if($result = $mysqli->query( "SELECT id FROM wardrobe WHERE status = '0'")){
           // echo "<h2 class=\"heronumber\">".$result->num_rows."</h2>";
            if($result->num_rows >= $user_limit){
                echo "<p id='wardrobe_refuse' class='bg-warning text-center'>Your wardrobe is full with ".$user_limit." clothes! Please give some clothes away.</p>
                <a href='#' class='hidden'>I really want to buy it!</a>
                <div style='clear:both;'></div>
                <div id='wardrobe_suggestion' class='row center'>";
                $query = "SELECT A.id, A.name, A.price, B.picfile, A.times, A.birthday, round(A.price/A.times,4) AS performance FROM wardrobe AS A, picdata AS B 
                 WHERE round(A.price/A.times,4) = (SELECT MIN(ROUND(price/times,4)) FROM wardrobe) AND A.pic_id = B.id";
                $stmt2 = $mysqli->stmt_init();
                if($stmt2->prepare($query)){
                    $stmt2->execute();
                    $stmt2->bind_result($maxT_id, $maxT_name, $maxT_price, $maxT_pic_id, $maxT_times, $maxT_birthday, $maxT_performance);
                    $stmt2->store_result();
                    while ($stmt2->fetch()) {
                        echo "<div class='col-sm-6 text-center'><p>The one has the highest Price/performance ratio is:</p>
                        <img class=\"img-responsive picCover\"  src='img/".$maxT_pic_id."'/>
                        <h3>".$maxT_name."</h3>
                        <p>price: ".$maxT_price."</p>
                        <p>you bought in ".$maxT_birthday."</p>
                        <p>have worn: ".$maxT_times." times</p>
                        <a href=\"https://twitter.com/intent/tweet?button_hashtag=LIM_donate&text=My%20LIM%20wardrobe%20just%20helped%20me%20donate%20my%20".$maxT_name."%20away.%20Please%20contact%20me%20to%20get%20it!\" class=\"twitter-hashtag-button\" data-size=\"large\" >Tweet to donate</a>
                        <div style='clear:both'></div>
                        <button onclick='changeStatus(".$maxT_id.");' type=\"button\" class=\"btn btn-danger\">Get rid from wardrobe</button>
                        </div>";
                    }
                }
                $query = "SELECT A.id, A.name, A.price, B.picfile, A.times, A.birthday FROM wardrobe AS A, picdata AS B WHERE (A.times/DATEDIFF(CURRENT_DATE(),A.birthday)) = (SELECT MIN(times/DATEDIFF(CURRENT_DATE(),birthday)) FROM wardrobe) 
                AND A.pic_id = B.id;";
                $stmt3 = $mysqli->stmt_init();
                if($stmt3->prepare($query)){
                    $stmt3->execute();
                    $stmt3->bind_result($minT_id, $minT_name, $minT_price, $minT_pic_id, $minT_times, $minT_birthday);
                    $stmt3->store_result();
                    while ($stmt3->fetch()) {
                        echo "<div class='col-sm-6 text-center'><p>The one you wear the least:</p>
                        <img class=\"img-responsive picCover\" src='img/".$minT_pic_id."'/>
                        <h3>".$minT_name."</h3>
                        <p>price: ".$minT_price."</p>
                        <p>you bought in ".$minT_birthday."</p>
                        <p>have worn: ".$minT_times." times</p>
                        <a href=\"https://twitter.com/intent/tweet?button_hashtag=LIM_donate&text=My%20LIM%20wardrobe%20just%20helped%20me%20donate%20this%20away.%20Please%20contact%20me%20to%20get%20it!\" class=\"twitter-hashtag-button\" data-size=\"large\">Tweet #LIM_donate</a>
                        <div style='clear:both'></div>
                        <button onclick='changeStatus(".$maxT_id.");' type=\"button\" class=\"btn btn-danger\">Get rid from wardrobe</button>
                        </div>
                        ";
                    }
                }
            }else{
                $query = "SELECT A.name, A.price, A.weight, A.pic_id, B.picfile FROM shop AS A, picdata AS B WHERE 
                A.pic_id = B.id AND A.id = ?";
                $stmt = $mysqli->stmt_init();
                if($stmt->prepare($query)){
                    $stmt->bind_param("i",$id);
                    $stmt->execute();   
                    $stmt->bind_result( $name, $price, $weight, $pic_id, $picfile);
                    $stmt->store_result();
                    while ($stmt->fetch()){
                        echo "<div class='text-center'>
                        <img class=\"img-responsive picCover\" src='img/".$picfile."'/>
                        <h3>".$name."</h3>
                        <p>price: ".$price."</p>
                        </div>";
                    }
                }
                //id, name, price, pic_id, weight, user_id
                echo "
                <p id='text'></p>
                <button onclick='buy(\"".$name."\",".$price.",".$pic_id.",".$weight.",".$user_id.")' class='btn btn-info'>I really want to buy it!</button>
                ";
            }
            /* free result set */
            $result->close();
        
        }

        $query = "SELECT name, weight, price FROM shop WHERE id = ?";
        if($stmt->prepare($query)){
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($name, $weight, $price);
            while($stmt->fetch()){
                #
            }
        }

    ?>
</div>
<div >
<a class="btn btn-default" href="index.php">View more clothes in wardrobe</a>
</div>
</div>
<!-- End of Clothes Group -->
    
<!-- End of Funding Group -->
<?php include "footer.php";?>

