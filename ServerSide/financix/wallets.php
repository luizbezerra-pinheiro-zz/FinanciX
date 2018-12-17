<?php

header('Cache-Control: no-cache, must-revalidate');

header('Expires: Mon, 01 Jul 1980 05:00:00 GMT');

header('Content-type: application/json; charset=utf-8');

header('Access-Control-Allow-Origin:*'); ///<-Attention, ne pas oublier cette ligne!!!

require 'database.class.php';

$dbh = Database::connect();
// Valeur par défaut : Dominique

$query = "SELECT name, amount, currency FROM financix_wallets WHERE 1";

$sth = $dbh->prepare($query);

$requestSucceeded = $sth->execute();

$result = $sth->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($result);

$dbh = null;