<?php
require "db.php";
require "Classes.php";
session_start();


//-------------------------------------------------------- code Admin--------------------------------------------------------------------------


//----------------------------------------afichage------------------------------------------------------------
if(isset($_POST["TypeUser"])){

    if($_POST["TypeUser"] == "Client"){
        
        $sql = "select c.idCL,c.Cnt,c.CLname,c.CLpass,s.SRname from client c inner join services s on c.idSR = s.idSR";
      
        $run = mysqli_query($cn,$sql);
        while($raw=mysqli_fetch_array($run)){
            echo "<tr><td>".$raw[1]."</td><td>".$raw[2]."</td><td>".$raw[3]."</td><td>".$raw[4]."</td><td><input type='button' class='btnEdit' onclick='EditClient(".$raw[0].")' title='Modifier'/><input type='button' class='btnDelete' onclick='DeleteClient(".$raw[0].")' title='Suprimer'/></td></tr>";
        }

    }else{
        $sql = "select a.idAD,a.Cnt,a.ADname,a.ADpass,a.typeAD,m.MGname,s.SRname from admin a 
        inner join services s on a.idSR = s.idSR
        left join magasin m on m.idM = a.idM";
      
        $run = mysqli_query($cn,$sql);
        while($raw=mysqli_fetch_array($run)){
            $mag = "";
            if($raw[4]=="admin"){$mag = "Tout";}else{$mag = $raw[5];}

            echo "<tr><td>".$raw[1]."</td><td>".$raw[2]."</td><td>".$raw[3]."</td><td>".$raw[4]."</td><td>".$mag."</td><td>".$raw[6]."</td><td><input type='button' class='btnEdit' onclick='EditAdmin(".$raw[0].")' title='Modifier'/><input type='button' class='btnDelete' onclick='DeleteAdmin(".$raw[0].")' title='Suprimer'/></td></tr>";
        }
    }
}
else if(isset($_POST["Services"])){
    $sql = "select * from services where activation = true";
  
    $run = mysqli_query($cn,$sql);
    while($raw=mysqli_fetch_array($run)){
        echo "<tr><td>".$raw[1]."</td><td><input type='button' class='btnEdit' onclick='EditServices(".$raw[0].")' title='Modifier'/><input type='button' class='btnDelete' onclick='DeleteServices(".$raw[0].")' title='Suprimer'/></td></tr>";
    }
}
else if(isset($_POST["ShowDetailDsCom"])){

    $total = 0;
    $sql = "select distinct cm.idCM,cm.numCom,cm.dateCom,cl.CLname,s.SRname,lnc.qtD from commandes cm
    inner join ligneCommande lnc on lnc.idCM = cm.idCM
    inner join Designation d on lnc.idDS=d.idDS
    inner join client cl on cl.idCL=cm.idCL
    inner join services s on s.idSR=cl.idSR
    where cm.idM =".$_SESSION["idM"]." and cm.confirmation = false and(cm.delaiO = '< 4mois' or cm.delaiO = '> 4mois') and lnc.idDS = ".$_POST["ShowDetailDsCom"]." order by cm.dateCom desc";
  
    $run = mysqli_query($cn,$sql);

    while($raw=mysqli_fetch_array($run)){
        echo "<tr><td>".$raw[1]."</td><td>".$raw[2]."</td><td>".$raw[3]."</td><td>".$raw[4]."</td><td>".$raw[5]."</td></tr>";
        $total = $total + $raw[5];
    }
    echo "<tr><td colspan='4'></td><td class='tdTotal'>Total : $total</td></tr>";
}
else if(isset($_POST["ShowDsCom"])){

    $sql = "select d.idDs,d.DSname from  Designation d
    inner join categorie c on d.idC=c.idC
    inner join famille f on c.idF=f.idF 
    inner join magasin m on f.idM=m.idM
    where m.idM =".$_SESSION["idM"]." and d.demande = true";
  
    $run = mysqli_query($cn,$sql);

    while($raw=mysqli_fetch_array($run)){
        echo "<li>".$raw[1]."<span></span></li>";
    }
}
//----------------------------------------afichage ajouter modifier------------------------------------------------------------
else if(isset($_POST["ServicesAddMD"])){
    $sql = "select * from services";
  
    $run = mysqli_query($cn,$sql);
    while($raw=mysqli_fetch_array($run)){
        echo "<option value='".$raw[0]."'>".$raw[1]."</option>";
    }
}
else if(isset($_POST["table"]) && isset($_POST["nomColumn"]) && isset($_POST["whereVal"])){
    $html="";

    if($_POST["table"]=="magasin"){ $sql= "select * from magasin where activation = true"; }else {$sql= "select * from ".strip_tags($_POST["table"])." where `".strip_tags($_POST["nomColumn"])."` =".strip_tags($_POST["whereVal"])." and activation = true";}

    $req = mysqli_query($cn,$sql);
    while ($raw=mysqli_fetch_array($req)) {

        $html .= "<option value='".$raw[0]."'>".$raw[1]."</option>";
    }
    echo $html;
}
else if(isset($_POST["tableMagasin"])){
    $html="";

    $sql= "select * from magasin where activation = true";

    $req = mysqli_query($cn,$sql);
    $html .= "<option value='-1' disabled>Tout</option>";
    while ($raw=mysqli_fetch_array($req)) {

        $html .= "<option value='".$raw[0]."'>".$raw[1]."</option>";
    }
    echo $html;
}


