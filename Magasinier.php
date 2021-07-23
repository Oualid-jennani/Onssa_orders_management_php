<?php
session_start();
require "db.php";
require "Classes.php";

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


if($_SESSION['IDadminMG'] == null || $_SESSION['adminMG'] == null || $_SESSION["idM"] == null || $_SESSION["MGName"]==null ){
    header("location: administrateur.php");
}

function CheckPost($var){
    $var=trim($var);
    $var=strip_tags($var);
    $var=stripslashes($var);
    if(empty($var)) return false;
    else return $var;
}

/*
if($_SERVER["REQUEST_METHOD"]=="POST"){


    $myfile = $_FILES['image-0'];
    $url = "";

    if($myfile['tmp_name'] == ""){
        $url = "images/Images_Designation/dist_test.png";
    }
    else{
        $url = "images/Images_Designation/".date('d-M-Y-h-i-s')."-".$myfile['name'];
    }
    

    $sql = 'insert into Designation(DSname,image,price,idC,idUM,idST,DScode,DSquantite,Val) values
    ("'.CheckPost($_POST['AjNomDS']).'","'.$url.'",'.CheckPost($_POST['AjPrixDS']).','.CheckPost($_POST['AjCategorieDS']).','.CheckPost($_POST['AjUniteDS']).','.CheckPost($_POST['AjStockDS']).',"'.CheckPost($_POST['AjCodeDS']).'",'.CheckPost($_POST['AjQtDs']).','.CheckPost($_POST['AjValDs']).')';
    
    if($myfile['tmp_name'] != ""){
        copy($myfile['tmp_name'], $url);
    }
    $run = mysqli_query($cn,$sql);

}*/


