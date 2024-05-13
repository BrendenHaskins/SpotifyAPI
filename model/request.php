<?php
//Brenden Haskins       functions representing API calls

//get credentials for API
require "secrets/credentials.php";

//get db connection and query statements
//TODO: Change include to a require once everyone has database established
include "secrets/db.php";
require "model/query.php";



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
    $artistArray = array();

    foreach($trimmedTemp as $entry) {
        $jsonArray[] = $entry['track']['artists'];
    }

    foreach($jsonArray as $entry) {
        $artistArray[] = $entry[0]['name'];
    }

    $outputArray = array_unique($artistArray);

    $f3->set('SESSION.artists',$outputArray);
}

//returns all artists in a static playlist. Change the URL variable to change the set playlist.
function getAllArtistsFromSetUrl($f3): void {
    //Rolling Stone: Top 100 Artists
    $url = '6GBQBVorVOZQo7qaMBAiyu';

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
    $artistArray = array();

    foreach($trimmedTemp as $entry) {
        $jsonArray[] = $entry['track']['artists'];
    }

    foreach($jsonArray as $entry) {
        $artistArray[] = $entry[0]['name'];
    }

    $outputArray = array_unique($artistArray);

    $f3->set('SESSION.artists',$outputArray);
}

//accesses f3 SESSION.artists to randomly select an artist to hide.
function selectHiddenArtist($f3): void
{

    $artistIndex = rand(0,100);
    $artists = $f3->get('SESSION.artists');

    $f3->set('SESSION.hiddenArtist', $artists[$artistIndex]);
}

//get hidden artist's info to compare against guesses
function getHiddenArtistInfo($f3): void {
    $rawArtist = $f3->get('SESSION.hiddenArtist');

    $linkSafeArtist = str_replace(' ','+',$rawArtist);

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.spotify.com/v1/search?q=artist%3A'.$linkSafeArtist.'&type=artist&limit=1',
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

    $firstArray = json_decode($response, true);
    $secondArray = $firstArray['artists']['items'][0];
    $popularity = $secondArray['popularity'];
    $genres = $secondArray['genres'];
    $lookupAPILink = $secondArray['href'];

    //TODO: Use the $lookupAPILink to fetch top songs, other info

    //Use these statements to print out info known about the artist
    //var_dump($popularity);
    //echo "<hr>";
    //var_dump($genres);
    //echo "<hr>";
    //var_dump($lookupAPILink);



}

function searchForArtist($artistName, $f3): bool {
    return false;
}