//------------------------------------------------ajouter------------------------------------------------------------
if(isset($_POST["AjtClient"])){
    $sql = 'insert into client(CNT,CLname,CLpass,idSR)values("'.$_POST["AjCntCL"].'","'.$_POST["AjNomCL"].'","'.$_POST["AjPassCL"].'",'.$_POST["AjSerCL"].');';
    $run = mysqli_query($cn,$sql);
}
else if(isset($_POST["AjtAdmin"])){

    if($_POST["AjTypeAD"] == 'admin'){
        $sql = 'insert into admin(Cnt,Adname,Adpass,typeAD,idM,idSR)values("'.$_POST["AjCntAD"].'","'.$_POST["AjNomAD"].'","'.$_POST["AjPassAD"].'","'.$_POST["AjTypeAD"].'",1,'.$_POST["AjSerAD"].')';
    }else{
        $sql = 'insert into admin(Cnt,Adname,Adpass,typeAD,idM,idSR)values("'.$_POST["AjCntAD"].'","'.$_POST["AjNomAD"].'","'.$_POST["AjPassAD"].'","'.$_POST["AjTypeAD"].'",'.$_POST["AjMagAD"].','.$_POST["AjSerAD"].')';
    }
    $run = mysqli_query($cn,$sql);
}
else if(isset($_POST["AjtServices"])){
    $sql = 'insert into services(SRname) values ("'.$_POST["AjNomSR"].'")';
    $run = mysqli_query($cn,$sql);
}

//------------------------------------------------modifier------------------------------------------------------------
if(isset($_POST["MdfClient"])){
    $sql = 'update client set Cnt = "'.$_POST["MdCntCL"].'" ,CLname = "'.$_POST["MdNomCL"].'",CLpass = "'.$_POST["MdPassCL"].'" ,idSR = '.$_POST["MdSerCL"].' where idCL = '.$_POST["idCL"].';';
    $run = mysqli_query($cn,$sql);
}
else if(isset($_POST["MdfAdmin"])){

    if($_POST["MdTypeAD"] == 'admin'){
        $sql = 'update admin set Cnt = "'.$_POST["MdCntAD"].'",Adname = "'.$_POST["MdNomAD"].'",Adpass = "'.$_POST["MdPassAD"].'",typeAD = "'.$_POST["MdTypeAD"].'" ,idSR = '.$_POST["MdSerAD"].',idM = 1 where idAD = '.$_POST["idAD"].'';
    }else{
        $sql = 'update admin set Cnt = "'.$_POST["MdCntAD"].'",Adname = "'.$_POST["MdNomAD"].'",Adpass = "'.$_POST["MdPassAD"].'",typeAD = "'.$_POST["MdTypeAD"].'" ,idSR = '.$_POST["MdSerAD"].',idM = '.$_POST["MdMagAD"].' where idAD = '.$_POST["idAD"].'';
    }
    $run = mysqli_query($cn,$sql);
}
else if(isset($_POST["MdfServices"])){
    $sql = 'update services set SRname = "'.$_POST["MdNomSR"].'" where idSR = '.$_POST["idSR"].'';
    $run = mysqli_query($cn,$sql);
}

//------------------------supprimer---------------------------------
if(isset($_POST["table"]) && isset($_POST["nomColumn"]) && isset($_POST["whereIdDelete"])){

    if($_POST["table"]=="services"){ $sql= "update services set activation = false where `idSR` =".strip_tags($_POST["whereIdDelete"])." "; }
    else {$sql= "delete from ".strip_tags($_POST["table"])." where `".strip_tags($_POST["nomColumn"])."` =".strip_tags($_POST["whereIdDelete"])."";}

    $req = mysqli_query($cn,$sql);
}



?>