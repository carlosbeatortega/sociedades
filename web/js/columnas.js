      $(function(){
          var dondeponer=$("h3.gridconfigurable");
          var clonico=$("li.lanzagrid a");
          if(dondeponer.length==0 || clonico.length==0){
              return;
          }
          var cadenaref=clonico[0].attributes['href'];
          var cadauno=null;
          var cadena="";
          var parametro1="";
          var parametro2="";
          var columnas;
          var colfilas;
          var columnasprop;
          var x;
          var propiedades=new Array();
          var propiedadfilas=new Array();
          var datafilas=new Array();
          var datacolumnas=new Array();
          var datacolumnaserializadas=""
          var propiedadserializada="";
          var propiedadfilasserializada="";
          var atributoa="";
          var datafilasserializada="";
          var ppvar;
          for(var x=0; x<dondeponer.length;x++){
              cadauno=dondeponer[x];
              cadena=cadauno.attributes['name'];
              if(cadena){
                   propiedades=[];
                   parametro1='"'+cadenaref.value+','+cadena.value+'"';
                   parametro2='"Columnas '+cadena.value+'"';
                   cadauno=$("h3.gridconfigurable#"+cadena);
                   columnas=$("table td[name='"+cadena.value+"']").parent().parent().parent().find("thead tr th");
                   colfilas=$("table td[name='"+cadena.value+"']").parent().parent().parent().find("tbody tr:first").find("td");
                   columnasprop=$("table td[name='"+cadena.value+"']").parent().find("td");
                   for(x=0;x<columnas.length;x++){
                       propiedades.push('', '','');
                       propiedades[x*3]=columnas[x].outerHTML;
                       propiedades[(x*3)+1]=columnas[x].textContent;
                       propiedades[(x*3)+2]="";
                       ppvar=columnasprop[x];
                       if(columnasprop[x].attributes['name']){
                        propiedades[(x*3)+2]=columnasprop[x].attributes['name'].value;
                       }
                   }
                   propiedadserializada=JSON.stringify(propiedades);  //serialize(propiedades);
                   propiedades="'"+propiedadserializada+"'";

                   for(x=0;x<colfilas.length;x++){
                       propiedadfilas.push('', '','');
                       propiedadfilas[x]=colfilas[x].outerHTML;
                   }
                   propiedadfilasserializada=JSON.stringify(propiedadfilas);  //serialize(propiedades);
                   propiedadfilas="'"+propiedadfilasserializada+"'";
                   for(x=0;x<colfilas.length;x++){
                       datafilas.push('');
                       datacolumnas.push('');
                       datafilas[x]=colfilas[x].dataset;
                       datacolumnas[x]=columnas[x].dataset;
//                       if(colfilas[x].attributes['name']){
//                           datafilas[x]['name']=colfilas[x].attributes['name'].value;
//                       }
//                       if(colfilas[x].attributes['class']){
//                           datafilas[x]['class']=colfilas[x].attributes['class'].value;
//                       }
//                       atributoa=colfilas[x].firstChild;
//                       if(atributoa){
//                           if(atributoa.localName){
//                               if(atributoa.localName=="a" && atributoa.attributes['class']){
//                                    datafilas[x]['classa']=atributoa.attributes['class'].value;
//                               }
//                           }
//                       }
//                       if(colfilas[x].attributes['texto']){
//                           datafilas[x]['texto']=colfilas[x].attributes['texto'].value;
//                       }
                   }
                   datafilasserializada=JSON.stringify(datafilas);  //serialize(propiedades);
                   datacolumnaserializadas=JSON.stringify(datacolumnas);


                    //jQuery.data($("h3.gridconfigurable#"+cadena),propiedadserializada,"defecto");
                   $("h3.gridconfigurable#"+cadena).data("defecto",propiedadserializada);
                   $("h3.gridconfigurable#"+cadena).data("filas",propiedadfilasserializada);
                   $("h3.gridconfigurable#"+cadena).data("atributos",datafilasserializada);
                   $("h3.gridconfigurable#"+cadena).data("titulos",datacolumnaserializadas);
                   $("h3.gridconfigurable#"+cadena).html("<a href='javascript:void(0);'onclick='sacarListadoVentana("+parametro1+","+parametro2+")'>"+$("h3.gridconfigurable#"+cadena).text()+"</a>");
              }
          }
            $(".nuevaColumna").live('click', function(event){      
                //var uno=$(".nuevaColumna").removeAttr('href');
                sacarEdicionVentana('/app_dev.php/cabeceras/new/1,volumenes');
            });
          
      });

function sacarListadoVentana($ruta,$titulo){
  var sdata=$titulo.substring($titulo.indexOf(' '));
  sdata=jQuery.trim( sdata );
  var sdata2=$("h3.gridconfigurable#"+sdata).data("defecto");
  var sdata3=$("h3.gridconfigurable#"+sdata).data("filas");
  var sdata4=$("h3.gridconfigurable#"+sdata).data("atributos");
  var sdata5=$("h3.gridconfigurable#"+sdata).data("titulos");
  $.ajax({type: "POST", url: $ruta,
      data:{
          propiedad: sdata2,
          filas: sdata3,
          atributos: sdata4,
          titulos: sdata5
          
      },
 
 success: function(data){
    $("#ventanacabecera").append('<div>'+data+'</div>');
    $("#ventanacabecera").dialog({
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
        $("#ventanacabecera div").remove();
      }
    });
  }});
  return true;
}
function sacarEdicionVentana($ruta){
  $.ajax({type: "GET", url: $ruta,
 
 success: function(data){
    $("#edicioncabecera").append('<div>'+data+'</div>');
    $("#edicioncabecera").dialog({
      modal: true,
      closeText: 'hide',
      stack: true,
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


function serialize(arr)
{
var res = 'a:'+arr.length+':{';
for(i=0; i<arr.length; i++)
{
    
if(!arr[i]){
    arr[i]='';
}
    
res += 'i:'+i+';s:'+arr[i].length+':"'+arr[i]+'";';
}
res += '}';
 
return res;
}
