<?php
//Brenden Haskins       functions representing API calls

//get credentials for API
require "secrets/credentials.php";



//calling this function will bind a valid token to SESSION.apiToken, good for one hour
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
        CURLOPT_POSTFIELDS => getAPICredentials(),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    //$response is either a JSON string (success) or a boolean (failure)
    //handle JSON object, or notify of failure.

    if(is_string($response)) {
        $temp = json_decode($response,true);
        $token = $temp['access_token'];
        $f3->set('SESSION.token',$token);
        $f3->set('SESSION.apiStatus',true);

    } else {
        $f3->set('SESSION.apiStatus',false);
    }
}

//returns all artists in a playlist given the playlists' id (subset of url)
function getAllArtistsFromPlaylist($url, $f3): void {
    $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.spotify.com/v1/playlists/'.$url.
                '?market=ES&fields=tracks.items%28track%28artists%28name%29%29',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$f3->get('SESSION.token')
            ),
        ));

    $response = curl_exec($curl);
    $temp = json_decode($response, true);
    $trimmedTemp = $temp['tracks']['items'];

    $jsonArray = array();
    $outputArray = array();

    foreach($trimmedTemp as $entry) {
        $jsonArray[] = $entry['track']['artists'];
    }

    foreach($jsonArray as $entry) {
        $outputArray[] = $entry[0]['name'];
    }

    var_dump($outputArray);
    //TODO: Change return type
}


//makes a basic request for information given a typical spotify URL.
//TODO: Revisit this function or delete it.
function makeArtistInfoRequest($url, $f3): void {
    $badUrl = $url;

    $urlArray = explode("/",$badUrl);

    $lastIndex = sizeof($urlArray);

    $urlSuffix = $urlArray[$lastIndex-1];

    $goodUrl = "https://api.spotify.com/v1/artists/".$urlSuffix;

    $curl = curl_init();

    $token = //WHAT TO PUT HERE?

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
            'Authorization: Bearer '.$f3->get('SESSION.token')
        ),
    ));

    $response = curl_exec($curl);
    var_dump($response);
    curl_close($curl);
}

