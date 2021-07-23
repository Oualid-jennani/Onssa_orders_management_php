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


if($_SESSION['IDadminChef'] == null || $_SESSION['adminChef'] == null || $_SESSION["idM"] == null || $_SESSION["MGName"]==null ){
  header("location: administrateur.php");
}

/*
function NameMagasine(){
  $NameMG = "";
  global $cn;

  $sql = "select distinct m.MGname from commandes cm inner join magasin m on cm.idM=m.idM inner join admin ad on ad.idM=m.idM where ad.idAD=".$_SESSION['IDadminChef']."";

  $run = mysqli_query($cn,$sql);
  if($raw=mysqli_fetch_array($run)){$NameMG = $raw[0];}
  return $NameMG;
}
*/
$NameMG = "";
function CommandeClient($Statut,$delai,$validate){

  if($delai == "tout"){$delai = "";}
  else if($delai == "4mois"){$delai = " and (cm.delaiO = '< 4mois' or cm.delaiO = '> 4mois') ";}
  else if($delai == "immediat"){$delai = " and cm.delaiO = 'immediat' ";}

  global $cn;
  $NameMG = "";
  $sql = "select distinct cm.idCM,cl.CLname,s.SRname,cm.dateCom,cm.delaiO,cm.validate,cm.confirmation,cm.numCom,cm.validateGest,cm.NewvalidateGest,cm.dateValidate from commandes cm
    inner join ligneCommande lnc on lnc.idCM = cm.idCM
    inner join Designation d on lnc.idDS=d.idDS 
    inner join categorie c on d.idC=c.idC
    inner join famille f on c.idF=f.idF 
    inner join magasin m on f.idM=m.idM
    inner join client cl on cl.idCL=cm.idCL
    inner join services s on s.idSR=cl.idSR where m.idM=".$_SESSION["idM"]." and cm.validate $validate $delai  order by cm.idCM desc";

  $run = mysqli_query($cn,$sql);
  while($raw=mysqli_fetch_array($run)){

    $natureCom="";
    if($raw[4] == "> 4mois" || $raw[4] == "< 4mois"){
      $natureCom="Demande d'achat";
    }else{
      $natureCom="Demmande d'approvisionnement";
    }

    $disabledConfermation = "";$hidden = "";
    $btnConfermation = "class='firstConfermation'";
    if(!$raw[8]){$disabledConfermation = "disabled";}
    else if($raw[9]){$btnConfermation = "class='newConfermation'";}


    $NameMG = "";
    $InfoCommande = "";
    
    $sqlLigneCm = "select m.MGname,f.FMname,c.CTname,d.DSname,lnc.qtD,lnc.qtA,lnc.accorder,lnc.idDS from ligneCommande lnc
    inner join Designation d on lnc.idDS=d.idDS 
    inner join categorie c on d.idC=c.idC
    inner join famille f on c.idF=f.idF 
    inner join magasin m on f.idM=m.idM
    inner join commandes cm on lnc.idCM = cm.idCM
    where cm.idCM = ".$raw[0]."";
    $runLigne = mysqli_query($GLOBALS["cn"],$sqlLigneCm);
    while($rawLigneCm=mysqli_fetch_array($runLigne)){
        $Accorde="";
        $classModifier="";
        if(($rawLigneCm[4] != $rawLigneCm[5]) || $rawLigneCm[6] == false){$classModifier="class='RowModifier'";}
        if($rawLigneCm[6]){$Accorde = "Oui";} else {$Accorde = "Non";}

        $qtAp = "";
        if($rawLigneCm[6]){$qtAp = $rawLigneCm[5]; }else{$qtAp = "Non approuvé";}

        $InfoCommande .= "<tr $classModifier><td>".$rawLigneCm[1]."</td><td>".$rawLigneCm[2]."</td><td>".$rawLigneCm[3]."</td><td>".$rawLigneCm[4]."</td><td>".$qtAp."</td>
        <td>".$Accorde."</td></tr>";
        $NameMG = $rawLigneCm[0];
    }
    //--------------------------------------------------------------------------------------------------------------------

    $buttonConfirmation = "";
    $classConfirmation = "lampOn";
    if(!$Statut){
      $buttonConfirmation = "<td class='conferm'><input type='button' value='Annuler Confermation' onclick='AnnulerConfermation(".$raw[0].");' hidden><input type='button' $btnConfermation value='Confirmé' onclick='funConfermation(".$raw[0].");' $disabledConfermation></td>";
      echo "<tr><td>".$raw[7]."</td><td>".$raw[1]."</td><td>".$raw[2]."</td><td><label>Voir Detai ....</label><table><tr><th>Famille</th><th>Catégorie</th><th>Désignation</th><th>Quantité Demandé</th><th>Quantité approuvé</th><th>accorder</th></tr>".$InfoCommande."</table></td><td>".$raw[3]."</td><td>".$natureCom."</td><td>".$raw[5]."</td>
        ".$buttonConfirmation."</tr>";
    }
    else{
        echo "<tr><td>".$raw[7]."</td><td>".$raw[1]."</td><td>".$raw[2]."</td><td><label>Voir Detai ....</label><table><tr><th>Famille</th><th>Catégorie</th><th>Désignation</th><th>Quantité Demandé</th><th>Quantité approuvé</th><th>accorder</th></tr>".$InfoCommande."</table></td><td>".$raw[3]."</td><td>".$natureCom."</td><td>".$raw[10]."</td>
        <td>".$raw[5]."</td></tr>";
    }
  }
}


