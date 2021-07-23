var idF = null,idC = null,idDS = null;
var AddF = false,AddC = false,AddDS = false;
var DsQT = null,QuantiteR = 0;
var qtEntree = false ,qtSortie = false;

//----------- Ajouter input ---------------------
var AjNomF = document.getElementById("AjNomF");

var AjFamilleCT = document.getElementById("AjFamilleCT");
var AjNomC = document.getElementById("AjNomC");

var AjFamilleDS = document.getElementById("AjFamilleDS");
var AjCategorieDS = document.getElementById("AjCategorieDS");
var AjStockDS = document.getElementById("AjStockDS");
var AjNomDS = document.getElementById("AjNomDS");
var AjUniteDS = document.getElementById("AjUniteDS");
var AjCodeDS = document.getElementById("AjCodeDS");
var AjPrixDS = document.getElementById("AjPrixDS");
var AjQtDs = document.getElementById("AjQtDs");
var AjValDs = document.getElementById("AjValDs");
var AjimageDs = document.getElementById("AjimageDs");

//----------- Update input ---------------------

var MdNomF = document.getElementById("MdNomF");

var MdFamilleCT = document.getElementById("MdFamilleCT");
var MdNomC = document.getElementById("MdNomC");

var MdFamilleDS = document.getElementById("MdFamilleDS");
var MdCategorieDS = document.getElementById("MdCategorieDS");
var MdStockDS = document.getElementById("MdStockDS");
var MdNomDS = document.getElementById("MdNomDS");
var MdUniteDS = document.getElementById("MdUniteDS");
var MdCodeDS = document.getElementById("MdCodeDS");
var MdPrixDS = document.getElementById("MdPrixDS");
var MdQtDs = document.getElementById("MdQtDs");
var MdValDs = document.getElementById("MdValDs");


//----------- input QT entree et sortir---------------------

var Ebc = document.getElementById("Ebc");

var Edate = document.getElementById("Edate");

var Efamille = document.getElementById("Efamille");
var Ecategorie = document.getElementById("Ecategorie");
var Edesignation = document.getElementById("Edesignation");

var EntreQT = document.getElementById("EntreQT");

var EQtDs = document.getElementById("EQtDs");

//----new price enter --------
var EnprixDs = document.getElementById("EnprixDs");

var QtRestanteE = document.getElementById("QtRestanteE");
var inpConfermBC = document.getElementById("inpConfermBC");


var tableEntre = [];


var idBA = document.getElementById("idBA");
var newdate = document.getElementById("newdate");



function clear(){
    $('.AJT-MDF input[type="text"],.AJT-MDF input[type="number"]').val('');
}

function clearBon(){
    Ebc.value = "";idBA.value = "";Edate.value = "";Sdate.value = "";
}


