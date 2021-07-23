<?php

require "db.php";

session_start();


//deconexion
if(isset($_POST['dec'])){
	unset($_SESSION["IDadminChef"]);
	unset($_SESSION["adminChef"]);
	unset($_SESSION["IDadminGesT"]);
	unset($_SESSION["adminGesT"]);
	unset($_SESSION["IDadminMG"]);
    unset($_SESSION["adminMG"]);
    unset($_SESSION["IDAdmin"]);
	unset($_SESSION["Admin"]);

	unset($_SESSION["idM"]);
    unset($_SESSION["MGName"]);
  
    header("location: administrateur.php");
}
else if($_SESSION['IDAdmin'] == null || $_SESSION['Admin'] == null ){
    header("location: administrateur.php");
}




function submitMagasin(){
    if(isset($_POST["ADmagasin"])){


        $_SESSION["idM"]=null;
        $_SESSION["MGName"]=null;
    
        $sql = "select m.MGname from magasin m where m.idM = ".$_POST["ADmagasin"]." and m.activation = true";
        $run = mysqli_query($GLOBALS["cn"],$sql);
        
        if($raw=mysqli_fetch_array($run)){
            $_SESSION["MGName"] = $raw[0];
            $_SESSION["idM"] = $_POST["ADmagasin"];
        }
    }
}

if(isset($_POST["ADChef"])){

    $_SESSION['IDadminChef']=$_SESSION['IDAdmin'];
    $_SESSION['adminChef']=$_SESSION['Admin'];

    unset($_SESSION["IDadminGesT"]);
    unset($_SESSION["adminGesT"]);
    unset($_SESSION["IDadminMG"]);
    unset($_SESSION["adminMG"]);

    submitMagasin();
    header("location: ChefAdmin.php");

}else if(isset($_POST["ADGestionnaire"])){

    $_SESSION['IDadminGesT']=$_SESSION['IDAdmin'];
    $_SESSION['adminGesT']=$_SESSION['Admin'];

    unset($_SESSION["IDadminChef"]);
    unset($_SESSION["adminChef"]);
    unset($_SESSION["IDadminMG"]);
    unset($_SESSION["adminMG"]);

    submitMagasin();
    header("location: GestionAdmin.php");

}else if(isset($_POST["ADMagasinier"])){

    $_SESSION['IDadminMG']=$_SESSION['IDAdmin'];
    $_SESSION['adminMG']=$_SESSION['Admin'];

    unset($_SESSION["IDadminChef"]);
    unset($_SESSION["adminChef"]);
    unset($_SESSION["IDadminGesT"]);
    unset($_SESSION["adminGesT"]);

    submitMagasin();
    header("location: Magasinier.php");
}




