<?php
    require "db.php";
    require "Classes.php";
    session_start();
    if($_SESSION["IDclient"]==null)header("location: index.php");

    $_SESSION["ligneCommande"]=array();
    
    //----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    function CommandeClient ($Statut){

      $encours ="en cours";$Plivre="pret a livrer";$livre="not"; if($Statut){$livre="livre";$encours ="";$Plivre="";}
      
      $sql = "select cm.idCM,cm.dateCom,cm.delaiO,cm.validate,cm.confirmation,cm.numCom from commandes cm
      inner join client cl on cl.idCL=cm.idCL
      inner join services s on s.idSR=cl.idSR  where cl.idCL =".$_SESSION["IDclient"]." and (cm.validate = '$livre' or (cm.validate = '$encours' or cm.validate = '$Plivre')) order by cm.dateCom desc ";



      $run = mysqli_query($GLOBALS["cn"],$sql);
      while($raw=mysqli_fetch_array($run)){

        $natureCom="";
        if($raw[2] == "> 4mois" || $raw[2] == "< 4mois"){
          $natureCom="Demande d'achat";
        }else{
          $natureCom="Demmande d'approvisionnement";
        }
    

          $NameMG = "";
          $InfoCommande = "";
          
          $sqlLigneCm = "select m.MGname,f.FMname,c.CTname,d.DSname,lnc.qtD,lnc.qtA,lnc.accorder from ligneCommande lnc
          inner join Designation d on lnc.idDS=d.idDS 
          inner join categorie c on d.idC=c.idC
          inner join famille f on c.idF=f.idF 
          inner join magasin m on f.idM=m.idM
          inner join commandes cm on lnc.idCM = cm.idCM
          where cm.idCM = ".$raw[0]."";

        
          $runLigne = mysqli_query($GLOBALS["cn"],$sqlLigneCm);
          while($rawLigneCm=mysqli_fetch_array($runLigne)){
          $qtAp = "";
          if($raw[4] && $rawLigneCm[6]){$qtAp = $rawLigneCm[5]; }else if($raw[4] && !$rawLigneCm[6]){$qtAp = "Non approuvé";}else{$qtAp = "En cours" ;}
          $InfoCommande .= "<tr><td>".$rawLigneCm[1]."</td><td>".$rawLigneCm[2]."</td><td>".$rawLigneCm[3]."</td><td>".$rawLigneCm[4]."</td><td>".$qtAp."</td></tr>";
          $NameMG = $rawLigneCm[0];
          }

          echo "<tr><td><label>".$NameMG." ....</label><table><caption>".$NameMG." :</caption><tr><th>Famille</th><th>Catégorie</th><th>Désignation</th><th>Quantité Demmandé</th><th>Quantité Approuvé</th></tr>".$InfoCommande."</table></td><td>".$raw[1]."</td><td>".$natureCom."</td><td>".$raw[3]."</td></tr>";
      } 
    }
    //----------------------------------------------------------------------------------------------------------------------------------------------------------------------

     

?>


