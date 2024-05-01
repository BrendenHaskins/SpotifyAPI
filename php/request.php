<?php
//Brenden Haskins       functions representing API calls
require "secrets.php";

//use this function to get a key to the API. It can be accessed by: $myVar = $f3->get('SESSION.accessToken');
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
        $jsonResult = json_decode($response,true);
        $prepToken = $jsonResult['accessToken'];
        $f3->set('SESSION.accessToken', $prepToken);

        //debugging
        $f3->set('SESSION.debug', "TOKEN RECEIVED");
    } else {
        if($response === false) {
            echo "fatal issue reaching Spotify API: did not receive token.";
            $f3->set('SESSION.debug', "NO RESPONSE");
        }
    }
}


function makeArtistInfoRequest($url, $f3): void {
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