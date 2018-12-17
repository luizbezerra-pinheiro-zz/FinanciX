<?php

header('Cache-Control: no-cache, must-revalidate');

header('Expires: Mon, 01 Jul 1980 05:00:00 GMT');

header('Content-type: application/json; charset=utf-8');

header('Access-Control-Allow-Origin:*'); ///<-Attention, ne pas oublier cette ligne!!!

require '../database.class.php';

$dbh = Database::connect();

require 'getuserdata.php';

//
$category_name = $_POST['category_name'];
$month = $_POST['month'];
$year = $_POST['year'];

//Now we get the id of this category
$query = "SELECT id FROM financix_categories WHERE name = ?";
$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($category_name));
$result = $sth->fetchAll(PDO::FETCH_ASSOC);
$id_category = $result[0]['id']; //Now we have the id_category
//
//Now, for each id we need to get their sub categories

require 'getsubcategories.php'; //$result
require 'getcategorydata_month.php'; //$totalcat
$z[0] = ['Subcategory', 'Expensed'];

$subcatexpenses = 0;
for ($j = 0; $j < sizeof($result); $j = $j + 1) {
    $subcategory_name = $result[$j]['name'];
    require 'getsubcategorydata_month.php'; //$totalsubcat
    $z[$j + 1][0] = $subcategory_name;
    $z[$j + 1][1] = $totalsubcat;
    $subcatexpenses = $subcatexpenses + $totalsubcat;
}
if ($totalcat - $subcatexpenses) {
    $w[0] = ['Subcategory', 'Expensed'];
    $z[0][0] = $category_name;
    $z[0][1] = $totalcat - $subcatexpenses;
    $res = array_merge($w, $z);
    echo json_encode($res);
}
else{
echo json_encode($z);
}
$dbh = null;

exit();
