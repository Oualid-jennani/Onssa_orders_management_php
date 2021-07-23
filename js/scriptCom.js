


$(document).ready(function(){
    
    
//----------------message Confirm---------------------------------
    $('#ajouterCom').click(function(){
        $('.dialog-confirm').fadeIn(400);
    });

    $('#hideConfirm').click(function(){
        $('.dialog-confirm').fadeOut(300);
    });
    $('#confirmFalse').click(function(){
        $('.dialog-confirm').fadeOut(300);
    });
    $('#confirmTrue').click(function(){
        ajouterCommande();
    });
//----------------message Annuler---------------------------------
    $('#annulerCom').click(function(){
        $('.dialog-Annuler').fadeIn(400);
    });
    $('#hideAnnuler').click(function(){
        $('.dialog-Annuler').fadeOut(300);
    });
    $('#AnnulerFalse').click(function(){
        $('.dialog-Annuler').fadeOut(300);
    });
    $('#AnnulerTrue').click(function(){
        $.post('CodeValid.php',{inpAnnulerBABC:"true"},function(data){location.reload();});
    });

    
    $(".tableCommande tbody").not($('.tableCommande table tbody')).sortable();
});

//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------

var idM = document.getElementById("magasin");
var idF = document.getElementById("famille");
var idC = document.getElementById("categorie");
var idDS = document.getElementById("Designation");
var unite = document.getElementById("unite");
var imageDesignation = document.getElementById("imageDesignation");

function remplirMagasin(){$.post('CodeValid.php',{table:"magasin",nomColumn:"null",whereVal:"null"}, function(data){idM.innerHTML= data; remplirFamille(); });}
function remplirFamille(){$.post('CodeValid.php',{table:"famille",nomColumn:"idM",whereVal:idM.value}, function(data){idF.innerHTML= data; remplirCategorie(); });}
function remplirCategorie(){$.post('CodeValid.php',{table:"categorie",nomColumn:"idF",whereVal:idF.value}, function(data){idC.innerHTML= data; remplirDesignation(); });}
function remplirDesignation(){$.post('CodeValid.php',{table:"Designation",nomColumn:"idC",whereVal:idC.value}, function(data){idDS.innerHTML= data; hideOptionPanier(); changeUnite(); });}

function changeUnite(){$.post('CodeValid.php',{changeDsUnite:idDS.value}, function(data){unite.innerHTML= data;  changePhotoDs(); });}

function changePhotoDs(){$.post('CodeValid.php',{changePhotoDs:idDS.value}, function(data){imageDesignation.innerHTML= data;});}


//remplir combobox
remplirMagasin();
idM.onchange = function(){remplirFamille();}
idF.onchange = function(){remplirCategorie();}
idC.onchange = function(){remplirDesignation();}

//change Unite
idDS.onchange = function(){changeUnite();}

var qt = document.getElementById("qt");
var ajouterLine = document.getElementById("ajouterLine");
var listPanier = document.getElementById("listPanier");
var Pquantite = document.getElementsByClassName('Pquantite');
var countPanier = document.getElementById("countPanier");
var videP = document.getElementById("videP");
var tablePanier = [];

qt.value = 1 ;
qt.onchange = function(){if(this.value < 1){this.value = 1 ;}}

Pquantite.onchange = function(){if(this.value < 1){this.value = 1 ;}}


$.post('CodeValid.php',{addDS:null,quantite:null}, function(data){listPanier.innerHTML= data;});
$.post('CodeValid.php',{countP:true}, function(data){countPanier.innerHTML= data;});

ajouterLine.onclick = function(){

    if(qt.value > 0 ){
        idM.disabled = true;
        idF.disabled = true;
        
        $.post('CodeValid.php',{countP:true}, function(data){
            if(data == 20){alert("max 20");}
            else{$.post('CodeValid.php',{addDS:idDS.value,quantite:qt.value}, function(data){
                listPanier.innerHTML= data;
                countP(); 
                tablePanier.push(idDS.value);
                hideOptionPanier();
            });}        
        });
        
        

        ajouterCom.disabled = false;
        annulerCom.disabled = false;
    }

}
function changeListP(){
    $.post('CodeValid.php',{addDS:null,quantite:null}, function(data){listPanier.innerHTML= data;countP();});
}

function countP(){
    
    $.post('CodeValid.php',{countP:true}, function(data){countPanier.innerHTML= data;});

}

function hideOptionPanier(){
    var count = 0;
    //-----------------------------------------------------
    var selectHide = document.getElementById("Designation");
    for (var i = 0; i < selectHide.length; i++) {

        if(tablePanier.includes(selectHide.options[i].value)){
            selectHide.options[i].disabled = true;
        }else{selectHide.options[i].disabled = false;}

    }
    //------------------------------------------------------
    for (var i = 0; i < selectHide.length; i++) {

        if(selectHide.options[i].disabled == false){
            selectHide.options[i].selected = true;
            break;
        }
        count++;
    }
    //-----------------------------------------------------
    if(count == selectHide.length ){idDS.value = null;}
    if(tablePanier.length != 0 ){videP.style.display = "none";}else{videP.style.display = "block";}
    
}



var ajouterCom = document.getElementById("ajouterCom");
var annulerCom = document.getElementById("annulerCom");


function ajouterCommande(){ 
    var delaichecked = null; 
    var checkElements = document.getElementsByName('delai');
    for(var i=0; i < checkElements.length; ++i){
          if(checkElements[i].checked){
               delaichecked = checkElements[i].value;
               break;
          }
    }
    $.post('CodeValid.php',{ajouterCom:true,delai:delaichecked,idMagasine:idM.value},function(){location.reload();});

    
}




function delpanier(key,val){
    $.post('CodeValid.php',{deletkey:key},function(data){changeListP();});

    
    tablePanier.splice(tablePanier.indexOf(""+val+""), 1);
    hideOptionPanier();
}

function editqtP(key,DsQt,event){
    $.post('CodeValid.php',{editKey:key,idDsQt:DsQt,valueqt:event.target.value},function(data){});
}


function refreshP(){
    changeListP();
}



//----------------------------------------------------------------------------------------------------------------------------------------------------------------------


//-------function script.js------------------------------------
document.getElementById("COM").style.display = "block";
//-------function script.js------------------------------------


/*
document.getElementById("enCours").style.display = "block";
function InopenTab(evt, InTabeName) {
    var i, Intabcontent, Intablinks;
    Intabcontent = document.getElementsByClassName("Intabcontent");
    for (i = 0; i < Intabcontent.length; i++) {
    Intabcontent[i].style.display = "none";
    }
    Intablinks = document.getElementsByClassName("Intablinks");
    for (i = 0; i < Intablinks.length; i++) {
    Intablinks[i].className = Intablinks[i].className.replace(" active", "");
    }
    document.getElementById(InTabeName).style.display = "block";
    evt.currentTarget.className += " active";
*/

