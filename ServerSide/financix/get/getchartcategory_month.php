<?php

header('Cache-Control: no-cache, must-revalidate');

header('Expires: Mon, 01 Jul 1980 05:00:00 GMT');

header('Content-type: application/json; charset=utf-8');

header('Access-Control-Allow-Origin:*'); ///<-Attention, ne pas oublier cette ligne!!!

require '../database.class.php';

$dbh = Database::connect();

require 'getuserdata.php';

//
$month = $_POST['month'];
$year = $_POST['year'];

$query = "SELECT financix_categories.id, financix_categories.name FROM financix_categories
        INNER JOIN financix_categories_users
        ON financix_categories.id = financix_categories_users.id_category
        WHERE financix_categories_users.user_email = ?;";


$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($user_email));
$resultA = $sth->fetchAll(PDO::FETCH_ASSOC); //here we have [0]: {"id":"...", "name":"..."}
//Now, for each id we need to get their sub categories

$z[0] = ['Category', 'Expensed'];
for ($i = 0; $i < sizeof($resultA); $i = $i + 1) {
    $category_name = $resultA[$i]['name'];
    $id_category = $resultA[$i]['id'];
    require 'getcategorydata_month.php'; //$totalcat
    $z[$i+1][0] = $category_name;
    $z[$i+1][1] = $totalcat;
}

echo json_encode($z);

$dbh = null;
exit();