//--------------------------------------------------------------------------------------------------------------------------------------------------------------------
function hideOptionEntre(){
    var count = 0;
    //-----------------------------------------------------
    var selectHide = document.getElementById("Edesignation");
    for (var i = 0; i < selectHide.length; i++) {

        if(tableEntre.includes(selectHide.options[i].value)){
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
    if(count == selectHide.length ){Edesignation.value = null;}
    
    $.post('CodeValid.php',{ShowidDSQT:Edesignation.value}, function(data){QtRestanteE.innerHTML= data; QuantiteR = parseInt(data); });

    
}



function delTable_E_S(){
    tableEntre = [];
}



//--------------------------------------------------------------------------------------------------------------------------------------------------------------------



var loadFile = function(event) {
    var reader = new FileReader();
    reader.onload = function(){
      var output = document.getElementById('FT');
      output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
};




$(document).ready(function(){
    
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
    
    
    
    //-------Entree & sortie Quantité----------------------------------------------
    
    //-------Entree----------------------------------------------
    $('#EbtnQT').click(function(){
        
        var testInp = true;
        var QTchange = 0;
        
        if(EQtDs.value == "" || isNaN(EQtDs.value) || EQtDs.value < 0){
            EQtDs.className += " Eroor";
            testInp = false;
        }

        if(EnprixDs.value == "" || isNaN(EnprixDs.value) || EnprixDs.value < 0){
            EnprixDs.className += " Eroor";
            testInp = false;
        }
        

        QTchange = parseInt(EQtDs.value) + QuantiteR ;
        console.log(QTchange);

        if(QTchange < 0){
            testInp = false;
            alert("Taper un autre Qanntité");
        }


        if(testInp){

            $.post('CodeValid.php',{addDS:Edesignation.value,qtEntree:EQtDs.value,nouveauPrix:EnprixDs.value},function(){});
            
            //-----------------------------------
            tableEntre.push(Edesignation.value);
            hideOptionEntre();
            //-----------------------------------
            
            clear();
        }      
        
    });
    $('#inpConfermBC').click(function(){

        var testInp = true;

        if(Ebc.value == ""){
            Ebc.className += " Eroor";
            testInp = false;
        }

        if(Edate.value == ""){
            Edate.className += " Eroor";
            testInp = false;
        }


        if(testInp){
            $.post('CodeValid.php',{ajouterBcomN:Ebc.value,dateEntree:Edate.value},function(){remplirTabStockDsEntree();});
            clear();
            clearBon();
            $('.QTE-QTS').hide();
            
            
            delTable_E_S();
        }

    });
    //-------Entree----------------------------------------------
    
    
    //-------Sortie change date----------------------------------------------

    $('#ChangeBADate').click(function(){

        var testInp = true;

        if(idBA.value == ""){
            idBA.className += " Eroor";
            testInp = false;
        }

        if(Sdate.value == ""){
            Sdate.className += " Eroor";
            testInp = false;
        }


        if(testInp){
            $.post('CodeValid.php',{updateBA:idBA.value,dateSortie:Sdate.value},function(data){remplirTabStockDsSorie();console.log(data)});
            clear();
            clearBon();
            $('.QTE-QTS').hide();
            
            delTable_E_S();
        }

    });
    //-------Sortie----------------------------------------------
    //---------Annuler BC & BA -------------------------------------------------------
    $('.inpAnnulerBC,.inpAnnulerBA').click(function(){
        $('.QTE-QTS').hide();
        $.post('CodeValid.php',{inpAnnulerBABC:"true"});
        clear();
        
        delTable_E_S();
    })
    //---------Annuler -------------------------------------------------------
    //-------Entree & sortie Quantité----------------------------------------------
  
    
    
    
    
    
    //-------Supprimer--------------------------------------------------------------
    $('.tableMagasinier').on('click','.btnEdit,.btnDelete',function(){
        $('.btnEdit,.btnDelete').parent().parent('tr').removeClass('selected');
        $(this).parent().parent('tr').addClass('selected');
    });
    
    $('.tableMagasinier > tbody').on('click','tr', function(){
        $('.tableMagasinier tbody tr').removeClass('selected');
        $(this).addClass('selected');
    });

    $('#hideDelete').click(function(){
        $('.dialog-delete').fadeOut(300);
    });
    $('#DeleteFalse').click(function(){
        $('.dialog-delete').fadeOut(300);
    });
    $('#DeleteTrue').click(function(){
        
        if(idF != null){
            $.post('CodeValid.php',{table:"famille",nomColumn:"idF",whereIdDelete:idF});
            $('.selected').hide();
        }else if (idC != null){
            $.post('CodeValid.php',{table:"categorie",nomColumn:"idC",whereIdDelete:idC});
            $('.selected').hide();
        }else if (idDS != null){
            $.post('CodeValid.php',{table:"Designation",nomColumn:"idDS",whereIdDelete:idDS});
            $('.selected').hide();
        }
        
        $('.dialog-delete').fadeOut(300);
    });
    //-------Supprimer--------------------------------------------------------------
    
    
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
        if($(this).val() < 0){$(this).val(0)}
    })
    //-------------------------------------------
    

    
    
    
    //-------Ajouter--------------------------------------------------------------
    


    $('#AjouterFamille').click(function(){
        $('#AjtF').fadeIn(300);
        AddFamille();
    })
    $('#AjouterCategorie').click(function(){
        $('#AjtC').fadeIn(300);
        AddCategorie()
    })
    $('#AjouterDesignation').click(function(){
        $('#AjtDS').fadeIn(300);
        AddDs();
    })
    
    $('.BtnAjt').click(function(){
       
        
        if(AddF == true){
            //-------------------------------
            var testInp = true;
            
            if(AjNomF.value == ""){
                AjNomF.className += " Eroor";
                testInp = false;
            }
            
            if(testInp){
                $.post('CodeValid.php',{AjtFamille:"true",AjNomF:AjNomF.value},function(){remplirTableFamille();});
                $('.AJT-MDF').hide();
                clear();
            }
            
        }else if (AddC == true){
            //------------------------------
            
            var testInp = true;
            
            if(isNaN(AjFamilleCT.value)){
                AjFamilleCT.className += " Eroor";
                testInp = false;
            }
            if(AjNomC.value == ""){
                AjNomC.className += " Eroor";
                testInp = false;
            }
            
            if(testInp){
                $.post('CodeValid.php',{AjtCategorie:"true",AjNomC:AjNomC.value,AjtIDF:AjFamilleCT.value},function(){remplirTableCategorie();});
                $('.AJT-MDF').hide();
                clear();
            }
            
        }else if (AddDS == true){
            //------------------------------

            var testInp = true;
            
            if(isNaN(AjFamilleDS.value)){
                AjFamilleDS.className += " Eroor";
                testInp = false;
            }
            if(isNaN(AjCategorieDS.value)){
                AjCategorieDS.className += " Eroor";
                testInp = false;
            }
            if(isNaN(AjStockDS.value)){
                AjStockDS.className += " Eroor";
                testInp = false;
            }
            if(AjNomDS.value == ""){
                AjNomDS.className += " Eroor";
                testInp = false;
            }
            if(isNaN(AjUniteDS.value)){
                AjUniteDS.className += " Eroor";
                testInp = false;
            }
            if(AjCodeDS.value == ""){
                AjCodeDS.className += " Eroor";
                testInp = false;
            }
            if(AjPrixDS.value == "" || isNaN(AjPrixDS.value)){
                AjPrixDS.className += " Eroor";
                testInp = false;
            }
            if(AjQtDs.value == "" || isNaN(AjQtDs.value)){
                AjQtDs.className += " Eroor";
                testInp = false;
            }
            if(AjValDs.value == "" || isNaN(AjValDs.value)){
                AjValDs.className += " Eroor";
                testInp = false;
            }
            
            if(testInp){
                var data = new FormData();

                jQuery.each(jQuery('#myFileInput')[0].files, function(i, file) {
                    data.append('image-'+i, file);
                });

                data.append('AjtDesignation',true);
                data.append('AjCategorieDS',AjCategorieDS.value);
                data.append('AjStockDS',AjStockDS.value);
                data.append('AjNomDS',AjNomDS.value);
                data.append('AjUniteDS',AjUniteDS.value);
                data.append('AjCodeDS',AjCodeDS.value);
                data.append('AjPrixDS',AjPrixDS.value);
                data.append('AjQtDs',AjQtDs.value);
                data.append('AjValDs',AjValDs.value);


                $.ajax({
                    url: "CodeValid.php",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    method: 'POST',
                    type: 'POST',
                    success: function (data) {
                        clear();
                        $('.AJT-MDF').hide();
                        remplirTableDesignation();
                    }
                });           
            }
            
            
        } 
    })
    //-------Ajouter--------------------------------------------------------------
    
   
    
    //-------Modifier--------------------------------------------------------------
    
    $('.BtnMd').click(function(){
       
        
        if(idF != null){
            //-------------------------------
            var testInp = true;
            
            if(MdNomF.value == ""){
                MdNomF.className += " Eroor";
                testInp = false;
            }
            
            if(testInp){
                $.post('CodeValid.php',{MdFamille:"true",idF:idF,MdNomF:MdNomF.value},function(){remplirTableFamille();});
                $('.AJT-MDF').hide();
                clear();
                
            }
            
        }else if (idC != null){
            //------------------------------
            
            var testInp = true;
            
            if(isNaN(MdFamilleCT.value)){
                MdFamilleCT.className += " Eroor";
                testInp = false;
            }
            if(MdNomC.value == ""){
                MdNomC.className += " Eroor";
                testInp = false;
            }
            
            if(testInp){
                $.post('CodeValid.php',{MdCategorie:"true",idC:idC,MdNomC:MdNomC.value,MdIDF:MdFamilleCT.value},function(){remplirTableCategorie();});
                $('.AJT-MDF').hide();
                clear();
            }
            
        }else if (idDS != null){
            //------------------------------
            var testInp = true;
            
            if(isNaN(MdFamilleDS.value)){
                MdFamilleDS.className += " Eroor";
                testInp = false;
            }
            if(isNaN(MdCategorieDS.value)){
                AjCategorieDS.className += " Eroor";
                testInp = false;
            }
            if(isNaN(MdStockDS.value)){
                MdStockDS.className += " Eroor";
                testInp = false;
            }
            if(MdNomDS.value == ""){
                AjNomDS.className += " Eroor";
                testInp = false;
            }
            if(isNaN(MdUniteDS.value)){
                MdUniteDS.className += " Eroor";
                testInp = false;
            }
            if(MdCodeDS.value == ""){
                MdCodeDS.className += " Eroor";
                testInp = false;
            }
            if(MdPrixDS.value == "" || isNaN(MdPrixDS.value)){
                MdPrixDS.className += " Eroor";
                testInp = false;
            }
            if(MdQtDs.value == "" || isNaN(MdQtDs.value)){
                MdQtDs.className += " Eroor";
                testInp = false;
            }
            if(MdValDs.value == "" || isNaN(MdValDs.value)){
                MdValDs.className += " Eroor";
                testInp = false;
            }

            if(testInp){
                $.post('CodeValid.php',{MdDesignation:"true",idDS:idDS,MdCategorieDS:MdCategorieDS.value,MdStockDS:MdStockDS.value,MdNomDS:MdNomDS.value,MdUniteDS:MdUniteDS.value,MdCodeDS:MdCodeDS.value,MdPrixDS:MdPrixDS.value,MdQtDs:MdQtDs.value,MdValDs:MdValDs.value},function(data){console.log(data);remplirTableDesignation();});
                $('.AJT-MDF').hide();
                clear();
            }
        } 
    })
    
    //-------Modifier--------------------------------------------------------------
    
    
    //-------Recherche-------------------------------------------------------------
    
    
    
    
    
    $("#EntreSearsh").on("keyup", function() {
         
        var searsh = $(this).val().toLowerCase();

        $("#rawStockEntree tr").not($('#rawStockEntree table tr')).filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(searsh) > -1)
        });
        
    });
    
    $("#SorteSearsh").on("keyup", function() {
         
        var searsh = $(this).val().toLowerCase();

        $("#rawStockSortie tr").not($('#rawStockSortie table tr')).filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(searsh) > -1)
        });
        
    });
    
    
    $("#STDSsearsh").on("keyup", function() {

        var searsh = $(this).val().toLowerCase();

        $("#RowSTdesignation tr").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(searsh) > -1)
        });
        
    });
    
    $("#FMsearsh").on("keyup", function() {

        var searsh = $(this).val().toLowerCase();

        $("#rawFamille tr").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(searsh) > -1)
        });
        
    });
    
    $("#CTsearsh").on("keyup", function() {

        var searsh = $(this).val().toLowerCase();

        $("#rawCategorie tr").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(searsh) > -1)
        });
        
    });
    
    $("#DSsearsh").on("keyup", function() {

        var searsh = $(this).val().toLowerCase();

        $("#rawDesignation tr").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(searsh) > -1)
        });
        
    });
    //-------Recherche-------------------------------------------------------------
    
    
    
    //---------Annuler -------------------------------------------------------
    $('.AN').click(function(){
        $('.AJT-MDF').hide();
        clear();
    })
 
});




