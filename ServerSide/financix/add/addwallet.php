<?php

header('Cache-Control: no-cache, must-revalidate');

header('Expires: Mon, 01 Jul 1980 05:00:00 GMT');

header('Content-type: application/json; charset=utf-8');

header('Access-Control-Allow-Origin:*'); ///<-Attention, ne pas oublier cette ligne!!!

require '../database.class.php';

$dbh = Database::connect();

if (!isset($_POST['wallet_name'])) {
    echo '{"error":"Missing wallet\'s name"}';
    $dbh = null;
    exit();
}
if (!isset($_POST['access_token'])) {
    echo '{"error":"You must create an account to use this function}';
    $dbh = null;
    exit();
}
$currency = 1;
$wallet_name = $_POST['wallet_name'];
//First we get the user_id to always associate the new wallet with the user


//First we get the $username and $user_email
require '../get/getuserdata.php';
//

//We need to check if this user doesn't have that wallet yet
$query = "SELECT id FROM financix_wallets WHERE name = ? AND user_email = ? AND currency = ?";

$sth = $dbh->prepare($query);

$requestSucceeded = $sth->execute(array($wallet_name, $user_email, $currency));
$result = $sth->fetchAll(PDO::FETCH_ASSOC);

if (sizeof($result) !== 0) {
    echo '{"error":"You already have this wallet!"}';
    $dbh = null;
    exit();
}
//Here and so on we are sure that this is really a new category
//First we add it
$query = "INSERT INTO financix_wallets (name, currency, user_email) VALUES(?,?,?)";
$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($wallet_name, $currency, $user_email));

echo '{"success":"New wallet created with success!"}';
$dbh = null;
exit();





