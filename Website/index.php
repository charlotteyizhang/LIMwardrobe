<!DOCTYPE html>
<?php include "header.php";?>
<script >
$(function(){
    $("#warning_btn").click(function(){
    $("#warning").removeClass("hide", 1000);  
    });
});
</script>
<script type="text/javascript">getSlider()</script>
<!-- clothes group -->

<div class="container-fluid bg-action">
            <?php
            $user_id = isset($_SESSION['user_id'])? $_SESSION['user_id'] : null;
            if($user_id != null){
                $mysqli = getConnect();
                $stmt = $mysqli->stmt_init();
                $query = "SELECT user_limit FROM users WHERE user_id = ".$_SESSION['user_id'];
                if($stmt->prepare($query)){
                    $stmt->execute();
                    $stmt->bind_result($user_limit);
                    $stmt->store_result();
                    while ($stmt->fetch()) {
                        echo"
                        <div class='row text-center'>
                            <div class='statement'>
                                <label class='normalText'>My wardrobe only wants:<span id='amount' class='normalText barText'>".$user_limit."</span>clothes</label>
                                <input type='range' id='slider' class='barSlider' min='0' max='37' step='1' value='".$user_limit."'/>
                            </div>
                        </div>
                        <div class='center'>
                        ";
                    }
                }
                $stmt = $mysqli->stmt_init();
                $query = "SELECT A.id, A.name, A.weight, A.price, A.pic_id, A.times, B.picfile FROM wardrobe AS A, picdata AS B 
                WHERE A.pic_id = B.id AND A.status = '0' AND A.user_id = ".$user_id;
                if($stmt->prepare($query)){
                    $stmt->execute();
                    $stmt->bind_result($id, $name, $weight, $price, $pic_id, $time, $picfile);
                    /* store result */
                    $stmt->store_result();
                    $result_num = $stmt->num_rows;
                    if($result_num == 0){
                        echo "there is nothing in your Wardrobe";
                    }else{
                        //set 3s to update
                        echo "<script type='text/javascript'>setInterval(getDataFromWardrobe,3000, ".$user_id.")</script>";
                        $n=1;
                        echo"<div class=\"row text-center\" style=\"margin=30px;\">";
                        while($stmt->fetch()){
                           if($n<=$result_num){
                            if($n%2 != 0){
                                echo "</div><div class=\"row text-center\" style=\"margin=30px;\">";
                            }
                            echo "
                            <div class=\"col-sm-6 text-center\">
                            <img src='img/".$picfile."' class='img-responsive picCover'>
                                <div>
                                <h3>".$name."</h3>
                                <p>Price: ".$price."</p>
                                <p id=\"times".$id."\">Times: ".$time."</p>";
                                if($time == 0){
                                    $time = 1;
                                }
                                $PT = number_format($price/$time, 2);
                               echo" <p id=\"PT".$id."\">Price/Time: ".$PT."</p>
                               <a href=\"https://twitter.com/intent/tweet?button_hashtag=LIM_donate&text=My%20LIM%20wardrobe%20just%20helped%20me%20donate%20my%20".$name."%20away.%20Please%20contact%20me%20to%20get%20it!\" class=\"twitter-hashtag-button\" data-size=\"large\">Tweet to donate</a>
                                <div style='clear:both'></div>
                               <button onclick='changeStatus(".$id.");' type=\"button\" class=\"btn btn-danger\">Get rid from wardrobe</button>
                                </div>
                            </div>
                            ";
                            $n++;
                            }   
                        }
                    }
                }

            }else{
                include ("about.php");
            }
            ?>
        
    </div>
</div>
</div>

<!-- End of Clothes Group -->

<!-- End of Funding Group -->
<?php include "footer.php";?>