//-------Entree & sortie Quantité----------------------------------------------


EntreQT.onclick = function (){
    qtEntree = true;
    qtSortie = false;
    
    $.post('CodeValid.php',{remplireSelF:"true"}, function(data){Efamille.innerHTML= data;EchangeCT();});
    Efamille.onchange = function(){EchangeCT();}
    
    function EchangeCT(){$.post('CodeValid.php',{table:"categorie",nomColumn:"idF",whereVal:Efamille.value}, function(data){Ecategorie.innerHTML= data; EchangeDS();});}
    Ecategorie.onchange = function(){EchangeDS();}
     
    function EchangeDS(){$.post('CodeValid.php',{table:"Designation",nomColumn:"idC",whereVal:Ecategorie.value}, function(data){Edesignation.innerHTML= data; hideOptionEntre(); EchangeDSQT(); });}
    Edesignation.onchange = function(){EchangeDSQT();}
    
    
    function EchangeDSQT(){$.post('CodeValid.php',{ShowidDSQT:Edesignation.value}, function(data){QtRestanteE.innerHTML= data; QuantiteR = parseInt(data); });}

    
    $('#QTE').fadeIn(400);
}

SorteQT.onclick = function (){
    qtSortie = true;
    qtEntree = false;
    
    $.post('CodeValid.php',{remplireSelBA:"true"}, function(data){idBA.innerHTML= data;});

    $('#QTS').fadeIn(400);
}

