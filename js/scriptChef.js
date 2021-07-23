var BtnDsCom = document.getElementById("BtnDsCom");
var hideVoirComDs = document.getElementById("hideVoirComDs");


var Vfamille = document.getElementById("Vfamille");
var Vcategorie = document.getElementById("Vcategorie");
var Vdesignation = document.getElementById("Vdesignation");

var raw_detail_ds_com = document.getElementById("raw_detail_ds_com");

var Ul_Ds_Com = document.getElementById("Ul_Ds_Com");

var QtRestanteV = document.getElementById("QtRestanteV");


var idCom;

    
$(document).ready(function(){

    $('#hideChefconfirm').click(function(){
        $('.dialog-Chefconfirm').fadeOut(300);
    });
    $('#ChefconfirmFalse').click(function(){
        $('.dialog-Chefconfirm').fadeOut(300);
    });
    $('#ChefconfirmTrue').click(function(){
        $.post('CodeValid.php',{postenumCommande:idCom,posteConfirm:"true"});
        $('.dialog-Chefconfirm').fadeOut(300);
    });
    
    $(".tableCommande tbody").not($('.tableCommande table tbody')).sortable();

 // Recherche dynamique dans la table statistics
    $('#search_statistics').keyup(function(){

        var search = $(this).val();

        $('.table-statistics tbody tr').hide();

        // Count total search result
        var len = $('.table-statistics tbody tr:not(.notfound) td:contains("'+search+'")').length;

        if(len > 0){
            // Searching text in columns and show match row
            $('.table-statistics tbody tr:not(.notfound) td:contains("'+search+'")').each(function(){
                $(this).closest('tr').show();
            });
        } else {
            $('.notfound').show();
        }
    });

    // Case-insensitive searching
    $.expr[":"].contains = $.expr.createPseudo(function(arg) {
        return function( elem ) {
            return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
        };
    });


     function removeHighlighting(highlightedElements)
     {
        highlightedElements.each(function(){
            var element = $(this);
            element.replaceWith(element.html());
        })
     }
    
    function addHighlighting(element, textToHighlight)
    {
        var text = element.text();
        var highlightedText = '<em style="background-color: yellow">' + textToHighlight + '</em>';
        var newText = text.replace(textToHighlight, highlightedText);
    
        element.html(newText);
    }
  
 $(function(){
        $(".filter_criteria").keyup( function() {

            var code, client, qtA,qtD;

            removeHighlighting($('.table-statistics tbody tr td em'));
            $(".table-statistics tbody tr").each(function(index) {
                $(this).show();
            });

            code = $("#code").val().toLowerCase();
            client = $("#client").val().toLowerCase();
            qtD = $("#qteD").val().toLowerCase();
            qtA = $("#qteA").val().toLowerCase();

            if(code)
            {
                $(".table-statistics tbody tr:visible").each(function(index) {
                    $row = $(this);
                    var $tdElement;

                    $tdElement = $row.find("td:nth-child(1)");
                    var id = $tdElement.text().toLowerCase();

                    if (!(id.includes(code))) {
                        $row.hide();
                    } else {
                        addHighlighting($tdElement, code);
                        $row.show();
                    }
                });
            }
            if(client)
            {
                $(".table-statistics tbody tr:visible").each(function(index) {
                    $row = $(this);
                    var $tdElement;

                    $tdElement = $row.find("td:nth-child(4)");
                    var id = $tdElement.text().toLowerCase();

                    if (!(id.includes(client))) {
                        $row.hide();
                    } else {
                        addHighlighting($tdElement, client);
                        $row.show();
                    }
                });
            }
            if(qtD)
            {
                $(".table-statistics tbody tr:visible").each(function(index) {
                    $row = $(this);
                    var $tdElement;

                    $tdElement = $row.find("td:nth-child(5)");
                    var id = $tdElement.text().toLowerCase();

                    if (!(id.includes(qtD))) {
                        $row.hide();
                    } else {
                        addHighlighting($tdElement, qtD);
                        $row.show();
                    }
                });
            }
            if(qtA)
            {
                $(".table-statistics tbody tr:visible").each(function(index) {
                    $row = $(this);
                    var $tdElement;

                    $tdElement = $row.find("td:nth-child(6)");
                    var id = $tdElement.text().toLowerCase();

                    if (!(id.includes(qtA))) {
                        $row.hide();
                    } else {
                        addHighlighting($tdElement, qtA);
                        $row.show();
                    }
                });
            }

        });
    });


    $('.data_range').change(function()
    {
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            var idM = $('#idM').val();

           
            var httpc = new XMLHttpRequest(); // simplified for clarity
            var url = "get_data.php";
            httpc.open("POST", url, true); // sending as POST
            httpc.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            httpc.send("start_date="+start_date+"&end_date="+end_date+"&idM="+idM);

            httpc.onreadystatechange = function()
            { //Call a function when the state changes.
                if(httpc.readyState == 4 && httpc.status == 200)
                {
           // alert(httpc.responseText); //==> le resultat retourné du fichier get_data.php
                    $('#datarange_body').empty();
                    $('#datarange_total').empty();
                    var data = httpc.responseText;
                    var datarange_html = "";

                    $.each(JSON.parse(data), function(index, value)
                    {
                        datarange_html += "<tr>" +
                            "<td>" + value.DSname + "</td>" +
                            "<td>" + value.countCM + "</td>" +
                            "<td>" + value.sumQTE + "</td>" +
                            "</tr>";
                    });
                    $('#datarange_body').append(datarange_html);
                    $('#datarange_total').append("Total des articles : "+ JSON.parse(data).length);
                }
            };
    })(jQuery);
});




hideVoirComDs.onclick = function(){
    $('#Voir_Com_Ds').fadeOut(400);
}

BtnDsCom.onclick = function (){

    $.post('CodeValid2.php',{ShowDsCom:"true"}, function(data){Ul_Ds_Com.innerHTML= data;});
    
    $.post('CodeValid.php',{remplireSelF:"true"}, function(data){Vfamille.innerHTML= data;VchangeCT();});
    Vfamille.onchange = function(){VchangeCT();}
    
    function VchangeCT(){$.post('CodeValid.php',{table:"categorie",nomColumn:"idF",whereVal:Vfamille.value}, function(data){Vcategorie.innerHTML= data; VchangeDS();});}
    Vcategorie.onchange = function(){VchangeDS();}
     
    function VchangeDS(){$.post('CodeValid.php',{table:"Designation",nomColumn:"idC",whereVal:Vcategorie.value}, function(data){Vdesignation.innerHTML= data; VchangeDSQT();Vchange_detail_ds_com(); });}
    
    Vdesignation.onchange = function(){VchangeDSQT();Vchange_detail_ds_com();}
    
    
    function VchangeDSQT(){$.post('CodeValid.php',{ShowidDSQT:Vdesignation.value}, function(data){QtRestanteV.innerHTML= data;  });}
    
    function Vchange_detail_ds_com(){$.post('CodeValid2.php',{ShowDetailDsCom:Vdesignation.value}, function(data){raw_detail_ds_com.innerHTML= data;  });}

    
    $('#Voir_Com_Ds').fadeIn(400);
}







  function funConfermation(idCM){
    idCom = idCM;
    $('.dialog-Chefconfirm').fadeIn(400);
  }
  function AnnulerConfermation(idCM){
    $.post('CodeValid.php',{postenumCommande:idCM,posteConfirm:"false"});
  }



//----------------------------------------------------------------------------------------------------------------------------------------------------------------------

//-------function script.js------------------------------------
document.getElementById("statistics").style.display = "block";
//-------function script.js------------------------------------

