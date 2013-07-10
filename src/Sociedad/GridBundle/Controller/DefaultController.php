<?php

namespace Sociedad\GridBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Sociedad\Comunes\gCalendar;
use Sociedad\Comunes\mysqlSearchAndReplace;
use Sociedad\Comunes\gTareas;
use Sociedad\Comunes\GoogleTasks;
use Sociedad\ReservasBundle\Entity\Reservas;
use Sociedad\SociosBundle\Entity\Contactos;
use Sociedad\GridBundle\Controller\oauthClientClass;
use \OAuthStore;
use \OAuthRequester;



class DefaultController extends Controller
{
    /**
     * @Route("/hello/{name}")
     * @Template()
     */
    public function indexAction($name)
    {
        return array('name' => $name);
    }

  public function externoAction(){
      
    $em = $this->getDoctrine()->getManager();
    $userManager = $this->get('security.context')->getToken()->getUser();
    if (!$userManager) {
        throw $this->createNotFoundException('Imposible encontrar socio.');
    }
    $Sociedades=$userManager->getSociedades();
    $usuariosociedad=$Sociedades->getEmail();
    //$pass=$Sociedades->getPassword();
    //$idcalendario=$Sociedades->getCalendario();

    $usuario=$userManager->getEmailCanonical();
    $pass=$userManager->getPasswordCanonical();
    $idcalendario=$userManager->getCalendario();
    $contactosociedad = $em->getRepository('SociedadSociosBundle:Contactos')->findBy(array('socios_id'=>$userManager->getId(),'email'=>$usuariosociedad));
    
    $reservas = $em->getRepository('SociedadReservasBundle:Reservas')->TodasReservasFuturas($userManager->getSociedadesId());
      
    
//			ADDPROPERTY(Myarray[m.lccalendarid],"ID",ALLTRIM(Myconstructor.id))
//			ADDPROPERTY(Myarray[m.lccalendarid],"START",Myconstructor.START)
//			ADDPROPERTY(Myarray[m.lccalendarid],"END",Myconstructor.END)
//			ADDPROPERTY(Myarray[m.lccalendarid],"TITULO",ALLTRIM(this.limpiatexto(Myconstructor.TITLE)))
//			ADDPROPERTY(Myarray[m.lccalendarid],"DESCRIPCION",ALLTRIM(this.limpiatexto(Myconstructor.description)))
//			ADDPROPERTY(Myarray[m.lccalendarid],"I_UNICO",Myconstructor.I_UNICO)
//			ADDPROPERTY(Myarray[m.lccalendarid],"LUGAR",Myconstructor.LUGAR)
//			ADDPROPERTY(Myarray[m.lccalendarid],"EMAIL",this.limpiaemail(Myconstructor.EMAIL))
//			ADDPROPERTY(Myarray[m.lccalendarid],"CONTAC_ID",Myconstructor.CONTAC_ID)
//			ADDPROPERTY(Myarray[m.lccalendarid],"GMAIL_ID",Myconstructor.GMAIL_ID)
//			ADDPROPERTY(Myarray[m.lccalendarid],"MODIFICADO",Myconstructor.modificado)
    $eventos = array();
    $contador = 0;
    foreach ($reservas as $reserva) {
        $eventos[$contador]['id']=(is_null($reserva->getCalendarid())) ? '' : $reserva->getCalendarid();
        $eventos[$contador]['start']=$reserva->getFechadesde()->format('Y-m-d H:i:s');
        $eventos[$contador]['end']=$reserva->getFechahasta()->format('Y-m-d H:i:s');
        $eventos[$contador]['titulo']=$reserva->getComida().' en '.$Sociedades->getNombre();
        $eventos[$contador]['descripcion']=$userManager->getName().' te invita a la '.$reserva->getComida().' día '.$eventos[$contador]['start'].' en '.$Sociedades->getNombre();
        $eventos[$contador]['i_unico']=$reserva->getId();
        $eventos[$contador]['lugar']=$Sociedades->getDireccion();
        $eventos[$contador]['email']=$reserva->getCalendario();
        $invitados = $em->getRepository('SociedadReservasBundle:Invitados')->InvitadosEmail($reserva->getId());
        $contai=0;
        $haysociedad=false;
        foreach ($invitados as $invitado){
            if(!empty($invitado['internetid']) && !empty($invitado['email'])){
                $eventos[$contador]['contac_id'][$contai][0]=$invitado['email'];
                $eventos[$contador]['contac_id'][$contai][1]=$invitado['internetid'];
                if($invitado['email']==$usuariosociedad){
                    $haysociedad=true;
                }
                $contai++;
            }
        }
        if(!$haysociedad && $contactosociedad){
            $emailsoci=$contactosociedad[0]->getEmail();
            $internetsoci=$contactosociedad[0]->getEmail();
            if(!empty($internetsoci) && !empty($emailsoci)){
                $eventos[$contador]['contac_id'][$contai][0]=$contactosociedad[0]->getEmail();
                $eventos[$contador]['contac_id'][$contai][1]=$contactosociedad[0]->getInternetid();
            }
        }            
        $eventos[$contador]['modificado']=$reserva->getFechamodi();
        switch ($reserva->getComida()) {
            case 'Desayuno':
                $eventos[$contador]['start']=  str_replace("00:00:00", "08:00:00", $eventos[$contador]['start']);
                $eventos[$contador]['end']=  str_replace("00:00:00", "10:00:00", $eventos[$contador]['end']);
                break;
            case 'Comida':
                $eventos[$contador]['start']=  str_replace("00:00:00", "14:00:00", $eventos[$contador]['start']);
                $eventos[$contador]['end']=  str_replace("00:00:00", "16:00:00", $eventos[$contador]['end']);
                break;
            case 'Merienda':
                $eventos[$contador]['start']=  str_replace("00:00:00", "17:00:00", $eventos[$contador]['start']);
                $eventos[$contador]['end']=  str_replace("00:00:00", "19:00:00", $eventos[$contador]['end']);
                break;

            default:
                $eventos[$contador]['start']=  str_replace("00:00:00", "20:00:00", $eventos[$contador]['start']);
                $eventos[$contador]['end']=  str_replace("00:00:00", "23:00:00", $eventos[$contador]['end']);
                break;
        }
        $contador++;
    }
    
    //$usuario = $this->get('request')->request->get('usuario');
    //$pass = $this->get('request')->request->get('password');
    //$eventos = json_decode($this->get('request')->request->get('calendario'));
    //$idcalendario = $this->get('request')->request->get('idcalendario');
    $modo = $this->get('request')->request->get('modo');
    $minutos = $this->get('request')->request->get('minutos');
    if($minutos=="0"){
        $minutos="";
    }
   
     if($_SERVER['SERVER_NAME']=="localhost"){
//        $usuario = 'anercarlos@gmail.com';
//        $pass = 'prowinaner';
//        $idcalendario='anercarlos@gmail.com';
//        $usuario = 'fortizdezarate@ecenarro.com';
//        $pass = 'fortizdezarate198';
//        $idcalendario='fortizdezarate@ecenarro.com';
//        $eventos=  \json_decode('[{"contac_id": 0,"descripcion": "","email": "","end": "2013-12-05 04:00:00 PM","gmail_id": "","id": "01nbbck4ubmcgqulua029bas9o","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-12-05 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2013-03-28 12:00:00 AM","gmail_id": "","id": "04oh79ctdtefn7hsfc4cs19v5g","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-03-28 12:00:00 AM","titulo": "Festivo"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-08-14 12:00:00 AM","gmail_id": "","id": "0jtdq4h2sa5hnvb91lj6823bt0","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-08-14 12:00:00 AM","titulo": "Festivo"},{"contac_id": 0,"descripcion": "","email": "","end": "2022-12-14 12:00:00 AM","gmail_id": "","id": "0khd74h29a467bni32v8dtar3s","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2022-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2020-12-14 12:00:00 AM","gmail_id": "","id": "0ooh1ijs247v0q0o0gohj8fu6s","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2020-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-12-31 12:00:00 AM","gmail_id": "","id": "0st4hha5e0b3cf9idbqqr50k5c","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-12-31 12:00:00 AM","titulo": "Festivo"},{"contac_id": 1396,"descripcion": "","email": "jlazkano@ecenarro.com","end": "2013-02-18 03:30:00 PM","gmail_id": "724863250a2a06e1","id": "0t067vqatcfbabfa78g45ecaic","i_unico": 0,"lugar": "AMILLAGA KALEA, 15 00000","modificado": "    -  -     :  :   AM","start": "2013-02-18 02:30:00 PM","titulo": "(ECENARRO.,S.COOP).Reunión Semanal"},{"contac_id": 0,"descripcion": "","email": "","end": "2024-12-14 12:00:00 AM","gmail_id": "","id": "0top9acvo5vdidjuqg0rnlft24","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2024-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2016-06-07 12:00:00 AM","gmail_id": "","id": "0vd3v7idr9h7eqqdugk1qi8a68","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2016-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2035-06-07 12:00:00 AM","gmail_id": "","id": "0vkaiorept27m5m7qs18savqa8","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2035-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2027-12-14 12:00:00 AM","gmail_id": "","id": "13sut2m0r87thpeo96tffu4bd4","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2027-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2031-12-14 12:00:00 AM","gmail_id": "","id": "13t51f0pqqen6408ie5fine508","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2031-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-12-26 12:00:00 AM","gmail_id": "","id": "18o1ghtgejn92do3kpcnqju3ug","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-12-26 12:00:00 AM","titulo": "Festivo"},{"contac_id": 0,"descripcion": "","email": "","end": "2022-12-14 12:00:00 AM","gmail_id": "","id": "1e1buileap1iq5tciqvdh2nu88","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2022-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2017-06-07 12:00:00 AM","gmail_id": "","id": "1elvt49ls903174tf5lhmjnvi8","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2017-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-12-25 12:00:00 AM","gmail_id": "","id": "1ffvp14k2kim1r5jol6vppktq8","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-12-25 12:00:00 AM","titulo": "Festivo"},{"contac_id": 0,"descripcion": "","email": "","end": "2035-12-14 12:00:00 AM","gmail_id": "","id": "1geoml2pl4ddmprd91gbrn3ngs","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2035-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-09-12 04:00:00 PM","gmail_id": "","id": "1sp57a1le1gr1f48luk4ob8vn8","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-09-12 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2032-12-14 12:00:00 AM","gmail_id": "","id": "1t6n1eop0993knio1fsidbocg0","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2032-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-02-28 04:00:00 PM","gmail_id": "","id": "1u7q7c30872vuvkjtv5srt1vqs","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-02-28 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2032-06-07 12:00:00 AM","gmail_id": "","id": "21iqan5m1k0iqish05kont4mio","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2032-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2015-06-07 12:00:00 AM","gmail_id": "","id": "24cde8hgfkr1cct3tomgf50t90","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2015-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "CONVOCADO POR: JAVIER  \nMOTIVO: Consejo Direccion extra. Febrero 2012\nFECHA: 07-02-2013\nHORA: 09:00-12:00\nUSUARIOS CONVOCADOS : BEGOÑA,GENI,IÑAKI,JAVIER,JON,JOSEANTO,PAKO    \nLUGAR: Sala Asamblea\nORDEN DEL DIA:\n1- Revision del Sistema por loa Direccion","email": "","end": "2013-02-07 12:00:00 PM","gmail_id": "","id": "2506vaurt8af2tsroou9hb0770","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-02-07 09:00:00 AM","titulo": "Consejo Direccion extra. Febre"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-02-14 04:00:00 PM","gmail_id": "","id": "276nro3njpc72jltnhosf1s0d8","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-02-14 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2021-12-14 12:00:00 AM","gmail_id": "","id": "2dcmv4neoq1ei06p1o7pg04rac","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2021-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2035-12-14 12:00:00 AM","gmail_id": "","id": "2k9onm1bjj3qu41714gbog4vpg","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2035-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-03-01 05:00:00 PM","gmail_id": "","id": "2omeg4e30mc0iq5efp4s62po9o","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-03-01 08:00:00 AM","titulo": "Días opcionales."},{"contac_id": 0,"descripcion": "","email": "","end": "2031-06-07 12:00:00 AM","gmail_id": "","id": "2srhbnpf7dtk47s1h888km32dc","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2031-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2033-12-14 12:00:00 AM","gmail_id": "","id": "2ti4aml3bqhu99a5gcnbs3su6s","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2033-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-05-30 04:00:00 PM","gmail_id": "","id": "36e4hpkjsbsg4pc7tlaaa911gs","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-05-30 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2013-10-03 04:00:00 PM","gmail_id": "","id": "3f2kdu8j6dejd5229p3ch3rp24","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-10-03 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2019-12-14 12:00:00 AM","gmail_id": "","id": "3hv3mhs7ohmjq3dfvj5ludn3ro","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2019-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-03-07 04:00:00 PM","gmail_id": "","id": "3o5lkvp2eefk1aqq5tetcdor7g","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-03-07 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2028-12-14 12:00:00 AM","gmail_id": "","id": "3p63egvn4hur4ca16mmr384ajg","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2028-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2029-06-07 12:00:00 AM","gmail_id": "","id": "3r2tr8ju4i24402u92jobvq3h4","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2029-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2025-12-14 12:00:00 AM","gmail_id": "","id": "45kbl73jamhcs4bb60qlotm42k","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2025-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-05-01 12:00:00 AM","gmail_id": "","id": "49iu59c16ofvsnab3sm19kudi4","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-05-01 12:00:00 AM","titulo": "Festivo"},{"contac_id": 0,"descripcion": "","email": "","end": "2026-06-07 12:00:00 AM","gmail_id": "","id": "49jju4vlah15dofbj138qsldt4","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2026-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2024-12-14 12:00:00 AM","gmail_id": "","id": "4igbfk260tnnl1firuapaa957s","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2024-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-05-02 04:00:00 PM","gmail_id": "","id": "4l2pkho8ufhb8mihde35mjmnf8","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-05-02 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2033-12-14 12:00:00 AM","gmail_id": "","id": "4pd3h030qdub2jtr99kkt1lqsg","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2033-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-05-23 04:00:00 PM","gmail_id": "","id": "4sb31agbuven7vttklkks8uskg","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-05-23 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2023-12-14 12:00:00 AM","gmail_id": "","id": "57f3q6ce7qmvrb8rcigg661eqg","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2023-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2018-06-07 12:00:00 AM","gmail_id": "","id": "57th9tlsuqftkth7mtph5orc1g","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2018-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-02-28 05:00:00 PM","gmail_id": "","id": "58ako7eam63fflmje7b7c1dpbc","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-02-28 08:00:00 AM","titulo": "Días opcionales."},{"contac_id": 0,"descripcion": "","email": "","end": "2036-12-14 12:00:00 AM","gmail_id": "","id": "5l304jeukdt82pvo5f3a4d8p70","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2036-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2030-12-14 12:00:00 AM","gmail_id": "","id": "5pbsnpk413d58abf6mg2tl4ulc","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2030-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2029-12-14 12:00:00 AM","gmail_id": "","id": "5stq5l5dd73uc9v5joavamps74","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2029-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-03-04 01:00:00 PM","gmail_id": "","id": "648mejj18omb8grpoqtbc7u8us","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-03-04 09:00:00 AM","titulo": "(Comité de Calidad Total (Divisiónal)).Comité de Calidad Total"},{"contac_id": 0,"descripcion": "","email": "","end": "2021-12-14 12:00:00 AM","gmail_id": "","id": "66a843kqa4qp6du1i6soiao7lo","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2021-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2036-06-07 12:00:00 AM","gmail_id": "","id": "6degm3pdg17lfgmhier855ebog","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2036-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2019-06-07 12:00:00 AM","gmail_id": "","id": "6nhdf2617ilmcca09ft91jrqck","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2019-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2025-12-14 12:00:00 AM","gmail_id": "","id": "6os6a3d9lkp5gaum1t46pqc7ac","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2025-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-07-25 04:00:00 PM","gmail_id": "","id": "6tcd5fhrcha3fbmu0gp2cj4le8","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-07-25 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2013-02-21 04:00:00 PM","gmail_id": "","id": "6ukgc9rgn9d0jn12qa2peuidp4","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-02-21 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2013-08-01 04:00:00 PM","gmail_id": "","id": "6v1b7lv91mh2pkeundqbc72js8","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-08-01 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2019-12-14 12:00:00 AM","gmail_id": "","id": "78i9v3kphfr9jnobiv0lknmbe0","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2019-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-09-02 01:00:00 PM","gmail_id": "","id": "7hbvuajse0nb6tvq13mup6abs0","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-09-02 09:00:00 AM","titulo": "(Comité de Calidad Total (Divisiónal)).Comité de Calidad Total"},{"contac_id": 0,"descripcion": "","email": "","end": "2021-12-14 12:00:00 AM","gmail_id": "","id": "7lt562d645hf6qj74r20e75tno","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2021-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2027-06-07 12:00:00 AM","gmail_id": "","id": "7poh29k3ivlau142hgvdtou11g","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2027-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2024-06-07 12:00:00 AM","gmail_id": "","id": "7qv49skfrnv1f16jnkv4sh9ob0","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2024-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2020-12-14 12:00:00 AM","gmail_id": "","id": "80j1kpnbsvlf5n4bkifojvgirc","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2020-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-08-15 04:00:00 PM","gmail_id": "","id": "85kjpgaoqgb2vh44v1is1hlebk","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-08-15 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2020-06-07 12:00:00 AM","gmail_id": "","id": "85vfv9a8hvvcdqnb8kdjlnmm14","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2020-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "CONVOCADO POR: ELIG    \nMOTIVO: Cumplimentación de entregas.\nFECHA: 08-02-2013\nHORA: 11:00-12:00\nUSUARIOS CONVOCADOS : ELIG,JAVIER,MERTXE,PAKO,XABI    \nLUGAR: \nORDEN DEL DIA:\n1.- Cierre 2012.\n2.- Cumplimentación entregas  clientes -  enero 2.013.\n3.- Sercicio entregas provedores - enero 2.013","email": "","end": "2013-02-08 12:00:00 PM","gmail_id": "","id": "8710gfeek2ugvdv0ss1g5afue8","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-02-08 11:00:00 AM","titulo": "Cumplimentación de entregas."},{"contac_id": 0,"descripcion": "","email": "","end": "2023-06-07 12:00:00 AM","gmail_id": "","id": "8a3e3f4gpf14abakmdatt9hvpo","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2023-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2019-06-07 12:00:00 AM","gmail_id": "","id": "8e9o8042t2q6a62h6rjmseuo5s","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2019-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2016-06-07 12:00:00 AM","gmail_id": "","id": "91p903horutvu5n9dvrf588ho4","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2016-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2016-12-14 12:00:00 AM","gmail_id": "","id": "9517cva2ku58us2nt6vpjctdds","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2016-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2027-12-14 12:00:00 AM","gmail_id": "","id": "97h35s731armsiqornr32tuu9k","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2027-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-12-12 04:00:00 PM","gmail_id": "","id": "983a8kc7dad3i4ab811ht88o2c","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-12-12 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2030-12-14 12:00:00 AM","gmail_id": "","id": "99p2klt4l1n6hfobvv594mbnbs","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2030-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-02-07 04:00:00 PM","gmail_id": "","id": "9e37r2doj4kcg433o1kd9i9ohk","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-02-07 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2025-12-14 12:00:00 AM","gmail_id": "","id": "9fafoo923cpmpmi05jcsnvu6oc","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2025-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-03-29 12:00:00 AM","gmail_id": "","id": "9hsks9eertu1crkvbe33924dn0","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-03-29 12:00:00 AM","titulo": "Festivo"},{"contac_id": 0,"descripcion": "","email": "","end": "2032-12-14 12:00:00 AM","gmail_id": "","id": "9jm83qec3lc8furpqjksnu0oco","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2032-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2034-06-07 12:00:00 AM","gmail_id": "","id": "9m1ecj0i2gn9rjqfcbrmg3bghg","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2034-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-09-05 04:00:00 PM","gmail_id": "","id": "9obejhbenhqjl3p22bs5q0tsj0","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-09-05 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2014-12-14 12:00:00 AM","gmail_id": "","id": "9opjjh5kbgc0laor7u621ruu64","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2014-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2014-12-14 12:00:00 AM","gmail_id": "","id": "9rflulggsppl5ui6n6jpt3ko3g","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2014-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-05-16 04:00:00 PM","gmail_id": "","id": "9v30hkrq6mq1dieqeun0jd2t14","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-05-16 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2013-03-14 04:00:00 PM","gmail_id": "","id": "a5lirkf46urg6rppvc3vkfgq0k","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-03-14 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2024-12-14 12:00:00 AM","gmail_id": "","id": "ab1qgumhkgo331nem4gg5qgq1o","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2024-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2022-06-07 12:00:00 AM","gmail_id": "","id": "agg0pnpa027qn2c10eb6qh2tvc","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2022-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-03-28 04:00:00 PM","gmail_id": "","id": "bg3l7065aslnsolemvpk6rrt7k","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-03-28 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2018-12-14 12:00:00 AM","gmail_id": "","id": "bm3u7qei6g2qs49nmvjluj1tgo","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2018-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-02-25 05:00:00 PM","gmail_id": "","id": "bpm1uq2avsh8dajhmk8b56bk4g","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-02-25 08:00:00 AM","titulo": "Días opcionales."},{"contac_id": 0,"descripcion": "","email": "","end": "2013-05-21 12:00:00 AM","gmail_id": "","id": "bu5ur17j1u9l85lb0oov9rb6ls","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-05-21 12:00:00 AM","titulo": "Festivo"},{"contac_id": 0,"descripcion": "","email": "","end": "2031-12-14 12:00:00 AM","gmail_id": "","id": "c2l5tfb3bipct2nnsjis3et5lo","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2031-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2014-12-14 12:00:00 AM","gmail_id": "","id": "c6ip0lm9hk24m7ndghjf0g9soc","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2014-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2017-06-07 12:00:00 AM","gmail_id": "","id": "c73cmskqghph9pf0c13ncqh1ss","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2017-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-05-09 04:00:00 PM","gmail_id": "","id": "c87hnqu17asdhe5aof7shedung","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-05-09 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2013-11-21 04:00:00 PM","gmail_id": "","id": "cl4na7eqe6s68r20kvo1lf23p0","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-11-21 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2013-06-20 04:00:00 PM","gmail_id": "","id": "crjj5jh7d46ltjdv4u7ujbgiek","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-06-20 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2028-12-14 12:00:00 AM","gmail_id": "","id": "crnpqs2cpqdit2f3njc8f9i6m4","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2028-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-12-06 12:00:00 AM","gmail_id": "","id": "d1hqifetqkvd8s0ik3vchkl6oc","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-12-06 12:00:00 AM","titulo": "Festivo"},{"contac_id": 0,"descripcion": "","email": "","end": "2028-12-14 12:00:00 AM","gmail_id": "","id": "d1sakf8girqbtpm035lplalshk","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2028-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-05-20 12:00:00 AM","gmail_id": "","id": "d7pdenr1sdpd6fia2n192bedk8","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-05-20 12:00:00 AM","titulo": "Festivo"},{"contac_id": 0,"descripcion": "","email": "","end": "2015-12-14 12:00:00 AM","gmail_id": "","id": "d99fjobajc5ut2q304tkotrhbg","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2015-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2023-12-14 12:00:00 AM","gmail_id": "","id": "dcqruq61icvevvhrsnsf77203g","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2023-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2015-12-14 12:00:00 AM","gmail_id": "","id": "dg26v7s3dpc844q9gi2cbask3o","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2015-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2014-12-14 12:00:00 AM","gmail_id": "","id": "di716kimo6383dr6fpd1032984","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2014-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-07-04 04:00:00 PM","gmail_id": "","id": "di8ivbd73k60t8tp5lvu73fgf8","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-07-04 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2026-12-14 12:00:00 AM","gmail_id": "","id": "dmdiomgog6mbe5i9c6ibrl4l8c","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2026-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2023-12-14 12:00:00 AM","gmail_id": "","id": "dt7phiv912254gv0glcc4hiv68","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2023-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2030-06-07 12:00:00 AM","gmail_id": "","id": "dvpminkqf6hfu8a87mf12umf7g","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2030-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-10-24 04:00:00 PM","gmail_id": "","id": "eed6ghn09082qihqhuoj8trqs8","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-10-24 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2015-06-07 12:00:00 AM","gmail_id": "","id": "ef9374ub4j1c9pssru0v2d2bd0","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2015-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2024-12-14 12:00:00 AM","gmail_id": "","id": "eij25ndb0hohq3kjjg25h02g40","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2024-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2027-12-14 12:00:00 AM","gmail_id": "","id": "ekebe9n4eflj24ete28sq79q3s","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2027-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2017-12-14 12:00:00 AM","gmail_id": "","id": "eno9tg36kt9hda6ego7pv7n7rc","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2017-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-11-07 04:00:00 PM","gmail_id": "","id": "esi516k1h70ncde3dmq08so3j8","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-11-07 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2022-06-07 12:00:00 AM","gmail_id": "","id": "et7nhecfo2370c4201lagre4ns","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2022-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-04-01 12:00:00 AM","gmail_id": "","id": "f5sf5um3o98uu3utat50h21oqk","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-04-01 12:00:00 AM","titulo": "Festivo"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-09-19 04:00:00 PM","gmail_id": "","id": "f8b4afq06iefbevpqiv5j4mb3k","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-09-19 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2034-12-14 12:00:00 AM","gmail_id": "","id": "fbj61cl9bocmd39161k4o2t5s0","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2034-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-02-26 05:00:00 PM","gmail_id": "","id": "fd5u7lsa0g36hktmbculdkk0ao","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-02-26 08:00:00 AM","titulo": "Días opcionales."},{"contac_id": 0,"descripcion": "","email": "","end": "2029-06-07 12:00:00 AM","gmail_id": "","id": "fobk48oj785596fmf5b0jnrl5k","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2029-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-03-21 04:00:00 PM","gmail_id": "","id": "frq6tj8rhdeqpoqb3e1h46ctds","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-03-21 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2014-06-07 12:00:00 AM","gmail_id": "","id": "fsj4c0ks6263p4nqtrak0p44mo","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2014-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-11-01 12:00:00 AM","gmail_id": "","id": "gai2nk9urr7q1onrr6girvndeg","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-11-01 12:00:00 AM","titulo": "Festivo"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-12-30 12:00:00 AM","gmail_id": "","id": "gpo55sa1kd0pbc3afa47i8dgso","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-12-30 12:00:00 AM","titulo": "Festivo"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-06-13 04:00:00 PM","gmail_id": "","id": "grmr7e7rrlq7vrktekmnbnuovs","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-06-13 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2013-08-08 04:00:00 PM","gmail_id": "","id": "h5obphl1vc82v98ll5rdj0s11k","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-08-08 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2029-12-14 12:00:00 AM","gmail_id": "","id": "h5v0b4441nbcdmrd0pbdspudg8","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2029-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2025-06-07 12:00:00 AM","gmail_id": "","id": "h8iv4l2ocrkdf48kmsvd4ueovk","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2025-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-04-25 04:00:00 PM","gmail_id": "","id": "hbl6sie9gr6b1tunuknlpln1cc","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-04-25 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2037-12-14 12:00:00 AM","gmail_id": "","id": "hcuomaf574lv1u6uldb3tvks78","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2037-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2023-12-14 12:00:00 AM","gmail_id": "","id": "hos3vg73e7p5hb7j1frn9mod58","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2023-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-12-27 12:00:00 AM","gmail_id": "","id": "hqnmaml2saq3cijqevm6inhchk","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-12-27 12:00:00 AM","titulo": "Festivo"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-10-25 12:00:00 AM","gmail_id": "","id": "hutnphlu9s4d545lrjmr024c9o","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-10-25 12:00:00 AM","titulo": "Festivo"},{"contac_id": 0,"descripcion": "","email": "","end": "2026-12-14 12:00:00 AM","gmail_id": "","id": "hvqtpcd9aro3t3gkmehq3l600k","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2026-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-11-28 04:00:00 PM","gmail_id": "","id": "i3090doqdhrdur2214a4ihrhh0","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-11-28 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2037-12-14 12:00:00 AM","gmail_id": "","id": "i81tkkaiapds8rq2e8eme67m10","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2037-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2020-12-14 12:00:00 AM","gmail_id": "","id": "i9uot62lbn96uv14qpavcisr1s","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2020-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "CONVOCADO POR: JON.A   \nMOTIVO: Propuesta del Instituto Miguel Altuna para visitar estampadores en Rusia\nFECHA: 06-02-2013\nHORA: 15:30-17:00\nUSUARIOS CONVOCADOS : IÑAKI,JAVIER,JON.A,PAKO    \nLUGAR: Ecenarro\nORDEN DEL DIA:","email": "","end": "2013-02-06 05:00:00 PM","gmail_id": "","id": "ibfos28kdr85l0lj0fg4ret36g","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-02-06 03:30:00 PM","titulo": "Propuesta del Instituto Miguel"},{"contac_id": 0,"descripcion": "","email": "","end": "2015-12-14 12:00:00 AM","gmail_id": "","id": "jistdb0k125rnfm4mv1gihdl7g","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2015-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "CONVOCADO POR: JOSEANTO\nMOTIVO: Auditoria ISO-14001 - Fase 2\nFECHA: 20-02-2013\nHORA: 09:00-18:00\nUSUARIOS CONVOCADOS : AGUILA,AGURTZAN,ALTUNA,ASIER,BEGOÑA,ELIG,ELIY,ENRI,GENI,IÑAKI,JAGOBA,JAVIER,JON,JON.A,JOSEANTO,LUISA,MARILU,MARISOL,MERTXE,MOLI,MOLINA,PAKO,POZO,RAFA,SANTI,SUSANA,XABI    \nLUGAR: ECENARRO\nORDEN DEL DIA:\nAUDITORIA MEDIOAMBIENTAL PARA CERTIFICACION.","email": "","end": "2013-02-20 06:00:00 PM","gmail_id": "","id": "jkll230lp3ln4rifretop072dg","i_unico": 0,"lugar": "PZ.DE EUSKADI, 5, PLANTA 20ª EDIF.TORRE 48009 BILBAO","modificado": "    -  -     :  :   AM","start": "2013-02-20 09:00:00 AM","titulo": "AENOR PAIS VASCO( BILBAO).Auditoria ISO-14001 - Fase 2"},{"contac_id": 0,"descripcion": "","email": "","end": "2023-06-07 12:00:00 AM","gmail_id": "","id": "juructiuc1imrj6fgga18dvghk","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2023-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-12-26 04:00:00 PM","gmail_id": "","id": "k5uablkj81u2ko3movadcuo8v0","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-12-26 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "CONVOCADO POR: MERTXE  \nMOTIVO: reunión Equipo EP7- apoyoa compras\nFECHA: 04-02-2013\nHORA: 11:00-13:00\nUSUARIOS CONVOCADOS : JAGOBA,JAVIER,JOSEANTO,MARISOL,MERTXE,PAKO    \nLUGAR: \nORDEN DEL DIA:\n1.- Lectura del acta anterior y repaso de compromisos\n2.- seguimiento de homologación proveedores\n3.- analisis de indicadores\n4.- objetivos 2013","email": "","end": "2013-02-04 01:00:00 PM","gmail_id": "","id": "ka4imgfpmj695m9i7m126ebui8","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-02-04 11:00:00 AM","titulo": "reunión Equipo EP7- apoyoa com"},{"contac_id": 0,"descripcion": "","email": "","end": "2018-12-14 12:00:00 AM","gmail_id": "","id": "ke7v6jj732c7r8ekmk76bgr5rg","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2018-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2028-12-14 12:00:00 AM","gmail_id": "","id": "ke9sm7qvn282to93u3ola85ggo","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2028-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2020-06-07 12:00:00 AM","gmail_id": "","id": "kfjilljdrfq4lm030heesquq10","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2020-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2032-06-07 12:00:00 AM","gmail_id": "","id": "kfn39hf4c86ut7645e8ps9vcq8","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2032-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2019-12-14 12:00:00 AM","gmail_id": "","id": "kg474dlaseti54mgvnsqq0gp10","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2019-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-07-01 01:00:00 PM","gmail_id": "","id": "kobfpev83d347l5ha7ehq2smgk","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-07-01 09:00:00 AM","titulo": "(Comité de Calidad Total (Divisiónal)).Comité de Calidad Total"},{"contac_id": 0,"descripcion": "","email": "","end": "2031-06-07 12:00:00 AM","gmail_id": "","id": "kq7fljmben8oduosb0rcirvgm4","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2031-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-08-15 12:00:00 AM","gmail_id": "","id": "krffk5ctt3lvv2dm0l3jlblcgo","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-08-15 12:00:00 AM","titulo": "Festivo"},{"contac_id": 0,"descripcion": "","email": "","end": "2030-12-14 12:00:00 AM","gmail_id": "","id": "kuob1suumlfiqkd808l7f0rv9k","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2030-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2031-12-14 12:00:00 AM","gmail_id": "","id": "l4i282k1f8g8rt8dd33ih9r5gc","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2031-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-12-19 04:00:00 PM","gmail_id": "","id": "l9an22tad2p3a30mu70ft1grvc","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-12-19 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2013-04-18 04:00:00 PM","gmail_id": "","id": "le8238hqbkbks1tpggmagdjmlg","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-04-18 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2030-06-07 12:00:00 AM","gmail_id": "","id": "lnis4gct3rlesuc58skmbiml4g","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2030-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-10-10 04:00:00 PM","gmail_id": "","id": "lo8egfa61v0hono4gq8n5qd56k","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-10-10 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2021-06-07 12:00:00 AM","gmail_id": "","id": "lptdqi9sjtd370ulha31i7g86k","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2021-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2025-06-07 12:00:00 AM","gmail_id": "","id": "m36cl5v1ph1e5254jt5ov8jirs","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2025-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2022-12-14 12:00:00 AM","gmail_id": "","id": "m5fcbvah8l0qrnhl4dslts4cg0","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2022-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2030-12-14 12:00:00 AM","gmail_id": "","id": "m6i150e26gqpfl967i6uggdndc","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2030-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2016-12-14 12:00:00 AM","gmail_id": "","id": "m7bkbls8l3j6depd11s48tdkpk","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2016-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-10-31 04:00:00 PM","gmail_id": "","id": "mjo2uhb7ft5k1g1t3tupppplqc","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-10-31 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2013-08-12 12:00:00 AM","gmail_id": "","id": "mkq95ca0gphjumj3tt3fir9gmk","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-08-12 12:00:00 AM","titulo": "Festivo"},{"contac_id": 0,"descripcion": "","email": "","end": "2027-12-14 12:00:00 AM","gmail_id": "","id": "mli7i3kjoq3670i5ir5baul27s","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2027-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2037-06-07 12:00:00 AM","gmail_id": "","id": "mn4j96ggn42odquta0t1vsqlas","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2037-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2026-12-14 12:00:00 AM","gmail_id": "","id": "mnpt7nilgth36n1m4c3h6rbga8","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2026-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2029-12-14 12:00:00 AM","gmail_id": "","id": "mqjubre63etaruib6rlfe4lns4","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2029-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2020-12-14 12:00:00 AM","gmail_id": "","id": "mrudl72gnvafu59gbc04ucrq2c","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2020-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2021-12-14 12:00:00 AM","gmail_id": "","id": "mvqiim5bt2aaqed8vj03mkugb0","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2021-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-07-18 04:00:00 PM","gmail_id": "","id": "n2rlc1t1vf2u2c4pdnjhb8ld6c","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-07-18 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2013-12-23 12:00:00 AM","gmail_id": "","id": "n3p62nosuc283gk2gl5rthkr6c","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-12-23 12:00:00 AM","titulo": "Festivo"},{"contac_id": 0,"descripcion": "","email": "","end": "2018-12-14 12:00:00 AM","gmail_id": "","id": "n4bcmm0t77njsocba3hvrd1hdc","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2018-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-11-04 01:00:00 PM","gmail_id": "","id": "n4ut7tpgadf4sqjt635o2iekvg","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-11-04 09:00:00 AM","titulo": "(Comité de Calidad Total (Divisiónal)).Comité de Calidad Total"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-04-11 04:00:00 PM","gmail_id": "","id": "n7pufv7t7hou3ia9m6riq1mkr8","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-04-11 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2024-06-07 12:00:00 AM","gmail_id": "","id": "n981d64e8c2962kv7pam39u1bc","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2024-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-09-26 04:00:00 PM","gmail_id": "","id": "ngvhffj0t2r8ck7cjmpi3fkmqg","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-09-26 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2021-06-07 12:00:00 AM","gmail_id": "","id": "o0amgcocrg5ektt4vogtbb50kg","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2021-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-06-27 04:00:00 PM","gmail_id": "","id": "o6jd7ttf807q4s4h7tmnlrcikc","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-06-27 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2019-12-14 12:00:00 AM","gmail_id": "","id": "o8o03dovifkpehvs4mrtctl0r0","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2019-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2018-12-14 12:00:00 AM","gmail_id": "","id": "obmgjf3tu4ucd0hvfekrk4fcbc","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2018-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2016-12-14 12:00:00 AM","gmail_id": "","id": "ogmhhg0jhfltsu2g8227qf90ds","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2016-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2027-06-07 12:00:00 AM","gmail_id": "","id": "ol3naqkrjsr0ft7ghk3o8g8jn8","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2027-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "CONVOCADO POR: JOSEANTO\nMOTIVO: Reunión Cte.S y S.\nFECHA: 13-03-2013\nHORA: 11:00-13:00\nUSUARIOS CONVOCADOS : ELIY,JAVIER,JOSEANTO,PAKO,XABI    \nLUGAR: ECENARRO\nORDEN DEL DIA:\n1.- Lectura del acta anterior.\n2.- Temas y acciones pendientes.\n3.- Temas varios","email": "","end": "2013-03-13 01:00:00 PM","gmail_id": "","id": "on4dt855fjt0gikgopkrggq55s","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-03-13 11:00:00 AM","titulo": "Reunión Cte.S y S."},{"contac_id": 0,"descripcion": "","email": "","end": "2013-06-06 04:00:00 PM","gmail_id": "","id": "p12kja9m0qjr4e5aunjrr8tvfs","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-06-06 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2013-12-24 12:00:00 AM","gmail_id": "","id": "pbb636dohm57plg6rccopmtvo8","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-12-24 12:00:00 AM","titulo": "Festivo"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-08-16 12:00:00 AM","gmail_id": "","id": "pdc1j2tnketdtv2o6qsco5399k","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-08-16 12:00:00 AM","titulo": "Festivo"},{"contac_id": 0,"descripcion": "","email": "","end": "2036-12-14 12:00:00 AM","gmail_id": "","id": "petjipnbo62hd0p8vkmomu0cfk","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2036-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2025-12-14 12:00:00 AM","gmail_id": "","id": "q8d0p7ttldv93e8mppsjpd11i8","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2025-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2014-06-07 12:00:00 AM","gmail_id": "","id": "qjfna1ea5imrb50f012g5ftmcg","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2014-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2026-12-14 12:00:00 AM","gmail_id": "","id": "qlfnr9ft9oj48hsiemcdmefr04","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2026-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2026-06-07 12:00:00 AM","gmail_id": "","id": "qqn517heqh636q74v6mackgqvk","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2026-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-08-13 12:00:00 AM","gmail_id": "","id": "qvjse55ncmepa3gddmprrng3bo","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-08-13 12:00:00 AM","titulo": "Festivo"},{"contac_id": 1396,"descripcion": "","email": "jlazkano@ecenarro.com","end": "2013-02-25 03:30:00 PM","gmail_id": "724863250a2a06e1","id": "r3ek6fl731crdf0fqtd01ierak","i_unico": 0,"lugar": "AMILLAGA KALEA, 15 00000","modificado": "    -  -     :  :   AM","start": "2013-02-25 02:30:00 PM","titulo": "(ECENARRO.,S.COOP).Reunión Semanal"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-07-11 04:00:00 PM","gmail_id": "","id": "r42o00tqqjsaideeo1nepll580","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-07-11 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2028-06-07 12:00:00 AM","gmail_id": "","id": "r6ci9f7ejrgog2n8raghtrsfvg","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2028-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2028-06-07 12:00:00 AM","gmail_id": "","id": "rheabu64j1sktngaf445tqkcdo","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2028-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-09-16 12:00:00 AM","gmail_id": "","id": "s0dedcs1softtg0mvhmuiktq1o","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-09-16 12:00:00 AM","titulo": "Festivo"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-02-04 03:30:00 PM","gmail_id": "","id": "satvocv8spgv4328enuork92p8","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-02-04 02:30:00 PM","titulo": "Despacho Pako"},{"contac_id": 0,"descripcion": "","email": "","end": "2018-06-07 12:00:00 AM","gmail_id": "","id": "sd6sc44d44tsk52l0gkcrfkuvk","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2018-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2032-12-14 12:00:00 AM","gmail_id": "","id": "sfr3jpkk22gmmcl452i9brgr70","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2032-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 1396,"descripcion": "","email": "jlazkano@ecenarro.com","end": "2013-02-11 03:30:00 PM","gmail_id": "724863250a2a06e1","id": "sgqqq2ri9c5edr1r7opr3qdjc4","i_unico": 0,"lugar": "AMILLAGA KALEA, 15 00000","modificado": "    -  -     :  :   AM","start": "2013-02-11 02:30:00 PM","titulo": "(ECENARRO.,S.COOP).Reunión Semanal"},{"contac_id": 0,"descripcion": "","email": "","end": "2033-06-07 12:00:00 AM","gmail_id": "","id": "sinvihecoki7eksi29enpcqek8","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2033-06-07 12:00:00 AM","titulo": "Cumpleaños de Carlos Valea"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-08-22 04:00:00 PM","gmail_id": "","id": "skr9hacbdilb7hiv6nvmk72jno","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-08-22 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2013-10-17 04:00:00 PM","gmail_id": "","id": "t89d30tee9kprt58gc75drh6do","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-10-17 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2013-05-06 01:00:00 PM","gmail_id": "","id": "t8a7v96m1gh8u1ecvmim4vc12k","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-05-06 09:00:00 AM","titulo": "(Comité de Calidad Total (Divisiónal)).Comité de Calidad Total"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-08-29 04:00:00 PM","gmail_id": "","id": "tcgt4thgaulbd1n86ka4gno4ck","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-08-29 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2013-02-27 05:00:00 PM","gmail_id": "","id": "tmje290iik47us9imd30idt59k","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-02-27 08:00:00 AM","titulo": "Días opcionales."},{"contac_id": 0,"descripcion": "","email": "","end": "2015-12-14 12:00:00 AM","gmail_id": "","id": "tsmi44f1f1igplmv3bsnvf2d0k","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2015-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 1396,"descripcion": "","email": "jlazkano@ecenarro.com","end": "2013-02-04 03:30:00 PM","gmail_id": "724863250a2a06e1","id": "u0mv1360hn668r5he8a7f8s6qs","i_unico": 0,"lugar": "AMILLAGA KALEA, 15 00000","modificado": "    -  -     :  :   AM","start": "2013-02-04 02:30:00 PM","titulo": "(ECENARRO.,S.COOP).Reunión Semanal"},{"contac_id": 0,"descripcion": "","email": "","end": "2016-12-14 12:00:00 AM","gmail_id": "","id": "u3b8s6rfocd5fimlf44ba8v3ug","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2016-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2031-12-14 12:00:00 AM","gmail_id": "","id": "u7cifqm7aec7n1amqumdnu61k8","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2031-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-11-14 04:00:00 PM","gmail_id": "","id": "ults2pvenl5djvc7gg0fon2m90","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-11-14 02:30:00 PM","titulo": "English Training."},{"contac_id": 0,"descripcion": "","email": "","end": "2022-12-14 12:00:00 AM","gmail_id": "","id": "uq3h6k2gn4p09vpcd94ueepfck","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2022-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2034-12-14 12:00:00 AM","gmail_id": "","id": "urpnhg4bko133ikp5nls5b22dg","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2034-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-02-07 05:00:00 PM","gmail_id": "","id": "utcsr5nl6ft76sk9q8fuq4ena4","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-02-07 04:00:00 PM","titulo": "Lanbi: Mecanizado casquillo."},{"contac_id": 0,"descripcion": "","email": "","end": "2029-12-14 12:00:00 AM","gmail_id": "","id": "v0pv1lrn36g8g6h4738hoba3o4","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2029-12-14 12:00:00 AM","titulo": "Cumpleaños de Monika (Mintzala"},{"contac_id": 0,"descripcion": "CONVOCADO POR: JON.A   \nMOTIVO: NUEVO DIAGRAMA DE LANZAMIENTO DE NUEVOS PRODUCTOS\nFECHA: 06-02-2013\nHORA: 09:00-11:00\nUSUARIOS CONVOCADOS : IÑAKI,JAGOBA,JAVIER,JON,JON.A,JOSEANTO,MOLINA,PAKO,RAFA,SANTI   \nLUGAR: Ecenarro\nORDEN DEL DIA:\n- Mostrar el nuevo diagrama adjuntado en la gestión documental de esta convocatoria\n- Decidir que tareas requieren trabajar en sus procedimientos\n- Finalizar la plantilla de lanzamiento de nuevos productos que está en el ERP\n- Comentar la separación diseño y utillajes","email": "","end": "2013-02-06 11:00:00 AM","gmail_id": "","id": "v894d3dplg6pgqkl6d1kpp87ek","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-02-06 09:00:00 AM","titulo": "NUEVO DIAGRAMA DE LANZAMIENTO"},{"contac_id": 0,"descripcion": "","email": "","end": "2013-04-04 04:00:00 PM","gmail_id": "","id": "vtove4ftqt38lqb0qblsrkaga4","i_unico": 0,"lugar": "","modificado": "    -  -     :  :   AM","start": "2013-04-04 02:30:00 PM","titulo": "English Training."}]');
//        $modo="alert";
//        $minutos="20";
     }
   
//    para probar limites de tiempo
//    while(true){
//        
//    }
    if(!$idcalendario){
        $idcalendario = 'default';
    }
    //$gCalendar = new \Zend_Gdata_Calendar(); 
    $gcal = gCalendar::activarServicio($usuario, $pass);

    if($eventos){
      //crear o editar visitas
      foreach($eventos as $i => $e){
        //crear una nueva visita en google
        //$e = get_object_vars($e);
        date_default_timezone_set('Europe/Madrid');
        $e['start'] = date(DATE_ATOM, strtotime($e['start']));
        $e['end'] = date(DATE_ATOM, strtotime($e['end']));
        //$e['modificado']= date(DATE_ATOM, strtotime($e['modificado']));
        if($e['id'] == ''){
          $evento = gCalendar::crearEvento($e, $gcal,null,$usuario,$modo,$minutos);
          if($evento){
            $eventos[$i]['id'] = gCalendar::getIdEvento($evento);
            $resultado = $em->getRepository('SociedadReservasBundle:Reservas')->modificaGoogleId($eventos[$i]['i_unico'],$eventos[$i]['id']);        
          }
        }
        //editar una visita en google
        else{
          $evento = gCalendar::obtenerEvento($e['id'], $gcal, $idcalendario, false,$e['modificado']);
          if($evento){
            gCalendar::editarEvento($e, $gcal, $evento,$usuario);
            $resultado = $em->getRepository('SociedadReservasBundle:Reservas')->modificaGoogleId($eventos[$i]['i_unico'],$eventos[$i]['id']);        
          }
          else{
              $eventos[$i]['id']="";
          }
        }
      }
    }
    $reservaid = $this->get('request')->getSession()->get('reservaid');
    if($reservaid){
        return $this->redirect($this->generateUrl('reservas_edit',array('id'=>$reservaid)));      
    }
    $response = new Response(json_encode($eventos));

    $response->headers->set('Content-Type', 'application/json; charset=utf-8');
    return $response;
    
    echo json_encode($eventos);
  }

