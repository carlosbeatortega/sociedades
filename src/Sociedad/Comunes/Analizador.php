<?php

    class PhpKeywordAnalyser {
        private $website="";
        private $motor="";
        private $keyword="";
        private $url="";
        private $start=0;
        private $page=false;
        private $records=false;
        public $weboriginal="";
        public $webcontador=0;
        private $profundidad=10;
        public $fechainicio = '';
        public $fechafin = '';
        private $tiempo_espera = 0;
        private $tiempo_error = 3600;
        public $fallo_fopen = '';
     
        /**
         * Function to pre process and store the values of Keyword and Website
         *
         * @param string $keyword
         * @param string $website
         * @return resource
         */
        public function __construct($keyword="", $website="",$motor='http://www.google.es',$profundidad=10,$tiempo_espera=0){
            if(trim($keyword)==""){
                trigger_error("Keyword cannot the left blank.",E_USER_ERROR); die();
            }
            if(trim($website)==""){
                trigger_error("Website cannot the left blank.",E_USER_ERROR); die();
            }
            $website=strtolower(trim($website));
            $website=str_replace("http://www.","",$website);
            $website=str_replace("http://","",$website);
            $website=str_replace("www.","",$website);
            $website=str_replace("/","",$website);
            $this->profundidad=$profundidad;
            $this->website=$website;
            $this->keyword=trim($keyword);
            $this->motor=$motor;
            $this->enableVerbose= '';//$enableVerbose;        
            $this->url=$this->updateUrl($keyword, $this->start,$motor);
            $this->tiempo_espera = $tiempo_espera;
        }
     
        /**
         * This function starts the crawling process and it verbose the content as it goes to next page.
         *
         * @return string [buffer]
         */
        public function initSpider(){
            //echo "<p>Searching: <b>".$this->keyword."</b> and Looking for: <b>".$this->website."</b></p>";
            //echo str_repeat(" ", 256);
            $this->fechainicio = new \DateTime();
            $contador=0;
            $encontre=false;
            $this->weboriginal="";
            $this->webcontador=0;
            $i=10;
            $c=1;
            while($c<=$this->profundidad) {            
                //echo "<ul><li><b>Searching in Page: $c de $this->profundidad</b></li>";                
                //flush();ob_flush();  lo he quitado porque me da error desde app/console
                $records= $this->getRecordsAsArray($this->url);  
                if($this->fallo_fopen != ''){
                    break;
                }
                    
                $count=count($records);
                //echo "<ul>";
                for($k=0;$k<$count;$k++){
                    $j=$k+1;
                    $link=$records[$k][2];
                    $linkOriginal = $link;
                    $link=strip_tags($link);
                    $link=str_replace("http://www.","",$link);
                    $link=str_replace("http://","",$link);
                    $link=str_replace("www.","",$link);
                    $linkOriginal=str_replace("<b>"," ",$link);
                    $linkOriginal=str_replace("</b>"," ",$linkOriginal);
                    $pos=strpos($link, "/");
                    $link=trim(substr($link,0,$pos));
                    $contador++;
                    if($this->website==$link){
                        //echo $linkOriginal;
                        $domain=$this->website;
                        $this->weboriginal=$linkOriginal;
                        $this->webcontador=$contador;
                        //echo "<li><h1>Result was found in Page: $c and Record: $j</h1></li>";
                        //echo "Web original ".$linkOriginal;
                        //echo "<div>Congrats, We searched google's top 10 pages for <b>\"".$this->keyword."</b>\", we found your domain <b>\"$domain\"</b> listed on page: $c at $j place </div>";echo "</ul></ul>";
                        $encontre=true;
                        break;
                    }
                    else{
                        //echo "<li>Result not found on Page: $c and Record: $j</li>";
                    }                
                }
                if($encontre==true){
                    break;
                }
                //echo "</ul></ul>";
                $c++;
                
                if($this->tiempo_espera > 0){
                  sleep($this->tiempo_espera);
                }
                $this->url = $this->updateUrl($this->keyword, $i,$this->motor);
            }
            //echo "Crawled through all 10 pages.";            
             
            if($this->page==false){
                $domain=$this->website;
                $keyword=$this->keyword;
                //echo "<div>Sorry, We searched google's top 10 pages for <b>\"$keyword\"</b>, but was unable to find your domain <b>\"$domain\"</b> listed anywhere. </div>";
            }
            else {
                $page=$this->page;
                $records=$this->records;
                $domain=$this->website;
                $keyword=$this->keyword;
                //echo "<div>Congrats, We searched google's top 10 pages for <b>\"$keyword\"</b>, we found your domain <b>\"$domain\"</b> listed on page: $page at $record place </div>";
            }
            
            $this->fechafin = new \DateTime();
        }
         
        /**
         * Function to get records as an array.
         *
         * @access private
         * @param string $url
         *
         * @return array
         */
        private function getRecordsAsArray($url){
            $matches=array();
            $pattern='/<div class="s"(.*)\<cite\>(.*)\<\/cite\>/Uis';
            $html=$this->getCodeViaFopen($url);
            preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);
            return $matches;
        }
         
        /**
         * Function to update the google search query.
         *
         * @access private
         * @param string $keyword
         * @param string $start
         *
         * @return string
         */
        public function updateUrl($keyword, $start, $motor){
            $this->start=$this->start+$start;    
            $keyword=trim($keyword);
            $keyword=urlencode($keyword);
            return $motor."/search?start=".$this->start."&q=$keyword";            
        }
     
        /**
         * Function to get HTML code from remote url
         *
         * @access private
         * @param string $url
         *
         * @return string
         */
        private function getCodeViaFopen($url){
            $returnStr="";
            try{
              $fp=fopen($url, "r") or die("ERROR: Invalid search URL");
              while (!feof($fp)) {
                $returnStr.=fgetc($fp);
              }
              fclose($fp);
            }catch (\Exception $e){
              $this->fallo_fopen = $e->getMessage();
              //sleep($this->tiempo_error);
            }
            
            return $returnStr;
        }
        public function setTiempoError($tiempo){
            $this->tiempo_error=$tiempo;
        }
    }
