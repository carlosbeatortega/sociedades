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
use \Google\Client;
use \Google_TasksService;
use \Google_PlusService;
use \Google_Oauth2Service;

class gTareas {
    private $tasksService;
    
    public static function activarTareas($user = '',$pass = '',$token=''){
        $client = new \Google_Client();
    // Visit https://code.google.com/apis/console to generate your
    // oauth2_client_id, oauth2_client_secret, and to register your oauth2_redirect_uri.
    //     $client->setClientId('insert_your_oauth2_client_id');
        $client->setClientId('697885352902-99bm1dqg2s2u9bkka7qi46d1vspjnlc2.apps.googleusercontent.com');
    //     $client->setClientSecret('insert_your_oauth2_client_secret');
        $client->setClientSecret('rMNyCInefUvQl622vYQYim-9');
        if($_SERVER['SERVER_NAME']=="localhost"){
            $client->setRedirectUri('http://localhost:8084/app_dev.php/tareas');
        }else{
            $client->setRedirectUri('http://servicios.eprowin.com/app.php/tareas');
        }
        $client->setApplicationName("Lector de Tareas");
        //$client->setDeveloperKey('AIzaSyCrywKx3941y3S89dmRJhpXAhYy7IhCjPc');
        //anercarlos
        $client->setClientId('697885352902-ah4enjt9afldvba2kll5thlpog7hctk5.apps.googleusercontent.com');
        $client->setClientSecret('xAQdcQwR7rkxVuhq2QKHejz4');
        $client->setRedirectUri('urn:ietf:wg:oauth:2.0:oob');
        //aritz
        $client->setClientId('394695921419-p887uvhvdhf5pr5m2ig29rf77lk9ej41.apps.googleusercontent.com');
        $client->setClientSecret('039xq3HDnKSxRud_7bx0EREG');
        $client->setRedirectUri('urn:ietf:wg:oauth:2.0:oob');
        
        
        
//        $client->setScopes(array('https://www.googleapis.com/auth/plus.me'));
//        $plus = new \Google_PlusService($client);
//
//        $aut=$client->authenticate();
//        $token=$client->getAccessToken();
//        if ($token) {
//            $client->setAccessToken($token);
//        }
//
//        if ($client->getAccessToken()) {
//            $me = $plus->people->get('me');
//        }
        
        
        $tasksService = new \Google_TasksService($client);

//        if (isset($_REQUEST['logout'])) {
//        unset($_SESSION['access_token']);
//        }
//
//        if (isset($_SESSION['access_token'])) {
//        $client->setAccessToken($_SESSION['access_token']);
//        } else {

        if($token){
           //$aut=$client->authenticate($token);
		   $client->setAccessToken($client->authenticate($token));
		   //var_dump($client);

           return $tasksService;
            }
        $authUrl = $client->createAuthUrl();
        return $authUrl;
        
        
//        $token=$client->getAccessToken();
//        $client->setAccessToken($_SESSION['access_token']);
//        $_SESSION['access_token'] = $client->getAccessToken();
//        }

        if (isset($authUrl)) {
        $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
        header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
        }

//        $lists = $tasksService->tasklists->listTasklists();
//        foreach ($lists['items'] as $list) {
//            print "<h3>{$list['title']}</h3>";
//            $tasks = $tasksService->tasks->listTasks($list['id']);
//        }
//        $_SESSION['access_token'] = $client->getAccessToken();



//        if (isset($_REQUEST['logout'])) {
//            unset($_SESSION['access_token']);
//        }
//
//        if (isset($_SESSION['access_token'])) {
//        $client->setAccessToken($_SESSION['access_token']);
//        } else {
//            $setAccessToken = $client->setAccessToken($client->authenticate($_GET['code']));
//            $_SESSION['access_token'] = $client->getAccessToken();
//        }
//
//        if (isset($_GET['code'])) {
//        $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
//        header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
//        }
        return $tasksService;
    }

