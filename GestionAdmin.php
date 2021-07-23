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


if($_SESSION['IDadminGesT'] == null || $_SESSION['adminGesT'] == null || $_SESSION["idM"] == null || $_SESSION["MGName"]==null ){
  header("location: administrateur.php");
}



function CommandeClient($Statut){ 
  $NameMG = "";
  global $cn;
  $encours ="en cours";$Plivre="pret a livrer";$livre="not"; if($Statut){$livre="livre";$encours ="";$Plivre="";}

  $data_id="";
  $sql = "select distinct cm.idCM,cl.CLname,s.SRname,cm.dateCom,cm.delaiO,cm.validate,cm.confirmation,cm.numCom,cm.validateGest,cm.dateValidate from commandes cm
  inner join ligneCommande lnc on lnc.idCM = cm.idCM
  inner join Designation d on lnc.idDS=d.idDS 
  inner join categorie c on d.idC=c.idC
  inner join famille f on c.idF=f.idF 
  inner join magasin m on f.idM=m.idM
  inner join client cl on cl.idCL=cm.idCL
  inner join services s on s.idSR=cl.idSR where m.idM=".$_SESSION["idM"]." and (cm.validate = '$livre' or (cm.validate = '$encours' or cm.validate = '$Plivre'))  order by cm.idCM desc";

  $run = mysqli_query($cn,$sql);
  while($raw=mysqli_fetch_array($run)){

    $natureCom="";
    if($raw[4] == "> 4mois" || $raw[4] == "< 4mois"){
      $natureCom="Demande d'achat";
    }else{
      $natureCom="Demmande d'approvisionnement";
    }

    $ButtonPassChef = "";
    if(!$raw[8]){$ButtonPassChef = "<tr><td class='inpChefPass' colspan='7'><input type='button' value='Passer à la Confirmation ' onclick='passChef(".$raw[0].");'></td></tr>";}
    else{$ButtonPassChef = "<tr><td class='inpNewChefPass' colspan='7'><input type='button' value='Nouvelle Confirmation' onclick='NewpassChef(".$raw[0].");'></td></tr>";}
    
    $imprime = ""; $showEncours = "";$hidden = "";if(!$raw[6]){$hidden="hidden"; $imprime = "disabled";}else{$showEncours = "hidden";}

    $NameMG = "";
    $InfoCommande = "";
    $i=0;
    
    $sqlLigneCm = "select m.MGname,f.FMname,c.CTname,d.DSname,d.DSquantite,lnc.qtD,lnc.qtA,lnc.accorder,lnc.idDS from ligneCommande lnc
    inner join Designation d on lnc.idDS=d.idDS 
    inner join categorie c on d.idC=c.idC
    inner join famille f on c.idF=f.idF 
    inner join magasin m on f.idM=m.idM
    inner join commandes cm on lnc.idCM = cm.idCM
    where cm.idCM = ".$raw[0]."";
    $runLigne = mysqli_query($GLOBALS["cn"],$sqlLigneCm);
    if($raw["validate"] == "livre"){
      while($rawLigneCm=mysqli_fetch_array($runLigne)){
        $accorder = "Oui";
        if(!$rawLigneCm["accorder"]){$accorder = "Non";}
        $InfoCommande .= "<tr><td>".$rawLigneCm[1]."</td><td>".$rawLigneCm[2]."</td><td>".$rawLigneCm[3]."</td><td>".$rawLigneCm[4]."</td><td>".$rawLigneCm[5]."</td><td>".$rawLigneCm[6]."</td><td>$accorder</td></tr>";
        $NameMG = $rawLigneCm[0];
      }
    }
    else{
      while($rawLigneCm=mysqli_fetch_array($runLigne)){
        $trueAccorder = "";
        $falseAccorder = "";
  
        if($rawLigneCm[7]){$trueAccorder = "checked";} else {$falseAccorder = "checked";}
        
        $InfoCommande .= "<tr><td>".$rawLigneCm[1]."</td><td>".$rawLigneCm[2]."</td><td>".$rawLigneCm[3]."</td><td>".$rawLigneCm[4]."</td><td>".$rawLigneCm[5]."</td><td><input type='number' class='qtApprove' value='".$rawLigneCm[6]."' onchange='quantiteApprove(event,".$raw[0].",".$rawLigneCm[8].")'></td>
        <td>
        <input type='radio' id='oui".$raw[0].$rawLigneCm[8]."' name='accorder".$raw[0].$rawLigneCm[8]."' value='true' onchange='funAccorder(event,".$raw[0].",".$rawLigneCm[8].")' $trueAccorder><label for='oui".$raw[0].$rawLigneCm[8]."'>Oui</label>
        <input type='radio' id='non".$raw[0].$rawLigneCm[8]."' name='accorder".$raw[0].$rawLigneCm[8]."' value='false' onchange='funAccorder(event,".$raw[0].",".$rawLigneCm[8].")' $falseAccorder><label for='non".$raw[0].$rawLigneCm[8]."'>Non</label>
        </td>
        </tr>";
        $NameMG = $rawLigneCm[0];
      }
    }

    //--------------------------------------------------------------------------------------------------------------------
    $check1="";if($raw[5]=="en cours"){$check1="checked";}
    $check2="";if($raw[5]=="pret a livrer"){$check2="checked";}
    $check3="";if($raw[5]=="livre"){$check3="checked";}

    if($raw["validate"] == "livre"){
      echo "<tr><td>".$raw[7]."</td><td>".$raw[1]."</td><td>".$raw[2]."</td>
      <td>
        <label>Voir Detai ....</label>
        <table>
          <caption>".$NameMG." :</caption>
          <tr><th>Famille</th><th>Catégorie</th><th>Désignation</th><th>Qontité restante</th><th>Quantité Demandé</th><th>Quantité approuvé</th><th>accorder</th></tr>
          ".$InfoCommande."
        </table>
      </td>
      <td>".$raw[3]."</td><td>".$natureCom."</td><td>".$raw[9]."</td>
      <td class='imprime'><input type='button' value='Imprimé' onclick='imprimer(".$raw[0].")' $imprime></td>
      </tr>";
    }else{
      echo "<tr><td>".$raw[7]."</td><td>".$raw[1]."</td><td>".$raw[2]."</td>
      <td>
        <label>Voir Detai ....</label>
        <table>
          <tr><th>Famille</th><th>Catégorie</th><th>Désignation</th><th>Quantité en stock</th><th>Quantité Demandé</th><th>Quantité approuvé</th><th>accorder</th></tr>
          ".$InfoCommande."
          ".$ButtonPassChef."
        </table>
      </td>
      <td>".$raw[3]."</td><td>".$natureCom."</td>
      <td class='validateColumn'>
      <label class='$check1' for='r1".$raw[0]."' $showEncours>En cours</label>
      <input type='radio' id='r2".$raw[0]."' name='commande".$raw[0]."' value='pret a livrer' onchange='funValidation(event,".$raw[0].");' $check2 $hidden><label class='$check2' for='r2".$raw[0]."' $hidden>Prêt à livrer</label>
      <input type='radio' id='r3".$raw[0]."' name='commande".$raw[0]."' value='livre' onchange='funValidation(event,".$raw[0].");' $check3  $hidden><label class='$check3' for='r3".$raw[0]."' $hidden>livré</label>
      </td><td class='imprime'><input type='button' value='Imprimé' onclick='imprimer(".$raw[0].")' $imprime></td>
      </tr>";
    }


    
  } 
}