?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Magasinier</title>
        <link rel="icon" type="image/png" href="images/icons/icone-onssa.png"/>
        <link rel="stylesheet" type="text/css" href="css/MagasinierStyle.css">
        <link rel="stylesheet" href="css/jquery-ui.min.css">
    </head>
    <body>
        <div class="header">
            
            <div class="infoHeader">
                <div class="infoApp">
                    <ul>
                        <li><span class="iconApp"></span></li>
                        <li><span class="nameApp">Magasinier Admin</span></li>
                    </ul>
                </div>

                <div class="infoProfil">

                    <ul>
                        <li>
                        <form method="post">
                            <input type="submit" class="dec" name="dec" value="">
                        </form>
                        </li>
                        <li><span class="name">Magasinier : <?php echo $_SESSION['adminMG'] ; ?></span></li>
                        <li><span class="photo"></span></li>
                    </ul>

                </div>
            </div>
            
            
            <div class="infoMagasin"><h4>Magasin : </h4><h5><?php echo $_SESSION["MGName"]; ?></h5></div>
        </div>

        <div class="main">
          <div class="menu">
            <ul>
              <li class="tablinks active" onclick="openTab(event, 'stock')">Entrees &#38; Sorties</li>
              <li class="tablinks" onclick="openTab(event, 'famille')"> Contrôle Des Familles</li>
              <li class="tablinks" onclick="openTab(event, 'categorie')"> Contrôle Des Categories</li>
              <li class="tablinks" onclick="openTab(event, 'Designation')"> Contrôle Des Designations</li>
            </ul>
          </div>

          <div class="contain">
            <!--------------------------------------------------------------------------------------------->
            <div id="stock" class="tabcontent">

                <div class="Intab">
                    <button class="Intablinks active" onclick="InopenTab(event, 'Entree')">Entree</button>
                    <button class="Intablinks" onclick="InopenTab(event, 'Sortie')">Sortie</button>
                </div>

                <div id="Entree" class="Intabcontent">
                    <div class="Action">
                        <input class="txtsearsh" id="EntreSearsh" type="text" placeholder="Search..."><input type='button' id='EntreQT' value='Nouvelle Entrée' />
                    </div>

                    <div class='divTable'>
                        <table id='tabStockDsEntree' class='tableMagasinier'>
                            <thead>
                            <tr>
                                <th>N° du B.C</th><th>Date D'entree</th><th>Quantitees Reçue</th>
                            </tr>
                                
                            </thead>
                            <tbody id='rawStockEntree'></tbody>
                        </table>
                    </div>
                </div>
                
                
                <div id="Sortie" class="Intabcontent">
                    <div class="Action">
                        <input class="txtsearsh" id="SorteSearsh" type="text" placeholder="Search..."><input type='button' id='SorteQT' value='Change Date Sortie' />
                    </div>

                    <div class='divTable'>
                        <table id='tabStockDsSortie' class='tableMagasinier'>
                        <thead>
                            <tr>
                                <th>N° du B.A</th><th>Date Sorties</th><th>Quantitees Livrée</th>
                            </tr>
                                
                            </thead>
                            <tbody id='rawStockSortie'></tbody>
                        </table>
                    </div>
                </div>




                <div class="QTE-QTS" id='QTE'>
                    <div class='BonCom'>
                        <div class='infoBC'>
                            <div>
                                <div>
                                    <label>Entrée N° du B.C</label>
                                </div>
                                <div>
                                    <input class="Ebc" id="Ebc" type="text">
                                </div>
                            </div>

                            <div>
                                <div>
                                    <label>Date Entrée</label>
                                </div>
                                <div>
                                    <input class="Edate" id="Edate" type="date">
                                </div>
                            </div>
                        </div>
                    </div>

                     <fieldset class="divQT">

                        <legend class="LGBack">Quantité Reçue</legend>
                        <div class='divsel'>
                            <div>
                                <div>
                                    <label>Famile</label>
                                </div>
                                <div>
                                    <select id='Efamille'></select>
                                </div>                      
                            </div>

                            <div>
                                <div>
                                    <label>Categorie</label>
                                </div>
                                <div>
                                    <select id='Ecategorie'></select>
                                </div>                      
                            </div>

                            <div>
                                <div>
                                    <label>Designation</label>
                                </div>
                                <div>
                                    <select id='Edesignation'></select>
                                </div>                      
                            </div>
                        </div>
                        
                        
                        <div class='divqt'>
                            <div>
                                <label>Quantité Restante : <span id = "QtRestanteE"></span></label>
                            </div>
                            <div>
                                <input class="EQtDs" id="EQtDs" type="number" placeholder='Entrée Quantité Reçue'>
                            </div>
                            <div>
                                <input class="EnprixDs" id="EnprixDs" type="number" placeholder='Entrée Neveau Prix'>
                            </div>
                        </div>


                         <div class='LGPlus'>
                            <input type="button" class='plus EbtnQT' id='EbtnQT' value="Ajouter a la bon"/>
                         </div>

                     </fieldset>

                     <div class='ConfermBC'>
                        <input type='button' class='inpAnnulerBC' value='Annuler B.C'/>
                        <input type='button' class='inpConfermBC' id='inpConfermBC' value='Conferm B.C'/>
                     </div>

                 </div>





                 <div class="QTE-QTS" id='QTS'>
                    <div class='BonA'>
                        <div class='infoBA'>
                            <div>
                                <div>
                                    <label>N° du B.A Nouveaux</label>
                                </div>
                                <div>
                                    <select id='idBA'></select>
                                </div>
                            </div>

                            <div>
                                <div>
                                    <label>Date Sortie</label>
                                </div>
                                <div>
                                    <input class="Sdate" id="Sdate" type="date">
                                </div>
                            </div>
                        </div>
                    </div>

                     

                     <div class='ConfermBA'>
                        <input type='button' class='inpAnnulerBA' value='Annuler'/>
                        <input type='button' class='inpConfermBA' id='ChangeBADate' value='Change'/>
                     </div>

                 </div>


            </div>




















            <!--------------------------------------------------------------------------------------------->
            <div id="famille" class="tabcontent">

                <div class="Action"><input class="txtsearsh" id="FMsearsh" type="text" placeholder="Search..."><input type='button' id='AjouterFamille' value='Ajouter Famille' /></div>

                <div class='divTable'>
                    <table id='tabFamille' class='tableMagasinier'>
                        <thead>
                            <tr>
                                <th>Nom Famille</th><th>Action</th>
                            </tr>
                        </thead>
                        <tbody id='rawFamille'></tbody>
                     </table>
                </div>
             
            </div>
            <!--------------------------------------------------------------------------------------------->
            <div id="categorie" class="tabcontent"> 
                <div class='divSel'>
                    <select id='selFamille' class='selMagasinier'></select>
                </div>
              
                <div class="Action"><input class="txtsearsh" id="CTsearsh" type="text" placeholder="Search..."><input type='button' id='AjouterCategorie' value='Ajouter Categorie' /></div>
                
                <div class='divTable'>
                    <table id='tabCategorie' class='tableMagasinier'>
                        <thead>
                            <tr>
                                <th>Nom Categorie</th><th>Action</th>
                            </tr>
                        </thead>
                        <tbody id='rawCategorie'></tbody>
                    
                    </table>
                </div>
                

            </div>
            <!--------------------------------------------------------------------------------------------->
            <div id="Designation" class="tabcontent">
              
              <div class='divSel'>
                    <select id='selFamilleCT' class='selMagasinier'></select>
                    <select id='selCategorie' class='selMagasinier'></select>
              </div>
              
              <div class="Action"><input class="txtsearsh" id="DSsearsh" type="text" placeholder="Search..."><input type='button' id='AjouterDesignation' value='Ajouter Designation' /></div>

              <div class='divTable'>
                <table id='tabDesignation' class='tableMagasinier'>
                    <thead>
                    <tr>
                        <th>Nom Designation</th><th>Unité</th><th>Code</th><th>Price</th><th>Qontité restante</th><th>Montant</th><th>Valeur</th><th>Action</th>
                    </tr>
                    </thead>
                    <tbody id='rawDesignation'></tbody>
                </table>
              </div>

            </div>
              <!--------------------------------------------------------------------------------------------->
          </div>

          <div class="info">
            <div class="aside">
              <h2>Nombre de Famille : 3</h2>
              <p>Dernière famille ajoutée : fmtest</p>
              <h2>Nombre de Categorie : 11</h2>
              <p>Dernière Categorie ajoutée : ctest</p>
              <h2>Nombre de Designation : 120</h2>
              <p>Dernière Designation ajoutée : dstest</p>
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








        <div class="AJT-MDF" id='AjtF'>
             
             <fieldset class="">
                 <legend class="LGBack">Ajouter Nouveau Famille</legend>
                 
                 <div>
                     <div>
                         <label>Nom Famille :</label>
                     </div>
                     <div>
                         <input class="AjNomF" id="AjNomF" type="text">
                     </div>
                 </div>
 
                 <div class='LGaction'>
                     <input type="button" class='OK BtnAjt' value="OK"/>
                     <input type="button" class='AN' value="Cancel"/>
                 </div>
 
             </fieldset>
             
         </div>
        <!-------------------------------------------------------------------->
        <div class="AJT-MDF" id='AjtC'>
            
            <fieldset class="">
                <legend class="LGBack">Ajouter Nouveau Categorie</legend>
                
                <div>
                  <div>
                    <label>Famile :</label>
                  </div>
                  <div>
                      <select id='AjFamilleCT'></select>
                  </div>                      
                </div>
                

                <div>
                    <div>
                        <label>Nom Categorie :</label>
                    </div>
                    <div>
                        <input class="AjNomC" id="AjNomC" type="text">
                    </div>
                </div>

                <div class='LGaction'>
                    <input type="button" class='OK BtnAjt' value="OK"/>
                    <input type="button" class='AN' value="Cancel"/>
                </div>

            </fieldset>
            
        </div>
        <!-------------------------------------------------------------------->
        <div class="AJT-MDF" id='AjtDS'>
            
            <form id="formAjtDs" method="post" action='' autocomplete="off" enctype="multipart/form-data">
            <fieldset class="">
                <legend class="LGBack">Ajouter Nouveau Designation</legend>

                <div>
                    <div>
                        <label>Image</label>
                    </div>
                    <div style='text-align: center'>
                        <div>
                            <input type="file" id="myFileInput" accept="image/*" onchange="loadFile(event)" hidden/>
                            
                            <div id="div-photo" class='div-photo'>
                                <img id="FT" width="100px" height="100px"onclick="document.getElementById('myFileInput').click()">
                            </div>
                        </div>
                    </div>
                </div>

                
                <div>
                  <div>
                    <label>Famile</label>
                  </div>
                  <div>
                      <select id='AjFamilleDS'></select>
                  </div>                      
                </div>

                <div>
                  <div>
                    <label>Categorie</label>
                  </div>
                  <div>
                      <select id='AjCategorieDS'></select>
                  </div>                      
                </div>
                

                <div>
                  <div>
                    <label>Stock</label>
                  </div>
                  <div>
                      <select id='AjStockDS' ></select>
                  </div>                      
                </div>


                <div>
                    <div>
                        <label>Nom Designation</label>
                    </div>
                    <div>
                        <input class="AjNomDS" id="AjNomDS" type="text">
                    </div>
                </div>

                <div>
                  <div>
                    <label>Unité</label>
                  </div>
                  <div>
                      <select id='AjUniteDS' ></select>
                  </div>                      
                </div>

                <div>
                    <div>
                        <label>Code</label>
                    </div>
                    <div>
                        <input class="AjCodeDS" id="AjCodeDS" type="text">
                    </div>
                </div>


                <div>
                    <div>
                        <label>Prix</label>
                    </div>
                    <div>
                        <input class="AjPrixDS" id="AjPrixDS" type="number">
                    </div>
                </div>

                <div>
                    <div>
                        <label>Quantité</label>
                    </div>
                    <div>
                        <input class="AjQtDs" id="AjQtDs" type="number">
                    </div>
                </div>
                <div>
                    <div>
                        <label>Valeur</label>
                    </div>
                    <div>
                        <input class="AjValDs" id="AjValDs" type="number">
                    </div>
                </div>

                <div class='LGaction'>
                    <input type="button" name='ajtDS' class='OK BtnAjt' value="OK"/>
                    <input type="button" class='AN' value="Cancel"/>
                </div>

            </fieldset>
            </form>
            
        </div>
































        <div class="AJT-MDF" id='MdfF'>
             
             <fieldset class="">
                 <legend class="LGBack">Modifier Famille</legend>
                 
                 <div>
                     <div>
                         <label>Nom Famille</label>
                     </div>
                     <div>
                         <input class="MdNomF" id="MdNomF" type="text">
                     </div>
                 </div>
 
                 <div class='LGaction'>
                     <input type="button" class='OK BtnMd' value="OK"/>
                     <input type="button" class='AN' value="Cancel"/>
                 </div>
 
             </fieldset>
             
         </div>
        <!-------------------------------------------------------------------->
        <div class="AJT-MDF" id='MdfC'>
            
        <fieldset class="">
                <legend class="LGBack">Modifier Categorie</legend>
                <div>
                  <div>
                    <label>Famile</label>
                  </div>
                  <div>
                      <select id='MdFamilleCT'></select>
                  </div>                      
                </div>

                <div>
                    <div>
                        <label>Nom Categorie</label>
                    </div>
                    <div>
                        <input class="MdNomC" id="MdNomC" type="text">
                    </div>
                </div>

                <div class='LGaction'>
                    <input type="button" class='OK BtnMd' value="OK"/>
                    <input type="button" class='AN' value="Cancel"/>
                </div>

            </fieldset>
        </div>
        <!-------------------------------------------------------------------->
        
        <div class="AJT-MDF" id='MdfDS'>
            
        <fieldset class="">
                <legend class="LGBack">Modifier Designation</legend>
                
                <div>
                  <div>
                    <label>Famile</label>
                  </div>
                  <div>
                      <select id='MdFamilleDS'></select>
                  </div>                      
                </div>

                <div>
                  <div>
                    <label>Categorie</label>
                  </div>
                  <div>
                      <select id='MdCategorieDS'></select>
                  </div>                      
                </div>


                <div>
                  <div>
                    <label>Stock</label>
                  </div>
                  <div>
                      <select id='MdStockDS'></select>
                  </div>                      
                </div>


                <div>
                    <div>
                        <label>Nom Designation</label>
                    </div>
                    <div>
                        <input class="MdNomDS" id="MdNomDS" type="text">
                    </div>
                </div>

                <div>
                  <div>
                    <label>Unité</label>
                  </div>
                  <div>
                      <select id='MdUniteDS'></select>
                  </div>                      
                </div>

                <div>
                    <div>
                        <label>Code</label>
                    </div>
                    <div>
                        <input class="MdCodeDS" id="MdCodeDS" type="text">
                    </div>
                </div>

                <div>
                    <div>
                        <label>Prix</label>
                    </div>
                    <div>
                        <input class="MdPrixDS" id="MdPrixDS" type="number">
                    </div>
                </div>

                <div>
                    <div>
                        <label>Quantité</label>
                    </div>
                    <div>
                        <input class="MdQtDs" id="MdQtDs" type="number">
                    </div>
                </div>

                <div>
                    <div>
                        <label>Valeur</label>
                    </div>
                    <div>
                        <input class="MdValDs" id="MdValDs" type="number">
                    </div>
                </div>

                <div class='LGaction'>
                    <input type="button" class='OK BtnMd' value="OK"/>
                    <input type="button" class='AN' value="Cancel"/>
                </div>

            </fieldset>
            
        </div>















    </body>
    
    <script src="JQUERY_FOLDER/jQuery-3.3.1.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/scriptMagasinier.js"></script>
    
    
</html>