  public function internoAction(){
      
    $em = $this->getDoctrine()->getManager();
    $userManager = $this->get('security.context')->getToken()->getUser();
    if (!$userManager) {
        throw $this->createNotFoundException('Imposible encontrar socio.');
    }
    $Sociedades=$userManager->getSociedades();
//    $usuario=$Sociedades->getEmail();
//    $pass=$Sociedades->getPassword();
//    $idcalendario=$Sociedades->getCalendario();
    $usuario=$userManager->getEmailCanonical();
    $pass=$userManager->getPasswordCanonical();
    $idcalendario=$userManager->getCalendario();
    $eventos = array();
    $contador = 0;
    $modo = $this->get('request')->request->get('modo');
    $minutos = $this->get('request')->request->get('minutos');
    if($minutos=="0"){
        $minutos="";
    }
   
    if(!$idcalendario){
        $idcalendario = 'default';
    }
    //$gCalendar = new \Zend_Gdata_Calendar(); 
    $gcal = gCalendar::activarServicio($usuario, $pass);

    //obtener listado de visitas de google a partir de la fecha de hoy
    $eventos = array();
    if($gcal){
      $myfechaunmesatras=date(DATE_ATOM,strtotime('-1 month ',strtotime(date('Y-m-d'))));
      $eventos = gCalendar::listaEventosArray($gcal,$idcalendario,$myfechaunmesatras,'',true);
    }
    foreach($eventos as $i => $e){
        $id=  explode('#', $e['id']);
        $reserva = $em->getRepository('SociedadReservasBundle:Reservas')->findBy(array('calendarid'=>$id[0]));
        if(!$reserva){
            continue;
        }
        date_default_timezone_set('Europe/Madrid');
        $modificado=date(DATE_ATOM,strtotime($reserva[0]->getFechamodi()->format('Y-m-d H:i:s')));
        if($modificado>$e['modificado']){
            continue;
        }
        $modificado=false;
        for($x=0;$x<count($e['email']);$x++){
            if($e['rol'][$x]=="organizer"){
                continue;
            }
            if($e['status'][$x]=="invited"){
                continue;
            }
            $email=$e['email'][$x];
            $contacto = $em->getRepository('SociedadSociosBundle:Contactos')->findBy(array('email'=>$email,'socios_id'=>$userManager->getId()));
            if(!$contacto){
                continue;
            }
            $invitado = $em->getRepository('SociedadReservasBundle:Invitados')->findBy(array('reservas_id'=>$reserva[0]->getId(),'contactos_id'=>$contacto[0]->getId()));
            if(!$invitado){
                continue;
            }
            if($e['status'][$x]=='accepted'){
                $invitado[0]->setAcepta('aceptado');
            }else{
                $invitado[0]->setAcepta('rechazado');
            }
            $modificado=true;
        }
        if($modificado){
            $reserva[0]->setFechamodiValue();
            $em->persist($reserva[0]);
            $em->flush();                          
        }
    }

    

    $reservaid = $this->get('request')->getSession()->get('reservaid');
    if($reservaid){
        return $this->redirect($this->generateUrl('reservas_edit',array('id'=>$reservaid)));      
    }
    $response = new Response(json_encode($eventos));
    $response->headers->set('Content-Type', 'application/json; charset=utf-8');
    return $response;
    
    echo json_encode($eventos);
  }
  public function listacalendariosAction(){
    $usuario = $this->get('request')->request->get('usuario');
    $pass = $this->get('request')->request->get('password');
    
     if($_SERVER['SERVER_NAME']=="localhost"){
        //$usuario = 'carlosbeatortega@gmail.com';
        //$pass = 'aitanamikele';
     }

    
    $idcalendario = 'default';
    $gcal = gCalendar::activarServicio($usuario, $pass);
    $uri = gCalendar::getCalendarKey($usuario, $pass);
    //obtener listado de calendario de google 
      $eventos = array();
      $calendars=  array();
      if($gcal){
//        $uri="https://www.googleapis.com/calendar/v3/freeBusy?fields=calendars&key=".$uri;
//        $eventos = gCalendar::getCalendariosList($gcal,$uri);
          $eventos = gCalendar::getCalendarios($gcal);
          $contador=0;
          //echo json_encode($eventos);
            foreach($eventos as $i => $e){
                    $apo=$e->getTitle()->getText();
                    $apoId=utf8_encode($e->getId()->getText());
                    $calendars[$contador]['titulo']=$apo;
                    $calendars[$contador]['id']=$apoId;
                    $contador++;
                //}
            }
          
          $response = new Response(json_encode($calendars));
      }else{
//      }
        $response = new Response(json_encode($uri));
      }

    $response->headers->set('Content-Type', 'application/json; charset=utf-8');
    return $response;
    
    //echo json_encode($eventos);
  }
    public function inoutContactosAction(){
        $primera=$this->clientAction();
        $segunda=$this->setClientAction();
        return $this->redirect($this->generateUrl('contactos'));      
    }
    public function clientAction(){
    $em = $this->getDoctrine()->getManager();
    $userManager = $this->get('security.context')->getToken()->getUser();
    if (!$userManager) {
        throw $this->createNotFoundException('Imposible encontrar socio.');
    }
    
    $usuario=$userManager->getEmailCanonical();
    $pass=$userManager->getPasswordCanonical();
            
     if($_SERVER['SERVER_NAME']=="localhost"){
//        $usuario = 'carlosbeatortega@gmail.com';
//        $pass = 'mikeleaitana';
        // o este
         
     }
        $feed=gCalendar::getClientes($usuario, $pass);    
        $results = array();
        foreach($feed as $entry){
            $obj = new \stdClass;
            $obj->edit = $entry->getEditLink()->href;
            $temp = explode('/', $obj->edit);
            $idunico = $temp[8];
            
            $apoyo=$entry->getXML();
            $xml = simplexml_load_string($entry->getXML());
            $obj->name = (string) $entry->title;
            $obj->orgName = (string) $xml->organization->orgName;
            $obj->orgTitle = (string) $xml->organization->orgTitle;
            $obj->instantmessenger = (string) $xml->im;
            foreach ($xml->email as $e) {
            $obj->emailAddress[] = (string) $e['address'];
            }

            foreach ($xml->phoneNumber as $p) {
            $obj->phoneNumber[] = (string) $p;
            }
            foreach ($xml->website as $w) {
            $obj->website[] = (string) $w['href'];
            }
            $contacto = $em->getRepository('SociedadSociosBundle:Contactos')->contactosSocio($userManager->getId(),$idunico);
            if(!$contacto){
                $contacto  = new Contactos();
                $contacto->setSocios($userManager);
                $contacto->setSociedadesId($userManager->getSociedadesId());
                $contacto->setInternetid($idunico);
            }else{
                $contacto=$contacto[0];
                $modi2=$contacto->getFechamodi();
                $modi=$modi2->format('Y-m-d H:i:s');
                if(!isset($modi)){
                    continue;
                }
                date_default_timezone_set('Europe/Madrid');
                $modificado=date(DATE_ATOM,strtotime((string)$entry->updated->text));
                $modificado2=date(DATE_ATOM,strtotime($modi));
                if($modificado2>$modificado){
                    continue;
                }
            }
            
            $contacto->setNombre($obj->name);
            $contacto->setFechamodiValue();
            foreach ($xml->email as $e) {
                $contacto->setEmail((string) $e['address']);
                break;
            }
            $contador=0;
            foreach ($xml->phoneNumber as $p) {
                if($contador==0){
                    $contacto->setMovil((string) $p);
                }
                if($contador==1){
                    $contacto->setFijo((string) $p);
                }
                $contador++;
            }
            $em->persist($contacto);
            $em->flush();
            
            $results[] = $obj; 
        }
        //$response=$this->setClientAction();
        //return $this->redirect($this->generateUrl('contactos'));      
        
        $response = new Response(json_encode($results));
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');
        return $response;
        
    }    
  
