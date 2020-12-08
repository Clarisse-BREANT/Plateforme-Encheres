<?php
// CHARGEMENT DES FICHIERS JSON
function load($file) {
    $json = file_get_contents("../data/" . $file .".json");
    return json_decode($json,true);
}

// SAUVEGARDE DES FICHIERS JSON
function save($var/*liste des encheres*/, $file) {
    $file_handler = fopen("../data/" . $file . ".json", "w");
    fwrite($file_handler, json_encode($var));
    fclose($file_handler);
}
?>