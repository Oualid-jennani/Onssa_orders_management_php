<?php

require "db.php";
require "Classes.php";
session_start();


function CheckPost($var){
    $var=trim($var);
    $var=strip_tags($var);
    $var=stripslashes($var);
    if(empty($var)) return false;
    else return $var;
}





//-----------------------------------------------------------------------

if(isset($_POST["table"]) && isset($_POST["nomColumn"]) && isset($_POST["whereVal"])){
    $html="";

    if($_POST["table"]=="magasin"){ $sql= "select * from magasin where activation = true"; }else {$sql= "select * from ".CheckPost($_POST["table"])." where `".CheckPost($_POST["nomColumn"])."` =".CheckPost($_POST["whereVal"])." and activation = true";}

    $req = mysqli_query($cn,$sql);

    while ($raw=mysqli_fetch_array($req)) {

        $html .= "<option value='".$raw[0]."'>".$raw[1]."</option>";
    }
    echo $html;
}
else if(isset($_POST["changeDsUnite"])){

    $nameUM="";
    $idUM="";

    $sql= "select idUM from Designation where `idDs`=".CheckPost($_POST["changeDsUnite"])."";
    $req = mysqli_query($cn,$sql);
    if ($raw=mysqli_fetch_array($req)) {
      $idUM=$raw[0];
    }

    $sql= "select distinct * from UniteMesure where `idUM`=".$idUM."";
    $req = mysqli_query($GLOBALS["cn"],$sql);
    if ($raw=mysqli_fetch_array($req)) {
      $nameUM=$raw[1];
    }
    echo  $nameUM;

}
else if(isset($_POST["changePhotoDs"])){

    $photoUrl="";

    $sql= "select image from Designation where `idDs`=".CheckPost($_POST["changePhotoDs"])."";
    $req = mysqli_query($cn,$sql);
    if ($raw=mysqli_fetch_array($req)) {
      $photoUrl=$raw[0];
    }
    echo "<img src='$photoUrl' class='photoDS' alt='image designation' width='250' height='150'>";

}
else if(isset($_POST["addDS"]) && isset($_POST["quantite"]) ){

    if($_POST["addDS"] != null && $_POST["quantite"] != null){

        if(count($_SESSION["ligneCommande"]) < 20){
            $ligne = new ligneCommande(CheckPost($_POST["addDS"]),CheckPost($_POST["quantite"]));
            array_push($_SESSION["ligneCommande"], $ligne);
        }
    }
    

    foreach($_SESSION["ligneCommande"] as $key => $val)
    { 
      $sql= "select * from Designation where `idDs`=".$val ->Designation."";
      $req = mysqli_query($GLOBALS['cn'],$sql);
      if ($raw=mysqli_fetch_array($req)) {
        echo "
        <li>
        <img src='".$raw[2]."'>
          <div>
              <label class='nameitems'>".$raw[1]."</label>
              <label class='price'>".$raw[3]."</label>
          </div>
          <div>
              <div class='action'>
                  <input type='number' class='Pquantite' value='".$val ->quantite."' onchange='editqtP(".$key.",".$raw[0].",event)'>
                  <input type='button' onclick='delpanier(".$key.",".$raw[0].")' class='deleteP' value=''>
                  <input type='button' onclick='refreshP()' class='refreshP' value=''>
              </div>
              <label class='subotal'>Sub Total : ".($val ->quantite*$raw[3])."</label>
          </div>
      </li>
      ";

      }

    }

}

//---------edit Panier -------------------------
else if(isset($_POST["deletkey"])){
    unset($_SESSION["ligneCommande"][$_POST["deletkey"]]);

}else if(isset($_POST["editKey"]) && isset($_POST["idDsQt"]) && isset($_POST["valueqt"])){

    $_SESSION["ligneCommande"][$_POST["editKey"]] = new ligneCommande(CheckPost($_POST["idDsQt"]),CheckPost($_POST["valueqt"]));
}
else if(isset($_POST["countP"])){
    echo count($_SESSION["ligneCommande"]);
}
//---------edit Panier -------------------------