  public function setClientAction(){
//    $usuario = $this->get('request')->request->get('usuario');
//    $pass = $this->get('request')->request->get('password');
//    $eventos = json_decode($this->get('request')->request->get('contactos'));
    $idcalendario = $this->get('request')->request->get('idcalendario');

    $em = $this->getDoctrine()->getManager();
    $userManager = $this->get('security.context')->getToken()->getUser();
    if (!$userManager) {
        throw $this->createNotFoundException('Imposible encontrar socio.');
    }
    
    $usuario=$userManager->getEmailCanonical();
    $pass=$userManager->getPasswordCanonical();
    $contactos = $em->getRepository('SociedadSociosBundle:Contactos')->findBy(array('socios_id'=>$userManager->getId()));
    $eventos = array();
    $contador = 0;
    foreach ($contactos as $contacto) {
        $eventos[$contador]['id']=(is_null($contacto->getInternetid())) ? '' : $contacto->getInternetid();
        $eventos[$contador]['nombre']=$contacto->getNombre();
        $eventos[$contador]['i_unico']=$contacto->getId();
        $eventos[$contador]['email']=$contacto->getEmail();
        $eventos[$contador]['telefono']=$contacto->getFijo();
        $eventos[$contador]['movil']=$contacto->getMovil();
        $eventos[$contador]['modificado']=$contacto->getFechamodi();
        $contador++;
    }
    
     if($_SERVER['SERVER_NAME']=="localhost"){
//           $usuario = 'fortizdezarate@ecenarro.com';
//           $pass = 'fortizdezarate198';
//           $idcalendario='fortizdezarate@ecenarro.com';
//        $usuario = 'carlosbeatortega@gmail.com';
//        $pass = 'mikeleaitana';
//        $idcalendario='carlosbeatortega@gmail.com';
//           $eventos=  \json_decode('[{"cumple": "    -  -     :  :   AM","direccion": "Avda. Santa Ana 7, 2º Of.16","email": "rsantamaria@prysma.es","empresa": "AENOR PAIS VASCO","i_unico": "1660","movil": "667408422","nombre": "Rosario Santa Maria Perez","notas": "Auditora Subcontratada por AENOR, perteneciente a la empresa PRYSMA","pais": "España","poblacion": "LEIOA (Vizcaya)","postal": "48940","provincia": "VIZCAYA","telefono": "902885766","url": "www.aenor.com"},{"cumple": "    -  -     :  :   AM","direccion": "AMILLAGA KALEA, 15","email": "ecenarro@ecenarro.com","empresa": "ECENARRO.,S.COOP","i_unico": "1655","movil": "","nombre": "Etxebarria-arteun Belategi Agurtzane","notas": "","pais": "España","poblacion": "","postal": "00000","provincia": "GUIPUZCOA","telefono": "667542218","url": "www.ecenarro.com"},{"cumple": "    -  -     :  :   AM","direccion": "AMILLAGA KALEA, 15","email": "ecabo@ecenarro.com","empresa": "ECENARRO.,S.COOP","i_unico": "626","movil": "","nombre": "Cabo Fernandez Eugenio","notas": "","pais": "España","poblacion": "","postal": "00000","provincia": "GUIPUZCOA","telefono": "9437625434","url": "www.ecenarro.com"},{"cumple": "    -  -     :  :   AM","direccion": "","email": "lydie.rossignol@zf-lenksysteme.com","empresa": "ZF Systemes de Direction Nacam S.A.S.","i_unico": "1667","movil": "","nombre": "Lydie Rossignol","notas": "","pais": "FRANCIA","poblacion": "F-41100 VENDOME","postal": "","provincia": "FRANCIA","telefono": "33254235283","url": ""},{"cumple": "    -  -     :  :   AM","direccion": "Nordstr.12","email": "htittes@mcgard.de","empresa": "McGard DEUTSCHLAND GMBH","i_unico": "1656","movil": "","nombre": "Harro-Georg Tittes","notas": "","pais": "ALEMANIA FEDERAL","poblacion": "D-74226 NORDHEIM","postal": "","provincia": "ALEMANIA FEDERAL","telefono": "4907133901922","url": ""},{"cumple": "    -  -     :  :   AM","direccion": "Langenauer StraBe 18","email": "dieter.roth@zf.com","empresa": "ZF Friedrichshafen AG","i_unico": "1657","movil": "","nombre": "Dieter Roth","notas": "","pais": "ALEMANIA FEDERAL","poblacion": "KREUZTAL,Deutschland","postal": "57223","provincia": "ALEMANIA FEDERAL","telefono": "495474603410","url": ""}]');
     }
    
    $vuelta=0;
    $tablavuelta="";

    
    if(!$idcalendario){
        $idcalendario = 'default';
    }
    $gdata=gCalendar::setClientes($usuario, $pass);    
    if($eventos){
      //crear o editar visitas
      foreach($eventos as $i => $e){
        //crear una nueva visita en google
//        $e = get_object_vars($e);
             // create new entry
        if(!empty($e['id'])){
            $entry=gCalendar::getCliente($gdata,$e,$usuario);
            if(!$entry){
                continue;
            }
            $xml = simplexml_load_string($entry->getXML());
            //$xml->name->namePrefix = "(".$usuario.")";
            $xml->name->fullname=$e['nombre'];
            $email=$xml->email['address'];
            foreach ($xml->email as $ema) {
                $email1=(string) $ema['address'];
                if((string) $ema['primary']=='true'){
                    $ema['address']=$e['email'];
                }
            }
            foreach ($xml->phoneNumber as $ema) {
                $fonenumber=(string) $ema[0];
                $apo=(string) $ema['rel'];
                $apo1=explode('/',$apo);
                $apo2=explode('#',$apo1[4]);
                if($apo2[1]=='mobile'){
                    $ema[0]=$e['movil'];
                }
                if($apo2[1]=='work'){
                    $ema[0]=$e['telefono'];
                }
            }
            $entryResult  = $gdata->updateEntry($xml->saveXML(),$entry->getEditLink()->href,null,array('If-Match'=>'*'));
            continue;
            
        }
        $doc  = new \DOMDocument();
        $doc->formatOutput = true;
        $entry = $doc->createElement('atom:entry');
        $entry->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:atom', 'http://www.w3.org/2005/Atom');
        $entry->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:gd', 'http://schemas.google.com/g/2005');
        $doc->appendChild($entry);
        // add name element
        $name = $doc->createElement('gd:name');
        $entry->appendChild($name);
        if(empty($e['nombre'])){
            $e['nombre']="Vacio";
        }
        $fullName = $doc->createElement('gd:fullName', $e['nombre']);
        $name->appendChild($fullName);
        // add email element
        $email = $doc->createElement('gd:email');
        $email->setAttribute('address' ,$e['email']);
        $email->setAttribute('rel' ,'http://schemas.google.com/g/2005#home');
        $entry->appendChild($email);
        if(!empty($e['movil'])){
            // añade movil
            $phone = $doc->createElement('gd:phoneNumber', $e['movil']);
            $phone->setAttribute('rel' ,'http://schemas.google.com/g/2005#mobile');
            $entry->appendChild($phone);
        }
        if(!empty($e['telefono'])){
            // añade fijo
            $fijo = $doc->createElement('gd:phoneNumber', $e['telefono']);
            $fijo->setAttribute('rel' ,'http://schemas.google.com/g/2005#work');
            $entry->appendChild($fijo);
        }
//        if(!empty($e['url'])){
//            $url = $doc->createElement('gd:website');
//            $url->setAttribute('rel' ,'http://schemas.google.com/contact/2008');
//            $entry->appendChild($url);
//            $web = $doc->createElement('gd:href',$e['url']);
//            //$url->setAttribute('href' ,$e['url']);
//            //$url->setAttribute('rel' ,'profile');
//            $web->setAttribute('rel' ,'profile');
//            $url->appendChild($web);
//        }
////<website xmlns="http://schemas.google.com/contact/2008" href="www.anerdata.com" rel="home-page"/>        
        
        
        
        // insert entry
        $entryResult = $gdata->insertEntry($doc->saveXML(),'https://www.google.com/m8/feeds/contacts/default/full');
        if(!is_null($entryResult)){
//            $tablavuelta[$vuelta][0]=utf8_encode($entryResult->getId()->getText());
//            $tablavuelta[$vuelta][1]=$e['i_unico'];
//            $eventos[$i]['id'] = gCalendar::getIdEvento($evento);
            $temp = explode('/', utf8_encode($entryResult->getId()->getText()));
            $idunico = $temp[8];
            
            $resultado = $em->getRepository('SociedadSociosBundle:Contactos')->modificaGoogleId($e['i_unico'],$idunico);        

            $vuelta++;
        }
       // echo '<h2>Add Contact</h2>';
       // echo 'The ID of the new entry is: ' . $entryResult->id;
      }    
    }
//    if($vuelta==0){
//        $vuelta=$this->get('request')->request->get('contactos');
//    }
    
        
    $response = new Response(json_encode($tablavuelta));
    $response->headers->set('Content-Type', 'application/json; charset=utf-8');
    return $response;
  }
  public function borraVisitaAction(){
    $usuario = $this->get('request')->request->get('usuario');
    $pass = $this->get('request')->request->get('password');
    $visita = json_decode($this->get('request')->request->get('visita'));
    $idcalendario = $this->get('request')->request->get('idcalendario');
    
     if($_SERVER['SERVER_NAME']=="localhost"){
//   $usuario = 'carlosbeatortega@gmail.com';
//   $pass = 'aitanamikele';
//   $idcalendario='carlosbeatortega@gmail.com';
//   $visita="p5d58figm2monk5glds32kpvt4";
     }
    if(!$idcalendario){
        $idcalendario = 'default';
    }
    //$gCalendar = new \Zend_Gdata_Calendar(); 
    $gcal = gCalendar::activarServicio($usuario, $pass);
    $results="OK";
    try {
        if($gcal && $visita){
            $evento = gCalendar::obtenerEvento($visita, $gcal, $idcalendario, false);
            if($evento){
                $evento->delete();
            }
        }
    } catch (Zend_Gdata_App_Exception $e) {
        $results=$e->getResponse();
    }        
    $response = new Response(json_encode($results));
    $response->headers->set('Content-Type', 'application/json; charset=utf-8');
    return $response;
    
  }
  public function cerrarsesionAction(){
      session_destroy();
      exit;      
  }
  