?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin</title>
        <link rel="stylesheet" type="text/css" href="css/AdminStyle.css">
        <link rel="icon" type="image/png" href="images/icons/icone-onssa.png"/>
        <link rel="stylesheet" href="css/jquery-ui.min.css">
    </head>
    <body>
        <div class="header">
            
            <div class="infoHeader">
                <div class="infoApp">

                    <ul>
                        <li><span class="iconApp"></span></li>
                        <li><span class="nameApp">Admin Onssa</span></li>
                    </ul>

                </div>

                <div class="infoProfil">

                    <ul>
                        <li>
                            <form method="post">
                                <input type="submit" class="dec" name="dec" value="">
                            </form>
                        </li>

                        <li><span class="name">Admin : <?php echo $_SESSION['Admin'] ; ?></span></li>
                        <li><span class="photo"></span></li>



                    </ul>

                </div>
            </div>
            <div class="infoMagasin"><h4>Panel Control</h4></div>

        </div>
            
            
        

        <div class="main">
          <div class="menu">
            <ul>
              <li class="tablinks active" onclick="openTab(event, 'ADclients')">Contrôle Des clients</li>
              <li class="tablinks" onclick="openTab(event, 'ADAdmin')"> Contrôle Des Admin</li>
              <li class="tablinks" onclick="openTab(event, 'ADservices')"> Contrôle Des Services</li>
            </ul>
          </div>

          <div class="contain">


            <!--------------------------------------------------------------------------------------------->
            <div id="ADclients" class="tabcontent">

                <div class="Action"><input class="txtsearsh" id="CLsearsh" type="text" placeholder="Search..."><input type='button' id='AjouterClient' value='Ajouter Client' /></div>

                <div class='divTable'>
                    <table id='tabClient' class='tableAdmin'>
                        <thead>
                            <tr>
                                <th>CNT</th><th>Nom</th><th>Mot de Passe</th><th>Service</th><th>Action</th>
                            </tr>
                        </thead>
                        <tbody id='rawClient'></tbody>
                    </table>
                </div>

            </div>
            <!--------------------------------------------------------------------------------------------->
            <div id="ADAdmin" class="tabcontent"> 

                <div class="Action"><input class="txtsearsh" id="CLsearsh" type="text" placeholder="Search..."><input type='button' id='AjouterAdmin' value='Ajouter Admin' /></div>

                <div class='divTable'>
                    <table id='tabAdmin' class='tableAdmin'>
                        <thead>
                            <tr>
                                <th>CNT</th><th>Nom</th><th>Mot de Passe</th><th>Type</th><th>Magasin</th><th>Service</th><th>Action</th>
                            </tr>
                        </thead>
                        <tbody id='rawAdmin'></tbody>
                    </table>
                </div>

            </div>
            <!--------------------------------------------------------------------------------------------->
            <div id="ADservices" class="tabcontent">

                <div class="Action"><input class="txtsearsh" id="CLsearsh" type="text" placeholder="Search..."><input type='button' id='AjouterServices' value='Ajouter Service' /></div>

                <div class='divTable'>
                    <table id='tabServices' class='tableAdmin'>
                        <thead>
                            <tr>
                                <th>Service</th><th>Action</th>
                            </tr>
                        </thead>
                        <tbody id='rawServices'></tbody>
                    </table>
                </div>

            </div>
            <!--------------------------------------------------------------------------------------------->



          </div>



          <div class="info">
            <div class="aside">
                <form method="post" action='' name='f'>

                    <div>
                        <select id="ADmagasin"  name="ADmagasin"></select>
                    </div>

                    <div>
                        <input type='submit' value='Chef' name="ADChef">
                    </div>
                    
                    <div>
                        <input type='submit' value='Gestionnaire' name="ADGestionnaire">
                    </div>

                    <div>
                        <input type='submit' value='Magasinier' name="ADMagasinier">
                    </div>
                    

                </form>
            
            </div>

          </div>

        </div>




        <div class="footer">
          <p>Copyright &copy; 2019 &reg;</p>
        </div>
        


        <!----------------------------------Dialog delete------------------------------------->

        <div class="dialog-delete">
            <div class="dialog-container">
                <div class="hideDelete"><button id="hideDelete">&#215;</button></div>
                
                <h4>Voulez-vous vraiment supprimer ces enregistrements ?</h4>
                <div class="DeleteBtn">
                    <button id="DeleteTrue">Oui</button>
                    <button id="DeleteFalse">Non</button>
                </div>
            </div>
        </div>
        <!----------------------------------------------------------------------->

            <div class="AJT-MDF" id='AjtCL'>
                
                <fieldset class="">
                    <legend class="LGBack">Ajouter Nouveau Client</legend>
                    
                    <div>
                        <div>
                            <label>CNT :</label>
                        </div>
                        <div>
                            <input class="AjCntCL" id="AjCntCL" type="text">
                        </div>
                    </div>

                    <div>
                        <div>
                            <label>Nom :</label>
                        </div>
                        <div>
                            <input class="AjNomCL" id="AjNomCL" type="text">
                        </div>                      
                    </div>

                    <div>
                        <div>
                            <label>Mot De pass :</label>
                        </div>
                        <div>
                            <input class="AjPassCL" id="AjPassCL" type="text">
                        </div>                      
                    </div>

                    <div>
                        <div>
                            <label>Service :</label>
                        </div>
                        <div>
                            <select id='AjSerCL'></select>
                        </div>                      
                    </div>

                    <div class='LGaction'>
                        <input type="button" class='OK BtnAjt' value="OK"/>
                        <input type="button" class='AN' value="Cancel"/>
                    </div>

                </fieldset>
                
            </div>

