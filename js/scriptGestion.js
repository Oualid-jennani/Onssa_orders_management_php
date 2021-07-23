var idCom;

$(document).ready(function(){
    
    $(".tableCommande tbody").not($('.tableCommande table tbody')).sortable();
    
    
    $('#hidePassChef').click(function(){
        $('.dialog-PassChef').fadeOut(300);
    });
    $('#PassChefFalse').click(function(){
        $('.dialog-PassChef').fadeOut(300);
    });
    $('#PassChefTrue').click(function(){
        
        $.post('CodeValid.php',{postenumCommande:idCom,postePassChef:"true"});
        $('.dialog-PassChef').fadeOut(300);
        
    });
    
    
    $('#hideNewPassChef').click(function(){
        $('.dialog-New-PassChef').fadeOut(300);
    });
    $('#PassNewChefFalse').click(function(){
        $('.dialog-New-PassChef').fadeOut(300);
    });
    $('#PassNewChefTrue').click(function(){
        
        $.post('CodeValid.php',{postenumCommande:idCom,posteNewPassChef:"true"});
        $('.dialog-New-PassChef').fadeOut(300);
        
    });
    
    
    $(".tableCommande tbody").not($('.tableCommande table tbody')).sortable();
    
    
});
  

var BtnVersion = document.getElementById("BtnVersion");
var inpVersion = document.getElementById("inpVersion");

BtnVersion.onclick = function(){
    if(inpVersion.value != ""){
        $.post('CodeValid.php',{addVersionImpresion:"true",VR:inpVersion.value});
        inpVersion.value = "";
    }else{
        inpVersion.classList.add("error");
    }
}

inpVersion.onkeyup = function(){

    inpVersion.classList.remove("error");
}

document.getElementById("voirCom").style.display = "block";


function quantiteApprove(evt,idCM,idDS){
    $.post('CodeValid.php',{posteidCM:idCM,posteidDS:idDS,postqtA:evt.target.value});
}
function funAccorder(evt,idCM,idDS){
    $.post('CodeValid.php',{posteidCM:idCM,posteidDS:idDS,postAccorder:evt.target.value});
}

function funValidation(evt,idCM){
    $.post('CodeValid.php',{postenumCommande:idCM,postevalidation:evt.target.value},function(data){console.log(data)});
}




function passChef(idCM){
    
    idCom = idCM;
    $('.dialog-PassChef').fadeIn(400);
}

function NewpassChef(idCM){
    
    idCom = idCM;
    $('.dialog-New-PassChef').fadeIn(400);
}



function imprimer(idcm){
    $.post('CodeValid.php',{idCmImprimer:idcm}, function(data){
        
        if(data >16){
            window.open('Imprimer.php?star=1&end=8','_blank');
            setTimeout(function(){window.open('Imprimer.php?star=9&end=16','_blank');}, 500);
            setTimeout(function(){window.open('Imprimer.php?star=16&end=20','_blank');}, 1000);
        }
        else if(data >8){
            
            window.open('Imprimer.php?star=1&end=8','_blank');
            setTimeout(function(){window.open('Imprimer.php?star=9&end=16','_blank');}, 500);
            
        }
        else{
            window.open('Imprimer.php?star=1&end=8','_blank');
        }
  
        /*if(data >16){
            window.open('Imprimer.php?star=1&end=8',"one",'_blank');
            window.open('Imprimer.php?star=9&end=16',"two",'_blank');
            window.open('Imprimer.php?star=16&end=20',"try",'_blank');
        }
        else if(data >8){
            window.open('Imprimer.php?star=1&end=8',"one",'_blank');
            window.open('Imprimer.php?star=9&end=16',"two",'_blank');
        }
        else{
            window.open('Imprimer.php?star=1&end=8',"one",'_blank');
        }*/
        
    });
}
/*
function imprimer(idcm){
  $.post('CodeValid.php',{idCmImprimer:idcm}, function(data){window.open('Imprimer.php','_blank');});
}
*/

//----------------------------------------------------------------------------------------------------------------------------------------------------------------------


//-------function script.js------------------------------------
document.getElementById("voirCom").style.display = "block";
//-------function script.js------------------------------------