<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="welcom to my web site">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="images/icons/icone-onssa.png"/>
		    <title>Gestion Commande</title>

        
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" href="css/jquery-ui.min.css">

        <style>

        </style>
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

                  <!-------------->
                    <div class="tab">
                        <button class="tablinks active" onclick="openTab(event, 'COM')">Etablir Une Demande</button>
                        <button class="tablinks" onclick="openTab(event, 'voirB')">Suivi Des Demande</button>
                        <button class="tablinks" onclick="openTab(event, 'history')">Historique</button>
                    </div>

                    <div id="COM" class="tabcontent">

                        <div class="Panier">

                          <div class="iconPanier"><span id="countPanier"></span></div>

                          
                          <div class="infoPanier">
                              <div class="listPruduit">
                                <span></span>

                                <h4>Les Produits Sélectionnés :</h4>
                                <h5 id="videP">Vide !</h5>
                                <ul id = "listPanier"></ul>
    
                              </div>
                        
                          </div>
                          
                        </div>

                      <!-------------------------------------------------------------------->
                      <div class="divSelect">
                        <div>
                          <label>Magasin</label>
                          <div class="box">
                              <select id="magasin" class="selectCommande" name="magasin" id='magasin'></select>
                          </div>
                        </div>
                        <!--------------------------------------------------------------------> 
                        <div>
                          <label>Famille</label>
                          <div class="box"> 
                              <select id="famille" class="selectCommande" name ="famille"id='famille'></select>
                          </div>
                        </div>
                        <!-------------------------------------------------------------------->  
                        <div>
                          <label>Catégorie</label>
                          <div class="box">
                            <select id="categorie" class="selectCommande" name="categorie" id='categorie'></select>
                          </div>
                        </div>
                        <!-------------------------------------------------------------------->
                        <div>
                          <label>Désignation</label>
                          <div class="box">
                              <select id="Designation" class="selectCommande" name="Designation" id='Designation'></select>
                          </div>
                        </div>
                      </div>

                        <!-------------------------------------------------------------------->
 
                        <!-------------------------------------------------------------------->
                        <div class="InfoProduit">

                          <div class='contentInfo'>
                            <div>
                              <div>
                                <div class="info">
                                    <div>
                                        <label>Unité :</label>
                                        <label id="unite" class="display"></label>
                                    </div>
                                  <!---------------------->
                                    <div>
                                        <label>Quantité :</label>
                                        <input id="qt" class="qt" type="number" name="qt" />
                                    </div>
                                    <div class="center">
                                        <input type="button" id="ajouterLine" name="ajouterLine" value="Ajouter a la commande"/>
                                    </div>
                                </div>
                              </div>
                            </div>

                            <div class='imageDesignation' id='imageDesignation'>
                              
                            </div>
                          </div>

                        </div>

                        <div  class="Validation">
                            <!---------------------->
                            <div class="delai">
                              
                              <label>Nature de la commande :</label>
                              
                              <div>

                                  <!--
                                  <div>
                                    <input id="r1" value="< 4mois" name="delai" type="radio" checked required/><label for="r1">&lt; 4 mois</label>
                                  </div>
                                  -->
                                  <div>
                                    <input id="r3" value="immediat" name="delai" type="radio" checked required/><label for="r3">Demande d'approvisionnement (Besoin immédiat)</label>
                                  </div>

                                  <div>
                                    <input id="r2" value="> 4mois" name="delai" type="radio" required/><label for="r2">Demande d'achat (Besoin prévisionnel)</label>
                                  </div>

                              </div>
                              <div class='both'></div>
                            </div>
                            <!---------------------->
                            
                            <div class="Validation-submit"> 
                                <input type="button" id="annulerCom" name="annulerCom" value="Annuler Commande" disabled/>       
                                <input type="button" id="ajouterCom" name="ajouterCom" value="Envoyer Commande" disabled/>
                            </div>
                        </div>

                      </div>            
                      <!-------------------------------------------------------->
                      <div id="voirB" class="tabcontent">
                          
                        <div class="searsh">
                            <input class="txtsearsh" id="txtsearsh" type="text" placeholder="Search...">
                        </div>
                          
                        <table Class="tableCommande">

                            <thead>
                              <tr>
                                <th>Magasin<button class="detai" id="detai">&#8623;</button></th><th>Date Commande</th><th>Nature de la commande</th><th>Confirmation</th>
                              </tr>
                            </thead>


                            <?php echo CommandeClient (false);?>

                        </table>
            
                      </div>
                       <!-------------------------------------------------------->
                      <div id="history" class="tabcontent">
                          
                        <div class="searsh">
                            <input class="txtsearsh" id="txtsearsh" type="text" placeholder="Search...">
                        </div>
                          
                        <table Class="tableCommande">

                            <thead>
                              <tr>
                               <th>Magasin<button class="detai" id="detai">&#8623;</button></th><th>Date Commande</th><th>Délai d'acquisition Optimal</th><th>Confirmation</th>
                              </tr>
                            </thead>

                            <?php echo CommandeClient (true);?>

                        </table>
                          
                      </div>
                      <!-------------------------------------------------------->
                </div>              


            </div>          
                
        </div>  
        
        <div class="dialog-confirm">
            <div class="dialog-container">
                <div class="hideConfirm"><button id="hideConfirm">&#215;</button></div>
                
                <h4>Voulez-vous vraiment Confirmer ?</h4>
                <div class="btnConfirm">
                    <button id="confirmTrue">Oui</button>
                    <button id="confirmFalse">Non</button>
                </div>
            </div>
        </div>
        <div class="dialog-Annuler">
            <div class="dialog-container">
                <div class="hideAnnuler"><button id="hideAnnuler">&#215;</button></div>
                
                <h4>Voulez-vous vraiment annuler ?</h4>
                <div class="btnAnnuler">
                    <button id="AnnulerTrue">Oui</button>
                    <button id="AnnulerFalse">Non</button>
                </div>
            </div>
        </div>


        
        <!---Star footer--->
        <?php include('Footer.php'); ?>
        <!---end footer--->

	</body>
    


    <!----------------------------------------------------->
    <script src="JQUERY_FOLDER/jQuery-3.3.1.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/scriptCom.js"></script>

</html>

   