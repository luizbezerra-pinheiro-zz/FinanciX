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

require 'gettotalexpenses_month.php'; // $total
$y['total-expensed'] = $total;

for ($i = 0, $key = 0; $i < sizeof($resultA); $i = $i + 1) {
    $category_name = $resultA[$i]['name'];
    $id_category = $resultA[$i]['id'];
    require 'getsubcategories.php'; //$result
    require 'getcategorydata_month.php'; //$totalcat   
    if ($totalcat) {
        $x[$key]['category'] = $category_name;
        $x[$key]['subcategories'] = $result; //If this category has no subcategories this vector is undefined
        $x[$key]['total-expensed'] = $totalcat;
        for ($j = 0, $key2=0; $j < sizeof($result); $j = $j + 1) {
            $subcategory_name = $x[$key]['subcategories'][$j]['name'];
            require 'getsubcategorydata_month.php'; //$totalsubcat
            if($totalsubcat){
                $x[$key]['subcategories'][$key2]['total-expensed'] = $totalsubcat;
                $key2 = $key2 +1;
            }
        }
        $key = $key+1;
    }
}
$y['expenses'] = $x;
//
require 'gettotalincomes_month.php'; // $total
$y['total-income'] = $total;

echo json_encode($y);

$dbh = null;

exit();
