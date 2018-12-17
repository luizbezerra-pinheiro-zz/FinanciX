<?php

header('Cache-Control: no-cache, must-revalidate');

header('Expires: Mon, 01 Jul 1980 05:00:00 GMT');

header('Content-type: application/json; charset=utf-8');

header('Access-Control-Allow-Origin:*'); ///<-Attention, ne pas oublier cette ligne!!!

require '../database.class.php';

$dbh = Database::connect();

$id = $_POST['id'];

$query = "SELECT value, flag, id_wallet FROM financix_transactions WHERE id = ?";
$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($id));
$result = $sth->fetchAll(PDO::FETCH_ASSOC);

if(!sizeof($result)){
    echo '{"error":"UNKOWN ERROR"}';    
}

$id_wallet = $result[0]['id_wallet']; //Now we have the id_wallet
$value = (1-2*$result[0]['flag'])*$result[0]['value'];

//Now we need to get the amount of this wallet
$query = "SELECT amount FROM financix_wallets WHERE id = ?";

$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($id_wallet));
$result = $sth->fetchAll(PDO::FETCH_ASSOC);

$amount = $result[0]['amount'];

$amount = $amount + $value;

//Now we update the wallet
$query = "UPDATE financix_wallets SET amount=? WHERE id = ?";
$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($amount, $id_wallet));

//Now we delete the transaction
$query = "DELETE FROM financix_transactions WHERE id = ?";
$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($id));

echo '{"success":"Transaction deleted"}';