  public function limpiaAction(){
      $basedatos = $this->get('request')->request->get('basedatos');
      $basedatos="aner";
      $mysqlSearchAndReplace = new mysqlSearchAndReplace($basedatos, "localhost", "root", "");
      $mysqlSearchAndReplace->searchAndReplace("de nada", "Muchas gracias");
      $mysqlSearchAndReplace->searchAndReplace('ÃƒÂ­', 'í');
      $mysqlSearchAndReplace->searchAndReplace('ÃƒÂ³', 'ó');
      $mysqlSearchAndReplace->searchAndReplace('ÃƒÂ¡', 'á');
      $mysqlSearchAndReplace->searchAndReplace('ÃƒÂº', 'ú');
      $mysqlSearchAndReplace->searchAndReplace('ÃƒÂ©', 'é');
      $mysqlSearchAndReplace->searchAndReplace('ÃƒÂ±', 'ñ');
      $mysqlSearchAndReplace->searchAndReplace('ÃƒÂ', 'Í');
      $mysqlSearchAndReplace->searchAndReplace('Ãƒâ€œ', 'Ó');
      $mysqlSearchAndReplace->searchAndReplace('ÃƒÂ', 'Á');
      
      $mysqlSearchAndReplace->searchAndReplace('Ã¡', 'á');
      $mysqlSearchAndReplace->searchAndReplace('Ã©', 'é');
      $mysqlSearchAndReplace->searchAndReplace('Ã*', 'í');
      $mysqlSearchAndReplace->searchAndReplace('Ã³', 'ó');
      $mysqlSearchAndReplace->searchAndReplace('Ãº', 'ú');

      $mysqlSearchAndReplace->searchAndReplace('Ã', 'Á');
      $mysqlSearchAndReplace->searchAndReplace('Ã‰ ', 'É');
      $mysqlSearchAndReplace->searchAndReplace('Ã', 'Í');
      $mysqlSearchAndReplace->searchAndReplace('Ã“', 'Ó');
      $mysqlSearchAndReplace->searchAndReplace('Ãš', 'Ú');

      $mysqlSearchAndReplace->searchAndReplace('Ã±', 'ñ');
      $mysqlSearchAndReplace->searchAndReplace('Ã‘', 'Ñ');
      $response = new Response(json_encode('Terminado'.$basedatos));
      return $response;

      
}
    public function borraClientAction(){
        $usuario = $this->get('request')->request->get('usuario');
        $pass = $this->get('request')->request->get('password');
        $id = $this->get('request')->request->get('contacto');

        if($_SERVER['SERVER_NAME']=="localhost"){
    //        $usuario = 'aner.asier@gmail.com';
    //        $pass = 'prowinaner';
    //        $id="4e3698708861c1bd";
            // o este

        }
        $id='https://www.google.com/m8/feeds/contacts/default/full/'.$id;
        $respuesta="";
        $gcal=gCalendar::getClienteControlador($usuario, $pass);    
       if($gcal){
           $vuelta=$gcal->delete($id);
           if(!null===$vuelta){
            $respuesta="borrado";
           }
       }
       $response = new Response(json_encode($respuesta));
       $response->headers->set('Content-Type', 'application/json; charset=utf-8');
       return $response;

    }
    public function modificaClientAction(){
        $usuario = $this->get('request')->request->get('usuario');
        $pass = $this->get('request')->request->get('password');
        $id = $this->get('request')->request->get('contacto');
        $myusuario = $this->get('request')->request->get('prefijo');

        if($_SERVER['SERVER_NAME']=="localhost"){
            $usuario = 'anercarlos@gmail.com';
            $pass = 'prowinaner';
            $id="30b427e88ff75199";
            $myusuario="MARISOL";
            // o este

        }
        $id='https://www.google.com/m8/feeds/contacts/default/full/'.$id;
        $respuesta="";
        $gcal=gCalendar::getClienteControlador($usuario, $pass,'cp');    
        $client=gCalendar::getClienteModificaControlador($gcal,$id,$myusuario);    
        if($client){
            $respuesta="Modificado";
       }
       $response = new Response(json_encode($respuesta));
       $response->headers->set('Content-Type', 'application/json; charset=utf-8');
       return $response;

    }

    
    public function listatareasAction(){
        $usuario = $this->get('request')->request->get('usuario');
        $pass = $this->get('request')->request->get('password');

        if($_SERVER['SERVER_NAME']=="localhost"){
//            $usuario = 'carlosbeatortega@gmail.com';
//            $pass = 'mikeleaitana';

        }
        $tareas=  array();
        $gt = new GoogleTasks($usuario,$pass);
        if($gt){
            $listas = $gt->getListasTareas();
            if($listas){
                $contador=0;
                foreach($listas as $lista){
                    $tareas[$contador]['titulo']=utf8_encode($lista->title);
                    $tareas[$contador]['id']=$lista->id;
                    $contador++;
                }
            }
        }
        $response = new Response(json_encode($tareas));
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');
        return $response;
    }	
    