else if(isset($_POST["ajouterCom"]) && isset($_POST["delai"]) && isset($_POST["idMagasine"]) && count($_SESSION["ligneCommande"]) != 0 ){

    $sql= "call insertCommande(".$_SESSION['IDclient'].",'".date("Y-m-d")."','".CheckPost($_POST["delai"])."',".CheckPost($_POST["idMagasine"]).")";
    $req = mysqli_query($cn,$sql);

    $idCmClient =0;
    $sql="select idCM from commandes where idCL = ".$_SESSION['IDclient']." order by idCM desc limit 1";
    $req = mysqli_query($cn,$sql);

    if($raw=mysqli_fetch_array($req)) {
        $idCmClient=$raw[0];
        
        foreach($_SESSION["ligneCommande"] as $val)
        { 
            $sql="call insertLigneCom(".$idCmClient.",".$val ->Designation.",".$val ->quantite.")";
            $req = mysqli_query($cn,$sql);
        }
    }
    $_SESSION["ligneCommande"]=array();
  }
 
//-------------------------------------------------------- code Gestionnaire--------------------------------------------------------------------------

else if(isset($_POST["addVersionImpresion"]) && isset($_POST["VR"])){
    $sql= 'update versionImpriment set nameVersion = "'.CheckPost($_POST["VR"]).'" where idVR > 0';
    $req = mysqli_query($cn,$sql);
}

else if(isset($_POST["posteidCM"]) && isset($_POST["posteidDS"]) && isset($_POST["postqtA"])){
    $sql= "update `TempligneCommande` set qtA = ".CheckPost($_POST["postqtA"])." where idCM =".CheckPost($_POST["posteidCM"])." and idDS = ".CheckPost($_POST["posteidDS"])."";
    $req = mysqli_query($cn,$sql);
  
}
else if(isset($_POST["posteidCM"]) && isset($_POST["posteidDS"]) && isset($_POST["postAccorder"])){

    $sql= "update `TempligneCommande` set accorder = ".CheckPost($_POST["postAccorder"])." where idCM =".CheckPost($_POST["posteidCM"])." and idDS = ".CheckPost($_POST["posteidDS"])."";

    if(CheckPost($_POST["postAccorder"]) == 0){
        $sql= "update `TempligneCommande` set accorder = ".CheckPost($_POST["postAccorder"]).",qtA = 0 where idCM =".CheckPost($_POST["posteidCM"])." and idDS = ".CheckPost($_POST["posteidDS"])."";
    }

    
    $req = mysqli_query($cn,$sql);
}
else if(isset($_POST["postePassChef"]) && isset($_POST["postenumCommande"])){

    $sql = 'select idDS,qtA,accorder from TempligneCommande where idCM = '.$_POST["postenumCommande"].'';
    $run = mysqli_query($cn,$sql);
    while($raw=mysqli_fetch_array($run)){

        $sql_2 = 'update ligneCommande set qtA = '.$raw[1].' , accorder = '.$raw[2].' where idCM = '.$_POST["postenumCommande"].' and idDS = '.$raw[0].'';
        $req = mysqli_query($cn,$sql_2);
    }

    $sql= "update `commandes` set validateGest = ".CheckPost($_POST["postePassChef"])." where idCM =".CheckPost($_POST["postenumCommande"])."";
    $req = mysqli_query($cn,$sql);
}
else if(isset($_POST["posteNewPassChef"]) && isset($_POST["postenumCommande"])){

    $sql = 'select idDS,qtA,accorder from TempligneCommande where idCM = '.$_POST["postenumCommande"].'';
    $run = mysqli_query($cn,$sql);
    while($raw=mysqli_fetch_array($run)){

        $sql_2 = 'update ligneCommande set qtA = '.$raw[1].' , accorder = '.$raw[2].' where idCM = '.$_POST["postenumCommande"].' and idDS = '.$raw[0].'';
        $req = mysqli_query($cn,$sql_2);
    }


    $sql= "update `commandes` set  confirmation = false , NewvalidateGest = ".CheckPost($_POST["posteNewPassChef"])." where idCM =".CheckPost($_POST["postenumCommande"])."";
    $req = mysqli_query($cn,$sql);
}
else if(isset($_POST["postevalidation"]) && isset($_POST["postenumCommande"])){
    $sql= "update `commandes` set validate = '".CheckPost($_POST["postevalidation"])."' , dateValidate = '".date("Y-m-d")."' where idCM = ".CheckPost($_POST["postenumCommande"])."";
    $req = mysqli_query($cn,$sql);

    echo CheckPost($_POST["postevalidation"]);
    
    $sql_2= 'insert into BonAppr (idCM,dateSortie) values ('.CheckPost($_POST["postenumCommande"]).',"'.date("Y-m-d").'")';
    $req2 = mysqli_query($cn,$sql_2);

    echo $sql_2;

     /*   if(CheckPost($_POST["postevalidation"]) == "livre"){
       $sql_2= 'insert into BonAppr (idCM,dateSortie) values ("'.CheckPost($_POST["postenumCommande"]).'","'.date("Y-m-d").'")';
        $req2 = mysqli_query($cn,$sql_2);


        $idCmClient =0;
        $sql="select idDS,qtA from ligneCommande where idCM = ".CheckPost($_POST["postenumCommande"])."";
        $req = mysqli_query($cn,$sql);

        if($raw=mysqli_fetch_array($req)) {
            $idBC=$raw[0];
            
            foreach($_SESSION["ligneBonCommande"] as $val)
            { 
                $sql="call insertBonCommande(".$idBC.",".$val ->Designation.",".$val ->qtEntree.",".$val ->nouveauPrix.")";
                $req = mysqli_query($cn,$sql);
            }
        }
        $_SESSION["ligneBonCommande"]= null;

    }*/
}
//-----------------------------impretion---------------------------
else if(isset($_POST["idCmImprimer"])){
    $_SESSION["idCmImprimer"] = CheckPost($_POST["idCmImprimer"]);
    
    $sqlLigneCm = "select count(*) from ligneCommande lnc
    inner join Designation d on lnc.idDS=d.idDS 
    inner join categorie c on d.idC=c.idC
    inner join famille f on c.idF=f.idF 
    inner join magasin m on f.idM=m.idM
    inner join commandes cm on lnc.idCM = cm.idCM
    inner join UniteMesure um on um.idUm = d.idUm
    where cm.idCM = ".CheckPost($_POST["idCmImprimer"])." and lnc.accorder = true";
    $runLigne = mysqli_query($cn,$sqlLigneCm);
    if($rawLigneCm=mysqli_fetch_array($runLigne)){
        echo $rawLigneCm[0];
    }

}


