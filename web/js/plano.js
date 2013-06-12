jQuery(document).ready(function(){
     var param2='"Plano"';
     var param3=$('table.sociedadportada .plano');
     var param4=$('table.sociedadportada .edit');
     for(var x=0; x<param3.length;x++){
         var cadauno=param3[x];     
         var idbb=param4[x].outerHTML;
         var a1=idbb.indexOf('sociedades/');
         var ruta=idbb.substring(a1+11);
         var a2=ruta.indexOf('/show');
         var id=ruta.substr(0,a2);
         var param1='"/app_dev.php/plano/'+id+'"';
         
         var lctextContent='"'+cadauno.textContent+'"';
         var lctextContent2=cadauno.textContent;
         cadauno.textContent="";
         $("<a href='javascript:void(0);'onclick='sacarEdicionVentana2("+param1+","+param2+","+lctextContent+")'>"+lctextContent2+"</a>").appendTo(cadauno);
     }
   
});

function sacarEdicionVentana2($ruta,$titulo,$direccion){
        codeAddress($direccion);
        $("#map_canvas").toggleClass("mapavisible");
        return false;
}
   
function sacarEdicionVentana($ruta,$titulo){
  $.ajax({type: "GET", url: $ruta,
 
 success: function(data){
    $("#edicioncabecera").append('<div>'+data+'</div>');
  }});
  return true;
}
    
    