?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="images/icons/icone-onssa.png"/>
        <title>Gestionnaire</title>
        <style></style>
        <link rel="stylesheet" type="text/css" href="css/GestionStyle.css">
        <link rel="stylesheet" href="css/jquery-ui.min.css">
    </head>
    <body>

        <div class="header">
            
            <div class="infoHeader">
                <div class="infoApp">
                    <ul>
                        <li><span class="iconApp"></span></li>
                        <li><span class="nameApp">Gestionnaire Admin</span></li>
                    </ul>
                </div>

                <div class="infoProfil">
                    <ul>
                        <li>
                          <form method="post">
                              <input type="submit" class="dec" name="dec" value="">
                          </form>      
                        </li>
                        <li><span class="name">Gestionnaire : <?php echo $_SESSION['adminGesT'] ; ?></span></li>
                        <li><span class="photo"></span></li>
                    </ul>

                </div>
            </div>

            <div class="infoMagasin"><h4>Magasin : </h4><h5><?php echo $_SESSION["MGName"]; ?></h5></div>
        </div>


        <form class='formGestion' action="" method='post'>

          <div class="NewVersion" >
            <div><input type='button' class='BtnVersion' id='BtnVersion' value='Ajouter Version Impresion'/></div>
            <div><input type='text' class='inpVersion' id='inpVersion' placholder='Type Version' /></div>
          </div>


        </form>


        <div class="tab">
          <button class="tablinks active" onclick="openTab(event, 'voirCom')">Voir Toutes les Commande</button>
          <button class="tablinks" onclick="openTab(event, 'voirhistory')">Historique</button>
        </div>

        <div>
            <input class="txtsearsh" id="txtsearsh" type="text" placeholder="Search...">
        </div>
        <!------------------------------------------------------------------------------------------------------------------------------------------------------------------>
        <div id="voirCom" class="tabcontent">
          <table Class="tableCommande">
              
            <thead>
                <tr>
                <th>Numéro commande</th><th>Client</th><th>Services</th><th>Magasin : <?php echo $_SESSION["MGName"]; ?><button class="detai" id="detai">&#8623;</button></th><th>Date Commande</th><th>Nature de la commande</th><th>Validation</th><th>Impression</th>
                </tr>
            </thead>
              
              <?php CommandeClient(false);?>
              
          </table>
        </div>
        <div id="voirhistory" class="tabcontent">
          <table Class="tableCommande">
              
            <thead>
                <tr>
                <th>Numéro commande</th><th>Client</th><th>Services</th><th>Magasin : <?php echo $_SESSION["MGName"]; ?><button class="detai" id="detai">&#8623;</button></th><th>Date Commande</th><th>Nature de la commande</th><th>Date Livraison</th><th>Impression</th>
                </tr>
            </thead>
              
              <?php CommandeClient(true);?>
              
          </table>
        </div>





        <div class="dialog-PassChef">
            <div class="dialog-container">
                <div class="hidePassChef"><button id="hidePassChef">&#215;</button></div>
                
                <h4>Voulez-vous vraiment Passer à la Confirmation ?</h4>
                <div class="btnPassChef">
                    <button id="PassChefTrue">Oui</button>
                    <button id="PassChefFalse">Non</button>
                </div>
            </div>
        </div>
        
        <div class="dialog-New-PassChef">
            <div class="dialog-container">
                <div class="hideNewPassChef"><button id="hideNewPassChef">&#215;</button></div>
                
                <h4>Voulez-vous vraiment Passer Une Nouvelle Confirmation ?</h4>
                <div class="btnNewPassChef">
                    <button id="PassNewChefTrue">Oui</button>
                    <button id="PassNewChefFalse">Non</button>
                </div>
            </div>
        </div>

    </body>

    <!----------------------------------------------------->
    <script src="JQUERY_FOLDER/jQuery-3.3.1.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/scriptGestion.js"></script>

</html> 