//-----------------------------impretion---------------------------
  
//-------------------------------------------------------- code Gestionnaire--------------------------------------------------------------------------

//-------------------------------------------------------- code Chef--------------------------------------------------------------------------

else if(isset($_POST["posteConfirm"]) && isset($_POST["postenumCommande"])){
    $sql= "update `commandes` set confirmation = ".CheckPost($_POST["posteConfirm"]).",validate = 'pret a livrer' where idCM =".CheckPost($_POST["postenumCommande"])."";
    $req = mysqli_query($cn,$sql);
}
//-------------------------------------------------------- code Chef--------------------------------------------------------------------------



//-------------------------------------------------------- code Magasinier--------------------------------------------------------------------------


//--------------------remplir les tables et select-------------------------
else if(isset($_POST["remplireTabStockDs"]) ){

    $sql = "select d.idDS,d.DScode,d.DSname,d.DSquantite from Designation d
    inner join categorie c on c.idC=d.idC
    inner join famille f on c.idF=f.idF 
    inner join magasin m on f.idM=m.idM
    inner join stock st on st.idST = d.idST
    inner join UniteMesure um on d.idUM = um.idUM
    where d.activation = true and m.idM = ".$_SESSION["idM"]."";
  
    $run = mysqli_query($cn,$sql);
    while($raw=mysqli_fetch_array($run)){
      echo "<tr><td>".$raw[1]."</td><td>".$raw[2]."</td><td>".$raw[3]."</td>
      <td><input type='button' class='btnQtAction' onclick='QtEntrees(".$raw[0].")' value='Entrée'/><input type='button' class='btnQtAction' onclick='QtSorties(".$raw[0].")' value='Sortie'/></td>
      </tr>";
    }
}
else if(isset($_POST["whereValFamille"]) ){

    $sql = "select c.idC,c.CTname from categorie c
    inner join famille f on f.idF=c.idF where f.idF =".CheckPost($_POST["whereValFamille"])." and c.activation = true";
  
    $run = mysqli_query($cn,$sql);
    while($raw=mysqli_fetch_array($run)){
      echo "<tr><td>".$raw[1]."</td><td><input type='button' class='btnEdit' onclick='EditCategorie(".$raw[0].")' title='Modifier'/><input type='button' class='btnDelete' onclick='DeleteCategorie(".$raw[0].")' title='Suprimer'/></td></tr>";
    }
}

