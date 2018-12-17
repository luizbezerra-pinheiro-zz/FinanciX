<?php

header('Cache-Control: no-cache, must-revalidate');

header('Expires: Mon, 01 Jul 1980 05:00:00 GMT');

header('Content-type: application/json; charset=utf-8');

header('Access-Control-Allow-Origin:*'); ///<-Attention, ne pas oublier cette ligne!!!

require '../database.class.php';

$dbh = Database::connect();

if (!isset($_POST['name'])) {
    echo '{"error":"UNKOWN ERROR"}';
    $dbh = null;
    exit();
}
if (!isset($_POST['access_token'])) {
    echo '{"error":"You must create an account to use this function"}';
    $dbh = null;
    exit();
}
$category_name = $_POST['name'];

//First we get the $username and $user_email
require '../get/getuserdata.php';
//

//Now we get the id of this category
$query = "SELECT id FROM financix_categories WHERE name = ?";
$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($category_name));
$result = $sth->fetchAll(PDO::FETCH_ASSOC);
$id_category = $result[0]['id']; //Now we have the id_category
//
if(sizeof($result)===0){
    echo '{"erro":"There\'s no such category!"}';
    exit();
}

//

//We need to check if this category is a non-removable category before going on
if($id_category < 20){
    echo '{"error":"You can\'t remove this category!"}';
    $dbh = null;
    exit();    
}
//Now we remove all subcategories

require 'clearsubcategories.php';
//Now we need to change in financix_transactions all transaction that were with this category
//We're going to update it with the Category: Other (id : 12)
//Remind to the client: this php is used only after cleaning all subcategories



$query = "UPDATE financix_transactions SET id_category='12' WHERE id_category = ? AND user_email = ?";
$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($id_category, $user_email));
//

//Now we have to delete the categorie from financix_categories_users
$query = "DELETE FROM `financix_categories_users` WHERE id_category = ? AND user_email = ?";
$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($id_category, $user_email));

echo '{"success":"Category deleted with success!"}';
$dbh = null;
exit();