<!----------------------------------------------------------------------->

            <div class="AJT-MDF" id='AjtAD'>
                
                <fieldset class="">
                    <legend class="LGBack">Ajouter Nouveau Administrateur</legend>
                    
                    <div>
                        <div>
                            <label>CNT :</label>
                        </div>
                        <div>
                            <input class="AjCntAD" id="AjCntAD" type="text">
                        </div>
                    </div>

                    <div>
                        <div>
                            <label>Nom :</label>
                        </div>
                        <div>
                            <input class="AjNomAD" id="AjNomAD" type="text">
                        </div>                      
                    </div>

                    <div>
                        <div>
                            <label>Mot De pass :</label>
                        </div>
                        <div>
                            <input class="AjPassAD" id="AjPassAD" type="text">
                        </div>                      
                    </div>
                    
                    <div>
                        <div>
                            <label>Type :</label>
                        </div>
                        <div>
                            <select id='AjTypeAD'>
                                <option value="chef">Chef</option>
                                <option value="gestionnaire">Gestionnaire</option>
                                <option value="magasinier">Magasinier</option>
                                <option value="admin">Super Admin</option>
                            </select>
                        </div>                      
                    </div>

                    <div>
                        <div>
                            <label>Magasin :</label>
                        </div>
                        <div>
                            <select id='AjMagAD'></select>
                        </div>                      
                    </div>

                    <div>
                        <div>
                            <label>Service :</label>
                        </div>
                        <div>
                            <select id='AjSerAD'></select>
                        </div>                      
                    </div>

                    <div class='LGaction'>
                        <input type="button" class='OK BtnAjt' value="OK"/>
                        <input type="button" class='AN' value="Cancel"/>
                    </div>

                </fieldset>
                
            </div>

             <!----------------------------------------------------------------------->

             <div class="AJT-MDF" id='AjtSR'>
                
                <fieldset class="">
                    <legend class="LGBack">Ajouter Nouveau Service</legend>
                    
                    <div>
                        <div>
                            <label>Libellé :</label>
                        </div>
                        <div>
                            <input class="AjNomSR" id="AjNomSR" type="text">
                        </div>
                    </div>

                    <div class='LGaction'>
                        <input type="button" class='OK BtnAjt' value="OK"/>
                        <input type="button" class='AN' value="Cancel"/>
                    </div>

                </fieldset>
                
            </div>


































            <!----------------------------------------------------------------------->

            <div class="AJT-MDF" id='MdfCL'>
                    
                <fieldset class="">
                    <legend class="LGBack">Modifier Client</legend>
                    
                    <div>
                        <div>
                            <label>CNT :</label>
                        </div>
                        <div>
                            <input class="MdCntCL" id="MdCntCL" type="text">
                        </div>
                    </div>

                    <div>
                        <div>
                            <label>Nom :</label>
                        </div>
                        <div>
                            <input class="MdNomCL" id="MdNomCL" type="text">
                        </div>                      
                    </div>

                    <div>
                        <div>
                            <label>Mot De pass :</label>
                        </div>
                        <div>
                            <input class="MdPassCL" id="MdPassCL" type="text">
                        </div>                      
                    </div>

                    <div>
                        <div>
                            <label>Service :</label>
                        </div>
                        <div>
                            <select id='MdSerCL'></select>
                        </div>                      
                    </div>

                    <div class='LGaction'>
                        <input type="button" class='OK BtnMdf' value="OK"/>
                        <input type="button" class='AN' value="Cancel"/>
                    </div>

                </fieldset>
                
            </div>

<!----------------------------------------------------------------------->

            <div class="AJT-MDF" id='MdfAD'>
                
                <fieldset class="">
                    <legend class="LGBack">Modifier Administrateur</legend>
                    
                    <div>
                        <div>
                            <label>CNT :</label>
                        </div>
                        <div>
                            <input class="MdCntAD" id="MdCntAD" type="text">
                        </div>
                    </div>

                    <div>
                        <div>
                            <label>Nom :</label>
                        </div>
                        <div>
                            <input class="MdNomAD" id="MdNomAD" type="text">
                        </div>                      
                    </div>

                    <div>
                        <div>
                            <label>Mot De pass :</label>
                        </div>
                        <div>
                            <input class="MdPassAD" id="MdPassAD" type="text">
                        </div>                      
                    </div>
                    
                    <div>
                        <div>
                            <label>Type :</label>
                        </div>
                        <div>
                            <select id='MdTypeAD'>
                                <option value="chef">Chef</option>
                                <option value="gestionnaire">Gestionnaire</option>
                                <option value="magasinier">Magasinier</option>
                                <option value="admin">Super Admin</option>
                            </select>
                        </div>                      
                    </div>

                    <div>
                        <div>
                            <label>Magasin :</label>
                        </div>
                        <div>
                            <select id='MdMagAD'></select>
                        </div>                      
                    </div>

                    <div>
                        <div>
                            <label>Service :</label>
                        </div>
                        <div>
                            <select id='MdSerAD'></select>
                        </div>                      
                    </div>

                    <div class='LGaction'>
                        <input type="button" class='OK BtnMdf' value="OK"/>
                        <input type="button" class='AN' value="Cancel"/>
                    </div>

                </fieldset>
                
            </div>

             <!----------------------------------------------------------------------->

             <div class="AJT-MDF" id='MdfSR'>
                
                <fieldset class="">
                    <legend class="LGBack">Ajouter Nouveau Service</legend>
                    
                    <div>
                        <div>
                            <label>Libellé :</label>
                        </div>
                        <div>
                            <input class="MdNomSR" id="MdNomSR" type="text">
                        </div>
                    </div>

                    <div class='LGaction'>
                        <input type="button" class='OK BtnMdf' value="OK"/>
                        <input type="button" class='AN' value="Cancel"/>
                    </div>

                </fieldset>
                
            </div>

           
    </body>
    
    <script src="JQUERY_FOLDER/jQuery-3.3.1.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/scriptAdmin.js"></script>
    
</html>
