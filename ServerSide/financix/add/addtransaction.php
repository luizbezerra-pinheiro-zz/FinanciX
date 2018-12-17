<?php

header('Cache-Control: no-cache, must-revalidate');

header('Expires: Mon, 01 Jul 1980 05:00:00 GMT');

header('Content-type: application/json; charset=utf-8');

header('Access-Control-Allow-Origin:*'); ///<-Attention, ne pas oublier cette ligne!!!

require '../database.class.php';

$dbh = Database::connect();

if (!isset($_POST['value'])) {
    echo '{"error": "Value can not be 0"}';
    $dbh = null;
    exit();
}
if (!isset($_POST['category'])) {
    echo '{"error": "You need to select a category"}';
    $dbh = null;
    exit();
}
if (!isset($_POST['subcategory'])) {
    $subcategory_name = null;
    $subcategory_id = null;
} else {
    $subcategory_id = null;
    $subcategory_name = $_POST['subcategory'];
}
if (!isset($_POST['description'])) {
    $description = null;
} else {
    $description = $_POST['description'];
}
if (!isset($_POST['access_token'])) {
    echo '{"error":"Please log in."}';
    $dbh = null;
    exit();
}
//The entries
$flag = $_POST['flag'];
$access_token = $_POST['access_token'];
$value = $_POST['value'];
$category_name = $_POST['category'];
$day = $_POST['this_day'];
if ($day < 10 && $day >= 1) {
    $day = '0' . $day;
}
$month = $_POST['this_month'];
if ($month < 10 && $month >= 1) {
    $month = '0' . $month;
}
$year = $_POST['this_year'];
$date = $year . '-' . $month . '-' . $day;
$wallet_name = $_POST['wallet_name'];


//First we get the $username and $user_email
require '../get/getuserdata.php';
//
//Now we need to get the id_wallet to associate the transaction with the user
$query = "SELECT id, amount FROM financix_wallets WHERE name = ? AND user_email = ?";

$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($wallet_name, $user_email));
$result = $sth->fetchAll(PDO::FETCH_ASSOC);
$id_wallet = $result[0]['id']; //Now we have the id_wallet
//
//Now we need to increase the amount in this wallet, to do that we first
//need to get the amount of this wallet
$amount_wallet = $result[0]['amount'];
//We increase it
if($flag){
    $amount_wallet = $amount_wallet + $value;
}
else{
     $amount_wallet = $amount_wallet - $value;
}
//Now we update the wallet
$query = "UPDATE financix_wallets SET amount=? WHERE name = ? AND user_email = ?";
$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($amount_wallet, $wallet_name, $user_email));
//
//Now we need to get the category_id and the subcategory_id
//
//Now we get the id of this category

$query = "SELECT id FROM financix_categories WHERE name = ?";
$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($category_name));
$result = $sth->fetchAll(PDO::FETCH_ASSOC);


$category_id = $result[0]['id']; //Now we have the id_category
//
//Now we get the id of this subcategory if it has a subcategory
if ($subcategory_name) {
    $query = "SELECT id FROM financix_subcategories WHERE name = ? AND id_mother = ?";
    $sth = $dbh->prepare($query);
    $requestSucceeded = $sth->execute(array($subcategory_name, $category_id));
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    $subcategory_id = $result[0]['id']; //Now we have the id_category
}
//
//
//Now we just have to finish saving the new transaction in financix_transactions
$query = "INSERT INTO financix_transactions (value, date, flag, description, id_category, id_subcategory, id_wallet, user_email) VALUES(?,?,?,?,?,?,?,?)";
$sth = $dbh->prepare($query);

$requestSucceeded = $sth->execute(array($value, $date, $flag, $description, $category_id, $subcategory_id, $id_wallet, $user_email));
//

echo '{"success":"Transaction enresgistred!"}';
$dbh = null;
exit();





