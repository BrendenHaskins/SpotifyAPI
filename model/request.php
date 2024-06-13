<?php
//Brenden Haskins       functions representing API calls

//get credentials for API
require "secrets/credentials.php";
require "classes/BaseHandler.php";
require "classes/LinkHandler.php";


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

   $handler = new LinkHandler('https://api.spotify.com/v1/playlists/'.$url.
       '?market=ES&fields=tracks.items%28track%28artists%28name%29%29', $f3->get('SESSION.token'));

    $temp = $handler->execute();
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
    ?>
<script>
    var artistArray = <?php echo json_encode($outputArray); ?>;
</script>
<?php
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

    $linkSafeArtist = urlencode($rawArtist);

    $handler = new LinkHandler('https://api.spotify.com/v1/search?q=artist%3A'.$linkSafeArtist.'&type=artist&limit=1', $f3->get('SESSION.token'));

    $firstArray = $handler->execute();
    $secondArray = $firstArray['artists']['items'][0];
    $popularity = $secondArray['popularity'];
    $genres = $secondArray['genres'];
    $lookupAPILink = $secondArray['href'];
    $icon = $secondArray['images'][0]['url'];

    $f3->set('SESSION.hiddenPopularity', $popularity);
    $f3->set('SESSION.hiddenGenres', $genres);
    $f3->set('SESSION.hiddenLink', $lookupAPILink);
    $f3->set('SESSION.hiddenIcon', $icon);
}

function searchForArtist($artistName, $f3): array {
    $rawArtist = $artistName;

    $linkSafeArtist = urlencode($rawArtist);

    $handler = new LinkHandler('https://api.spotify.com/v1/search?q=artist%3A'.$linkSafeArtist.'&type=artist&limit=1',$f3->get('SESSION.token'));

    $firstArray = $handler->execute();
    $secondArray = $firstArray['artists']['items'][0];
    $popularity = $secondArray['popularity'];
    $genres = $secondArray['genres'];
    $commonGenres = array();

    //only add to genres list if they are shared between searched artist and hidden artist.
    foreach($genres as $genre) {
        if(in_array($genre, $f3->get('SESSION.hiddenGenres'))) {
            $commonGenres[] = $genre;
        }
    }

    return array('Name'=>$rawArtist,'Popularity'=>$popularity, 'Genres'=>$commonGenres);


}

function getHiddenArtistTopSong($f3) : void {
    //get the session link
    $artistAPI = $f3->get('SESSION.hiddenLink');
    //split by slash
    $API = explode('/',$artistAPI);
    //get the last string, which should be the proper API id
    $artistID = $API[sizeof($API)-1];

    $f3->set('SESSION.hiddenArtistID', $artistID);

    $handler = new LinkHandler('https://api.spotify.com/v1/artists/'.$artistID.'/top-tracks', $f3->get('SESSION.token'));

    $response = $handler->execute();

    $secondArray = $response['tracks'];
    $output = $secondArray[0]['name'];


    $f3->set('SESSION.hiddenTopSong', $output);
}

function getHiddenArtistPhoto($f3) : void {
    $artistID = $f3->get('SESSION.hiddenArtistID');

    $handler = new LinkHandler('https://api.spotify.com/v1/artists/'.$artistID, $f3->get('SESSION.token'));

    $firstArray = $handler->execute();
    $secondArray = $firstArray['images'];
    $output = $secondArray[0]['url'];


    $f3->set('SESSION.hiddenPhotoURL', $output);
}