//-------Entree & sortie Quantité----------------------------------------------






//-------Supprimer--------------------------------------------------------------
function DeleteFamille(idf){
    idF = idf;
    idC = null;
    idDS = null;
    $('.dialog-delete').fadeIn(400);
}

function DeleteCategorie(idc){
    idC = idc;
    idF = null;
    idDS = null;
    $('.dialog-delete').fadeIn(400);
}

function DeleteDs(idDs){
    idDS = idDs;
    idF = null;
    idC = null;
    $('.dialog-delete').fadeIn(400);
}
//-------Supprimer--------------------------------------------------------------


//-------Modifier--------------------------------------------------------------------------------------------------------------------------------

function EditFamille(idf){
    idF = idf;
    idC = null;
    idDS = null;
    
    $('#MdfF').fadeIn(400);
}


function EditCategorie(idc){
    idC = idc;
    idF = null;
    idDS = null;
    $.post('CodeValid.php',{remplireSelF:"true"}, function(data){MdFamilleCT.innerHTML = data;  /* 1 */ SelectedMd_FM_CT();  });
    $('#MdfC').fadeIn(400);
}



function EditDs(idDs){
    idDS = idDs;
    idF = null;
    idC = null;
    
    $.post('CodeValid.php',{remplireSelF:"true"}, function(data){MdFamilleDS.innerHTML= data; SelectedMd_FM_DS(); changeCT();});
    
    function changeCT(){$.post('CodeValid.php',{table:"categorie",nomColumn:"idF",whereVal:MdFamilleDS.value}, function(data){MdCategorieDS.innerHTML= data; SelectedMd_CT_DS(); });}
    
    MdFamilleDS.onchange = function(){changeCT();}
    
    
    
    
    $.post('CodeValid.php',{remplireSelStock:"true",idDS:idDs}, function(data){MdStockDS.innerHTML= data;});
    $.post('CodeValid.php',{remplireUniteMesure:"true",idDS:idDs}, function(data){MdUniteDS.innerHTML= data;});
    
    $('#MdfDS').fadeIn(400);
}

