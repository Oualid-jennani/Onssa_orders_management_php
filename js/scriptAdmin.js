var idCL = null,idAD = null,idSR = null;
var AddCL = false,AddAD = false,AddSR = false;
var selectTP = "",selectMG = "",selectSR = "";



//----------- Ajouter input ---------------------
var AjCntCL = document.getElementById("AjCntCL");
var AjNomCL = document.getElementById("AjNomCL");
var AjPassCL = document.getElementById("AjPassCL");
var AjSerCL = document.getElementById("AjSerCL");


var AjCntAD = document.getElementById("AjCntAD");
var AjNomAD = document.getElementById("AjNomAD");
var AjPassAD = document.getElementById("AjPassAD");
var AjTypeAD = document.getElementById("AjTypeAD");
var AjMagAD = document.getElementById("AjMagAD");
var AjSerAD = document.getElementById("AjSerAD");


var AjNomSR = document.getElementById("AjNomSR");


//----------- Modifier input ---------------------
var MdCntCL = document.getElementById("MdCntCL");
var MdNomCL = document.getElementById("MdNomCL");
var MdPassCL = document.getElementById("MdPassCL");
var MdSerCL = document.getElementById("MdSerCL");


var MdCntAD = document.getElementById("MdCntAD");
var MdNomAD = document.getElementById("MdNomAD");
var MdPassAD = document.getElementById("MdPassAD");
var MdTypeAD = document.getElementById("MdTypeAD");
var MdMagAD = document.getElementById("MdMagAD");
var MdSerAD = document.getElementById("MdSerAD");


var MdNomSR = document.getElementById("MdNomSR");






function clear(){
    $('.AJT-MDF input[type="text"],.AJT-MDF input[type="number"]').val('');
}

AjTypeAD.onchange = function(){
    if(this.value == "admin"){
        AjMagAD.options[0].selected = true;
        AjMagAD.disabled = true;
    }else{
        AjMagAD.options[0].disabled = true;
        AjMagAD.options[1].selected = true;
        AjMagAD.disabled = false;
    }
}
MdTypeAD.onchange = function(){
    if(this.value == "admin"){
        MdMagAD.options[0].selected = true;
        MdMagAD.disabled = true;
    }else{
        MdMagAD.options[0].disabled = true;
        MdMagAD.options[1].selected = true;
        MdMagAD.disabled = false;
    }
}





