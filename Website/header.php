<?php
include ("php/login.php");
include ("php/public.php");
?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="initial-scale=1.0">
  <meta name="keywords" content="capsule wardrobe">
  <title>Wardrobe | <?php echo getTitle();?></title>
  <link rel="SHORTCUT ICON" href="img/favicon.ico">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/myjs.js"></script>
  <script type="text/javascript" src="js/ajax.js"></script> <!--Loading ajax.js-->
  <link rel="stylesheet" href="css/main.css">

</head>
<body>
<nav class="navbar">
  <div class="container-fluid maxWidth">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a href="index.php"><img class="logo" src="img/header_logo.png"></a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      
    <!-- /input-group -->
       <?php
      /**--logged in php **/
       $_SESSION['userurl'] = $_SERVER['REQUEST_URI'];
      $login = getConnect();
      // if( $_SESSION["name"] )
      if( isset($_SESSION["user_name"]) && $_SESSION["user_name"] )
      {
        echo " 
        <ul class=\"nav navbar-nav\">
          <li><a href=\"memory.php\">".$_SESSION['user_name']."'s Wardrobe</a></li>
        </ul>
        <div class=\"nav navbar-right\">
          <a id=\"userBtn\" class=\"loginBtn\" href=\"php/logout.php\">log out
          </a>
        </div>
      </div>";
      }else{
        echo"
      <div class=\"nav navbar-right\">
      <div class=\"notice row\">
        <h9 id=\"userText\"></h9>
       </div>
        <a id=\"loginBtn\" class=\"loginBtn\" href=\"#\"> Login</a>
        <!--Login form-->
        <div id=\"loginForm\" class=\"loginForm navForm hide\">
          <form action = \"php/login.php?url=".$_SESSION['userurl']."\" method=\"post\">
            <input type=\"text\" class=\"form-control marginbottom\" name=\"user_name\" placeholder=\"User Name\" required=\"required\">
            <input type=\"password\" class=\"form-control marginbottom\" name=\"password\" placeholder=\"Password\" required=\"required\">
            <button type=\"submit\" class=\"btn btn-block\" name=\"login\">Login</button>
          </form>
          <a href=\"#\">Forget password?</a>
        </div>
        <!-- register form-->
        <a id=\"registerBtn\" class=\"registerBtn\" href=\"#\"> Register</a>
        <div>
          <button type=\"button\" class=\"close\" id=\"close\"></button>
        </div>
        <div id=\"registerForm\" class=\"registerForm navForm hide\">
          <form action = \"php/register.php?url=".$_SESSION['userurl']."\" method=\"post\">
            <input type=\"text\" class=\"form-control marginbottom\" name=\"user_name\" onblur=\"valiate(this.value);\" placeholder=\"User Name\" required=\"required\">
            <input type=\"password\" class=\"form-control marginbottom\" name=\"password\" placeholder=\"Password\" required=\"required\">
            <input type=\"password\" class=\"form-control marginbottom\" name=\"rePw\" placeholder=\"Confirm Password\" required=\"required\">
            <input type=\"email\" class=\"form-control marginbottom\" name=\"email\"  placeholder=\"Email\" required=\"required\">
            <button type=\"submit\" class=\"btn btn-block\" name=\"register\">Register</button>
          </form>
        </div>
      </div>
    ";
        }
      
       $login->close();
      ?>

  </div>
</nav>