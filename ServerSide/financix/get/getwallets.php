<?php

header('Cache-Control: no-cache, must-revalidate');

header('Expires: Mon, 01 Jul 1980 05:00:00 GMT');

header('Content-type: application/json; charset=utf-8');

header('Access-Control-Allow-Origin:*'); ///<-Attention, ne pas oublier cette ligne!!!

require '../database.class.php';

$dbh = Database::connect();

//First we get the $username and $user_email
require 'getuserdata.php';
//


$query = "SELECT name, amount FROM financix_wallets WHERE user_email = ?";

$sth = $dbh->prepare($query);

$requestSucceeded = $sth->execute(array($user_email));

$result = $sth->fetchAll(PDO::FETCH_ASSOC);

$total=0;
for($i=0; $i<sizeof($result); $i=$i+1){
    $total = $total + $result[$i]['amount'];
}

$x['total'] = number_format((float)$total, 2, '.', '');
$x['wallets'] = $result;
echo json_encode($x);

$dbh = null;