$(document).ready(function(){
    
    //-------------------------------------------
    $('input').change(function(){
        $(this).removeClass('Eroor');
    })
    $('input').keyup(function(){
        $(this).removeClass('Eroor');
    })
    $('select').change(function(){
        $(this).removeClass('Eroor');
    })
    $('input[type=number]').change(function(){
        if($(this).val() < 1){$(this).val(1)}
    })
    //-------------------------------------------
    
    //----------Window Hider----------------
    var HeightHeder = $('.header').innerHeight();
    $('html').css({'padding-top':''+HeightHeder+'px'})
    
    var HeightFooter = $('.footer').innerHeight();
    $('html').css({'padding-bottom':''+HeightFooter+'px'})
    
    $(window).on('resize', function(){
        
        var HeightHeder = $('.header').innerHeight();
        $('html').css({'padding-top':''+HeightHeder+'px'})
        
        var HeightFooter = $('.footer').innerHeight();
        $('html').css({'padding-bottom':''+HeightFooter+'px'})
    });
    //---------------------------------------
    $('.tableMagasinier').on('click','.btnEdit,.btnDelete',function(){
        $('.btnEdit,.btnDelete').parent().parent('tr').removeClass('selected');
        $(this).parent().parent('tr').addClass('selected');
    });
    
    $('.tableMagasinier > tbody').on('click','tr', function(){
        $('.tableMagasinier tbody tr').removeClass('selected');
        $(this).addClass('selected');
    });
    
    
    
    //-------Supprimer--------------------------------------------------------------
    $('.tableAdmin').on('click','.btnEdit,.btnDelete',function(){
        $('.btnEdit,.btnDelete').parent().parent('tr').removeClass('selected');
        $(this).parent().parent('tr').addClass('selected');
    });
    
    $('.tableAdmin > tbody').on('click','tr', function(){
        $('.tableAdmin tbody tr').removeClass('selected');
        $(this).addClass('selected');
    });

    $('#hideDelete').click(function(){
        $('.dialog-delete').fadeOut(300);
    });
    $('#DeleteFalse').click(function(){
        $('.dialog-delete').fadeOut(300);
    });
    $('#DeleteTrue').click(function(){
        
        if(idCL != null){
            $.post('CodeValid2.php',{table:"client",nomColumn:"idCL",whereIdDelete:idCL});
            $('.selected').hide();
        }else if (idAD != null){
            $.post('CodeValid2.php',{table:"admin",nomColumn:"idAD",whereIdDelete:idAD});
            $('.selected').hide();
        }else if (idSR != null){
            $.post('CodeValid2.php',{table:"services",nomColumn:"idSR",whereIdDelete:idSR});
            $('.selected').hide();
        }
        
        $('.dialog-delete').fadeOut(300);
    });
    //-------Supprimer--------------------------------------------------------------
    
    
    
    //-------Ajouter--------------------------------------------------------------

    
    $('#AjouterClient').click(function(){
        $('#AjtCL').fadeIn(300);
        AddClient();
    })
    $('#AjouterAdmin').click(function(){
        $('#AjtAD').fadeIn(300);
        AddAdmin()
    })
    $('#AjouterServices').click(function(){
        $('#AjtSR').fadeIn(300);
        AddServices();
    })
    
    $('.BtnAjt').click(function(){
       if(AddCL == true){
           var testInp = true;
                      
            if(AjCntCL.value == ""){
                AjCntCL.className += " Eroor";
                testInp = false;
            }
            if(AjNomCL.value == ""){
                AjNomCL.className += " Eroor";
                testInp = false;
            }
            if(AjPassCL.value == ""){
                AjPassCL.className += " Eroor";
                testInp = false;
            }
            if(isNaN(AjSerCL.value)){
                AjSerCL.className += " Eroor";
                testInp = false;
            }
            
           
            if(testInp){
                $.post('CodeValid2.php',{AjtClient:"true",AjCntCL:AjCntCL.value,AjNomCL:AjNomCL.value,AjPassCL:AjPassCL.value,AjSerCL:AjSerCL.value},function(){remplirTableClient();});
                $('.AJT-MDF').hide();
                clear();
            }
           
       }else if(AddAD == true){
           var testInp = true;
           
            if(AjCntAD.value == ""){
                AjCntAD.className += " Eroor";
                testInp = false;
            }
            if(AjNomAD.value == ""){
                AjNomAD.className += " Eroor";
                testInp = false;
            }
            if(AjPassAD.value == ""){
                AjPassAD.className += " Eroor";
                testInp = false;
            }
            if(AjTypeAD.value == ""){
                AjTypeAD.className += " Eroor";
                testInp = false;
            }
            if(isNaN(AjMagAD.value) || AjMagAD.value == ""){
                AjMagAD.className += " Eroor";
                testInp = false;
            }
            if(isNaN(AjSerAD.value)){
                AjSerAD.className += " Eroor";
                testInp = false;
            }
            
           
            if(testInp){
                $.post('CodeValid2.php',{AjtAdmin:"true",AjCntAD:AjCntAD.value,AjNomAD:AjNomAD.value,AjPassAD:AjPassAD.value,AjTypeAD:AjTypeAD.value,AjMagAD:AjMagAD.value,AjSerAD:AjSerAD.value},function(){remplirTableAdmin();});
                $('.AJT-MDF').hide();
                clear();
            }
           
       }else if(AddSR == true){
            var testInp = true;
            
            if(AjNomSR.value == ""){
                AjNomSR.className += " Eroor";
                testInp = false;
            }
            
            if(testInp){
                $.post('CodeValid2.php',{AjtServices:"true",AjNomSR:AjNomSR.value},function(){remplirTableServices();});
                $('.AJT-MDF').hide();
                clear();
            }
       }

    })
    
     //-------Modifier--------------------------------------------------------------

    
    $('.BtnMdf').click(function(){
       if(idCL != null){
           var testInp = true;
                      
            if(MdCntCL.value == ""){
                MdCntCL.className += " Eroor";
                testInp = false;
            }
            if(MdNomCL.value == ""){
                MdNomCL.className += " Eroor";
                testInp = false;
            }
            if(MdPassCL.value == ""){
                MdPassCL.className += " Eroor";
                testInp = false;
            }
            if(isNaN(MdSerCL.value)){
                MdSerCL.className += " Eroor";
                testInp = false;
            }
            
           
            if(testInp){
                $.post('CodeValid2.php',{MdfClient:"true",idCL:idCL,MdCntCL:MdCntCL.value,MdNomCL:MdNomCL.value,MdPassCL:MdPassCL.value,MdSerCL:MdSerCL.value},function(){remplirTableClient();});
                $('.AJT-MDF').hide();
                clear();
            }
           
       }else if(idAD != null){
           var testInp = true;
           
            if(MdCntAD.value == ""){
                MdCntAD.className += " Eroor";
                testInp = false;
            }
            if(MdNomAD.value == ""){
                MdNomAD.className += " Eroor";
                testInp = false;
            }
            if(MdPassAD.value == ""){
                MdPassAD.className += " Eroor";
                testInp = false;
            }
            if(MdTypeAD.value == ""){
                MdTypeAD.className += " Eroor";
                testInp = false;
            }
            if(isNaN(MdMagAD.value) || MdMagAD.value == ""){
                MdMagAD.className += " Eroor";
                testInp = false;
            }
            if(isNaN(MdSerAD.value)){
                MdSerAD.className += " Eroor";
                testInp = false;
            }
            
           
            if(testInp){
                $.post('CodeValid2.php',{MdfAdmin:"true", idAD:idAD,MdCntAD:MdCntAD.value,MdNomAD:MdNomAD.value,MdPassAD:MdPassAD.value,MdTypeAD:MdTypeAD.value,MdMagAD:MdMagAD.value,MdSerAD:MdSerAD.value},function(data){remplirTableAdmin(); console.log(data)});
                $('.AJT-MDF').hide();
                clear();
            }
           
       }else if(idSR != null){
            var testInp = true;
            
            if(MdNomSR.value == ""){
                MdNomSR.className += " Eroor";
                testInp = false;
            }
            
            if(testInp){
                $.post('CodeValid2.php',{MdfServices:"true",idSR:idSR,MdNomSR:MdNomSR.value},function(){remplirTableServices();});
                $('.AJT-MDF').hide();
                clear();
            }
       }

    })
    //-------Ajouter--------------------------------------------------------------
    
    
    //---------Annuler -------------------------------------------------------
    $('.AN').click(function(){
        $('.AJT-MDF').hide();
        $('.AJT-MDF input').removeClass('Eroor');
        clear();
    })
});