else if(isset($_POST["whereValCategorie"]) ){

    $sql = "select d.idDS,d.DSname,um.UMname,d.DScode,d.price,d.DSquantite,d.coutStock,d.Val from Designation d
    inner join categorie c on c.idC=d.idC
    inner join stock st on st.idST = d.idST
    inner join UniteMesure um on d.idUM = um.idUM
    where c.idC =".CheckPost($_POST["whereValCategorie"])." and d.activation = true";
  
    $run = mysqli_query($cn,$sql);
    while($raw=mysqli_fetch_array($run)){
      echo "<tr><td>".$raw[1]."</td><td>".$raw[2]."</td><td>".$raw[3]."</td><td>".$raw[4]."</td><td>".$raw[5]."</td><td>".$raw[6]."</td><td>".$raw[7]."</td><td><input type='button' class='btnEdit' onclick='EditDs(".$raw[0].")' title='Modifier'/><input type='button' class='btnDelete' onclick='DeleteDs(".$raw[0].")' title='Suprimer'/></td></tr>";
    }
}

else if(isset($_POST["remplireTabF"])){
    $sql = "select distinct f.idF,f.FMname from magasin m
    inner join famille f on f.idM=m.idM where m.idM = ".$_SESSION["idM"]." and f.activation = true";
  
    $run = mysqli_query($cn,$sql);
    while($raw=mysqli_fetch_array($run)){
      echo "<tr><td>".$raw[1]."</td><td><input type='button' class='btnEdit' onclick='EditFamille(".$raw[0].")' title='Modifier'/><input type='button' class='btnDelete' onclick='DeleteFamille(".$raw[0].")' title='Suprimer'/></td></tr>";
    }
}


else if(isset($_POST["remplireSelF"])){
    $sql = "select distinct f.idF,f.FMname from magasin m
    inner join famille f on f.idM=m.idM where m.idM = ".$_SESSION["idM"]." and f.activation = true";
  
    $run = mysqli_query($cn,$sql);
    while($raw=mysqli_fetch_array($run)){
      echo "<option value='".$raw[0]."'>".$raw[1]."</option>";
    }
}

else if(isset($_POST["remplireSelStock"])){

    if(isset($_POST["idDS"])){
        $idST = "";
        $sql="select idST from Designation where idDS =".CheckPost($_POST["idDS"])."";
        $run = mysqli_query($cn,$sql);
        if($raw=mysqli_fetch_array($run)){$idST = $raw[0];}
    

        $sql = "select * from stock st inner join magasin m on st.idM=m.idM where m.idM = ".$_SESSION["idM"]." and st.activation = true";
        $run = mysqli_query($cn,$sql);
        while($raw=mysqli_fetch_array($run)){
            $selected = "";if($raw[0] == $idST){$selected = "selected";}
            echo "<option value='".$raw[0]."' $selected>".$raw[1]."</option>";
        }
    }
    else{
        $sql = "select * from stock st inner join magasin m on st.idM=m.idM where m.idM = ".$_SESSION["idM"]." and st.activation = true";
        $run = mysqli_query($cn,$sql);
        while($raw=mysqli_fetch_array($run)){
            echo "<option value='".$raw[0]."'>".$raw[1]."</option>";
        }
    }

}
else if(isset($_POST["remplireUniteMesure"])){

    if(isset($_POST["idDS"])){
        $idUM = "";
        $sql="select idUM from Designation where idDS =".CheckPost($_POST["idDS"])."";
        $run = mysqli_query($cn,$sql);
        if($raw=mysqli_fetch_array($run)){$idUM = $raw[0];}
    
        $sql = "select * from UniteMesure";
        $run = mysqli_query($cn,$sql);
        while($raw=mysqli_fetch_array($run)){
            $selected = "";if($raw[0] == $idUM){$selected = "selected";}
            echo "<option value='".$raw[0]."' $selected>".$raw[1]."</option>";
        }
    }
    else{
        $sql = "select * from UniteMesure";
        $run = mysqli_query($cn,$sql);
        while($raw=mysqli_fetch_array($run)){
            echo "<option value='".$raw[0]."'>".$raw[1]."</option>";
        }
    }

}
else if(isset($_POST["ShowidDSQT"])){

    if(CheckPost($_POST["ShowidDSQT"]) != null){

        $sql="select DSquantite from Designation where idDS =".CheckPost($_POST["ShowidDSQT"])."";
        $run = mysqli_query($cn,$sql);
        
        if($raw=mysqli_fetch_array($run)){
            echo $raw[0];
        }
    }
    else {echo "...";}
    
}
//------------------------supprimer---------------------------------
else if(isset($_POST["table"]) && isset($_POST["nomColumn"]) && isset($_POST["whereIdDelete"])){

    if($_POST["table"]=="magasin"){ $sql= "update magasin set activation = false where `".CheckPost($_POST["nomColumn"])."` =".CheckPos($_POST["whereIdDelete"])." "; }
    else {$sql= "update ".CheckPost($_POST["table"])." set activation = false where `".CheckPost($_POST["nomColumn"])."` =".CheckPost($_POST["whereIdDelete"])."";}

    $req = mysqli_query($cn,$sql);
}
//------------------------Ajouter----------------------------------
else if(isset($_POST["AjtFamille"])){
    $sql = 'insert into famille(FMname,idM) values ("'.CheckPost($_POST["AjNomF"]).'",'.CheckPost($_SESSION["idM"]).')';
    $run = mysqli_query($cn,$sql);
}
else if(isset($_POST["AjtCategorie"]) && isset($_POST["AjtIDF"]) ){
    $sql = 'insert into categorie(CTname,idF) values ("'.CheckPost($_POST["AjNomC"]).'",'.CheckPost($_POST["AjtIDF"]).')';
    $run = mysqli_query($cn,$sql);
}
else if(isset($_POST["AjtDesignation"])){
    
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

}

