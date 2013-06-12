<?php
namespace Sociedad\Comunes;

class GoogleTasks {
  protected $api_key;
  protected $auth;
  protected $curl;
  protected $headers;
  
  public function __construct($user = '',$pass = ''){
    $this->api_key = 'AIzaSyDKw2vfGsjasYqXP9KLQlutF4kEWIXGXxk';
    $this->iniciarServicio($user,$pass);
  }

  public function iniciarServicio($user = '',$pass = '') {
    // Construct an HTTP POST request
    $clientlogin_url = "https://www.google.com/accounts/ClientLogin";
    $clientlogin_post = array(
            "accountType" => "HOSTED_OR_GOOGLE",
            "Email" => $user,
            "Passwd" => $pass,
            "service" => "cl",
            "source" => "Google Tasks EPROWIN"
    );

    // Initialize the curl object
    $this->curl = curl_init($clientlogin_url);

    // Set some options (some for SHTTP)
    curl_setopt($this->curl, CURLOPT_POST, true);
    curl_setopt($this->curl, CURLOPT_POSTFIELDS, $clientlogin_post);
    curl_setopt($this->curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);

    // Execute
    $response = curl_exec($this->curl);

    // Get the Auth string and save it
    preg_match("/Auth=([a-z0-9_-]+)/i", $response, $matches);

    $this->auth = $matches[1];
    $this->headers = array(
      "Authorization: GoogleLogin auth=" . $this->auth,
      "GData-Version: 3.0"
    );
  }
  
  public function getListasTareas(){
    // Make the request
    curl_setopt($this->curl, CURLOPT_URL, "https://www.googleapis.com/tasks/v1/users/@me/lists?key=".$this->api_key);
    curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);
    curl_setopt($this->curl, CURLOPT_POST, false);

    $response = curl_exec($this->curl);
    $response = json_decode($response);
    
    return $response->items;
  }
  
  public function getTareas($lista_id){
    curl_setopt($this->curl, CURLOPT_URL, "https://www.googleapis.com/tasks/v1/lists/".$lista_id."/tasks?key=".$this->api_key);
    curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);
    curl_setopt($this->curl, CURLOPT_POST, false);

    $response = curl_exec($this->curl);
    $response = json_decode($response);
    
    return $response->items;
  }
  public function setTarea($lista_id,$arr_value){

   $this->headers = array(
      "Authorization: GoogleLogin auth=" . $this->auth,
      "GData-Version: 3.0",
       "Content-Type:  application/json"
    );
    curl_setopt($this->curl, CURLOPT_URL, "https://www.googleapis.com/tasks/v1/lists/".$lista_id."/tasks?key=".$this->api_key);
    curl_setopt($this->curl, CURLOPT_POSTFIELDS, json_encode($arr_value));
    curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);
    curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($this->curl, CURLOPT_POST, true);
    

    $content = curl_exec($this->curl);
    $response = json_decode($content);
    
    return $response;
  }
  public function getTarea($lista_id,$id){
    curl_setopt($this->curl, CURLOPT_URL, "https://www.googleapis.com/tasks/v1/lists/".$lista_id."/tasks/".$id."?key=".$this->api_key);
    curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);
    curl_setopt($this->curl, CURLOPT_POST, false);

    $response = curl_exec($this->curl);
    $response = json_decode($response);
    if(!isset($response->id)){
        return null;
    }    
    return $response;
  }
  public function getTareaFecha($lista_id,$id,$fecha){
    curl_setopt($this->curl, CURLOPT_URL, "https://www.googleapis.com/tasks/v1/lists/".$lista_id."/tasks/".$id."?key=".$this->api_key);
    curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);
    curl_setopt($this->curl, CURLOPT_POST, false);

    $response = curl_exec($this->curl);
    $response = json_decode($response);
    if(isset($response->updated)){
        $updated=date(DATE_ATOM, strtotime($response->updated));
        if($updated>$fecha){
            return null;
        }
    }else{
        return null;
    }    
    return $response;
  }
  public function editTarea($lista_id,$id,$arr_value){

   $this->headers = array(
      "Authorization: GoogleLogin auth=" . $this->auth,
      "GData-Version: 3.0",
       "Content-Type:  application/json"
    );
    
    curl_setopt($this->curl, CURLOPT_URL, "https://www.googleapis.com/tasks/v1/lists/".$lista_id."/tasks/".$id."?key=".$this->api_key);
    curl_setopt($this->curl, CURLOPT_POSTFIELDS, json_encode($arr_value));
    curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);
    curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "PUT" );
    

    $content = curl_exec($this->curl);
    $response = json_decode($content);
    
    return $response;
  }
  public function borraTarea($lista_id,$id){

    curl_setopt($this->curl, CURLOPT_URL, "https://www.googleapis.com/tasks/v1/lists/".$lista_id."/tasks/".$id."?key=".$this->api_key);
    curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);
    curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "DELETE" );
    

    $content = curl_exec($this->curl);
    $response = json_decode($content);
    
    return $response;
  }

  
  public function finalizarServicio(){
    curl_close($this->curl);
  }
}