//-------Supprimer--------------------------------------------------------------
function DeleteClient(idcl){
    iidCL = idcl;
    idAD = null;
    idSR = null;
    
    $('.dialog-delete').fadeIn(400);
}

function DeleteAdmin(idad){
    idCL = null;
    idAD = idad;
    idSR = null;
    
    $('.dialog-delete').fadeIn(400);
}

function DeleteServices(idsr){
    idCL = null;
    idAD = null;
    idSR = idsr;
    
    $('.dialog-delete').fadeIn(400);
}
//-------Supprimer--------------------------------------------------------------







function AddClient(){
    AddCL = true;
    AddAD = false;
    AddSR = false;
    
    $.post('CodeValid2.php',{ServicesAddMD:"true"}, function(data){AjSerCL.innerHTML = data;});
}
function AddAdmin(){
    AddCL = false;
    AddAD = true;
    AddSR = false;
    
    $.post('CodeValid2.php',{tableMagasin:"true"}, function(data){AjMagAD.innerHTML= data; });
    $.post('CodeValid2.php',{ServicesAddMD:"true"}, function(data){AjSerAD.innerHTML = data;});

}
function AddServices(){
    AddCL = false;
    AddAD = false;
    AddSR = true;
}







//-------Modifier--------------------------------------------------------------------------------------------------------------------------------

