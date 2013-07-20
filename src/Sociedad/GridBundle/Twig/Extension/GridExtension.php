<?php
namespace Sociedad\GridBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Sociedad\Comunes\Comparador;
use Symfony\Bundle\SecurityBundle\Twig\Extension\SecurityExtension;

class GridExtension extends \Twig_Extension
{
    private $container;         

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;   
    }        

    public function getContainer()   
    {       
        return $this->container;   
    }
    
    public function getName()
    {
    return 'grid';
    }
    public function getFilters()
        {
            return array(
                'sitelinks' => new \Twig_Filter_Method($this, 'sitelinks', array('is_safe' => array('html'))),       
                'evaluateString' => new \Twig_Filter_Method($this, 'evaluateString', array(
                         'needs_environment' => true,
                         'needs_context'     => true,
                         'debug' => true,
                         'is_safe' => array(
                            'evaluate' => true
                          ))),
                'solotexto' => new \Twig_Filter_Method($this,'solotexto'));   
        }    
    public function getFunctions(){
        return array('linkInterno' => new \Twig_Function_Method($this, 'linkInterno', array(
                         'needs_environment' => true,
                         'needs_context'     => true)),
                     'linkLineaEdit' => new \Twig_Function_Method($this, 'linkLineaEdit'),
                     'lineaEditable' => new \Twig_Function_Method($this, 'lineaEditable'),
                     'linkaEdita' => new \Twig_Function_Method($this, 'linkaEdita', array(
                         'needs_environment' => true,
                         'needs_context'     => true)),
                    'myevaluateString' => new \Twig_Function_Method($this, 'myevaluateString', array(
                         'needs_environment' => true,
                         'needs_context'     => true)));
    }        
    public function replace_username($matches)
    {
        $route = $this->getContainer()->get('router')->generate('user_index', array('username' => $matches[1]));       
        $link = '<a href="' . $route . '">' . $matches[0] . '</a>';        

        return $link;   
    }

    public function replace_tag($matches)
    {
        $route = $this->getContainer()->get('router')->generate('tag_detail', array('tagname' => $matches[1]));
        $link = '<a href="' . $route . '">' . $matches[0] . '</a>';

        return $link;
    }

    public function sitelinks($text)
    {
        //uso en la plantilla
        //{{ app.request.query.get('pagina') | sitelinks }}
        $html=$route = $this->getContainer()->get('router')->generate('tareas', array('idpagina' => $text));
        $link = '<a href="' . $html . '">' . $text . '</a>';        

        return $link;   
        
        $html = preg_replace_callback('/\@([a-zA-Z0-9\_]+)/', 'self::replace_username' , $text);       
        $html = preg_replace_callback('/\#([a-zA-Z0-9\_]+)/', 'self::replace_tag' , $html);

        return $html;
    }    
    //obtiene el texto de un <td....>texto</td>
    public function solotexto($text){
        $vuelta="";
        $formula=preg_match_all('/\>([a-zA-Z0-9\_\ ]+)/', $text,$salida);
        for($x=0;$x<$formula;$x++){
           $vuelta.=$salida[1][$x];
        }
        return $vuelta;        
    }
    public function linkInterno(\Twig_Environment $environment, $context,$etiqueta1,$param1,$text="",$condicion=array(),$td="td")
    {
        //uso en la plantilla
        //{{ linkInterno("tareas",{"idpagina":"query_pagina"},"Tareas")|raw }}
        $traducir=false;
        if($td=='th'){
            $traducir=true;
        }
        if(!empty($text)){
            $traducir=true;
        }
        $comienza=0;
        $left=0;
        $right=0;
        $checked="";
        $ida="";
        $valuea="";
        $classa="";
        $valorentity="";
        $value="";
        $type="";
        $align="";
        $center="";
        $headers="";
        $data="";
        $name="";
        $class="";
        $scr="";
        $scra="";
        $id="";
        $valign="";
        $condicionserializada="";
        if(gettype($condicion)!='array'){
            $condicion=array();
        }
            
        if(count($condicion)>0){
            if(!$this->ejecutacondicion($environment, $context,$condicion)){
                return "";
            }
            $condicionserializada= \serialize($condicion);
        }
        if(!empty($condicionserializada)){
            $data.=" data-condicion_cabecera=".$condicionserializada;
        }
        $valorarray="";
        $request = $this->getContainer()->get('request');
        $param2=array_keys($param1); //array de nombres de parametros
        $param3=array_values($param1);//query_pagina, campo_tabla
        $parametros = array();
        for($x=0;$x<count($param2);$x++) {
            $param21=array();
            $param21=explode("_", $param2[$x]);
            if(count($param21)>1){
                $origen=$param21[0];
                $cadaNombreParametro="";
                for($y=1;$y<count($param21);$y++){
                    $cadaNombreParametro.=$param21[$y]."_";
                }
                $cadaNombreParametro=substr($cadaNombreParametro, 0, strlen($cadaNombreParametro)-1);
                $valor=$param3[$x];
                $valorentity=$param3[$x];
                $param5=array();
                $param5=explode(".", $valor);
                if(count($param5)>1){
                    $valor=$this->evaluateString($environment, $context,$valor);
                }else{
                    $param5=array();
                    $param5=explode("_", $valor);
                    if(count($param5)>1){
                        if($param5[0]=="query"){
                            $valor=$request->get($param5[1]);
                        }
                    }
                }
                
            }else{
                $cadaNombreParametro=$param21[0];
                $origen=$cadaNombreParametro;
                if(gettype($param3[$x])=="array"){
                    $origen=$cadaNombreParametro;
                    $valor=$this->evaluateArray($environment, $context,$param3[$x]);
                    $cadaNombreParametro= \serialize($param3[$x]);
                }else{
                    $espegmatch=false;
                    if(gettype($param3[$x])=='string'){
                        $comienza=preg_match_all("[^'|'$]", $param3[$x],$salida);
                        if($comienza==2){
                            $cadaNombreParametro=$param3[$x];
                            $valor=$param3[$x];                                
                            $espegmatch=true;
                        }
                        if(!$espegmatch){
                            $formula=preg_match_all("[\+|\-|\*|\/]", $param3[$x],$salida);
                            if($formula>1){
                                $cadaNombreParametro=$param3[$x];
                                $valor=$this->evaluateFormula($environment, $context,$param3[$x]);
                                $espegmatch=true;
                            }
                            if(!$espegmatch){
                                $formula=preg_match_all("(\?|\:)", $param3[$x],$salida);
                                if($formula>1){
                                    $cadaNombreParametro=$param3[$x];
                                    $valor=$this->evaluateCondicional($environment, $context,$param3[$x]);
                                    $espegmatch=true;
                                }
                            }
                        }
                    }
                    if(!$espegmatch){
                        $param5=array();
                        $param5=explode(".", $param3[$x]);
                        if(count($param5)>1 && !empty($param5[1])){
                            $origen=$cadaNombreParametro;
                            $cadaNombreParametro=$param3[$x];
                            $valor="";
                            $valor=$this->evaluateString($environment, $context,$cadaNombreParametro);
                        }
                        else{                    
                            $param4=array();
                            $param4=explode("_", $param3[$x]);
                            if(count($param4)>1){
                                $origen=$param4[0]; //se presupone query
                                $valor="";
                                if($origen=='query'){
                                    $valor=$request->get($param4[1]);
                                }else{
                                    $origen=$cadaNombreParametro;
                                    $cadaNombreParametro=$param3[$x];
                                    $valor=$param3[$x];
                                    $traducir=true;
                                }
                            }else{
                                $cadaNombreParametro=$param3[$x];
                                $valor=$param3[$x];                                
                                $traducir=true;
                            }
                        }
                    }
                }
            }
            switch ($origen){
                case 'query':
                    $parametros[$cadaNombreParametro]=$valor;
                    $data.=" data-".$origen."_".$cadaNombreParametro."='query_".$cadaNombreParametro."')";
                    break;
                case 'entity':
                    $data.=" data-".$origen."_".$cadaNombreParametro."=".$valorentity;
                    $parametros[$cadaNombreParametro]=$valor;
                    break;
                case 'textospan':
                    $data.=" data-".$origen."='".$cadaNombreParametro."'";
                    $text='<span>'.$valor.'</span>';
                    break;
                case 'texto':
                    $data.=" data-".$origen."='".$cadaNombreParametro."'";
                    if($traducir && is_string($valor)){
                        $valor=$this->getContainer()->get('translator')->trans($valor);
                    }
                    $text=$valor;
                    break;
                case 'scr':
                    $data.=" data-".$origen."='".$cadaNombreParametro."'";
                    $scr='<img src='.$valor.'>';
                    break;
                case 'scra':
                    $data.=" data-".$origen."='".$cadaNombreParametro."'";
                    $scra='<img src='.$valor.'>';
                    break;
                case 'class':
                    $class.=" class='".$valor."'";
                    if(!empty($cadaNombreParametro) && $cadaNombreParametro!='class'){
                        $data.=" data-".$origen."='".$cadaNombreParametro."'";
                    }
                    break;
                case 'classa':
                    $classa.=" class=".$valor;
                    if(!empty($cadaNombreParametro) && $cadaNombreParametro!='classa'){
                        $data.=" data-".$origen."=".$cadaNombreParametro;
                    }
                    break;
                case 'name':
                    $name.=" name=".$valor;
                    if(!empty($cadaNombreParametro) && $cadaNombreParametro!='name'){
                        $data.=" data-".$origen."=".$cadaNombreParametro;
                    }
                    break;
                case 'value':
                    $value.=" value=".$valor;
                    if(!empty($cadaNombreParametro) && $cadaNombreParametro!='value'){
                        $data.=" data-".$origen."=".$cadaNombreParametro;
                    }
                    break;
                case 'type':
                    $type.=" type=".$valor;
                    $data.=" data-".$origen."=".$valor;
                    break;
                case 'align':
                    $align.=" align=".$valor;
                    if(!empty($cadaNombreParametro) && $cadaNombreParametro!='align'){
                        $data.=" data-".$origen."=".$cadaNombreParametro;
                    }
                    break;
                case 'center':
                    $center.=" center=".$valor;
                    if(!empty($cadaNombreParametro) && $cadaNombreParametro!='center'){
                        $data.=" data-".$origen."=".$cadaNombreParametro;
                    }
                    break;
                case 'headers':
                    $headers.=" headers=".$valor;
                    if(!empty($cadaNombreParametro) && $cadaNombreParametro!='headers'){
                        $data.=" data-".$origen."=".$cadaNombreParametro;
                    }
                    break;
                
                case 'id':
                    $id.=" id='".$valor."'";
                    $data.=" data-".$origen."=".$valor;
                    break;
                case 'ida':
                    $ida.=" id='".$valor."'";
                    $data.=" data-".$origen."=".$valor;
                    break;
                case 'valuea':
                    $valuea.=" value='".$valor."'";
                    $data.=" data-".$origen."=".$valor;
                    break;
                case 'valign':
                    $valign.=" value='".$valor."'";
                    $data.=" data-".$origen."=".$valor;
                    break;
                case 'checked':
                    $checked="checked";
                case 'left':
                    $left=$valor;
            }
                    //$parametros[$cadaNombreParametro]="#";
            
        }
        ///convertir fecha a string
        if($text instanceof \DateTime){
            $text=$text->format("d/m/Y");
        }
        if(is_string($text) && !empty($text)){
            $lgtext=$text;
            if($left>0){
                $text=substr($text,0,$left);    
            }
            if( strlen($lgtext)>strlen($text)){
                $text=$text."...";
            }
        }
        if(empty($etiqueta1)){
            
            if(!empty($type)){
                $salida="";
                $formula=preg_match_all("(^type)", ltrim($type),$salida);
                if($formula==1){
                    $salida=$salida[0][0];
                }
                switch($salida){
                    case 'type':
                        if($td=='td'){
                           $link = '<td '.$valign.' '.$id.' '.$class.' '.$align.' '.$headers.' '.$data.'><input '.' '.$type.' '.$name.' '.$valuea.' '.$classa.' '.$ida.' '.$checked.' '.$center.'>' .$scr. $text . '</td>';   
                        }else{
                            $link = '<th '.$id.' '.$class.' '.$align.' '.$headers.' '.$data.'><input '.' '.$type.' '.$name.' '.$valuea.' '.$classa.' '.$ida.' '.$center.'>' . $text . '</th>';   
                        }
                        break;
                    default:
                        if($td=='td'){
                            $link = '<td '.$valign.' '.$id.' '.$class.' '.$name.' '.$value.' '.$type.' '.$checked.' '.$align.' '.$center.' '.$headers.' '.$data.'>'.$scr . $text . '</td>';
                        }else{
                            $link = '<th '.$id.' '.$class.' '.$name.' '.$value.' '.$type.' '.$align.' '.$center.' '.$headers.' '.$data.'>' . $text . '</th>';
                        }
                        
                }
            }else{
                if($td=='td'){
                    $link = '<td '.$valign.' '.$id.' '.$class.' '.$name.' '.$value.' '.$type.' '.$checked.' '.$align.' '.$center.' '.$headers.' '.$data.'>'.$scr . $text . '</td>';
                }else {
                    $link = '<th '.$id.' '.$class.' '.$name.' '.$value.' '.$type.' '.$align.' '.$center.' '.$headers.' '.$data.'>' . $text . '</th>';
                }
            }
        }else{
            $data.=" data-etiqueta=".$etiqueta1;
            $contenedor=$this->getContainer();
            $html=$route = $this->getContainer()->get('router')->generate($etiqueta1, $parametros);
            if($td=='td'){            
                $link = '<td '.$valign.' '.$id.' '.$class.' '.$name.' '.$data.'>' .'<a '.' '.$valuea.' '.$classa.' '.$ida.' href="' . $html . '">' .$scra . $text . '</a>'.$scr. '</td>';        
            }else{
                $link = '<th '.$id.' '.$class.' '.$name.' '.$data.'>' .'<a '.' '.$valuea.' '.$classa.' '.$ida.' href="' . $html . '">' .$scra . $text . '</a>'. '</th>';        
            }
        }

        return $link;   
    }    

    public function linkaEdita(\Twig_Environment $environment, $context,$cabecerasid,$td="td"){
    
        $tratado=false;
        $parametros=array();
        $etiqueta="";
        $condicion=array();
        $apo="";
        $apoarray=array();
        foreach ($context['columnas'] as $columna){
            if($columna->getCabecerasId()==$cabecerasid && $columna->getTd()==$td){
                $tratado=true;
                $etiqueta=$columna->getRuta();
                $apo=$columna->getEntity1();
                $apoarray= @unserialize($apo);
                if($apoarray !== false || $apo === 'b:0;'){
                    $apo=$apoarray;                            
                }
                $parametros[$columna->getEntity()]=$apo;                
                if(empty($condicion)){
                    $apo=$columna->getCabeceras()->getCondicion();
                    if(!empty($apo)){
                        $condicion= @unserialize($apo);
                        if(!($condicion !== false || $apo === 'b:0;')){
                            $condicion=array();                            
                        }

                    }
                }
            }elseif($tratado){
                break;               
            }
        } 
        return $this->linkInterno($environment, $context,$etiqueta,$parametros,"",$condicion,$td);
    }
    
    
    
    