    public static function activarToken($user = '',$pass = ''){
        $client = new \Google_Client();
    // Visit https://code.google.com/apis/console to generate your
    // oauth2_client_id, oauth2_client_secret, and to register your oauth2_redirect_uri.
    //     $client->setClientId('insert_your_oauth2_client_id');
        $client->setClientId('697885352902-99bm1dqg2s2u9bkka7qi46d1vspjnlc2.apps.googleusercontent.com');
    //     $client->setClientSecret('insert_your_oauth2_client_secret');
        $client->setClientSecret('rMNyCInefUvQl622vYQYim-9');
        $client->setRedirectUri('https://localhost:8084/app_dev.php');
        $client->setApplicationName("Lector de Tareas");
        $client->setDeveloperKey('AIzaSyCrywKx3941y3S89dmRJhpXAhYy7IhCjPc');

        $client->setScopes(array('https://www.googleapis.com/auth/plus.me'));
        $plus = new \Google_PlusService($client);

        if (isset($_REQUEST['logout']))
        {
        unset($_SESSION['access_token']);
        }

        if (isset($_GET['code']))
        {
        $client->authenticate();
        $_SESSION['access_token'] = $client->getAccessToken();
        header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
        }

        if (isset($_SESSION['access_token']))
        {
        $client->setAccessToken($_SESSION['access_token']);
        }

        if ($client->getAccessToken())
        {
        $me = $plus->people->get('me');
        $_SESSION['access_token'] = $client->getAccessToken();
        }
        else
        $authUrl = $client->createAuthUrl();

        if(isset($me))
        {
        $_SESSION['gplusdata']=$me;
        header("location: home.php");
        }

        if(isset($authUrl))
        print "<a class='login' href='$authUrl'>Google Plus Login </a>";
        else
        print "<a class='logout' href='index.php?logout'>Logout</a>";


    }    

    public static function activarToken2($user = '',$pass = ''){
        $client = new \Google_Client();
    // Visit https://code.google.com/apis/console to generate your
    // oauth2_client_id, oauth2_client_secret, and to register your oauth2_redirect_uri.
    //     $client->setClientId('insert_your_oauth2_client_id');
        $client->setClientId('697885352902-99bm1dqg2s2u9bkka7qi46d1vspjnlc2.apps.googleusercontent.com');
    //     $client->setClientSecret('insert_your_oauth2_client_secret');
        $client->setClientSecret('rMNyCInefUvQl622vYQYim-9');
        //$client->setRedirectUri('https://localhost:8084/app_dev.php');
        $client->setRedirectUri('http://servicios.eprowin.com/app.php/tareas');
        $client->setApplicationName("Lector de Tareas");
        $client->setDeveloperKey('AIzaSyCrywKx3941y3S89dmRJhpXAhYy7IhCjPc');

        $oauth2 = new \Google_Oauth2Service($client);

        if (isset($_GET['code']))
        {
        $client->authenticate();
        $_SESSION['token'] = $client->getAccessToken();
        $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
        header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
        }

        if (isset($_SESSION['token'])) {
        $client->setAccessToken($_SESSION['token']);
        }

        if (isset($_REQUEST['logout'])) {
        unset($_SESSION['token']);
        unset($_SESSION['google_data']); //Google session data unset
        $client->revokeToken();
        }

        if ($client->getAccessToken())
        {
        $user = $oauth2->userinfo->get();
        $_SESSION['google_data']=$user; // Storing Google User Data in Session
        header("location: home.php");
        $_SESSION['token'] = $client->getAccessToken();
        } else {
        $authUrl = $client->createAuthUrl();
        }
//        if(isset($authUrl))
//        {
//        echo "<a class='login' href='$authUrl'>Login</a>";
//        } else {
//        echo "<a class='logout' href='?logout'>Logout</a>";
//        }
//        die;
        return $authUrl;


    }    
    
    
    
}

?>
