<?php

// Lecture des paramétres de connexion à la base dedonnée
try{
    $env = parse_ini_file('../.env');
}catch (exception $e) {
    die("Vous devez créer un `.env` à la racine");
}

// Connexion à la base de donnée
try {
    $host = $env['host'];
    $base = $env['database'];
    $bdd = new PDO("mysql:host=$host;dbname=$base", $env['user'], $env['password']);
} catch (PDOException $e) {
    echo "Erreur !: " . $e->getMessage() . "<br/>";
    die();
 }