    public function tareasAction(){
        $usuario = $this->get('request')->request->get('usuario');
        $pass = $this->get('request')->request->get('password');
        $id = $this->get('request')->request->get('id');

        if($_SERVER['SERVER_NAME']=="localhost"){
//            $usuario = 'anercarlos@gmail.com';
//            $pass = 'prowinaner';
//            $id = 'MDM2NjczNjExODMzMzMzMjA4MjY6MDow';
//            $usuario = 'fortizdezarate@ecenarro.com';
//            $pass = 'fortizdezarate198';
//            $id = 'MTE3ODI4MzU3ODU3Mzg3MTEyMzc6NzIzOTE1MTI3OjA';
        }
        $tareas=  array();
        $gt = new GoogleTasks($usuario,$pass);
        if($gt){
            $obtareas = $gt->getTareas($id);
            if($obtareas){
                    $contador=0;
                    foreach($obtareas as $tarea){
                        $tareas[$contador]['id']=$tarea->id;
                        $tareas[$contador]['titulo']=isset($tarea->title) ? $tarea->title : '';
                        $tareas[$contador]['notas']=isset($tarea->notes)?$tarea->notes:'';
                        $tareas[$contador]['status']=isset($tarea->status)?$tarea->status:'';
                        $tareas[$contador]['fecha']=isset($tarea->due)? date(DATE_ATOM, strtotime($tarea->due)) : '';
                        $tareas[$contador]['updated']=isset($tarea->updated)? date(DATE_ATOM, strtotime($tarea->updated)) : '';
                        $tareas[$contador]['finalizado']=isset($tarea->completed)? date(DATE_ATOM, strtotime($tarea->completed)) : '';
                        $contador++;
                    }
            }
        }
        $response = new Response(json_encode($tareas));
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');
        return $response;
                    
    }		
    public function subetareaAction(){
        $usuario = $this->get('request')->request->get('usuario');
        $pass = $this->get('request')->request->get('password');
        $id = $this->get('request')->request->get('id');
        $datos = json_decode($this->get('request')->request->get('tareas'));
        

        if($_SERVER['SERVER_NAME']=="localhost"){
//            $usuario = 'anercarlos@gmail.com';
//            $pass = 'prowinaner';
//            $id = 'MDM2NjczNjExODMzMzMzMjA4MjY6MDow';
//            $datos=  \json_decode('[{"fecha": "2013-05-10 12:00:00 AM","id": "MDM2NjczNjExODMzMzMzMjA4MjY6MDoxNzcxNzAxOTQ3","i_unico": 1069,"limite": "2013-05-15 01:00:00 PM","modificado": "2013-05-10 05:20:00 PM","notas": "","rechazo": "","tipo": "T","titulo": "trabajo para borrar fecha limite antes"}]');
//            $usuario = 'fortizdezarate@ecenarro.com';
//            $pass = 'fortizdezarate198';
//            $id = 'MTE3ODI4MzU3ODU3Mzg3MTEyMzc6MDow';
        }
        $obtareas="";
        $gt = new GoogleTasks($usuario,$pass);
        if($gt){
            if($datos){
                //crear o editar visitas
                foreach($datos as $i => $e){
                    $eventos = array();
                    //crear una nueva visita en google
                    $e = get_object_vars($e);
                    $eventos['title']=$e['titulo'];
                    $eventos['notes']=$e['notas'];
                    date_default_timezone_set('Europe/Madrid');
                    $eventos['due'] = date(DATE_ATOM, strtotime($e['limite']));
                    $eventos['kind']="tasks#task";
                    if($e['id'] == ''){
                      $obtareas = $gt->setTarea($id,$eventos);
                      if($obtareas){
                        $datos[$i]->id = $obtareas->id;
                      }
                    }
                    else{
                        $modificado = date(DATE_ATOM, strtotime($e['modificado']));
                        $evento = $gt->getTareaFecha($id,$e['id'],$modificado);
                        if($evento){
                            $eventos['id']=$e['id'];
                            $eventos['status']='needsAction';
                            $obtareas = $gt->editTarea($id,$e['id'],$eventos);
                            //$obtareas = $gt->borraTarea($id,$e['id']);
                        }
                    } 
                }
            }
            else{
                $datos=array();                
            }
        }
        $response = new Response(json_encode($datos));
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');
        return $response;
    }
    public function borratareaAction(){
        $usuario = $this->get('request')->request->get('usuario');
        $pass = $this->get('request')->request->get('password');
        $id = $this->get('request')->request->get('myidtarea');
        $datos = $this->get('request')->request->get('tarea');
        

        if($_SERVER['SERVER_NAME']=="localhost"){
//            $usuario = 'anercarlos@gmail.com';
//            $pass = 'prowinaner';
//            $id = 'MDM2NjczNjExODMzMzMzMjA4MjY6MDow';
//            $datos=  "MDM2NjczNjExODMzMzMzMjA4MjY6MDoyMDQ3OTgyOTA";
//            $usuario = 'fortizdezarate@ecenarro.com';
//            $pass = 'fortizdezarate198';
//            $id = 'MTE3ODI4MzU3ODU3Mzg3MTEyMzc6MDow';
        }
        $obtareas="";
        $gt = new GoogleTasks($usuario,$pass);
        if($gt){
            if($datos){
                //borrar tareas
                $evento = $gt->getTarea($id,$datos);
                if($evento){
                  $obtareas = $gt->borraTarea($id,$datos);
                }
            }
        }
        $response = new Response(json_encode($obtareas));
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');
        return $response;
    }
    
