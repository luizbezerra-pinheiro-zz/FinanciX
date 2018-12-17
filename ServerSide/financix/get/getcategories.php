<?php

//This php returns a vector that looks like [0]:{"category" : " ... ",
//                                               "subcategories" : [
//                                                                  {"subcategory" : " ... "},
//                                                                  {"subcategory" : " ... "},
//                                                                  ...
//                                                                 ]

//First we get the $username and $user_email
header('Cache-Control: no-cache, must-revalidate');

header('Expires: Mon, 01 Jul 1980 05:00:00 GMT');

header('Content-type: application/json; charset=utf-8');

header('Access-Control-Allow-Origin:*'); ///<-Attention, ne pas oublier cette ligne!!!

require '../database.class.php';

$dbh = Database::connect();

require 'getuserdata.php';
//

$query = "SELECT financix_categories.id, financix_categories.name FROM financix_categories
        INNER JOIN financix_categories_users
        ON financix_categories.id = financix_categories_users.id_category
        WHERE financix_categories_users.user_email = ?;";
$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($user_email));
$result1 = $sth->fetchAll(PDO::FETCH_ASSOC); //here we have [0]: {"id":"...", "name":"..."}
//Now, for each id we need to get their sub categories
for ($i = 0; $i < sizeof($result1); $i = $i + 1) {
    $category_name = $result1[$i]['name'];
    require 'getsubcategories.php';
    $x[$i]['category'] = $category_name;
    $x[$i]['subcategories'] = $result; //If this category has no subcategories this vector is undefined
}

echo json_encode($x);

$dbh = null;

exit();