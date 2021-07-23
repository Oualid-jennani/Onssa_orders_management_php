<?php
require "db.php";
session_start();


function nameVersion(){

    $sql="select nameVersion from versionImpriment order by idVR desc limit 1";
    $req = mysqli_query($GLOBALS["cn"],$sql);
   
    if($raw=mysqli_fetch_array($req)) {
        echo $raw[0];
    }

}



//----------------------------- impretion ------------------------------------------
function headerImpretion(){

    global $cn;

    $sql = "select distinct cm.numCom,m.MGcode,cl.CLname,s.SRname from commandes cm
    inner join ligneCommande lnc on lnc.idCM = cm.idCM
    inner join Designation d on lnc.idDS=d.idDS 
    inner join categorie c on d.idC=c.idC
    inner join famille f on c.idF=f.idF 
    inner join magasin m on f.idM=m.idM
    inner join admin ad on ad.idM=m.idM
    inner join client cl on cl.idCL=cm.idCL
    inner join services s on s.idSR=cl.idSR where cm.idCM=".$_SESSION["idCmImprimer"]."";

    $run = mysqli_query($cn,$sql);
    if($raw=mysqli_fetch_array($run)){

    echo "<ul class='infoGlobal'><li>N° demande</li><li>".$raw[0]."</li><li>N° du Bon</li><li>".$raw[0]."</li>
        <li>Code Magasin</li><li>".$raw[1]."</li>
        <li>Date</li><li>".date("d/m/Y")."</li>
        <li>Service</li><li>".$raw[3]."</li>
    </ul>";

    } 
    
    
}
function infoImpretion(){

    global $cn;

    $sql = "select distinct cm.idCM,cl.CLname,s.SRname,cm.dateCom,cm.delaiO,cm.validate,cm.confirmation,cm.numCom,m.MGcode from commandes cm
    inner join ligneCommande lnc on lnc.idCM = cm.idCM
    inner join Designation d on lnc.idDS=d.idDS 
    inner join categorie c on d.idC=c.idC
    inner join famille f on c.idF=f.idF 
    inner join magasin m on f.idM=m.idM
    inner join admin ad on ad.idM=m.idM
    inner join client cl on cl.idCL=cm.idCL
    inner join services s on s.idSR=cl.idSR where cm.idCM=".$_SESSION["idCmImprimer"]."";

    $run = mysqli_query($cn,$sql);
    if($raw=mysqli_fetch_array($run)){















        $count = 1;
        $NameMG = "";
        $Fammile = "";
        $InfoCommande = "";

        $sqlLigneCm = "select m.MGname,lnc.idDS,d.DSname,um.UMname,lnc.qtD,lnc.qtA,d.DScode,f.FMname from ligneCommande lnc
        inner join Designation d on lnc.idDS=d.idDS 
        inner join categorie c on d.idC=c.idC
        inner join famille f on c.idF=f.idF 
        inner join magasin m on f.idM=m.idM
        inner join commandes cm on lnc.idCM = cm.idCM
        inner join UniteMesure um on um.idUm = d.idUm
        where cm.idCM = ".$raw[0]." and lnc.accorder = true";
        $runLigne = mysqli_query($cn,$sqlLigneCm);
        while($rawLigneCm=mysqli_fetch_array($runLigne)){

            if($count >= (int)$_GET["star"] && $count <= (int)$_GET["end"]){
                $InfoCommande .= "<tr><td>".$rawLigneCm[6]."</td><td>".$rawLigneCm[2]."</td><td>".$rawLigneCm[3]."</td><td>".$rawLigneCm[4]."</td><td>".$rawLigneCm[5]."</td><td><textarea></textarea></td></tr>";
            }

            $NameMG = $rawLigneCm[0];
            $Fammile = $rawLigneCm[7];

            $count ++;
        }


        echo "<table class='infoDS'><caption>Famille : $Fammile</caption><tr><th>Code</th><th>Désignation</th><th>UTE</th><th>Quantité Demandée</th><th>Quantité Accordée</th><th>Observations</th></tr>".$InfoCommande."</table>";

    } 
    
    
}

?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="images/icons/icone-onssa.png"/>
        <title>Bon D’approvisionnement</title>

        <style></style>
        <link rel="stylesheet" type="text/css" href="css/ImprimerStyle.css">
        <link rel="stylesheet" href="css/jquery-ui.min.css">
    </head>
    <body>




        <div class="hederImprimer">
            <div>
                <div id="logo" class=logo></div>
                <h2>BON D’APPROVISIONNEMENT</h2>
                <?php headerImpretion(); ?>
            </div>

        </div>



        <div id="divImprimer" class="divImprimer">
            <div id="infoImprimer" class=infoImprimer>
                <?php infoImpretion(); ?>
            </div>
        </div>


        <div class='divCharge'>
            <div>
                <table class='infoCharge'>
                    <tr><th>Visa du Chef de l'entité émettrice</th><th>Visa de Représentant de l'untité réceptrice</th></tr>
                    <tr><td></td><td></td></tr>
                </table>
            
            </div>
            <div class='infoPage'>
                <div>
                    <div>ONSSA - DRO</div>
                    <div>ENr 01/ FBA6 GST8/19 <?php nameVersion(); ?> </div>
                    <div class="hrefImpimer"><A href="javascript:window.print()" >Imprimer</A></div>
                </div>
            </div>
        </div>
        
    </body>

    <!----------------------------------------------------->
    <script src="JQUERY_FOLDER/jQuery-3.3.1.js"></script>
    <script src="js/jquery-ui.min.js"></script>

</html> 