function listeDesignation(){
  global $cn;

  $query = "SELECT DISTINCT  DS.DScode ,DS.DSname,DS.price,DS.DSquantite, CL.CLname,LC.qtD,LC.qtA
    from ligneCommande LC 
    INNER JOIN Designation DS ON LC.idDS = DS.idDS 
    INNER JOIN commandes CM ON LC.idCM = CM.idCM 
    INNER JOIN client CL ON CL.idCL = CM.idCL 
    WHERE CM.idM= ". $_SESSION["idM"] ." ORDER BY LC.qtD DESC";

  $run_query = mysqli_query($cn,$query);

  while($raw = mysqli_fetch_array($run_query)){
      echo "<tr>
    <td>".$raw[0]."</td>
    <td>".$raw[1]."</td>
    <td>".$raw[2]."</td>
    <td>".$raw[3]."</td>
    <td>".$raw[4]."</td>
    <td>".$raw[5]."</td>
    <td>".$raw[6]."</td></tr>";
  }
}


function statistique_commandes(){
  global $cn;
    $result =array();

    $sql_total_cm = "SELECT COUNT(*) as total_cm FROM commandes WHERE idM=".$_SESSION["idM"];
    $row = mysqli_fetch_assoc(mysqli_query($cn,$sql_total_cm));
    $result['total_cm'] = $row['total_cm'];
   
    $sql_cm_livre = "SELECT COUNT(*) as cm_livre FROM commandes WHERE validate='livre' AND idM=".$_SESSION["idM"];
    $row = mysqli_fetch_assoc(mysqli_query($cn,$sql_cm_livre));
    $result['total_cm_livre'] = $row['cm_livre'];
   
    $result['total_cm_attente'] = $result['total_cm']  - $result['total_cm_livre'];
    

 return $result;
}

$array_statistics = statistique_commandes();


function getActiveClients()
{
  global $cn;
  $CmdparClient = "SELECT COUNT(commandes.idCM) as count,client.idCL, client.CLname 
       FROM commandes  INNER JOIN client 
       ON commandes.idCL = client.idCL
       WHERE idM=".$_SESSION["idM"].
       " GROUP BY client.idCL ORDER by  count DESC  LIMIT 10";
  $result = mysqli_query($cn,$CmdparClient);
  $data = array();
 
  while($raw = mysqli_fetch_array($result))
  {
        $data[] = $raw;
  }
  
  echo "<script> var client_data = ".json_encode($data).";</script>";
}
getActiveClients();

