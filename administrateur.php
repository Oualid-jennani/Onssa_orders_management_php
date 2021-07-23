<?php
require "db.php";
require "Classes.php";

session_start();

if(isset($_SESSION['IDAdmin']) && isset($_SESSION['Admin'])){
    header("location: Admin.php");
}
else if(isset($_SESSION['IDadminChef'] ) && isset($_SESSION['adminChef']) && isset($_SESSION["idM"] ) &&isset($_SESSION["MGName"])){
    header("location: chefAdmin.php");
}
else if(isset($_SESSION['IDadminGest'] ) && isset($_SESSION['IDadminGest']) && isset($_SESSION["idM"] ) &&isset($_SESSION["MGName"])){
    header("location: GestionAdmin.php");
}
else if(isset($_SESSION['IDadminMG'] ) && isset($_SESSION['IDadminMG']) && isset($_SESSION["idM"] ) &&isset($_SESSION["MGName"])){
    header("location: Magasinier.php");
}


if(isset($_POST['signIn'])){

	$AdminName=strip_tags($_POST['AdminName']);
	$psw=strip_tags($_POST['psw']);

	$sql = "select * from `admin` where `Adname` = '$AdminName' and `Adpass`='$psw'";
	$run = mysqli_query($cn,$sql);
	
	if($rows=mysqli_fetch_array($run)){

		if($rows['typeAD']=="admin"){

			$_SESSION['IDAdmin']=$rows[0];
			$_SESSION['Admin']=$rows[2];
			header("location: Admin.php");
		}else{

			//---------------------------------------------
			$_SESSION["idM"]=null;
			$_SESSION["MGName"]=null;

			$sql = "select distinct m.idM,m.MGname from magasin m inner join admin ad on ad.idM=m.idM where ad.idAD = ".$rows[0]." and m.activation = true";
			$run = mysqli_query($cn,$sql);
			
			if($raw=mysqli_fetch_array($run)){
				$_SESSION["MGName"] = $raw[1];$_SESSION["idM"] = $raw[0];
			}
			//---------------------------------------------

			if($rows['typeAD']=="chef"){

				$_SESSION['IDadminChef']=$rows[0];
				$_SESSION['adminChef']=$rows[2];

				unset($_SESSION["IDadminGesT"]);
				unset($_SESSION["adminGesT"]);
				unset($_SESSION["IDadminMG"]);
				unset($_SESSION["adminMG"]);
				unset($_SESSION["IDAdmin"]);
				unset($_SESSION["Admin"]);
	
				header("location: ChefAdmin.php");
	
			}else if ($rows['typeAD']=="gestionnaire"){
	
				$_SESSION['IDadminGesT']=$rows[0];
				$_SESSION['adminGesT']=$rows[2];

				unset($_SESSION["IDadminChef"]);
				unset($_SESSION["adminChef"]);
				unset($_SESSION["IDadminMG"]);
				unset($_SESSION["adminMG"]);
				unset($_SESSION["IDAdmin"]);
				unset($_SESSION["Admin"]);


				header("location: GestionAdmin.php");
	
			}else if($rows['typeAD']=="magasinier"){
	
				$_SESSION['IDadminMG']=$rows[0];
				$_SESSION['adminMG']=$rows[2];

				unset($_SESSION["IDadminChef"]);
				unset($_SESSION["adminChef"]);
				unset($_SESSION["IDadminGesT"]);
				unset($_SESSION["adminGesT"]);
				unset($_SESSION["IDAdmin"]);
				unset($_SESSION["Admin"]);

		
				header("location: Magasinier.php");
			}

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
        <title>Admin Onssa</title>
        <link rel="stylesheet" type="text/css" href="css/LoginAdminStile.css">
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
                        <label class='LBlogin' >Panel Control</label>
                    </div>
                    <form method="post" autocomplete="off">
                        <div class="con-in">
                            <input type="text" placeholder="Nom" name="AdminName" class="AdminName" required>
                        </div>

                        <div class="con-in">
                            <input type="password" placeholder="Mot de passe" name="psw" id="psw" class="psw"required>
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
                    </form>
                </div>
            </div> 

        </div>
       
    </body>

<script src="JQUERY_FOLDER/jQuery-3.3.1.js"></script>
<script src="js/scriptAdminIndex.js"></script>

</html>