<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of gCalendar
 *
 * @author aritz
 */

namespace Sociedad\Comunes;
//require_once('Zend/Loader.php');
use \Zend_Gdata;
use \Zend_Gdata_Calendar;
use \Zend_Gdata_ClientLogin;
use \Zend_Gdata_HttpClient;
use \Zend_Gdata_AuthSub;
use \Zend_Gdata_Query;
use \Zend_Gdata_Feed;
class gCalendar {
    public static function activarServicio($user = '',$pass = ''){
      $gcal = Zend_Gdata_Calendar::AUTH_SERVICE_NAME;
      try{
        $client = Zend_Gdata_ClientLogin::getHttpClient($user, $pass, $gcal);
      }catch (Zend_Gdata_App_AuthException $e){
         return false; 
      }
      $gcal = new Zend_Gdata_Calendar($client);
      
      return $gcal;
    }
    public static function getCalendarKey($user = '',$pass = ''){
      $gcal = Zend_Gdata_Calendar::AUTH_SERVICE_NAME;
      try{
        $client = Zend_Gdata_ClientLogin::getHttpClient($user, $pass, $gcal);
      }catch (Zend_Gdata_App_AuthException $e){
         return false; 
      }
      $calendarKey=$client->getClientLoginToken();
      
      return $calendarKey;
    }
  public static function getClientes($user, $pass){
      $gcal = Zend_Gdata_Calendar::AUTH_SERVICE_NAME;
      $feed=false;
      try{
          $client = Zend_Gdata_ClientLogin::getHttpClient($user, $pass,'cp');
          $gdata = new Zend_Gdata($client);
          $gdata->setMajorProtocolVersion(3);
          $query = new Zend_Gdata_Query('https://www.google.com/m8/feeds/contacts/default/full');
          $feed = $gdata->getFeed($query);
      }catch (Zend_Gdata_App_AuthException $e){
         return false; 
      }
      return $feed;
  }
  public static function getClienteControlador($user, $pass,$gcal=null){
      if(!$gcal){
        $gcal = Zend_Gdata_Calendar::AUTH_SERVICE_NAME;
      }
      //$gcal='cp';
      $feed=false;
      try{
        $client = Zend_Gdata_ClientLogin::getHttpClient(
            $user, $pass, $gcal);
        $client->setHeaders('If-Match: *');
        $gdata = new Zend_Gdata($client);
        $gdata->setMajorProtocolVersion(3);

          
          
//          $client = Zend_Gdata_ClientLogin::getHttpClient($user, $pass,$gcal);
//          $client->setHeaders('If-Match: *');
//          $gdata = new Zend_Gdata($client);
//          $gdata->setMajorProtocolVersion(3);
      }catch (Zend_Gdata_App_AuthException $e){
         return false; 
      }
      return $gdata;
  }
  public static function getClienteModificaControlador($gdata,$id,$usuario){
      $xml=false;
      $vuelta=false;
      try{
        $query = new Zend_Gdata_Query($id);
        $entry = $gdata->getEntry($query);
        if($entry){
            $xml = simplexml_load_string($entry->getXML());
            $xml->name->namePrefix = "(".$usuario.")";
            $entryResult  = $gdata->updateEntry($xml->saveXML(),$entry->getEditLink()->href,null,array('If-Match'=>'*'));
            if ($entryResult){
                $vuelta=true;
            }
        }
      }catch (Zend_Gdata_App_AuthException $e){
         return false; 
      }
      return $vuelta;
  }

  
  public static function setClientes($user, $pass) {
        $gdata=false;
        try {
            $client = Zend_Gdata_ClientLogin::getHttpClient(
                $user, $pass, 'cp');
            $gdata = new Zend_Gdata($client);
            $gdata->setMajorProtocolVersion(3);

        } catch (Exception $e) {
        die('ERROR:' . $e->getMessage());
        }        
        return $gdata;
        
    }
            
