<?php
require "db.php";
session_start();
if($_SESSION["IDclient"]==null)header("location: index.php");




function check($var){
    $var=trim($var);
    $var=strip_tags($var);
    $var=stripslashes($var);
    if(empty($var)) return false;
    else return $var;
}


if(isset($_POST['changeName'])){
    $name=check($_POST["paramname"]);
    
    $error="";
    
    if(!$name) $error .="plese insertt a valid name";
    
    if($error!="") die("$error");
    else{

        $sql="update `client` set CLname = '$name' where idCL = ".$_SESSION["IDclient"]."";

        if(mysqli_query($cn,$sql)){

            $_SESSION['client']= $name;
            //header("location: Accueil.php");
        }

    }
}

if(isset($_POST['changePsw'])){


    $psw=check($_POST["parampsw"]);
    $Cpsw=check($_POST["paramCpsw"]);

    
    $error="";

    if(!$psw) $error .="plese insertt a valid password";
    if(!$Cpsw) $error .="plese insertt a valid confirm password";
    if($Cpsw != $psw) $error .="conferm incorect";

    
    if($error!="") die("$error");
    else{

        $sql="update `client` set CLpass = '$Cpsw' where idCL = ".$_SESSION["IDclient"]."";

        if(mysqli_query($cn,$sql)){
            header("location: index.php");
        }

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
        <title>Paramètres</title>

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&lang=en">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.cyan-light_blue.min.css">
        <link rel="stylesheet" href="css/stylesBTS.css">
        
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body> 
        <!---Star Header--->
        <?php include('Header.php'); ?>
        <!---end Header--->

        <!---Star navigation--->
        <?php  include('Navigation.php'); ?>
        <!---end navigation--->
        <div class="App">

            <!---Star Main--->
            <div class="main">
                
                

                <div class="content">

                        <div class="param_panel">

                            <div class='title'>
                                <label class='LBparam' >Changement de Nom</label>
                            </div>
                                
                            <form method="post" autocomplete="off">

                                <div>
                                    <input type="text" placeholder="Nom" name="paramname" class="paramname" required>
                                </div>

                                <div>  
                                    <input type="submit" value="Modifier" name="changeName">
                                </div>
                                
                            </form>





                            <div class='title'>
                                    <label class='LBparam' >Changement de mot de passe</label>
                            </div>

                            <form method="post" autocomplete="off">
                                <div>
                                    <input type="password" placeholder="Mot de Passe" name="parampsw" class="parampsw" id="parampsw"  required>
                                </div>


                                <div>
                                    <input type="password" placeholder="Confirm Mot de Passe" name="paramCpsw" class="paramCpsw" id="paramCpsw" required>
                                </div>

                                <div>
                                    <label class="check">Afficher le mot de passe
                                        <input type="checkbox"  id="checkPsw">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>

                                <div>  
                                    <input type="submit" value="Modifier" name="changePsw">
                                </div>

                            </form>


                        </div>




                </div>


                        
            </div>

                
        </div>  

        <!---Star footer--->
        <?php include('Footer.php'); ?>
        <!---end footer--->


    </body>


<script src="JQUERY_FOLDER/jQuery-3.3.1.js"></script>
<script src="js/script.js"></script>

</html>
