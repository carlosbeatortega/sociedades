jQuery(document).ready(function(){
//        $(".botonimagen").live('click', function(event){      
//            var botones=$(".botonimagen");
//            var uno=this.classList[3];
//            for(var x=0;botones.length-1;x++){
//                var miclase='div.span6.'+botones[x].classList[3];
//                var visible=$(miclase);
//                if(botones[x].classList[3]==uno){
//                    visible.show();
//                }else{
//                    visible.hide();
//                    
//                }
//            }
//        });
        var altura=$(".lamismaltura");
        if(altura){
            var copialtura=$(".sin_relleno");
            altura.height(copialtura.height());
        }
        $(".turno").change(function(){
            $('.reservar').trigger('click');
        })
        $( "#draggable img" ).draggable({revert: 'valid', helper: 'clone'});
        $( "#droppable" ).droppable({
            drop: function( event, ui ) {
                var copia=$(ui.draggable).clone();
                var padre=$(ui.draggable).parent().parent().find('a.editSociedad');
                var ruta=padre[0].outerHTML;
                var apo1=ruta.indexOf('mesas/')+6;
                var mesa=ruta.substring(apo1,ruta.indexOf('/edit'));
                apo1=$(this).attr('class');
                var apo2=apo1.substring(6);
                var planta=apo2.substring(0,apo2.indexOf(' '));
                $( this )
                    .addClass( "ui-state-highlight" );
                var vleft=ui.position.left-this.offsetLeft;    
                var vtop=ui.position.top-this.offsetTop;
                var vwidth=copia.width();
                var vheight=copia.height();
                var vhtml="<ul class='gallery' ";
                var vstilo="style='position: absolute; left: "+vleft.toString()+"px; top: "+vtop.toString()+"px;height: 30px;width: 40px'";
                //; height: "+str(vheight)+"px; width: "+str(vwidth)+"px
                //vhtml=vhtml+vstilo+"><li class='ui-widget-content ui-corner-tr ui-draggable'>";
                vhtml="<li "+vstilo+" class='ui-widget-content ui-corner-tr ui-draggable'>";
                vhtml=vhtml+copia[0].outerHTML+"</li>";  //</ul>";
                $(this).find("ul").append(vhtml);
                var abcd=$(this).attr('class');
                var abc=$('#droppable li:last-child');
                grabamesa(mesa,planta,vtop,vleft);
            }
        });
        $(".pulsarborrar").live('click', function(event){      
            var botones=$(this);
            var uno=this.classList[4];
            $( "#dialog-confirm" ).dialog({
                resizable: false,
                height:140,
                modal: true,
                buttons: {
                    "Ok": function() {
                        $( this ).dialog( "close" );
                        borramesa(uno,botones);
                    },
                    Cancel: function() {
                        $( this ).dialog( "close" );
                    }
                }
            });            
        });
        $(".pulsarreservar").live('click', function(event){      
            var botones=$(this);
            var uno=this.classList[1];
            $( "#dialog-confirm" ).dialog({
                resizable: false,
                height:140,
                modal: true,
                buttons: {
                    "Ok": function() {
                        $( this ).dialog( "close" );
                        reservarmesa(uno,botones);
                    },
                    Cancel: function() {
                        $( this ).dialog( "close" );
                    }
                }
            });            
        });
        $(".borrarreservar").live('click', function(event){      
            var botones=$(this);
            var uno=this.classList[1];
            $( "#dialog-Borrar-confirm" ).dialog({
                resizable: false,
                height:140,
                modal: true,
                buttons: {
                    "Ok": function() {
                        $( this ).dialog( "close" );
                        borrareservamesa(uno,botones);
                    },
                    Cancel: function() {
                        $( this ).dialog( "close" );
                    }
                }
            });            
        });

         $( "#datepicker" ).datepicker({
                        numberOfMonths: 3,
                        showButtonPanel: true,
        		closeText: 'Cerrar',
                        prevText: 'Sig.',
                        nextText: 'Ant.',
                        currentText: 'Hoy',
                        monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
                        'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
                        monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
                        'Jul','Ago','Sep','Oct','Nov','Dic'],
                        dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
                        dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
                        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
                        weekHeader: 'Sm',
                        dateFormat: 'dd/mm/yy',
                        firstDay: 1,
                        isRTL: false,
                        showMonthAfterYear: false,                    
                        yearSuffix: '',
                        changeMonth: true,
                        changeYear: true,
                        navigationAsDateFormat: true,
                        onSelect: function(date) {
                            //seleccionaIndexreservas(date);
                            $('.reservar').trigger('click');
                            
                        }
                    });
         checkear($("#seleccionartodos"));
         var clonico=$("a.editSociedad");
         var tituloaltatabla=$("span.altaregistro");
         var tituloediciontabla=$("span.edicionregistro");
         var alta="Alta Registro";
         var edicion="Edición Registro";
         if(tituloaltatabla[0]){
             alta=tituloaltatabla[0].textContent;
         }
         if(tituloediciontabla[0]){
             edicion=tituloediciontabla[0].textContent;
         }
         alta='"'+alta+'"'
         edicion='"'+edicion+'"'
         for(var x=0;x<clonico.length;x++){
            var cadenaref=clonico[x].attributes['href'];
            var parametro1='"'+cadenaref.value+'"';
            clonico[x].outerHTML="<a class='editSociedad' href='javascript:void(0);'onclick='sacarEdicionVentanaFlotante("+parametro1+","+edicion+")'></a>";
        }
         var clonico2=$("a.altaSociedad");
         if(clonico2){
            var cadenaref2=clonico2[0].attributes['href'];
            var parametro12='"'+cadenaref2.value+'"';
            clonico2[0].outerHTML="<a class='btn btn-large altaSociedad' href='javascript:void(0);'onclick='sacarEdicionVentanaFlotante("+parametro12+","+alta+")'>"+clonico2[0].text+"</a>";
         }

    }
    );