    public function finalizatareaAction(){
        $usuario = $this->get('request')->request->get('usuario');
        $pass = $this->get('request')->request->get('password');
        $id = $this->get('request')->request->get('myidtarea');
        $datos = $this->get('request')->request->get('tarea');
        

        if($_SERVER['SERVER_NAME']=="localhost"){
//              $usuario = 'anercarlos@gmail.com';
//              $pass = 'prowinaner';
//              $id = 'MDM2NjczNjExODMzMzMzMjA4MjY6MDow';
//              $datos=  "MDM2NjczNjExODMzMzMzMjA4MjY6MDoyMDQ3OTgyOTA";
//            $usuario = 'fortizdezarate@ecenarro.com';
//            $pass = 'fortizdezarate198';
//            $id = 'MTE3ODI4MzU3ODU3Mzg3MTEyMzc6MDow';
        }
        $obtareas="";
        $gt = new GoogleTasks($usuario,$pass);
        if($gt){
            if($datos){
                //borrar tareas
                date_default_timezone_set('Europe/Madrid');
                $evento = $gt->getTarea($id,$datos);
                if($evento){
                  $eventos = array();
                  $eventos['id']=$datos;
                  $eventos['status']='completed';
                  $eventos['completed']=date(DATE_ATOM, strtotime(date('Y-m-d H:i:s')));
                  
                  $obtareas = $gt->editTarea($id,$datos,$eventos);
                }
            }
        }
        $response = new Response(json_encode($obtareas));
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');
        return $response;
    }
    