//-------function show detai row mdifier in select ----------------------

//--------CT--------
/* 1 */ 
function SelectedMd_FM_CT(){
   for (var i = 0; i < MdFamilleCT.length; i++) {

        if(MdFamilleCT.options[i].value == selFamille.value){
            MdFamilleCT.options[i].selected = true;
            break;
        }
    }
}

//--------DS--------
function SelectedMd_FM_DS(){
   for (var i = 0; i < MdFamilleDS.length; i++) {

        if(MdFamilleDS.options[i].value == selFamilleCT.value){
            MdFamilleDS.options[i].selected = true;
            break;
        }
    }
}
function SelectedMd_CT_DS(){
   for (var i = 0; i < MdCategorieDS.length; i++) {

        if(MdCategorieDS.options[i].value == selCategorie.value){
            MdCategorieDS.options[i].selected = true;
            break;
        }
    }
}
//-----------------------------------------------------------------------


//-------function show detai row mdifier in input ----------------------
var rawFamilleMd = document.getElementById("rawFamille");
var rawCategorieMd = document.getElementById("rawCategorie");
var rawDesignationMd = document.getElementById("rawDesignation");



function selectValueMd(){

    for(var i = 0 ;i  < rawFamilleMd.rows.length; i++){
        rawFamilleMd.rows[i].onclick = function(){
            MdNomF.value = this.cells[0].innerHTML;
        }
    }

    
    for(var i = 0 ;i  < rawCategorieMd.rows.length; i++){
        rawCategorieMd.rows[i].onclick = function(){
            MdNomC.value = this.cells[0].innerHTML;
        }
    }

    
    for(var i = 0 ;i  < rawDesignationMd.rows.length; i++){
        rawDesignationMd.rows[i].onclick = function(){
            MdNomDS.value = this.cells[0].innerHTML;
            MdCodeDS.value = this.cells[2].innerHTML;
            MdPrixDS.value = this.cells[3].innerHTML;
            MdQtDs.value = this.cells[4].innerHTML;
            MdValDs.value = this.cells[6].innerHTML;
        }
    }
    
}
//-----------------------------------------------------------------------

