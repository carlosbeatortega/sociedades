      $(function(){
          $("table td").bind('click',  function(event){
            var algo=$(this).hasClass('borrate');
            if(algo){
                return;
            }
            algo=$(this).children('a.edit').length;
            if(algo>0){
                return;
            }
            algo=this.attributes['name'];
            if(!algo){
                return;
            }
            var valorcelda=$(this).text();
            $(this).addClass('borrate');     
            var combo1=$("table th").get(this.cellIndex);
            var ancho=combo1.clientWidth;
            var paraelcaso=combo1.className.toUpperCase();
            var definitivo="GENERAL";
            if(paraelcaso.indexOf('FECHA')>=0){
                definitivo="fecha"
                paraelcaso="";
            }
            if(paraelcaso.indexOf('SELECCION')>=0){
                definitivo="seleccion"
                paraelcaso="";
            }
            switch (definitivo){
                case 'fecha':
                    $('td.borrate').html("<input type='text' class='borratext' name='fecha' value='"+$(this).text()+"'>");
                    $('.borratext').datepicker('destroy');
                    $('.borratext').datepicker({dateFormat:"dd/mm/yy",
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
                            date = $(this).datepicker('getDate');
                            $('td.borrate').text($.datepicker.formatDate('dd/mm/yy', date));
                            grabaajax('D');
                            $('td').removeClass('borrate');
                            $(this).hide();
                            $('.borratext').remove();
                            
                        },
                    onClose: function(date){
                            var apofec=$('td.borrate');
                            if(apofec.length=0){
                                $(this).hide();
                                $('.borratext').remove();
                                return;                                
                            }
                            date = $(this).datepicker('getDate');
                            $('td.borrate').text($.datepicker.formatDate('dd/mm/yy', date));
                            
                            var bb1=$('td.borrate').parent().find('a.edit');
                            if(bb1.length==0){
                                return;                                
                            }
                            grabaajax('D');

                            $('td').removeClass('borrate');
                            $(this).hide();
                            $('.borratext').remove();
                        
                    }
                    });
                    $('.borratext').trigger('focus');
                    break;
                case 'seleccion':
                    var clonico=$("select").clone();
                    clonico.addClass('comboout');
                    var destino=$('td.borrate');
                    destino.text("");
                    clonico.appendTo(destino);
                    var apo1=clonico.width();

                    var apo2=this.clientWidth;
                    $(this).attr('clientWidth',apo1);
                    clonico.width(ancho);
                    clonico.show();
                    $("select.seleccion option[value='" + valorcelda + "']").attr("selected","selected");

                    clonico.trigger('focus');
                    break;
                default:
                    $('td.borrate').html("<input type='text' class='borratext' name='fname' value='"+$(this).text()+"'>");
                    $('.borratext').width(this.clientWidth);
                    $('.borratext').trigger('focus');
            }
            
          
            });
          $("select.seleccion").live('focusout', function(event){ 
            $('td.borrate').text($("select.seleccion").val());
            $('td').removeClass('borrate');
            $("select.seleccion").hide();
      });
          $(".borratext").live('focusout', function(event){ 
            var dpicker=$('.hasDatepicker');
            if(dpicker.length>0){
                    return;
                
            }else{
                $('td.borrate').text($('.borratext').val());
                grabaajax();
            }            
            $('td').removeClass('borrate');
            $('.borratext').remove();
      });
          $(".comboout").live('change', function(event){ 
            var dpicker=$('.hasDatepicker');
            if(dpicker.length>0){
                    return;
                
            }else{
                var apod=$('td.borrate');
                $('td.borrate').text($('.comboout').val());
                grabaajax();
            }            
            $('td').removeClass('borrate');
            $('.comboout').hide();
      });      
      });
function insertar(){
    $(this).addClass('borrate');
    $('td.borrate').html("<input type='text' name='fname' value='"+$(this).text()+"'>");
    
}
function borrate(){
    $('.borratext').remove();
    
}

function grabaajax(tipo) {
    if(tipo==null){
        tipo="C";
    }
        
    var bb1=$('td.borrate').parent().find('a.edit');
    var idbb=bb1[0].outerHTML;
    var ruta=idbb.substring(idbb.indexOf('/'),idbb.indexOf('edit/'))+"grabame/";
    var myid=parseInt(idbb.substring(idbb.indexOf('edit/')+5));
    var myvalor=$('td.borrate').text();
    var mycampo=$('td.borrate').attr('name');
    var myfichero=bb1.parent().attr('name');
    ruta="/app_dev.php/volumenes/grabame/"

    $.ajax({
        url: ruta,
       data: {
               id: myid,
               fichero: myfichero,
               campo: mycampo,
               valor: myvalor,
               tipo: tipo
             }});
    
}