    public function tareasOldAction(){
        $usuario = $this->get('request')->request->get('usuario');
        $pass = $this->get('request')->request->get('password');

     if($_SERVER['SERVER_NAME']=="localhost"){
        $usuario = 'anercarlos@gmail.com';
        $pass = 'prowinaner';
     }
    //$token= gTareas::activarToken2($usuario, $pass);
    $tasksService  = gTareas::activarTareas($usuario, $pass); //,$token);
    
//    $lists = $tasksService->tasklists->listTasklists();
//    foreach ($lists['items'] as $list) {
//        print "<h3>{$list['title']}</h3>";
//        $tasks = $tasksService->tasks->listTasks($list['id']);
//    }
//    $_SESSION['access_token'] = $client->getAccessToken();

     
     
     
        $results="OK";
        $response = new Response(json_encode($results));
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');
        return $response;
        
    }    

    public function tareasOld2Action(){
        $code = $this->get('request')->query->get('code');
        $usuario = $this->get('request')->request->get('usuario');
        $pass = $this->get('request')->request->get('password');
     if($_SERVER['SERVER_NAME']=="localhost"){
        //$code = "4/-rR3Z3U-3bj2QiI56vk_dNyHracQ.Ik23ajaFWVMbOl05ti8ZT3ZhPH_IeQI";
        //$code='4/Kcun9Dt7fO3WUROfcbL9KD7YSTwJ.gndB9OPQjSgfOl05ti8ZT3bpcu7PeQI';
        //4/CtJibiqh4lMHhz8b-KOdMTmyluoj.AuVSvjhzoLgdOl05ti8ZT3ZXj_zPeQI
         $code="4/CNnyloc-Bvk3ZcBSSWFs23GuzlSK.ErufNsJNENMVOl05ti8ZT3ZLt-DWewI"; //anercarlos
         $code="4/qA_7_IULNpbGjyDwThVyb_eDkzg_.8sTkmj5MZ-sUOl05ti8ZT3bdOb7WewI"; //aritz
     }
     
    //$token= gTareas::activarToken2($usuario, $pass);
    if($code){
		//var_dump($this->get('request'));
		//var_dump($this->get('request')->getSession());
        $eventos = array();
        $tasksService  = gTareas::activarTareas($usuario, $pass,$code);
		//var_dump("juju estoy aqui");
        $lists = $tasksService->tasklists->listTasklists();
        foreach ($lists['items'] as $list) {
            //print "<h3>{$list['title']}</h3>";
            $eventos[]=$list['title'];
            $tasks = $tasksService->tasks->listTasks($list['id']);
			//var_dump($tasks);
			foreach($tasks['items'] as $tarea){
				$eventos[]=$tarea['title'];
				
			}
        }
     
        $results="OK";
        $response = new Response(json_encode($eventos));
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');
        return $response;
        
    }else{
        //$authUrl = gTareas::activarToken2($usuario, $pass);
        $authUrl= gTareas::activarTareas($usuario, $pass);
        $url=substr($authUrl,0,41);
        $data=substr($authUrl,42);
//          echo "<script language='JavaScript'>
//                window.location.href='$authUrl';
//              </script>";
//        $response = new Response(json_encode($authUrl));
//        $response->headers->set('Content-Type', 'application/json; charset=utf-8');
//        return $response;
//                
//                
         echo "<a class='login' id='pulsame' href='$authUrl'>Login</a>
                window.location.href='$authUrl'";
        die;

        echo "<!DOCTYPE html>
            <html>
            <script src='http://code.jquery.com/jquery-latest.js' type='text/javascript'></script>
            <script language='JavaScript'>
              $(function(){
                $.ajax({
                  async: true,
                  type: 'POST',
                  global: false,
                  dataType: 'html',
                  contentType: 'application/x-www-form-urlencoded',
                  url: '$url',
		  data: '$data',
                  success: function(data){
                    $('.email-div #Email',data).val('anercarlos@gmail.com');
                    $('.passwd-div #Passwd',data).val('anerprowin');
                    $('#gaia_loginform',data).submit();
                  },
                  error: function(result){
                    alert(result);
                  }
                });
                });
        </script>
        </html>";

        die;
        
        }
    }
    public function miautorizacionAction() {
       echo " <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8' />
        </head>
        <body>
            <button id='authorize-button' style='visibility: hidden'>Authorize</button>
            <script type='text/javascript'>
            var clientId = '697885352902-99bm1dqg2s2u9bkka7qi46d1vspjnlc2.apps.googleusercontent.com';
            var apiKey = 'AIzaSyCrywKx3941y3S89dmRJhpXAhYy7IhCjPc';

            var scopes = 'https://www.googleapis.com/auth/plus.me';

            function handleClientLoad() {
                gapi.client.setApiKey(apiKey);
                window.setTimeout(checkAuth,1);
            }

            function checkAuth() {
                gapi.auth.authorize({client_id: clientId, scope: scopes, immediate: true}, handleAuthResult);
            }


            function handleAuthResult(authResult) {
                var authorizeButton = document.getElementById('authorize-button');
                if (authResult && !authResult.error) {
                authorizeButton.style.visibility = 'hidden';
                makeApiCall();
                } else {
                authorizeButton.style.visibility = '';
                authorizeButton.onclick = handleAuthClick;
                }
            }

            function handleAuthClick(event) {
                gapi.auth.authorize({client_id: clientId, scope: scopes, immediate: false}, handleAuthResult);
                return false;
            }

            function makeApiCall() {
                gapi.client.load('plus', 'v1', function() {
                var request = gapi.client.plus.people.get({
                    'userId': 'me'
                });
                request.execute(function(resp) {
                    var heading = document.createElement('h4');
                    var image = document.createElement('img');
                    image.src = resp.image.url;
                    heading.appendChild(image);
                    heading.appendChild(document.createTextNode(resp.displayName));

                    document.getElementById('content').appendChild(heading);
                });
                });
            }
            </script>
            <script src='https://apis.google.com/js/client.js?onload=handleClientLoad'></script>
            <div id='content'></div>
            <p>Retrieves your profile name using the Google Plus API.</p>
        </body>
        </html>";
       die;

        
    }
    public function mistareasAction(){
      echo "<!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8' />
            <title>External issue #6</title>
            <script>
            var API_KEY = 'AIzaSyCrywKx3941y3S89dmRJhpXAhYy7IhCjPc';
            var CLIENT_ID = '697885352902-99bm1dqg2s2u9bkka7qi46d1vspjnlc2.apps.googleusercontent.com';
            var SCOPES = 'https://www.googleapis.com/auth/tasks';
            function authInit() {
                gapi.client.setApiKey(API_KEY);
                window.setTimeout(checkAuth,1);
            }

            function checkAuth() {
                gapi.auth.authorize({
                client_id: CLIENT_ID,
                scope: SCOPES,
                immediate: true
                }, handleAuthResult);
            }

            function handleAuthResult(authResult) {
                var authorizeButton = document.getElementById('authorize-button');
                var tasksView = document.getElementById('tasksView');
                if (authResult) {
                authorizeButton.style.display = 'none';
                tasksView.style.display = '';
                } else {
                tasksView.style.display = 'none';
                authorizeButton.style.display = '';
                //authorizeButton.onclick = handleAuthClick;
                handleAuthClick();
                }
            }

            function handleAuthClick(event) {
                gapi.auth.authorize({
                client_id: CLIENT_ID,
                scope: SCOPES,
                immediate: false
                }, handleAuthResult);
                return false;
            }

            function addLogItem(text) {
                var log = document.getElementById('log');
                var logItem = document.createElement('p');
                logItem.appendChild(document.createTextNode(text));
                log.appendChild(logItem);
            };

            var tasklistId;
            var taskId;
            function listaTasklists() {
                gapi.client.request({
                'path': '/tasks/v1/users/@me/lists',
                'callback': dameLista
                });
            }

            function dameLista(response) {
                var tasklists = response && response.items;
                if (!tasklists || !tasklists.length) { throw('no tasklists'); }

                tasklistId = tasklists[0].id;
                addLogItem('Identificador de la lista: ' + tasklistId);
                todasTareas();
            }
            function todasTareas() {
                var respuesta=gapi.client.request({
                'path': '/tasks/v1/lists/' + tasklistId + '/tasks'});
        	respuesta.execute(function(response) {
                	var items = response['items'];
			sacaTareas(items);
		});				
            }
	    function sacaTareas(response){
                var tasklists =  response;
                if (!tasklists || !tasklists.length) { throw('no hay tareas'); }
		for(var i=0;i<tasklists.length;i++){
                	tasklistId = tasklists[i].title;
                        addLogItem('Titulo: ' + tasklistId);
		}
            }
            function listTasklists() {
                gapi.client.request({
                'path': '/tasks/v1/users/@me/lists',
                'callback': handleListTasklists
                });
            }

            function handleListTasklists(response) {
                var tasklists = response && response.items;
                if (!tasklists || !tasklists.length) { throw('no tasklists'); }

                tasklistId = tasklists[0].id;
                addLogItem('Using tasklist ID: ' + tasklistId);
                createTask();
            }

            function createTask() {
                gapi.client.request({
                'path': '/tasks/v1/lists/' + tasklistId + '/tasks',
                'method': 'POST',
                'body': JSON.stringify({
                    'title': 'Sacar la basura'
                    }),
                'callback': handleCreateTask
                });
            };

            function handleCreateTask(response) {
                if (!response || !response.id) { throw('Failed to create task'); }

                addLogItem('Created task.')
                addLogItem('Title: ' + response.title + ', ID: ' + response.id);
                taskId = response.id
                //deleteTask();
            }

            function deleteTask() {
                if (!taskId) { throw('no task to delete'); }

                gapi.client.request({
                'path': '/tasks/v1/lists/' + tasklistId + '/tasks/' + taskId,
                'method': 'DELETE',
                'callback': handleDeleteTask
                });
            }

            function handleDeleteTask(response) {
                if (!response) {
                // No news is good news.
                addLogItem('Task ' + taskId + ' successfully deleted.');
                }
            }

            </script>
            <script src='https://apis.google.com/js/client.js?onload=authInit'></script>
        </head>
        <body>
            <button id='authorize-button' style='visibility: hidden;'>Authorizacion</button>
            <div id='tasksView' style='display:none;'>
            <button id='listaTask' onclick='listaTasklists();'>Lista Tareas</button>
            <button id='deleteTask' onclick='listTasklists();'>Crear Tarea</button>
            <span id='log'>
            </span>
            </div>
        </body>
        </html>";
        die;

        
        
    }