    public static function crearEvento($datos,$gcal,$url=NULL,$myEmail="",$modo="",$minutos=""){
      try {
        $event = $gcal->newEventEntry(); 
        $event->title = $gcal->newTitle($datos['titulo']);        
        $when = $gcal->newWhen();
        $when->startTime = $datos['start'];
        $when->endTime = $datos['end'];
        $event->when = array($when); 
        $where = $gcal->newWhere();
        $where->valueString = $datos['lugar'];
        $event->where = array($where);
        $event->content = $gcal->newContent($datos['descripcion']);
        if(!empty($modo) && !empty($minutos)){
            $reminder = $gcal->newReminder();
            $reminder->setMethod($modo);
            $reminder->setMinutes($minutos);
            $when=$event->when[0];
            $when->reminders=array($reminder);
        }

        if(empty($datos['contac_id']) || empty($datos['email']) ){
            $attendeeA = $gcal->newWho($myEmail, null, null,
                $gcal->newAttendeeStatus('http://schemas.google.com/g/2005#event.accepted'));
            $event->who = array($attendeeA);        
         }else{
            $invited=array();
            $attendeeA = $gcal->newWho($myEmail, null, null,
                $gcal->newAttendeeStatus('http://schemas.google.com/g/2005#event.accepted'));
            $invited[]=$attendeeA;
            foreach($datos['contac_id'] as $adatos){
                    if($adatos){
                        $attendeeB = $gcal->newWho($adatos[0], null, $adatos[1],
                            $gcal->newAttendeeStatus('http://schemas.google.com/g/2005#event.invited'));
                        $invited[]=$attendeeB;
                    }
                }
            $event->who = $invited;        
        }
        $newEvent = $gcal->insertEvent($event,$url); 
      }catch (Zend_Gdata_App_Exception $e) {
        echo "Error: " . $e->getResponse();
      } 

      return $newEvent;
    }
    