//------------------------Modifier----------------------------------
else if(isset($_POST["MdFamille"]) && isset($_POST["idF"])){
    $sql = 'update famille set FMname = "'.CheckPost($_POST["MdNomF"]).'" where idF = '.CheckPost($_POST["idF"]).'';
    $run = mysqli_query($cn,$sql);
}
else if(isset($_POST["MdCategorie"]) && isset($_POST["idC"]) ){
    $sql = 'update categorie set CTname = "'.CheckPost($_POST["MdNomC"]).'", idF = '.CheckPost($_POST["MdIDF"]).' where idC = '.CheckPost($_POST["idC"]).'';
    $run = mysqli_query($cn,$sql);
}
else if(isset($_POST["MdDesignation"]) && isset($_POST["idDS"])){

    $MdPrixDS = CheckPost($_POST['MdPrixDS']);
    if($MdPrixDS == ""){$MdPrixDS = "0";}

    $MdQtDs = CheckPost($_POST['MdQtDs']);
    if($MdQtDs == ""){$MdQtDs = "0";}
    
    $MdValDs = CheckPost($_POST['MdValDs']);
    if($MdValDs == ""){$MdValDs = "0";}


    $sql = 'update Designation set DSname= "'.CheckPost($_POST['MdNomDS']).'" ,idC = '.CheckPost($_POST['MdCategorieDS']).' ,idUM = '.CheckPost($_POST['MdUniteDS']).',idST = '.CheckPost($_POST['MdStockDS']).',DScode = "'.CheckPost($_POST['MdCodeDS']).'",price = '.$MdPrixDS.' ,DSquantite = '.$MdQtDs.',val = '.$MdValDs.'
    where idDS = '.CheckPost($_POST["idDS"]).'';

    $run = mysqli_query($cn,$sql);

    echo $sql;
}
//------------------------------------------------------------entré sortie------------------------------------------------------------
else if(isset($_POST["addDS"]) && isset($_POST["qtEntree"]) && isset($_POST["nouveauPrix"])){
            
    if($_SESSION["ligneBonCommande"] == null){
        $_SESSION["ligneBonCommande"]=array();
    }

    $ligne = new ligneBonCommande(CheckPost($_POST["addDS"]),CheckPost($_POST["qtEntree"]),CheckPost($_POST["nouveauPrix"]));
    array_push($_SESSION["ligneBonCommande"], $ligne);
    
}
//------------------------------------------------------------------------------------------------------------
else if(isset($_POST["ajouterBcomN"]) && isset($_POST["dateEntree"]) && count($_SESSION["ligneBonCommande"]) != 0 ){

    $sql= 'insert into BonCommande (N_BC,dateEntree,idM) values ("'.CheckPost($_POST["ajouterBcomN"]).'","'.CheckPost($_POST["dateEntree"]).'",'.$_SESSION["idM"].')';
    $req = mysqli_query($cn,$sql);

    $idCmClient =0;
    $sql="select idBC from BonCommande where idM = ".$_SESSION['idM']." order by idBC desc limit 1";
    $req = mysqli_query($cn,$sql);

    if($raw=mysqli_fetch_array($req)) {
        $idBC=$raw[0];
        
        foreach($_SESSION["ligneBonCommande"] as $val)
        { 
            $sql="call insertBonCommande(".$idBC.",".$val ->Designation.",".$val ->qtEntree.",".$val ->nouveauPrix.")";
            $req = mysqli_query($cn,$sql);
        }
    }
    $_SESSION["ligneBonCommande"]= null;
  }

