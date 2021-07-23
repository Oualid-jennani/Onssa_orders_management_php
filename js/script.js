$(document).ready(function(){
    
    //---------Navigation----------------------
    var toogle = 0;
    $('.MenuHideShow').click(function(){
        
        if(toogle == 0){
            $('.navigation').animate({width:'300px'},100);
            toogle=1;
        }
        else{
            $('.navigation').animate({width:'35px'},100);
            toogle=0;
        }
        $('.ulNavIcon').toggle(50);
        $('.ulNav').toggle(200); 
    });
    //-----------------------------------------
    
    
    
    
     var toogle = 0;
    $('.qtApprove').change(function(){

        if(toogle == 0){
            $('.navigation').animate({width:'300px'},100);
            toogle=1;
        }
        else{
            $('.navigation').animate({width:'35px'},100);
            toogle=0;
        }
        $('.ulNavIcon').toggle(50);
        $('.ulNav').toggle(200);



    });
    
    
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
    
    
    
    
    
    
    
    //---------------Tout les table commande :recherche - toogle row ... ---------------
    
    $(".txtsearsh").on("keyup", function() {
        $(".txtsearsh").val($(this).val());
        if($(this).val()==""){
            $(".tableCommande table").hide(); 
            $(".tableCommande table:first").show(); 
            $(".tableCommande table:first").prev().hide();
        }else{
            $(".tableCommande table").show(); 
        }
        
        
        var searsh = $(this).val().toLowerCase();

        $(".tableCommande tbody tr").not($('.tableCommande table tr')).filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(searsh) > -1)
        });

        $(".tableCommande tr:first").show();
        
    });
    
    var tabletoogle = 0;
    
	
    
    $(".tableCommande table:first").show(); 
    $(".tableCommande table:first").prev().hide();
    
    $(".tableCommande >tbody tr ").not($('.tableCommande table tr')).click(function() {
        if(tabletoogle == 0 && !$(this).hasClass("active")){
            $(".tableCommande table").prev().show();
            $(".tableCommande table").hide();
            $(".tableCommande tr").removeClass('active');
            $(this).children("td").children("table").slideDown(200);
            $(this).children("td").children("table").prev().hide();
            $(this).addClass('active');
        }
    });
    
    
    
    $(".detai").click(function() {
        if(tabletoogle == 0){
            $(".tableCommande table").show();
            $(".tableCommande table").prev().hide();
            $(this).html('&#9084;');
            tabletoogle=1;
        }
        else{
            $(".tableCommande table").hide(); 
            $(".tableCommande table").prev().show();
            $(".tableCommande table:first").show(); 
            $(".tableCommande table:first").prev().hide();
            $(this).html('&#8623;');
            tabletoogle=0;
        }
    });
    //---------------------------------------------------
    


    //-------------Maintenance-----------------------
    
    $('.Maintenance').click(function(){       
        $('.page_Maintenance').show(500);
    });

    $('.exitMaintenance').click(function(){  
        $('.page_Maintenance').hide(500);
    });
    
    $('.iconPanier').click(function(){  
        $('.infoPanier').slideToggle(150);
    });

});


//-------------------------------------------------------------------------------------------------------------------------------------------------------------------

//--------function tab change ------------------------------------------
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
//----------------------------------------------------------------------

/*
var checkPsw = document.getElementById("checkPsw");
var parampsw = document.getElementById("parampsw");
var paramCpsw = document.getElementById("paramCpsw");

checkPsw.onchange = function(){
    
    if(checkPsw.checked){
        parampsw.setAttribute("type", "text");
        paramCpsw.setAttribute("type", "text")
    }else{
        
        parampsw.setAttribute("type", "password");
        paramCpsw.setAttribute("type", "password");
    }
}
*/





//-----------------------------------------------------------------slider---------------------------------------------------------------------------
/*
$(document).ready(function(){

    for(var j = 0 ; j <= all_div-1 ; j++){
        $('.UL1').append('<li></li>');
    }

    $('.UL1 li').eq(all_div-1).animate( {height:'50px',opacity:'1'},1000);
    $('.UL1 li').not($('.UL1 li').eq(all_div-1)).animate( {height:'20px',opacity:'0.6'},1000);
    
    
    var time = setInterval(suiv,15000);
    $('.BTN1').click(function(){
        clearInterval(time);
        pres();
        time = setInterval(suiv,15000);
    });
    $('.BTN2').click(function(){
        clearInterval(time);
        suiv();
        time = setInterval(suiv,15000);
    });
    
    $('.UL1 li').click(function(){
        clearInterval(time);
        i=$(this).index()-1;
        suiv();
        time = setInterval(suiv,15000);
    });
    
    

    $('.MainMenu').hover(function(){
        $('.info').fadeOut(500).css({left: '-10%'});;
    });
    $('.SliderMain').hover(function(){
        $('.info').fadeIn(500).css({left: '10%'});
    });
});
*/
/*
var all_div = $(".Slider div").length;
var i =all_div-1;

function pres(){
    
    $(document).ready(function(){
       if(i<=0){
            i=all_div-1;
        }
        else{i--;}
        $('.Slider div').eq(i).animate( {width:'100%',height:'100%',opacity:'1',left:'0px',top:'0%'});
        $('.Slider div').not($('.Slider div').eq(i)).animate( {width:'0%',height:'0%',opacity:'0.5',left:'50%',top:'50%'});
        $('.UL1 li').eq(i).animate( {height:'50px',opacity:'1'},1000);
        $('.UL1 li').not($('.UL1 li').eq(i)).animate( {height:'20px',opacity:'0.6'},1000);
    });
    
}

function suiv(){
    $(document).ready(function(){
        if(i>=(all_div-1)){
            i=0;
        }
        else{i++;}
        $('.Slider div').eq(i).animate( {width:'100%',height:'100%',opacity:'1',left:'0px',top:'0%'});
        $('.Slider div').not($('.Slider div').eq(i)).animate( {width:'0%',height:'0%',opacity:'0.5',left:'50%',top:'50%'});
        $('.UL1 li').eq(i).animate( {height:'50px',opacity:'1'},1000);
        $('.UL1 li').not($('.UL1 li').eq(i)).animate( {height:'20px',opacity:'0.6'},1000);
    });
    
}
*/
//-----------------------------------------------------------------slider---------------------------------------------------------------------------