    public static function editarEvento($datos,$gcal,$event,$myEmail=""){
      try {
        $event->title = $gcal->newTitle($datos['titulo']);        
        $when = $gcal->newWhen();
        $when->startTime = $datos['start'];
        $when->endTime = $datos['end'];
        $event->when = array($when); 
        if(empty($event->where[0]->valueString)){
            $where = $gcal->newWhere();
            $where->valueString = $datos['lugar'];
            $event->where = array($where);
        }
        $event->content = $gcal->newContent($datos['descripcion']);
//        $attendeeA = $gcal->newWho($myEmail, null, null,
//            $gcal->newAttendeeStatus('http://schemas.google.com/g/2005#event.accepted'));
//        if(empty($datos['contac_id']) || empty($datos['email']) || empty($datos['gmail_id'])){
//            $event->who = array($attendeeA); 
//        }else{
//            $attendeeB = $gcal->newWho($datos['email'], null, $datos['gmail_id'],
//                $gcal->newAttendeeStatus('http://schemas.google.com/g/2005#event.invited'));
//            $event->who = array($attendeeA, $attendeeB);        
//        }
        if(empty($datos['contac_id']) || empty($datos['email']) ){
            $attendeeA = $gcal->newWho($myEmail, null, null,
                $gcal->newAttendeeStatus('http://schemas.google.com/g/2005#event.accepted'));
            $event->who = array($attendeeA);        
         }else{
            $invited=array();
            $attendeeA = $gcal->newWho($myEmail, null, null,
                $gcal->newAttendeeStatus('http://schemas.google.com/g/2005#event.accepted'));
            $invited[]=$attendeeA;
            foreach($datos['contac_id'] as $adatos){
                    if($adatos){
                        $attendeeB = $gcal->newWho($adatos[0], null, $adatos[1],
                            $gcal->newAttendeeStatus('http://schemas.google.com/g/2005#event.invited'));
                        $invited[]=$attendeeB;
                    }
                }
            $event->who = $invited;        
        }
        $event->save();   
      }catch (Zend_Gdata_App_Exception $e) {
        die("Error: " . $e->getResponse());
      }
      
      return $event;
    }
    //funcion para invitar contactos al calendario
    protected function sendInvite($eventId, $email)
    {
        $gdataCal = new Zend_Gdata_Calendar($this->client);

        if($eventOld = $this->getEvent($eventId))
        {
            $SendEventNotifications = new Zend_Gdata_Calendar_Extension_SendEventNotifications(); 
            $SendEventNotifications->setValue(true); 
            $eventOld->SendEventNotifications = $SendEventNotifications;
            $who = $gdataCal->newwho();
            $who->setEmail($email);

            $eventOld->setWho(array_merge(array($who), $eventOld->getWho())); 

            try
            {
                $eventOld->save();
            } catch(Zend_Gdata_App_Exception $e)
            {
                return false;
            }

            return true;
        } else
            return false;
    }     
    function getEvent($eventId)
    {
        $gdataCal = new Zend_Gdata_Calendar($client);
        $query = $gdataCal->newEventQuery();
        $query->setUser('default');
        $query->setVisibility('private');
        $query->setProjection('full');
        $query->setEvent($eventId);
        try
        {
            $eventEntry = $gdataCal->getCalendarEventEntry($query);
            return $eventEntry;
        } catch(Zend_Gdata_App_Exception $e)
        {
            return null;
        }
    }
    
    
    public static function validarEvento($datos){
      $sdate = $datos['fecha']['from'];
      $edate = $datos['fecha']['to'];
      $sdate_mm = $sdate['month'];
      $sdate_dd = $sdate['day'];
      $sdate_yy = $sdate['year'];
      $edate_mm = $edate['month'];
      $edate_dd = $edate['day'];
      $edate_yy = $edate['year'];
      isset($datos['hora_inicio'])?$sdate_hh = $datos['hora_inicio']['hour']:$sdate_hh = 0;
      isset($datos['hora_inicio'])?$sdate_ii = $datos['hora_inicio']['minute']:$sdate_ii = 0;
      isset($datos['hora_final'])?$edate_hh = $datos['hora_final']['hour']:$edate_hh = 0;
      isset($datos['hora_final'])?$edate_ii = $datos['hora_final']['minute']:$edate_ii = 0;
      
      $datos['titulo'] = htmlentities($datos['titulo']);
      //date_default_timezone_set('UTC');
      date_default_timezone_set('Europe/Madrid');
      if(!isset($datos['dia_entero']) && ($sdate_hh != 0 || $sdate_ii != 0 || $edate_hh != 0 || $edate_ii != 0) && ($sdate_hh.$sdate_ii <= $edate_hh.$edate_ii)){
        $start = date(DATE_ATOM, mktime($sdate_hh, $sdate_ii,0, $sdate_mm, $sdate_dd, $sdate_yy));
        $end = date(DATE_ATOM, mktime($edate_hh, $edate_ii,0, $edate_mm, $edate_dd, $edate_yy));
        $datos['allday'] = false;
      }else{
        $start = date('c', strtotime($sdate_yy.'-'.$sdate_mm.'-'.$sdate_dd));
        $end = date('c', strtotime($edate_yy.'-'.$edate_mm.'-'.$edate_dd));
        $datos['allday'] = true;
      }
      
      $datos['start'] = $start;
      $datos['end'] = $end;
      
      return $datos;
    }
    
    public static function obtenerEvento($id,$gcal, $calendar_id = 'default', $error = true,$updated=null){
      $query = $gcal->newEventQuery();
      $query->setUser($calendar_id);
      $query->setVisibility('private');
      $query->setProjection('full');
      $query->setEvent($id); 
      $event = false;
      
      if (isset($id)) {
        try {          
          $event = $gcal->getCalendarEventEntry($query);
        }catch (Zend_Gdata_App_Exception $e) {
          if($error){
            echo "Error: " . $e->getResponse();
          }
        }
      }else {
        die('ERROR: No event ID available!');  
      }
      //carlos 13/09/2012 $event puede venir nulo
      if(!is_null($event) && !is_null($updated)){
      //
          $apo1=date(DATE_ATOM, strtotime($event->updated->text));
          if($apo1>$updated){
              return null;
          }
      }
      return $event;
    }
    
