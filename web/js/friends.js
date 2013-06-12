jQuery(document).ready(function(){
     var param2='"Amigos"';
     var param3=$("#amigos");
     var param1='"/app_dev.php/friends"';
     $("<a href='javascript:void(0);'onclick='sacarEdicionVentana3("+param1+","+param2+")'>Amigos</a>").appendTo(param3);
   
});

  
function sacarEdicionVentana3($ruta,$titulo){
  $.ajax({type: "GET", url: $ruta,
 
 success: function(data){
    $(".fotosamigos").append('<div>'+data+'</div>');
  }});
  return true;
}
    
    