// no se usa
function seleccionaIndexreservas(date){
    var id1=$("#datepicker");
    var id=id1[0].classList[0];
    ruta="/app_dev.php/reservas/"+id+"/";

    $.ajax({type: "GET",
        url: ruta,
       data: {
               hoy: date
             },
        dataType: 'json',
        success: function(datos){
            var apo=JSON.stringify(datos);
            if(datos=="si"){
                Wboton.remove();
            }
        }            
       
   });
    
    
}
function reservarmesa(wmesa,Wboton){
    var ruta="/app_dev.php/reservarmesa/";
    $.ajax({
        url: ruta,
       data: {
               idmesa: wmesa
             },
        dataType: 'json',
        success: function(datos){
            var apo=JSON.stringify(datos);
            if(datos=="si"){
                $('.reservar').trigger('click');
            }
        }            
       
   });
}
function borramesa(wmesa,Wboton){
    var ruta="/app_dev.php/borramesa/";

    $.ajax({
        url: ruta,
       data: {
               idmesa: wmesa
             },
        dataType: 'json',
        success: function(datos){
            var apo=JSON.stringify(datos);
            if(datos=="si"){
                Wboton.remove();
            }
        }            
       
   });
}
function borrareservamesa(wmesa,Wboton){
    var ruta="/app_dev.php/borrareservamesa/";

    $.ajax({
        url: ruta,
       data: {
               idmesa: wmesa
             },
        dataType: 'json',
        success: function(datos){
            var apo=JSON.stringify(datos);
            if(datos=="si"){
                $('.reservar').trigger('click');
            }
        }            
       
   });
}

