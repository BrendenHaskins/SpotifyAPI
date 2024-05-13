<?php

function queryTableViaID($pdo, $user_id) {
    $something = $pdo->prepare('SELECT * FROM SpotifyUser WHERE user_id = ?');
    $something->execute([$user_id]);
    return $something->fetch();
}
function queryTable($pdo) {
    $something = $pdo->prepare('SELECT * FROM SpotifyUser');
    $something->execute();
    return $something->fetch();
}

//TODO: More specific queries depending on data needed to be taken out