?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="images/icons/icone-onssa.png"/>
        <title>Chef</title>
        <style></style>


        <link rel="stylesheet" type="text/css" href="css/bootstrap4-0-0.min.css">
        <link rel="stylesheet" type="text/css" href="css/ChefStyle.css">
        <link rel="stylesheet" href="css/font-awesome-4.7.0/css/font-awesome.min.css"/>
        <link rel="stylesheet" type="text/css" href="css/font-awesome-4.7.0/pro-fontawesome.css">
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
        <link rel="stylesheet" href="css/jquery-ui.min.css">

        <script src="js/jquery-3.5.1.min.js"></script>
        <script src="js/statistiqueChef.js"></script>
        <script  src="js/Chart.min.js"></script>
        <script>
        
          function showFilter() {
                $('#filtrer_exporter').hide();
                $('#exporter').show();
            }
            
            function hideFilter() {
              //  $('.table-statistics tbody tr td em').replaceWith($(this).html());
                $('.table-statistics tbody tr td em').each(function(){
                    var element = $(this);
                    element.replaceWith(element.html());
                });

                $(".table-statistics tbody tr:not(.notfound)").each(function(index) {
                    $(this).show();
                });

                $('#filtrer_exporter').show();
                $('#exporter').hide();
            }
            
            $(document).ready(function () {
                Chart.defaults.global.legend.display = false;
                barChart(client_data);
            });
        </script>
        
    </head>
    <body>


      <div class="header">
          
          <div class="infoHeader">
              <div class="infoApp">

                  <ul>
                      <li><span class="iconApp"></span></li>
                      <li><span class="nameApp">Chef admin </span></li>
                  </ul>

              </div>

              <div class="infoProfil">

                  <ul>
                      <li>
                        <form method="post">
                            <input type="submit" class="dec" name="dec" value="">
                        </form>
                      </li>
                      <li><span class="name">Chef : <?php echo $_SESSION['adminChef'] ; ?></span></li>
                      <li><span class="photo"></span></li>
                  </ul>

              </div>
          </div>
          
          <div class="infoMagasin"><h4>Magasin : </h4><h5><?php echo $_SESSION["MGName"]; ?></h5></div>
      </div>

    <form class='formChef' action="" method='post'>

      <div class="DivDsCom" >
        <div><input type='button' class='BtnDsCom' id='BtnDsCom' value='Control Des Quantité Demmande'/></div>
      </div>

    </form>

        
        <div class="content">
        
            <div class="tab">
              <button class="tablinks active" onclick="openTab(event, 'statistics')">Acceuil</button>
              <button class="tablinks" onclick="openTab(event, 'voirCom')">Voir Toutes les Demmandes</button>
              <button class="tablinks" onclick="openTab(event, 'voirComDelai')">Demande d'achat</button>
              <button class="tablinks" onclick="openTab(event, 'voirhistory')">Historique</button>
            </div>

            

            <!---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
            <div id="statistics" class="tabcontent">
                <div class="row mx-auto mt-2 mw-100">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total commandes</div>
                                        <div  class="h3 mb-0 font-weight-bold text-gray-800">
                                            <?php echo $array_statistics['total_cm'] ; ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fa fa-clipboard-list fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Commandes livrés</div>
                                        <div class="h3 mb-0 font-weight-bold text-gray-800">
                                            <?php echo $array_statistics['total_cm_livre']; ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-file-check fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Pourcentage des livraisons</div>
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-auto">
                                                <div class="h4 mb-0 mr-3 font-weight-bold text-gray-800">
                                                    <?php $cm_livre_percent = ($array_statistics['total_cm_livre'] / $array_statistics['total_cm'])*100 ;
                                                    echo round($cm_livre_percent, 2); ?>
                                                    %</div>
                                            </div>
                                            <div class="col">
                                                <div class="progress progress-sm mr-2">
                                                    <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo $cm_livre_percent; ?>%" aria-valuenow="<?php echo $cm_livre_percent; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-percent fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Commandes en attente</div>
                                        <div class="h3 mb-0 font-weight-bold text-gray-800">
                                            <?php echo $array_statistics['total_cm_attente']; ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fa fa-comments fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div  class="row mx-auto mw-100">
                    <div class="col-md-6">
                        <div class="card shadow mb-4">
                            <!-- Card Header - Dropdown -->
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Clients Active</h6>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body">
                                <div id="chart-container">
                                    <canvas id="graph_clients_active"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card shadow mb-4">
                            <!-- Card Header - Dropdown -->
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Plage de données</h6>
                            </div>
                            <!-- Card Body  <i class="fas fa-download"></i> -->
                            <div class="card-body">
                                <div class="datatable table-responsive">
                                    <form action="export2xls.php" method="post">
                                        <div class="row">
                                            <div class="col-md-6 content-row-top-spacer">
                                                <input type="hidden" id="idM" value="<?php echo $_SESSION["idM"]; ?>">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text ">date début:</span>
                                                    </div>
                                                    <input type="date"  id="start_date" name="start_date" class='form-control data_range'>
                                                </div>
                                            </div>
                                            <div class="col-md-6 content-row-top-spacer">
                                                <div class="input-group">
                                                    <div class="input-group-prepend ">
                                                        <span class="input-group-text">Date Fin:</span>
                                                    </div>
                                                    <input type="date" id="end_date" name="end_date" class='form-control data_range'>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                             <input type="hidden" name="action" value="range_designations" />
                                             <button type="submit" class="p-2 mt-2 btn btn-success"> 
                                                <i class="fas fa-download"> EXPORTER</i>
                                             </button>
                                        </div>
                                        
                                    </form>
                                    <table Class="table-striped table-bordered table-responsive table-statistics mt-2" style="height: 14rem;">
                                        <thead>
                                        <tr>
                                            <th>Designation</th>
                                            <th>Total commandes</th>
                                            <th>Total Qte demandée </th>
                                        </tr>
                                        </thead>
                                        <tbody id="datarange_body">
                                            <tr class=''>
                                                <td colspan='4'> Selectionnez une plage de dates .. </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                     <p id="datarange_total"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 text-center">
                        <div class="card mb-4">
                            <div class="card-header"> Table des designations </div>
                            <div class="card-body">
                                <div class="datatable table-responsive">
                                    
                                              <div id="filtrer_exporter" class="row">
                                        <div class="form-group col-6">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class=" input-group-text "> <i class="fa fa-search"></i></span>
                                                </div>
                                                <input type="text"   id='search_statistics' class="form-control" name="recherche" placeholder="recherche..">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <button class="btn btn-info" onclick="showFilter();">Filtrer & Exporter </button>
                                        </div>
                                    </div>

                                    <div id="exporter" style="display: none">
                                        <form id="form_filter" action="export2xls.php" method="post" >
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="input-group ">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text ">Code:</span>
                                                            </div>
                                                            <input type="text"   id='code' class="form-control filter_criteria" name='code' placeholder="Code">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="input-group ">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text ">Client:</span>
                                                        </div>
                                                        <input type="text"   id='client' class="form-control filter_criteria" name='client' placeholder="Client">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="input-group ">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text ">Qte demandée:</span>
                                                        </div>
                                                        <input type="text"   id='qteD' class="form-control filter_criteria" name="qteD" placeholder="Qte_Demandee">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="input-group ">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text ">Qte approuvée:</span>
                                                        </div>
                                                        <input type="text"   id='qteA' class="form-control filter_criteria" name="qteA" placeholder="Qte_Approuvee">
                                                    </div>
                                                </div>

                                                <input type="hidden" name="action" value="liste_designations" />
                                           <!--     <div class="input-group col-2">
                                                    <button type="button" id="filter" name="filter" >Filtrer</button>
                                                </div> -->
                                                <div class="col-sm-6 text-right mt-2 p-2">
                                                    <button type="submit" name="export" class="btn-success btn" style="height: 2.4rem;"> <i class="fas fa-download"> Exporter</i></button>
                                                </div>
                                                
                                                 <div class="col-sm-6 text-left mt-2 p-2">
                                                   <a onclick="hideFilter()" class="bg-danger btn text-white" >Annuler</a>
                                                </div>
                                               
                                            </div>
                                        </form>

                                    </div>
                                                
                                          <!--  <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class=" input-group-text "> <i class="fa fa-search"></i></span>
                                                </div>
                                                <input type="text"   id='search_statistics' class="form-control" name="recherche" placeholder="recherche..">
                                            </div>
                                        </div>
                                        
                                        <div class="col-4">
                                            <form action="export2xls.php" method="post">
                                                <input type="hidden" name="action" value="liste_designations" />
                                                <button type="submit" class="p-2"> <i class="fas fa-download"> EXPORTER</i></button>
                                            </form>
                                        </div>
                                    </div>    
                                        -->
                                    <table Class="table-striped table-bordered table-responsive table-statistics" style="height: 20rem;">
                                        <thead>
                                        <tr>
                                            <th>Code Designation</th>
                                            <th>Nom Designation</th>
                                            <th>Prix Designation</th>
                                            <th>Quantité en stock</th>
                                            <th>Client Demandeur</th>
                                            <th>Qte Demandée </th>
                                            <th>Qte Approuvée </th>
                                        </tr>
                                        </thead>
                                        <tbody class="">
                                        <?php listeDesignation(); ?>
                                        <tr class='notfound'>
                                            <td colspan='4'> Aucune données n'as été trouvée</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div id="voirCom" class="tabcontent">

              <div>
                  <input class="txtsearsh" id="txtsearsh" type="text" placeholder="Search...">
              </div>

              <table Class="tableCommande">

                <thead>
                    <tr>
                    <th>Numéro commande</th><th>Client</th><th>Services</th><th>Magasin : <?php echo $_SESSION["MGName"]; ?><button class="detai" id="detai">&#8623;</button></th><th>Date Commande</th><th>Nature de la commande</th><th>Validation</th><th>Confirmation</th>
                    </tr>
                </thead> 

                  <?php CommandeClient(false,"tout","<> 'livre'");?>

              </table>

            </div>
            <div id="voirComDelai" class="tabcontent">

              <div>
                  <input class="txtsearsh" id="txtsearsh" type="text" placeholder="Search...">
              </div>

              <table Class="tableCommande">

                <thead>
                    <tr>
                    <th>Numéro commande</th><th>Client</th><th>Services</th><th>Magasin : <?php echo $_SESSION["MGName"]; ?><button class="detai" id="detai">&#8623;</button></th><th>Date Commande</th><th>Nature de la commande</th><th>Validation</th><th>Confirmation</th>
                    </tr>
                </thead> 

                  <?php CommandeClient(false,"4mois","<> 'livre'");?>

              </table>

            </div>

            <div id="voirhistory" class="tabcontent">

              <div>
                  <input class="txtsearsh" id="txtsearsh" type="text" placeholder="Search...">
              </div>

              <table Class="tableCommande">
                <thead>
                    <tr>
                    <th>Numéro commande</th><th>Client</th><th>Services</th><th>Magasin : <?php echo $_SESSION["MGName"]; ?><button class="detai" id="detai">&#8623;</button></th><th>Date Commande</th><th>Nature de la commande</th><th>Date Livraison</th><th>Validation</th>
                    </tr>
                </thead>

                  <?php CommandeClient(true,"tout","= 'livre'");?>

              </table>
            </div>

            <div id="voirComDs" class="tabcontent">


            </div>

            
       </div>

            
        
        <div class="dialog-Chefconfirm">
            <div class="dialog-container">
                <div class="hideChefconfirm"><button id="hideChefconfirm">&#215;</button></div>
                
                <h4>Voulez-vous vraiment Confirmer ?</h4>
                <div class="btnChefconfirm">
                    <button id="ChefconfirmTrue">Oui</button>
                    <button id="ChefconfirmFalse">Non</button>
                </div>
            </div>
        </div>





        <div class="Voir_Com_Ds" id='Voir_Com_Ds'>
          <fieldset class="divVoirComDs">

            <div class='hide'><span class='hideVoirComDs' id='hideVoirComDs'></span></div>
            

            <legend class="LGBack">Control Des Quantité Demmande </legend>
            

            
            <div class='FS-content FS-content-1'>

              <h3>Sélection aléartoire</h3>

              <div class='divsel'>
                  <div>
                      <div>
                          <label>Famile</label>
                      </div>
                      <div>
                          <select id='Vfamille'></select>
                      </div>                      
                  </div>

                  <div>
                      <div>
                          <label>Categorie</label>
                      </div>
                      <div>
                          <select id='Vcategorie'></select>
                      </div>                      
                  </div>

                  <div>
                      <div>
                          <label>Designation</label>
                      </div>
                      <div>
                          <select id='Vdesignation'></select>
                      </div>                      
                  </div>
              </div>
              <div class='divqt'>
                <label>Quantité Restante : <span id = "QtRestanteV"></span></label>
              </div>

            </div>




            <div class='FS-content FS-content-2'>
              <h3>Les Demandes récentes</h3>
              <div class='divUL_DS'>
                <ul id="Ul_Ds_Com" class="Ul_Ds_Com">

                </ul>
              </div>
            </div>



            <div class='FS-content FS-content-3'>
                <h3>Details de la Demande</h3>
                <div class='divTable'>
                    <table id='detail_ds_com' class='detail_ds_com'>
                        <thead>
                            <tr>
                                <th>Numéro commande</th><th>Date Commande</th><th>Client</th><th>Services</th><th>Quantité Demandé</th>
                            </tr>
                        </thead>
                        <tbody id='raw_detail_ds_com'>
                        
                        </tbody>
                    </table>
                </div>
            </div>
            

          </fieldset>
        </div>

    </body>
    
    
    
    <!----------------------------------------------------->

    <!--  <script src="JQUERY_FOLDER/jQuery-3.3.1.js"></script>-->

    <script src="js/jquery-3.5.1.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/scriptChef.js"></script>


</html> 
