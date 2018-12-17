<?php

header('Cache-Control: no-cache, must-revalidate');

header('Expires: Mon, 01 Jul 1980 05:00:00 GMT');

header('Content-type: application/json; charset=utf-8');

header('Access-Control-Allow-Origin:*'); ///<-Attention, ne pas oublier cette ligne!!!

require '../database.class.php';

$dbh = Database::connect();

require 'getuserdata.php';

$month = $_POST['month'];
$year = $_POST['year'];
$category_name = $_POST['category_name'];

$query = "SELECT id FROM financix_categories WHERE name = ?";
$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($category_name));
$resultA = $sth->fetchAll(PDO::FETCH_ASSOC);

$id_category = $resultA[0];

require 'getsubcategories.php'; //$result
require 'getcategorydata_month.php'; //$totalcat   
$x['category'] = $category_name;
//$x['subcategories'] = $result; //If this category has no subcategories this vector is undefined
$subcatexpenses = 0;
for ($j = 0, $key2 = 0; $j < sizeof($result); $j = $j + 1) {
    $subcategory_name = $result[$j]['name'];
    require 'getsubcategorydata_month.php'; //$totalsubcat
    if ($totalsubcat) {
        $x['subcategories'][$key2]['name'] = $subcategory_name;
        $x['subcategories'][$key2]['total-expensed'] = $totalsubcat;
        $key2 = $key2 + 1;
        $subcatexpenses = $subcatexpenses + $totalsubcat;
    }
}
$x['total-expensed'] = $totalcat - $subcatexpenses;

echo json_encode($x);

$dbh = null;
exit();

