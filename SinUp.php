<?php
require "db.php";
session_start();




if(isset($_GET['dec'])){
	unset($_SESSION["IDclient"]);
}

//------------- Login------------------------
if(isset($_POST['signIn'])){

	$clientname=strip_tags($_POST['clientname']);
	$psw=strip_tags($_POST['psw']);

	$sql = "select * from `client` where `CLname` = '$clientname' and `CLpass`='$psw'";
	$run = mysqli_query($cn,$sql);
	if($rows=mysqli_fetch_array($run)){
		header("location: Accueil.php");

		$_SESSION['IDclient']=$rows[0];
		$_SESSION['client']=$rows[2];
		$_SESSION["ligneCommande"]=array();	
	}
}
//------------- Login------------------------




//------------- SinUp------------------------

function check($var){
    $var=trim($var);
    $var=strip_tags($var);
    $var=stripslashes($var);
    if(empty($var)) return false;
    else return $var;
}

if(isset($_POST["submit"])){
    $cnt=check($_POST["sinUpcnt"]);
    $name=check($_POST["sinUpname"]);
    $services=check($_POST["cntServices"]);
    $psw=check($_POST["sinUppsw"]);
    $Cpsw=check($_POST["sinUpCpsw"]);
    
    $error="";
    
    if(!$cnt) $error ="plese insertt a valid cnt";
    if(!$name) $error .="plese insertt a valid name";
    if(!$services) $error .="plese insertt a valid service";
    if(!$psw) $error .="plese insertt a valid password";
    if(!$Cpsw) $error .="plese insertt a valid confirm password";
    if($Cpsw != $psw) $error .="conferm incorect";
    
    if($error!="") die("$error");
    else{

        $sql="insert into `client`(`Cnt`, `CLname`, `CLpass`, `idSR`) values ('$cnt','$name','$psw','$services')";

        
        if(mysqli_query($cn,$sql)){
            header("location: SinUp.php");
        }

    }
}
//------------- SinUp------------------------


?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="description" content="welcom to my web site">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="images/icons/icone-onssa.png"/>
        <title>Inscription</title>
        <link rel="stylesheet" type="text/css" href="css/SinUpstyle.css">
    </head>
    <body>
        <div class="mainLogin">

            <div class="login">

                <div class="login-panel">

                        <div class='title'>
                            <label class='LBlogin' >Connecter</label>
                        </div>

                    <form method="post" autocomplete="off">
                        <div>
                            <input type="text" placeholder="Nom" name="clientname" class="clientname"  required>
                        </div>

                        <div>
                            <input type="password" placeholder="Mot de Pass" name="psw" id="psw" class="psw" required>
                        </div>
                        <div>
                            <label class="check">Afficher le mot de passe
                                <input type="checkbox" id="checkPsw">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div>  
                            <input type="submit" value="se connecter" name="signIn">
                        </div>
                    </form>
                </div>



                <div class="sinUp-panel">

                        <div class='title'>
                            <label class='LBsinUp' >Inscription</label>
                        </div>
                        
                    <form method="post" autocomplete="off">

                        <div>
                            <input type="text" placeholder="CNT" name="sinUpcnt" class="sinUpcnt" required>
                        </div>

                        <div>
                            <input type="text" placeholder="Nom" name="sinUpname" class="sinUpname" required>
                        </div>

                        <div>
                            <input type="password" placeholder="Mot de Pass" name="sinUppsw" class="sinUppsw"  required>
                        </div>


                        <div>
                            <input type="password" placeholder="Confirm Mot de Pass" name="sinUpCpsw" class="sinUpCpsw" required>
                        </div>

                        <div> 
                            <label class='LBservises'>Services</label>  
                            <select class='selServices' name="cntServices">
                            <?php 
                                $sql= "select * from services";
                                $req = mysqli_query($cn,$sql);
                                while ($raw=mysqli_fetch_array($req)) {
                                    echo "<option value=".$raw[0].">".$raw[1]."</option>";
                                }
                            ?>
                            </select>
                        </div>

                        <div>  
                            <input type="submit" value="Inscription" name="submit">
                        </div>


                        
                    </form>
                </div>
            </div> 

        </div>
       
    </body>

    <script src="JQUERY_FOLDER/jQuery-3.3.1.js"></script>
    <script src="js/scriptSinUp.js"></script>
</html>