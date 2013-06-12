$(function(){
  var clonico=$("a.button green marginright10");
  if(clonico.length==0){
      return;
  }
  var cadenaref=clonico[0].attributes['href'];
  var parametro1='"'+cadenaref.value+'"';
  cadenaref.value="<a href='javascript:void(0);'onclick='sacarEdicionVentana("+parametro1+")'></a>";
})

function sacarEdicionVentana($ruta){
  $.ajax({type: "GET", url: $ruta,
 
 success: function(data){
    $("#edicioncabecera").append('<div>'+data+'</div>');
    $("#edicioncabecera").dialog({
      modal: true,
      closeText: 'hide',
      stack: false,
      title: $titulo,
      draggable: true,
      resizable: false,
      height: 600,
      width: 900,
      show: 'fadeIn',
     
      open: function (event, ui) {
            //$('#ventana div').css('overflow', 'scroll');
      },
      close: function(){
        $("#edicioncabecera div").remove();
      }
    });
  }});
  return true;
}