    public static function listaEventos($gcal,$calendar_id = 'default',$startMin = '',$startMax = ''){
      $query = $gcal->newEventQuery();
      $query->setUser($calendar_id);
      $query->setVisibility('private');
      $query->setProjection('full');
      if($startMin != ''){
        $query->setStartMin($startMin);
      }
      if($startMax != ''){
        $query->setStartMax($startMax);
      }
      try {
        $feed = $gcal->getCalendarEventFeed($query);
      } catch (Zend_Gdata_App_Exception $e) {
        //echo "Error: " . $e->getResponse();
        return false;
      }
      
      return $feed;
    }
    
    public static function listaEventosArray($gcal,$calendar_id = 'default',$startMin = '',$startMax = '', $trans_fecha = false){
      //esta funcion devuelve los eventos de forma que los entienda el fullcalendar.js
      $query = $gcal->newEventQuery();
      $query->setUser($calendar_id);
      $query->setVisibility('private');
      $query->setProjection('full');
      //$query->setOrderby('starttime');
      $query->setMaxResults(2000);
      
      if($startMin != ''){
        $query->setStartMin($startMin);
      }
      if($startMax != ''){
        $query->setStartMax($startMax);
      }
      try {
        $feed = $gcal->getCalendarEventFeed($query);
        //$cuantos=$feed->count();
      } catch (Zend_Gdata_App_Exception $e) {
        echo "Error: " . $e->getResponse();
      }
      $datos = array();
      $timezone = $feed->timezone->value;
      date_default_timezone_set($timezone);
      foreach($feed as $f){
          $lncontador=0;
          $evento = array();
          foreach($f->who as $w){
              $apo11=$w;
              $evento['email'][$lncontador]=$w->email;             
              $evento['gmail_id'][$lncontador]=$w->valueString;             
              $lncontador++;
//              $apo1=$f->getLink($w->rel);
            }
//          $apo11=$f->getEntryLink();
//          $apo11=$f->getExtendedProperty();
//          $apo1=$f->who[0]->getRel();
//          $apo2=$f->who[1]->getRel();
//          $apo3=$f->who[2]->getRel();
//          $apo11=$f->who[0]->getAttendeeStatus()->getValue();
//          $apo21=$f->who[1]->getAttendeeStatus()->getValue();
//          $apo31=$f->who[2]->getAttendeeStatus()->getValue();
//          $apo12=$f->who[0]->getAttendeeType();
//          $apo22=$f->who[1]->getAttendeeType();
//          $apo33=$f->who[2]->getAttendeeType();
       if(isset ($f->when[0]) && date('Y',strtotime($f->when[0]->startTime))>1970){
        $id = substr($f->getId(), strrpos($f->getId(), '/')+1);
//obtener emails asociados
        $lncontador=0;
        
        foreach($f->who as $w){
          $apo11=$w;
          $evento['email'][$lncontador]=$w->email;             
          $arr=array();
          if($w->getAttendeeStatus()){
            $param1=preg_match_all('(invited)', $w->getAttendeeStatus()->getValue(), $arr, PREG_PATTERN_ORDER);
            if(!$param1){
                $param1=preg_match_all('(accepted)', $w->getAttendeeStatus()->getValue(), $arr, PREG_PATTERN_ORDER);
                if(!$param1){
                    $arr[0][0]='accepted';
                }
            }
          }else{
              $arr[0][0]='accepted';
          }
          $evento['status'][$lncontador]=$arr[0][0];
          $lncontador++;
//              $apo1=$f->getLink($w->rel);
        }

        $evento['id'] = $id;
        $evento['title'] = $f->title->text;
        $evento['description'] = $f->content->text;
        $evento['url'] = '/calendario_dev.php/inicio/editarEvento/id/'.$id;
        $evento['lugar']="";
        if(isset($f->where[0])){
            if(isset($f->where[0]->valueString)){
                $evento['lugar']=$f->where[0]->valueString;
            }
        }
        foreach($f->link as $link){
          if($link->type == 'text/html'){
            $evento['enlace'] = $link->href;
            
            if($f->timezone){
              $evento['enlace'] .= (strpos($evento['enlace'], '?') === false ? '?' : '&') + 'ctz='.$f->timezone;
            }
          }
        }
        
        if(isset ($f->when[0])){
          $contador=1;
          $identificador=$evento['id'];
          foreach($f->when as $when){
          //$when = $f->when[0];
            $start = $when->startTime;
            $end = $when->endTime;
            $allday = self::todoElDia($start,$end);

            $f_start = date('D M j Y G:i:s O',strtotime($start));
            $f_end = date('D M j Y G:i:s O',strtotime($end));

            if($trans_fecha){
                $f_start = date('Y-m-d H:i:s',strtotime($start));
                $f_end = date('Y-m-d H:i:s',strtotime($end)); 
            }

            $evento['start'] = $f_start;
            $evento['end'] = $f_end;
            $evento['allDay'] = $allday;
            $evento['modificado']=date(DATE_ATOM, strtotime($f->updated->text));
            $evento['id']=$identificador."#".self::zerofill($contador,6);
            $contador++;
            $datos[] = $evento;
          }
        }
        else{
          $evento['allDay'] = true;  
          $evento['modificado']=date(DATE_ATOM, strtotime($f->updated->text));
          $evento['id']=$evento['id']."#000001";
          $datos[] = $evento;
        }
//        print_r(json_encode($evento));
       }
      }

      return $datos;
    }
    