//-----------------------------------------------------------------------------------------
else if(isset($_POST["rmplireTabEntree"])){
      
    $sql = "select bc.idBC,bc.N_BC,bc.dateEntree from BonCommande bc
    inner join magasin m on m.idM = bc.idM where bc.idM =".$_SESSION["idM"]."";
  
    $run = mysqli_query($cn,$sql);
    while($raw=mysqli_fetch_array($run)){
  
        $InfoBc = "";
        
        $sqlLigneCm = "select f.FMname,c.CTname,d.DSname,lbc.qtEntree,lbc.newPrix from ligneBonCommande lbc
        inner join Designation d on lbc.idDS=d.idDS 
        inner join categorie c on d.idC=c.idC
        inner join famille f on c.idF=f.idF 
        inner join magasin m on f.idM=m.idM
        inner join BonCommande bc on lbc.idBC = bc.idBC
        where bc.idBC = ".$raw[0]."";
  
      
        $runLigne = mysqli_query($cn,$sqlLigneCm);
        while($rawLigne=mysqli_fetch_array($runLigne)){
            $InfoBc .= "<tr><td>".$rawLigne[0]."</td><td>".$rawLigne[1]."</td><td>".$rawLigne[2]."</td><td>".$rawLigne[3]."</td><td>".$rawLigne[4]."</td></tr>";
        }
  
        echo "<tr><td>".$raw[1]."</td><td>".$raw[2]."</td><td><table><thead><tr><th>Famille</th><th>Catégorie</th><th>Désignation</th><th>Quantité Reçue</th><th>Prix D'achat</th></tr></thead>".$InfoBc."</table></td></tr>";
    }
}
else if(isset($_POST["rmplireTabSortie"])){

    $sql = "select ba.idBA,ba.idCM,ba.dateSortie from BonAppr ba
    inner join commandes c on c.idCM = ba.idCM
    inner join magasin m on m.idM = c.idM where m.idM =".$_SESSION["idM"]."";
  
    $run = mysqli_query($cn,$sql);
    while($raw=mysqli_fetch_array($run)){
  
        $InfoBa = "";
        
        $sqlLigneCm = "select f.FMname,c.CTname,d.DSname,lba.qtSortie from ligneBonAppr lba
        inner join Designation d on lba.idDS=d.idDS 
        inner join categorie c on d.idC=c.idC
        inner join famille f on c.idF=f.idF 
        inner join magasin m on f.idM=m.idM
        inner join BonAppr ba on lba.idBA = ba.idBA
        where ba.idBA = ".$raw[0]."";
  
      
        $runLigne = mysqli_query($cn,$sqlLigneCm);
        while($rawLigne=mysqli_fetch_array($runLigne)){
            $InfoBa .= "<tr><td>".$rawLigne[0]."</td><td>".$rawLigne[1]."</td><td>".$rawLigne[2]."</td><td>".$rawLigne[3]."</td></tr>";
        }
  
        echo "<tr><td>".$raw[1]."</td><td>".$raw[2]."</td><td><table><thead><tr><th>Famille</th><th>Catégorie</th><th>Désignation</th><th>Quantité Livrée</th></tr></thead>".$InfoBa."</table></td></tr>";
    }
}
else if(isset($_POST["remplireSelBA"])){

    $sql = "select idBA,idCM from BonAppr where updateBon = false";
    $run = mysqli_query($cn,$sql);

    $html = "";
    while ($raw=mysqli_fetch_array($run)) {
        $html .= "<option value='".$raw[0]."'>Commande N° ".$raw[1]."</option>";
    }
    echo $html;
}
else if(isset($_POST["updateBA"]) && isset($_POST["dateSortie"])){

    $sql = "update BonAppr set dateSortie = '".CheckPost($_POST["dateSortie"])."' , updateBon = true where idBA = ".CheckPost($_POST["updateBA"])."";
    echo $sql;
    $run = mysqli_query($cn,$sql);
}

else if(isset($_POST["inpAnnulerBABC"])){

    $_SESSION["ligneBonCommande"]= null;    
}



?>