<?php
require "db.php";
session_start();

if(isset($_GET['dec'])){
	unset($_SESSION["IDclient"]);
}


function check($var){
    $var=trim($var);
    $var=strip_tags($var);
    $var=stripslashes($var);
    if(empty($var)) return false;
    else return $var;
}


if(isset($_POST['signIn'])){

	$clientName=check($_POST['clientName']);
	$psw=check($_POST['psw']);

	$sql = "select * from `client` where `CLname` = '$clientName' and `CLpass`='$psw'";
	$run = mysqli_query($cn,$sql);
	if($rows=mysqli_fetch_array($run)){
		header("location: Accueil.php");

        $_SESSION['IDclient']=$rows[0];
        $_SESSION['CNTclient']=$rows[1];
		$_SESSION['client']=$rows[2];
		$_SESSION["ligneCommande"]=array();
	}
}
	
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="description" content="welcom to my web site">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="images/icons/icone-onssa.png"/>
        <title>Onssa</title>
        <link rel="stylesheet" type="text/css" href="css/indexStyle.css">
    </head>
    <body>
        

        <div class="mainLogin">

            <div class="login">

                <div class="login-panel">
                    <header class="logo">
                        <div class="infoApp">
                            <ul>
                                <li><span class="iconApp"></span></li>
                                <li><span class="nameApp">NSSA</span></li>
                            </ul>
                        </div>
                    </header>

                    <div class='title'>
                        <label class='LBlogin' >Connecter</label>
                    </div>
                    <form method="post" autocomplete="off">
                        <div class="con-in">
                            <input type="text" placeholder="Nom" name="clientName" class="clientName" id="clientName" required>
                        </div>

                        <div class="con-in">
                            <input type="password" placeholder="Mot de passe" name="psw" class="psw" id="psw" required>
                        </div>
                        <div class="con-in">
                            <label class="check">Afficher le mot de passe
                                <input type="checkbox"  id="checkPsw">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div>  
                            <input type="submit" value="se connecter" name="signIn">
                        </div>
                        <!--
                        <div>
                            <a href="SinUp.php">Crée un nouveau compte ?</a>
                        </div>
                        -->
                    </form>
                </div>
            </div> 

        </div>
       
    </body>

<script src="JQUERY_FOLDER/jQuery-3.3.1.js"></script>
<script src="js/scriptIndex.js"></script>

</html>