//-------Modifier--------------------------------------------------------------------------------------------------------------------------------




//-------Ajouter--------------------------------------------------------------
function AddFamille(){
    AddF = true;
    AddC = false;
    AddDS = false;
    
}

function AddCategorie(){
    AddC = true;
    AddF = false;
    AddDS = false;
    $.post('CodeValid.php',{remplireSelF:"true"}, function(data){AjFamilleCT.innerHTML = data;});
}


function AddDs(){
    AddDS = true;
    AddF = false;
    AddC = false;
    
    $.post('CodeValid.php',{remplireSelF:"true"}, function(data){AjFamilleDS.innerHTML= data;changeCT();});
    
    function changeCT(){$.post('CodeValid.php',{table:"categorie",nomColumn:"idF",whereVal:AjFamilleDS.value}, function(data){AjCategorieDS.innerHTML= data;});}
    
    AjFamilleDS.onchange = function(){changeCT();}
    
    $.post('CodeValid.php',{remplireSelStock:"true"}, function(data){AjStockDS.innerHTML= data;});
    $.post('CodeValid.php',{remplireUniteMesure:"true"}, function(data){AjUniteDS.innerHTML= data;});
}
//-------Ajouter--------------------------------------------------------------






//----------------------------Afichage-------------------------------------
//-----------------------------------------------------------------

var rawStockSortie = document.getElementById("rawStockSortie");
function remplirTabStockDsSorie(){$.post('CodeValid.php',{rmplireTabSortie:"true"}, function(data){rawStockSortie.innerHTML= data;}); }
remplirTabStockDsSorie();

var rawStockEntree = document.getElementById("rawStockEntree");
function remplirTabStockDsEntree(){$.post('CodeValid.php',{rmplireTabEntree:"true"}, function(data){rawStockEntree.innerHTML= data;}); }
remplirTabStockDsEntree();


//-----------------------------------------------------------------
//-----------------------------------------------------------------
var rawFamille = document.getElementById("rawFamille");
function remplirTableFamille(){$.post('CodeValid.php',{remplireTabF:"true"}, function(data){rawFamille.innerHTML= data; selectValueMd(); }); }
remplirTableFamille();
//------------------------------------------------------------------------------------------

var selFamille = document.getElementById("selFamille");
function remplirSelFamille(){$.post('CodeValid.php',{remplireSelF:"true"}, function(data){selFamille.innerHTML = data;remplirTableCategorie();});}
remplirSelFamille();

var rawCategorie = document.getElementById("rawCategorie");
function remplirTableCategorie(){$.post('CodeValid.php',{whereValFamille:selFamille.value}, function(data){rawCategorie.innerHTML= data; selectValueMd(); });}

selFamille.onchange = function(){remplirTableCategorie();};
//-----------------------------------------------------------------

var selFamilleCT = document.getElementById("selFamilleCT");
function remplirSelFamilleCT(){$.post('CodeValid.php',{remplireSelF:"true"}, function(data){selFamilleCT.innerHTML = data;remplirCategorie();});}
remplirSelFamilleCT();

var selCategorie = document.getElementById("selCategorie");
var rawDesignation = document.getElementById("rawDesignation");

function remplirCategorie(){$.post('CodeValid.php',{table:"categorie",nomColumn:"idF",whereVal:selFamilleCT.value}, function(data){selCategorie.innerHTML= data; remplirTableDesignation();});}

function remplirTableDesignation(){$.post('CodeValid.php',{whereValCategorie:selCategorie.value}, function(data){rawDesignation.innerHTML= data;selectValueMd();});}

selFamilleCT.onchange = function(){remplirCategorie();};
selCategorie.onchange = function(){remplirTableDesignation();};

//-----------------------------------------------------------------
































document.getElementById("stock").style.display = "block";

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


document.getElementById("Entree").style.display = "block";
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
}






  

        