    public static function getIdEvento($event){
      $id = substr($event->getId(), strrpos($event->getId(), '/')+1);  
      
      return $id;
    }
    
    public static function getCalendarios($gcal){
      try {
        $calendarios = $gcal->getCalendarListFeed();
      } catch (Zend_Gdata_App_Exception $e) {
        echo "Error: " . $e->getResponse();
      }
      
      return $calendarios;
    }
    public static function getCalendariosList($gcal,$uri){
      try {
        $calendarios = $gcal->getCalendarListEntry($uri);
      } catch (Zend_Gdata_App_Exception $e) {
        echo "Error: " . $e->getResponse();
      }
      
      return $calendarios;
    }
    
    public static function getIdCalendario($calendario){
       $url =  $calendario->link[0]->href;
       $temp = explode('/', $url);
       $id = $temp[5];
       
       return $id;
    }
    
    public static function getUrlCalendario($calendario){
       $url =  $calendario->link[0]->href;
       
       return $url;
    }
    
    public static function getUrlCalendarioId($gcal,$calendar_id){
       try {
        $calendarios = $gcal->getCalendarListFeed();
      } catch (Zend_Gdata_App_Exception $e) {
        echo "Error: " . $e->getResponse();
      } 
      
      foreach($calendarios as $c){
        if(self::getIdCalendario($c) == str_replace('@', '%40', $calendar_id) || self::getIdCalendario($c) == $calendar_id){
          return self::getUrlCalendario($c);
        }
      }
    }
    
    public static function eventToArray($event,$calendar_id = 'default',$id_visita = 0){
      $datos = array();
      
      $datos['id_visita'] = $id_visita;
      $datos['id_evento'] = gCalendar::getIdEvento($event);
      $datos['id_calendario'] = $calendar_id;
      $datos['titulo'] = $event->title->text;
      $datos['lugar'] = $event->where[0]->valueString;
      $datos['descripcion'] = $event->content->text;
      
      $when = $event->when[0];
      date_default_timezone_set('Europe/Madrid');
      $startTime = strtotime($when->getStartTime());
      $endTime = strtotime($when->getEndTime());
      
      $datos['fecha_inicio'] = date('Y-m-d',$startTime);
      $datos['fecha_final'] = date('Y-m-d',$endTime);
      $datos['hora_inicio'] = date('H:i:s',$startTime);
      $datos['hora_final'] = date('H:i:s',$endTime);
      $datos['usuario'] = $event->author[0]->email->text;

      return $datos;
    }
    