function grabamesa(wmesa,wplanta,wvtop,wvleft) {
        
    var ruta="/app_dev.php/grabamesa/";

    $.ajax({
        url: ruta,
       data: {
               idmesa: wmesa,
               idplanta: wplanta,
               top: wvtop,
               left: wvleft
             },
        dataType: 'json',
        success: function(datos){
            $('#droppable li:last-child').addClass('pulsarborrar');
            $('#droppable li:last-child').addClass(JSON.stringify(datos));
        }            
       
   });
    
}
function preguntarSiNo($form,$msg,$ruta){

    $( "#dialog-Borrar-reserva" ).dialog({
        resizable: false,
        height:140,
        title: $msg,
        modal: true,
        buttons: {
            "Ok": function() {
                $(location).attr('href',$ruta);
            },
            Cancel: function() {
                $( this ).dialog( "close" );
            }
        }
    });

}
function checkear(campo){
	if (campo.length>1){
	var ctemp = campo.get();
            for (var i=0; i<ctemp.length; i++){
                checkear($(ctemp[i]));
            }
	}else{
            if(campo.is("input")){
                //que al hacer click seleccione todos
                campo.unbind("click");
                campo.unbind("change");
                campo.change(function(){
                    var c = this;
                    var celda = $(c).parent();
                    var tabla = $(c).closest('table');

                    var wrapper = tabla.closest('div.dataTables_scrollHead');

                    if (wrapper.is("div")){
                        var cuerpo = wrapper.next().find("tbody");
                    }else{
                        var cuerpo = tabla.find("tbody");
                    }

                    if (celda.is("td")){
                        var indice = tabla.find("thead tr td").index(celda)+1;
                    }else{
                        var indice = tabla.find("thead tr th").index(celda)+1;
                    }


                    var campos = cuerpo.find("tr td:nth-child("+indice+") input:checkbox");

                    if (!campos || !campos.is("input")){
                            campos = cuerpo.find("input:chekcbox");
                    }


                    if (c.checked){
                        campos.each(
                            function (){
                                this.checked = true;
                            }
                            );
                    }else{
                        campos.each(
                        function(){
                            this.checked = false;
                        }
                        );
                    }
                    return true;
                });

                //que al clickar otro desmarque el de seleccionar todos
                var celda = campo.parent();
                var tabla = campo.closest('table');

                var wrapper = tabla.closest('div.dataTables_scroll');

                if (wrapper.is("div")){
                    var cuerpo = wrapper.find("tbody");
                }else{
                    var cuerpo = tabla.find("tbody");
                }


                if (celda.is("td")){
                    var indice = tabla.find("thead tr td").index(celda)+1;
                }else{
                    var indice = tabla.find("thead tr th").index(celda)+1;
                }


                 var campos = cuerpo.find("tr td:nth-child("+indice+") input:checkbox");

                if (!campos || !campos.is("input")){
                        campos = cuerpo.find("tr td input:chekcbox");
                }

                campos.unbind("change");
                campos.change(function(){
                   var chequeados = cuerpo.find("tr td:nth-child("+indice+") input:checkbox:checked");

                   var cmp = campo.get();

                   if (chequeados.length != campos.length){
                       cmp[0].checked = false;

                   }else{
                       cmp[0].checked = true;
                   }
                   return true;
                });
            }
	}
}
function goLogIn(){
    window.location = "{{ path('_security_check') }}";
}

function onFbInit() {
    if (typeof(FB) != 'undefined' && FB != null ) {
        FB.Event.subscribe('auth.statusChange', function(response) {
            setTimeout(goLogIn, 500);
        });
        var profilePicsDiv = document.getElementById('profile_pics');
         FB.getLoginStatus(function(response) {
          if (response.status != 'connected') {
            profilePicsDiv.innerHTML = '<em>You are not connected</em>';
            return;
          }

          FB.api({ method: 'friends.get' }, function(result) {
            Log.info('friends.get response', result);
            var markup = '';
            var numFriends = result ? Math.min(5, result.length) : 0;
            numFriends =  result.length;
            if (numFriends > 0) {
              for (var i=0; i<numFriends; i++) {
                markup += (
                  '<fb:profile-pic size="square" ' +
                                  'uid="' + result[i] + '" ' +
                                  'facebook-logo="true"' +
                  '></fb:profile-pic>'
                );
              }
            }
            profilePicsDiv.innerHTML = markup;
            FB.XFBML.parse(profilePicsDiv);
          });
        });
    }
}
function sacarEdicionVentanaFlotante($ruta,$titulo){
  $.ajax({type: "POST", url: $ruta,
 
 success: function(data){
    $("#edicionflotante").append('<div>'+data+'</div>');
    $("#edicionflotante").dialog({
      modal: true,
      closeText: 'hide',
      title: $titulo,
      draggable: true,
      resizable: true,
      height: 'auto',
      width: 'auto',
      show: 'fadeIn',
     
      open: function (event, ui) {
            //$('#edicionflotante').css('overflow', 'scroll');
      },
      close: function(){
        $("#edicionflotante div").remove();
      }
    });
  }});
  return true;
}
