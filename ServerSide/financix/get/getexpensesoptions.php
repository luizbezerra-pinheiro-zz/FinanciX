<?php

header('Cache-Control: no-cache, must-revalidate');

header('Expires: Mon, 01 Jul 1980 05:00:00 GMT');

header('Content-type: application/json; charset=utf-8');

header('Access-Control-Allow-Origin:*'); ///<-Attention, ne pas oublier cette ligne!!!

require '../database.class.php';

$dbh = Database::connect();

require 'getuserdata.php';
//


$this_day = $_POST['this_day'];
$this_month = $_POST['this_month'];
$this_year = $_POST['this_year'];

$category_name = $_POST['category_name'];

require 'getsubcategories.php'; // $result

//Now we get the wallets
$query = "SELECT name FROM financix_wallets WHERE user_email = ?";

$sth = $dbh->prepare($query);

$requestSucceeded = $sth->execute(array($user_email));

$result2 = $sth->fetchAll(PDO::FETCH_ASSOC);
//

for($i = 0; $i<12; $i=$i+1){
    $month[$i]['name'] = $i+1; 
    if ($i + 1 == $this_month) {
        $month[$i]['selected'] = 'selected';
    } else {
        $month[$i]['selected'] = '';
    }
}

for($i = 0; $i<3; $i=$i+1){
    $year[$i]['name'] = $i+2018;
    
    if ($i + 2018 == $this_year) {
        $year[$i]['selected'] = 'selected';
    } else {
        $year[$i]['selected'] = '';
    }
}


$x['subcategories'] = $result;
$x['wallets'] = $result2;
$x['month'] = $month;
$x['year'] = $year;

echo json_encode($x);
$dbh = null;
exit();