    public static function xmlToArray($xml){
      $datos = array();
      $i = 0;
      foreach($xml->event as $event){
        $temp = array();
        $temp['id_visita'] = $event->id_visita->__toString();
        $temp['id_evento'] = $event->id_evento->__toString();
        $temp['id_calendario'] = $event->id_calendario->__toString();
        $temp['titulo'] = $event->titulo->__toString();
        $temp['lugar'] = $event->lugar->__toString();
        $temp['descripcion'] = $event->descripcion->__toString();
        $temp['start'] = '';
        $temp['end'] = '';
        $temp['usuario'] = $event->usuario->__toString();
        $temp['clave'] = '';
        
        $datos[$event->id_visita->__toString().$i] = $temp;
        $i++;
      }
      
      return $datos;
    }
    
    public static function obtenerComentarios($event,$gcal){
      $commentUrl = $event->comments->feedLink->href;
 
      try {
        $commentFeed = $gcal->getFeed($commentUrl);
      }catch (Zend_Gdata_App_Exception $e) {
        echo "Error: " . $e->getMessage();
      }
      
      return $commentFeed;
    }
    
    public static function fechaInicio($event){
      return $event->when[0]->startTime;
    }
    
    public static function fechaFinal($event){
      return $event->when[0]->endTime;
    }
    
    protected static function todoElDia($start,$end){
      $ts = date('G:i:s',strtotime($start));
      $te = date('G:i:s',strtotime($end));
      
      if($ts == $te && $ts == '0:00:00'){
        return true;
      }
      return false;
    }
    
    protected function actualizarCalendar(){
    $eventos = VisitasTable::listarActualizables('gCalendar');
    
    foreach($eventos as $e){
      $gcal = gCalendar::activarServicio($e->getUsuario(), $e->getClave());
      $datos = $e->visitaToCalendar();
      
      if($e->getIdEvento() == ''){
        //la visita se ha creado en alguna aplicacion y no esta en el calendar
        $url = gCalendar::getUrlCalendarioId($gcal, $e->getIdCalendario());
        $newEvent = gCalendar::crearEvento($datos, $gcal,$url);
        VisitasTable::insertarEventoCalendar($e,$newEvent);
      }
      else{
        //la visita se ha editado en alguna aplicacion y ya existia en el calendar
        $update = gCalendar::obtenerEvento($e->getIdEvento(), $gcal, $e->getIdCalendario());
        $newEvent = gCalendar::editarEvento($datos, $gcal,$update); 
        VisitasTable::actualizarEventoTabla($e,$newEvent);
      }
      $e->delete();
    }
  }
  
  protected static function zerofill($entero, $largo){
    // Limpiamos por si se encontraran errores de tipo en las variables
    $entero = (int)$entero;
    $largo = (int)$largo;
     
    $relleno = '';
     
    /**
     * Determinamos la cantidad de caracteres utilizados por $entero
     * Si este valor es mayor o igual que $largo, devolvemos el $entero
     * De lo contrario, rellenamos con ceros a la izquierda del número
     **/
    if (strlen($entero) < $largo) {
        $apo1=strlen($entero);
        $apo=$largo-$apo1;
        $relleno = str_repeat('0', $apo);
    }
    return $relleno . $entero;
}

  public static function getTareas($user, $pass){
      $gcal = Zend_Gdata_Calendar::AUTH_SERVICE_NAME;
      $feed=false;
      try{
          $client = Zend_Gdata_ClientLogin::getHttpClient($user, $pass);
          $gdata = new Zend_Gdata($client);
          $gdata->setMajorProtocolVersion(3);
          $query = new Zend_Gdata_Query('https://www.googleapis.com/tasks/v1/users/@me/lists?key=AIzaSyBk7gODsG2i6o-z7wIKi7QBHTOIkb6B-FE');
          $feed = $gdata->getFeed($query);
      }catch (Zend_Gdata_App_AuthException $e){
         return false; 
      }
      return $feed;
  }


}

?>