//    public function evaluateString(\Twig_Environment $env, $context, $string){
//        
//        $newEnv = $this->setLoader(clone $env);
//        return $newEnv->loadTemplate($string)->render($context);
//    }
//    private function setLoader(\Twig_Environment $env){
//        $loader = new \Twig_Loader_String();
//        $env->setLoader($loader);
//        return $env;
//    }
    
/**
     * This function will evaluate $string through the $environment, and return its results.
     * 
     * @param array $context
     * @param string $string 
     */
    public function ejecutacondicion( \Twig_Environment $environment, $context, $condicion=array() ) {
        
        $vuelta="";
        $sumacond=array();
        $x=0;
        foreach($condicion as $key => $value){
            $formula=preg_match_all("(^isgranted)", $key,$salida);
            if($formula==1){
                $abc=$this->getContainer()->get('security.context');
                //if (!$this->get('security.context')->isGranted(array("CONTEXT"), $entity)) 
                $apo=substr($key, 11, strlen($key)-13);
                $sumacond[$x][0]=$abc->isGranted($apo);
            }else{
                $param5=explode(".", $key);
                if(count($param5)>1){
                    $valor="";
                    $valor=$this->evaluateString($environment, $context,$key);
                    $sumacond[$x][0]=$valor;
                }else{
                    $sumacond[$x][0]= $key;
                }  
            }
            foreach($value as $criterio=>$condi2) {
                $sumacond[$x][1]=$criterio;
//                $param5=explode(".", $condi2);
//                if(count($param5)>1){
                    $valor="";
                    $valor=$this->evaluateString($environment, $context,$condi2);
                    $sumacond[$x][2]=$valor;
//                }else{
//                    $sumacond[$x][2]=$condi2;
//                }  
            }
            $x++;
        }
        $retorno=new Comparador($sumacond);
        $vuelta=$retorno->compara();
        return $vuelta;
    }
    public function evaluateArray($environment, $context,$tabla,$concatena=0){
        $vuelta="";
        foreach($tabla as $key => $value){        
            if(gettype($value)=="array"){
                $vuelta=$this->evaluateCondicional($environment, $context,$key);
                if($vuelta){
                    $vuelta=$this->evaluateArray($environment, $context, $value, 1);
                }else{
                    $vuelta=$this->evaluateArray($environment, $context, $value, 2);
                }
            }else{
                switch($concatena){
                    case 0:
                        $vuelta=$this->evaluateString($environment, $context,$key);
                        $vuelta.=$this->evaluateString($environment, $context,$value);            
                        break;
                    case 1:
                        $vuelta=$this->evaluateString($environment, $context,$key);
                        break;
                    default:
                        $vuelta=$this->evaluateString($environment, $context,$value);                                    
                }
            }
        }
        return $vuelta;
    }
    public function evaluateCondicional($environment, $context,$cadena){
        $tablaformula=preg_match_all('[(!=)|(>=)|(<=)|(==)|(&&)|(\|\|)|[!-¡]]', $cadena, $arr, PREG_PATTERN_ORDER);
        $evaluable="";
        $condicion=array();
        $cumplecondi=true;
        $essimple=true;
        $apoyo1=0;
        $apoyo2=0;
        $operador='';
        $vuelta="";
        $contador=0;
        $opera2="";
        $evalua2="";
        for($x=0;$x<$tablaformula;$x++){
            if($x==$tablaformula-1){
                $evaluable.=$arr[0][$x];
            }
            if ($x==$tablaformula-1 || in_array($arr[0][$x], array('?', ':','>', '<', '>=', '<=', '==','!=','&&','||'))){
                if($x==$tablaformula-1){
                    if($essimple){
                        $condicion[$evalua2]=array($opera2=>$evaluable);
                        $vuelta=$this->ejecutacondicion($environment, $context,$condicion);                        
                    }else{
                        $apoyo2=$this->evaluateString($environment, $context,$evaluable);
                        if($cumplecondi){
                            $vuelta=$apoyo1;
                        }else{
                            $vuelta=$apoyo2;                            
                        }
                    }
                    break;
                }
                $operador=$arr[0][$x];
                switch($operador){
                    case '?':
                        $essimple=false;
                        $condicion[$evalua2]=array($opera2=>$evaluable);
                        $cumplecondi=$this->ejecutacondicion($environment, $context,$condicion);
                        break;
                    case ':':
                        $apoyo1=$this->evaluateString($environment, $context,$evaluable);
                        break;
                    case '&&':
                    case '||':
                        $condicion[$contador][$opera2]=$evaluable;
                        $condicion[$contador][2]=$operador;
                        $contador++;
                        break;
                    case '>':
                    case '>=':
                    case '<':
                    case '<=':
                    case '==':
                    case '!=':
                        $condicion=array($evaluable=>array());
                        $evalua2=$evaluable;
                        $opera2=$operador;
                    
                }
                $operador=$arr[0][$x];
                $evaluable="";
            }else{
                    $evaluable.=$arr[0][$x];
                    $operador=$arr[0][$x];
            }
            
        }
        return $vuelta;
    }
    public function evaluateFormula($environment, $context,$cadena){
        $tablaformula=preg_match_all('[\+|\-|\*|\/|\.|\_|[aA0-zZ9]]', $cadena, $arr, PREG_PATTERN_ORDER);
        $evaluable="";
        $valor=0;
        $apoyo=0;
        $operador='+';
        for($x=0;$x<$tablaformula;$x++){
            if($x==$tablaformula-1){
                $evaluable.=$arr[0][$x];
            }
            if ($x==$tablaformula-1 || in_array($arr[0][$x], array('+', '-', '*', '/'))){
                $apoyo=$this->evaluateString($environment, $context,$evaluable);
                switch($operador){
                    case '+':
                        $valor+=$apoyo;
                        break;
                    case '-':
                        $valor-=$apoyo;
                        break;
                    case '*':
                        $valor*=$apoyo;
                        break;
                    case '/':
                        if($apoyo!=0){
                            $valor/=$apoyo;
                        }
                        break;
                }
                $operador=$arr[0][$x];
                $evaluable="";
            }else{
                    $evaluable.=$arr[0][$x];
            }
            
        }
        return $valor;
    }
    public function myevaluateString( \Twig_Environment $environment, $context, $string ) {
        return $this->evaluateString($environment, $context, $string );
    }
    public function evaluateString( \Twig_Environment $environment, $context, $string ) {
        $loader = $environment->getLoader( );
        $parsed = $this->parseString( $environment, $context, $string );
        $environment->setLoader( $loader );
        //return $parsed;
        // si tenemos una funcion($param)
        // $var='funcion';
        // $var('valor'); y ejecutara funcion pasandole el parametro 'valor'
        $apovar=explode(".",$string);
        $retorno="";
        if(count($apovar)>1){
            $var1=$context[$apovar[0]];
            $var2=$apovar[1];
            $apoget=explode("_",$var2);
            $myvar='get';
            $myelementable="";
            for($x=0;$x<count($apoget);$x++){
                $myvar.=ucfirst($apoget[$x]);
                $myelementable=$apoget[$x];
            }
            if(method_exists($var1, $myvar)){
                $retorno=$var1->$myvar();
            }elseif(isset($var1[$myelementable])){
                $retorno=$var1[$myelementable];
            }else{
                $retorno=$apovar[1];
            }
            if(count($apovar)>2){
                $myvar='get'.ucfirst($apovar[2]);
                if(method_exists($retorno, $myvar)){
                    $retorno=$retorno->$myvar();
                }
            }
        }else{
                $apovar=explode("_", $string);
                if(count($apovar)>1){
                    switch($apovar[0]){
                    case 'query':
                        $request = $this->getContainer()->get('request');
                        $retorno=$request->get($apovar[1]);
                        break;
                    case 'valor':
                        for($y=1;$y<count($apovar);$y++){
                            $retorno.=$apovar[$y];
                            if($y<count($apovar)-1){
                                $retorno.="_";
                            }
                        }
                        break;
                    default:
                        if(empty($apovar[1])){
                            $retorno=$string;                            
                        }else{
                            $retorno=$apovar[1];
                        }
                    }
                }else{
                    $apovar=explode("[", $string);
                    $retorno=null;
                    if(count($apovar)>1){
                        try {
                                $var1=$context[$apovar[0]];
                                $var2=explode("]", $apovar[1]);
                                if(isset($var1[$var2[0]])){
                                    $retorno=$var1[$var2[0]];
                                }
                            } catch (Exception $exc) {
                                $retorno=null;
                            }
                        }else{
                            $retorno=$string;                                
                    }
                    
                }            
        }
        
        return $retorno;
        
    }
    public function linkLineaEdit($etiqueta1,$param1,$text=""){
        //uso en la plantilla
        //{{ linkInterno("tareas",{"idpagina":"query_pagina"},"Tareas")|raw }}
        
        //$entidad=array();
        $data="";
        $request = $this->getContainer()->get('request');
        $param2=array_keys($param1); //array de nombres de parametros
        $param3=array_values($param1);//query_pagina, campo_tabla
        $parametros = array();
        for($x=0;$x<count($param2);$x++) {
            $param21=explode("_", $param2[$x]);
            if(count($param21)>1){
                $origen=$param21[0];
                $cadaNombreParametro=$param21[1];
                $valor=$param3[$x];
            }else{
                $cadaNombreParametro=$param21[0];
                $param4=explode("_", $param3[$x]);
                $origen=$param4[0];
            }
            switch ($origen){
                case 'query':
                    $parametros[$cadaNombreParametro]=$request->get($param4[1]);
                    $data.=" data-".$origen."_".$cadaNombreParametro."='query_".$param4[1]."')";
                    break;
                case 'entity':
                    $data.=" data-".$origen."_".$cadaNombreParametro."=".$origen.".".$cadaNombreParametro;
                    $parametros[$cadaNombreParametro]=$valor;
                    break;
            }
                    //$parametros[$cadaNombreParametro]="#";
            
        }
        $contenedor=$this->getContainer();
        $html=$route = $this->getContainer()->get('router')->generate($etiqueta1, $parametros);
        $link = '<a '.$data.' class="edit" href="' . $html . '">' . $text . '</a>';        

        return $link;   
        
    }
