<?php
header('Content-Type: application/json');

require "db.php";
$idM = $_POST['idM'];

$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];

//$newest_date = mysqli_query($cn,"select MAX(dateCom) from commandes");
$newest_date = date("Y-m-d");
$oldest_date = date("Y-m-d",2017-01-01);

$data_range = "";

if ($start_date == "" && $end_date == "" )
{
    $start_date = $oldest_date;
    $end_date = $newest_date;
}else if($start_date != "" && $end_date == "" )
{
    $end_date = $newest_date;
}else if($start_date == "" && $end_date != "" )
{
    $start_date = $oldest_date;
}

$data_range = "SELECT DSname, COUNT(DISTINCT lnc.idCM) as countCM,SUM(qtA) as sumQTE 
               FROM ligneCommande lnc 
               INNER JOIN commandes cm on lnc.idCM = cm.idCM 
               INNER JOIN Designation d on lnc.idDS=d.idDS 
               WHERE cm.dateCom BETWEEN '".$start_date."' and '".$end_date."'  
                and cm.idM= ".$idM." GROUP BY lnc.idDS ORDER BY sumQTE DESC";

$result1 = mysqli_query($cn,$data_range);

while($raw = mysqli_fetch_array($result1))
{
    $data[] = $raw;
}
mysqli_close($cn);
echo json_encode($data);
