<?php
session_start();

//error reporting
ini_set('display_errors',1);
error_reporting(E_ALL);

//requirements
require_once('vendor/autoload.php');
require('php/secrets.php');

//instantiate base F3 base class
$f3 = Base::instance();

//define default route
$f3->route('GET|POST /', function($f3){
    if($_SERVER['REQUEST_METHOD'] == 'GET') {
        $view = new Template();
        echo $view->render('views/home.html');
    } else {
        $view = new Template();
        echo $view->render('views/home.html').getToken($f3);
    }
});

$f3->route('GET|POST /token', function($f3){
    if($_SERVER['REQUEST_METHOD'] == 'GET') {
        $view = new Template();
        echo $view->render('views/token.html');
    } else {
        $view = new Template();
        echo $view->render('views/artist.html').makeRequest( $_POST['url'], $f3);
    }
});

//other
function getToken($f3): void {

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://accounts.spotify.com/api/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => getSecret(),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    if(is_string($response)) {
        $f3->set('SESSION.SpotifyToken',$response);

        $jsonResult = json_decode($f3->get('SESSION.SpotifyToken'),true);
        $f3->set('SESSION.accessToken', $jsonResult);

        $f3->reroute("token");

    } else {
        if($response === false) {
            echo "fatal issue reaching Spotify API: did not receive token.";
        }
    }
}

function makeRequest($url, $f3): void {
    $badUrl = $url;

    $urlArray = explode("/",$badUrl);

    $lastIndex = sizeof($urlArray);

    $urlSuffix = $urlArray[$lastIndex-1];

    $goodUrl = "https://api.spotify.com/v1/artists/".$urlSuffix;

    $curl = curl_init();

    $token = $f3->get('SESSION.accessToken')['access_token'];

    curl_setopt_array($curl, array(
        CURLOPT_URL => $goodUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$token
),
));

    $response = curl_exec($curl);
    $jsonResult = json_decode($response,true);

    $f3->set('SESSION.requestResponse', $jsonResult);

    $f3->set("SESSION.artistName", $jsonResult['name']);
    $f3->set("SESSION.genre", $jsonResult['genres']);
    $f3->set("SESSION.img", $jsonResult['images']);

    curl_close($curl);
}


//run fat free
$f3->run();