// {{ lineaEditable({"name":"volumen_mes","class":"posicionr","entity":'entity.volumen_mes'|evaluateString})|raw }} 

    public function lineaEditable($param1){

        $data="";
        $name="";
        $class="";
        $text="";
        $request = $this->getContainer()->get('request');
        $param2=array_keys($param1); //array de nombres de parametros
        $param3=array_values($param1);//query_pagina, campo_tabla
        $parametros = array();
        for($x=0;$x<count($param2);$x++) {
            $param21=explode("_", $param2[$x]);
            if(count($param21)>1){
                $origen=$param21[0];
                $cadaNombreParametro="";
                for($y=1;$y<count($param21);$y++){
                    $cadaNombreParametro.=$param21[$y]."_";
                }
                $cadaNombreParametro=substr($cadaNombreParametro, 0, strlen($cadaNombreParametro)-1);
                $valor=$param3[$x];
            }else{
                $param4=explode("_", $param3[$x]);
                if($param21[0]=='class' || $param21[0]=='name'){
                    $origen=$param21[0];
                    $valor=$param3[$x];
                }else{
                    $cadaNombreParametro=$param21[0];
                    $origen=$param4[0];
                }
            }
            switch ($origen){
                case 'query':
                    $parametros[$cadaNombreParametro]=$request->get($param4[1]);
                    $data.=" data-".$origen."_".$cadaNombreParametro."='query_".$param4[1]."')";
                    break;
                case 'entity':
                    $data.=" data-".$origen."_".$cadaNombreParametro."=".$origen.".".$cadaNombreParametro;
                    $text=$valor;
                    break;
                case 'class':
                    $class.=" class=".$valor;
                    break;
                case 'name':
                    $name.=" name=".$valor;
                    break;
            }
                    //$parametros[$cadaNombreParametro]="#";
            
        }
        $html="";
        $link = '<td '.$class.' '.$name.' '.$data.'>' . $text . '</td>';        

        return $link;   
        
        
        
        
    }

    /**
     * Sets the parser for the environment to Twig_Loader_String, and parsed the string $string.
     * 
     * @param \Twig_Environment $environment
     * @param array $context
     * @param string $string
     * @return string 
     */
    protected function parseString( \Twig_Environment $environment, $context, $string ) {
        $environment->setLoader( new \Twig_Loader_String( ) );
        return $environment->render( $string, $context );
    }    
    
    
}


?>