    public function miloginAction(){
	$client = new oauthClientClass();
	$client->server = 'Google';
	$client->offline = true;
        $client->request_token_url='4/upjGICCRFvIaW_FCCYMMY-8j4e8e.MvdyIzrF1U4WOl05ti8ZT3YFfXSDfwI';
	$client->debug = false;
	$client->debug_http = true;
//	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
//		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_google.php';
        // anercarlos Client ID for web applications 
        $client->redirect_uri="http://servicios.eprowin.com/app.php/tareas";
        $client->offline_dialog_url="http://servicios.eprowin.com/app.php/tareas";
	$client->client_id = '697885352902-99bm1dqg2s2u9bkka7qi46d1vspjnlc2.apps.googleusercontent.com'; 
        $application_line = __LINE__;
	$client->client_secret = 'rMNyCInefUvQl622vYQYim-9';


        //anercarlos Client ID for installed applications
        $client->redirect_uri="urn:ietf:wg:oauth:2.0:oob";
        $client->offline_dialog_url="";
	$client->client_id = '697885352902-ah4enjt9afldvba2kll5thlpog7hctk5.apps.googleusercontent.com'; 
        $application_line = __LINE__;
	$client->client_secret = 'xAQdcQwR7rkxVuhq2QKHejz4';

        //Aritz Client ID for installed applications
//        $client->redirect_uri="urn:ietf:wg:oauth:2.0:oob";
//        $client->offline_dialog_url="";
//	$client->client_id = '394695921419-p887uvhvdhf5pr5m2ig29rf77lk9ej41.apps.googleusercontent.com'; 
//        $application_line = __LINE__;
//	$client->client_secret = '039xq3HDnKSxRud_7bx0EREG';
        
        
	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Please go to Google APIs console page '.
			'http://code.google.com/apis/console in the API access tab, '.
			'create a new client ID, and in the line '.$application_line.
			' set the client_id to Client ID and client_secret with Client Secret. '.
			'The callback URL must be '.$client->redirect_uri.' but make sure '.
			'the domain is valid and can be resolved by a public DNS.');

	/* API permissions
	 */
	$client->scope = 'https://www.googleapis.com/auth/userinfo.email '.
		'https://www.googleapis.com/auth/userinfo.profile';
        $client->scope="https://www.googleapis.com/auth/tasks";
	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->authorization_error))
			{
				$client->error = $client->authorization_error;
				$success = false;
			}
			elseif(strlen($client->access_token))
			{
				$success = $client->CallAPI(
					'https://www.googleapis.com/oauth2/v1/userinfo',
					'GET', array(), array('FailOnAccessError'=>true), $user);
			}
		}
		$success = $client->Finalize($success);
	}
	if($client->exit)
		exit;
	if($success)
	{
        echo "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN>
                <html>
                <head>
                <title>Google OAuth client results</title>
                </head>
                <body>
                        '<h1>', HtmlSpecialChars($user->name),
                                ' you have logged in successfully with Google!</h1>';
                                '<pre>', HtmlSpecialChars(print_r($user, 1)), '</pre>';
                </body>
                </html>";
	}
	else
	{
            echo "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>
            <html>
            <head>
            <title>OAuth client error</title>
            </head>
            <body>
            <h1>OAuth client error</h1>
            <pre>Error: <?php echo HtmlSpecialChars($client->error); ?></pre>
            </body>
            </html>";
	}

        
    }
    public function tareasOauthAction(){
        define("GOOGLE_CONSUMER_KEY", "www.servicios.eprowin.com"); // 
        define("GOOGLE_CONSUMER_SECRET", "fVXea4THHZymbW0wmXTmRUjF"); // 

        define("GOOGLE_OAUTH_HOST", "https://www.google.com");
        define("GOOGLE_REQUEST_TOKEN_URL", GOOGLE_OAUTH_HOST . "/accounts/OAuthGetRequestToken");
        define("GOOGLE_AUTHORIZE_URL", GOOGLE_OAUTH_HOST . "/accounts/OAuthAuthorizeToken");
        define("GOOGLE_ACCESS_TOKEN_URL", GOOGLE_OAUTH_HOST . "/accounts/OAuthGetAccessToken");

        define('OAUTH_TMP_DIR', function_exists('sys_get_temp_dir') ? sys_get_temp_dir() : realpath($_ENV["TMP"]));

        //  Init the OAuthStore
        $options = array(
                'consumer_key' => GOOGLE_CONSUMER_KEY, 
                'consumer_secret' => GOOGLE_CONSUMER_SECRET,
                'server_uri' => GOOGLE_OAUTH_HOST,
                'request_token_uri' => GOOGLE_REQUEST_TOKEN_URL,
                'authorize_uri' => GOOGLE_AUTHORIZE_URL,
                'access_token_uri' => GOOGLE_ACCESS_TOKEN_URL
        );
        // Note: do not use "Session" storage in production. Prefer a database
        // storage, such as MySQL.
        OAuthStore::instance("Session", $options);

        try
        {
                //  STEP 1:  If we do not have an OAuth token yet, go get one
                if (empty($_GET["oauth_token"]))
                {
                        $getAuthTokenParams = array('scope' => 
                                'http://docs.google.com/feeds/',
                                'xoauth_displayname' => 'Oauth test',
                                'oauth_callback' => 'http://likeorhate.local/google.php');

                        // get a request token
                        $tokenResultParams = OAuthRequester::requestRequestToken(GOOGLE_CONSUMER_KEY, 0, $getAuthTokenParams);

                        //  redirect to the google authorization page, they will redirect back
                        header("Location: " . GOOGLE_AUTHORIZE_URL . "?btmpl=mobile&oauth_token=" . $tokenResultParams['token']);
                }
                else {
                        //  STEP 2:  Get an access token
                        $oauthToken = $_GET["oauth_token"];

                        // echo "oauth_verifier = '" . $oauthVerifier . "'<br/>";
                        $tokenResultParams = $_GET;

                        try {
                            OAuthRequester::requestAccessToken(GOOGLE_CONSUMER_KEY, $oauthToken, 0, 'POST', $_GET);
                        }
                        catch (OAuthException2 $e)
                        {
                                var_dump($e);
                            // Something wrong with the oauth_token.
                            // Could be:
                            // 1. Was already ok
                            // 2. We were not authorized
                            return;
                        }

                        // make the docs requestrequest.
                        $request = new OAuthRequester("http://docs.google.com/feeds/documents/private/full", 'GET', $tokenResultParams);
                        $result = $request->doRequest(0);
                        if ($result['code'] == 200) {
                                var_dump($result['body']);
                        }
                        else {
                                echo 'Error';
                        }
                }
        }
        catch(OAuthException2 $e) {
                echo "OAuthException:  " . $e->getMessage();
                var_dump($e);
        }
        
    }
}


//  public function borraClientAction(){
//    $id = 'http://www.google.com/m8/feeds/contacts/default/base/29e98jf648c495c7b';
//
//        try {
//        // perform login and set protocol version to 3.0
//        $client = Zend_Gdata_ClientLogin::getHttpClient(
//            $user, $pass, 'cp');
//        $client->setHeaders('If-Match: *');
//        $gdata = new Zend_Gdata($client);
//        $gdata->setMajorProtocolVersion(3);
//
//        // delete entry
//        $gdata->delete($id);
//        echo '<h2>Delete Contact</h2>';
//        echo 'Entry deleted';
//        } catch (Exception $e) {
//        die('ERROR:' . $e->getMessage());
//        }      
//    }
//  public function modificaClientAction(){
//    $id = 'http://www.google.com/m8/feeds/contacts/default/full/0';
//
//    try {
//    // perform login and set protocol version to 3.0
//    $client = Zend_Gdata_ClientLogin::getHttpClient(
//        $user, $pass, 'cp');
//    $client->setHeaders('If-Match: *');
//
//    $gdata = new Zend_Gdata($client);
//    $gdata->setMajorProtocolVersion(3);
//
//    // perform query and get entry
//    $query = new Zend_Gdata_Query($id);
//    $entry = $gdata->getEntry($query);
//    $xml = simplexml_load_string($entry->getXML());
//
//    // change name
//    $xml->name->fullName = 'John Rabbit';
//
//    // change primary email address  
//    foreach ($xml->email as $email) {
//        if (isset($email['primary'])) {
//        $email['address'] = 'jr@example.com';  
//        }  
//    }
//
//    // update entry
//    $entryResult = $gdata->updateEntry($xml->saveXML(), 
//    $entry->getEditLink()->href);
//    echo 'Entry updated';
//    } catch (Exception $e) {
//    die('ERROR:' . $e->getMessage());
//    }      
//  }
//}
