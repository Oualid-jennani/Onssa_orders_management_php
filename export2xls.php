<?php

require "db.php";
session_start();
if(isset($_SESSION['idM'])) {
    $idM = $_SESSION['idM'];
} else {
    die('$'."_SESSION['idM'] n'est pas defini");
}

if (isset($_POST['action']))
{
    switch($_POST['action'])
    {
        case 'liste_designations':
            exportAll();
            break;
        case 'range_designations':
            exportRange();
            break;
    }
}
    function exportAll()
    {
        global $cn;
    global $idM;
    $filename = "ListeDesignations.csv";         //File Name

    $code = strtolower($_POST['code']);
    $client = strtolower($_POST['client']);
    $qtD = strtolower($_POST['qteD']);
    $qtA = strtolower($_POST['qteA']);



    //create MySQL connection
    $query = "SELECT DISTINCT DS.DScode as Code_designation,DS.DSname as Designation ,DS.DSquantite as Qte_en_Stock, CL.CLname as Client,
                    LC.qtD as Qte_demandee,LC.qtA as Qte_approuvee
                from ligneCommande LC 
                INNER JOIN Designation DS ON LC.idDS = DS.idDS 
                INNER JOIN commandes CM ON LC.idCM = CM.idCM 
                INNER JOIN client CL ON CL.idCL = CM.idCL 
                WHERE CM.idM= " . $idM ;
        
    if($code)
    {
        $query .= " AND DS.DScode LIKE '%".$code."%'";
    }
    if($client)
    {
        $query .= " AND CL.CLname LIKE '%".$client."%'";
    }
    if($qtD)
    {
        $query .= " AND LC.qtD LIKE '%".$qtD."%'";
    }
    if($qtA)
    {
        $query .= " AND LC.qtA LIKE '%".$qtA."%'";
    }

    $query .= " ORDER BY LC.qtD DESC";

    $result = mysqli_query($cn, $query);

    export($result, $filename);
    
  }

    function exportRange()
    {
        global $cn;
        global $idM;
        $filename = "ListeDesignations_plage.csv";         //File Name

       $start_date = $_POST['start_date'];
       $end_date = $_POST['end_date'];

       //$newest_date = mysqli_query($cn,"select MAX(dateCom) from commandes");
        $newest_date = date("Y-m-d");

       //oldest_date = date("Y-m-d", strtotime(mysqli_fetch_array(mysqli_query($cn,"select MIN(dateCom) from commandes"))[0]));
        
         $row = mysqli_fetch_assoc(mysqli_query($cn,"select MIN(dateCom) from commandes"));
        $oldest_date = date("Y-m-d", strtotime($row['MIN(dateCom)']));

            
        $start_date = $start_date == "" ? $oldest_date : $start_date;
        $end_date = $end_date == "" ? $newest_date : $end_date;


        //create MySQL connection
        $query = "SELECT DSname as Designation, COUNT(DISTINCT lnc.idCM) as Total_commandes,SUM(qtA) as Total_Qte_demandees
               FROM ligneCommande lnc 
               INNER JOIN commandes cm on lnc.idCM = cm.idCM 
               INNER JOIN Designation d on lnc.idDS=d.idDS 
               WHERE cm.dateCom BETWEEN '".$start_date."' and '".$end_date."'  
                and cm.idM= ".$idM." GROUP BY lnc.idDS ORDER BY Total_Qte_demandees DESC";


        $result = mysqli_query($cn,$query);
        export($result,$filename);
    }

    function export($result,$filename)
    {
        $type = 'text/plain';

        header('Content-Description: File Transfer'."\n");
        header('Content-Type: application/octet-stream'."\n");
        header('Content-Disposition: attachment; filename="'.basename($filename).'";' );
        header('Content-Transfer-Encoding: '.$type."\n");
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');


        /*******Start of Formatting for Excel*******/
        //define separator (defines columns in excel & tabs in word)
        $step = ";";

        //start of printing column names as names of MySQL fields
        for ($i = 0; $i < mysqli_num_fields($result); $i++) {
            echo mysqli_fetch_field_direct($result,$i)->name . $step;
        }
        print("\n");
        //end of printing column names
        //start while loop to get data
        while($row = mysqli_fetch_row($result))
        {
            $schema_insert = "";
            for($j=0; $j<mysqli_num_fields($result);$j++)
            {
                if(!isset($row[$j]))
                    $schema_insert .= "NULL".$step;
                elseif ($row[$j] != "")
                    $schema_insert .= str_replace(";", ",", "$row[$j]").$step;
                else
                    $schema_insert .= "".$step;
            }
            $schema_insert = str_replace($step."$", "", $schema_insert);
            $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
            $schema_insert .= "\t";
            print(trim($schema_insert));
            print "\n";
        }
    }