function EditClient(idcl){
    idCL = idcl;
    idAD = null;
    idSR = null;
    
    $.post('CodeValid2.php',{ServicesAddMD:"true"}, function(data){MdSerCL.innerHTML = data; SelectedMd_SR_CL(); });
    
    
    $('#MdfCL').fadeIn(400);
}
function EditAdmin(idad){
    idCL = null;
    idAD = idad;
    idSR = null;
    
    $.post('CodeValid2.php',{tableMagasin:"true"}, function(data){MdMagAD.innerHTML= data; });
    $.post('CodeValid2.php',{ServicesAddMD:"true"}, function(data){MdSerAD.innerHTML = data;SelectedMd_TP_AD(); SelectedMd_MG_AD(); SelectedMd_SR_AD()});
    
    $('#MdfAD').fadeIn(400);
}
function EditServices(idsr){
    idCL = null;
    idAD = null;
    idSR = idsr;
    
    $('#MdfSR').fadeIn(400);
}












//-----------------------afichage----------------------------

var rawClient = document.getElementById("rawClient");
function remplirTableClient(){$.post('CodeValid2.php',{TypeUser:"Client"}, function(data){rawClient.innerHTML= data; selectValueMd(); }); }

remplirTableClient();

var rawAdmin = document.getElementById("rawAdmin");
function remplirTableAdmin(){$.post('CodeValid2.php',{TypeUser:"admin"}, function(data){rawAdmin.innerHTML= data; selectValueMd(); }); }

remplirTableAdmin();

var rawServices = document.getElementById("rawServices");
function remplirTableServices(){$.post('CodeValid2.php',{Services:"true"}, function(data){rawServices.innerHTML= data; selectValueMd(); }); }

remplirTableServices();















//-------function show detai row mdifier in select -----------

//--------CL--------
function SelectedMd_SR_CL(){
   for (var i = 0; i < MdSerCL.length; i++) {

        if(MdSerCL.options[i].innerHTML == selectSR){
            MdSerCL.options[i].selected = true;
            break;
        }
    }
}

//--------AD--------
function SelectedMd_TP_AD(){
   for (var i = 0; i < MdTypeAD.length; i++) {

        if(MdTypeAD.options[i].value == selectTP){
            MdTypeAD.options[i].selected = true;
            break;
        }
    }
}


function SelectedMd_MG_AD(){
    for (var i = 0; i < MdMagAD.length; i++) {

        if(MdMagAD.options[i].innerHTML == selectMG){
            MdMagAD.options[i].selected = true;
            break;
        }
    }
    
    
    if(selectMG == "Tout"){
        MdMagAD.disabled = true;
    }else{
        MdMagAD.disabled = false;
    }
    

}


function SelectedMd_SR_AD(){
   for (var i = 0; i < MdSerAD.length; i++) {

        if(MdSerAD.options[i].innerHTML == selectSR){
            MdSerAD.options[i].selected = true;
            break;
        }
    }
}
//-------function show detai row mdifier in select ----------------------


























var rawClientMd = document.getElementById("rawClient");
var rawAdminMd = document.getElementById("rawAdmin");
var rawServicesMd = document.getElementById("rawServices");


function selectValueMd(){

    for(var i = 0 ;i  < rawClientMd.rows.length; i++){
        rawClientMd.rows[i].onclick = function(){
            MdCntCL.value = this.cells[0].innerHTML;
            MdNomCL.value = this.cells[1].innerHTML;
            MdPassCL.value = this.cells[2].innerHTML;
            selectSR = this.cells[3].innerHTML;
        }
    }

    
    for(var i = 0 ;i  < rawAdminMd.rows.length; i++){
        rawAdminMd.rows[i].onclick = function(){
            MdCntAD.value = this.cells[0].innerHTML;
            MdNomAD.value = this.cells[1].innerHTML;
            MdPassAD.value = this.cells[2].innerHTML;
            selectTP = this.cells[3].innerHTML;
            selectMG = this.cells[4].innerHTML;
            selectSR = this.cells[5].innerHTML;
        }
    }

    
    for(var i = 0 ;i  < rawServicesMd.rows.length; i++){
        rawServicesMd.rows[i].onclick = function(){
            MdNomSR.value = this.cells[0].innerHTML;
        }
    }
    
}















var ADmagasin = document.getElementById("ADmagasin");
function remplirMagasin(){$.post('CodeValid2.php',{table:"magasin",nomColumn:"null",whereVal:"null"}, function(data){ADmagasin.innerHTML= data; });}
remplirMagasin()










document.getElementById("ADclients").style.display = "block";

function openTab(evt, TabeName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(TabeName).style.display = "block";
    evt.currentTarget.className += " active";
}



