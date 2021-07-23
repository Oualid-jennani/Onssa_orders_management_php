<?php 
class ligneCommande {
    public $Designation;
    public $quantite;

    public static $ligneC = array();

    function __construct($Designation,$quantite){
        $this->Designation = $Designation;
        $this->quantite = $quantite;
    }
}

class ligneBonCommande {
    public $Designation;
    public $qtEntree;
    public $nouveauPrix;

    public static $ligneC = array();

    function __construct($Designation,$qtEntree,$nouveauPrix){
        $this->Designation = $Designation;
        $this->qtEntree = $qtEntree;
        $this->nouveauPrix = $nouveauPrix;
    }
}

class ligneBonAppr {
    public $Designation;
    public $qtSortie;

    public static $ligneC = array();

    function __construct($Designation,$qtSortie){
        $this->Designation = $Designation;
        $this->qtSortie = $qtSortie;
    }
}
?>