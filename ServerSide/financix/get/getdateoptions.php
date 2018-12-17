<?php

header('Cache-Control: no-cache, must-revalidate');

header('Expires: Mon, 01 Jul 1980 05:00:00 GMT');

header('Content-type: application/json; charset=utf-8');

header('Access-Control-Allow-Origin:*'); ///<-Attention, ne pas oublier cette ligne!!!

require '../database.class.php';

$dbh = Database::connect();


//
$i = $_POST['this_month'];
$this_day = $_POST['this_day'];
$i = $i - 1;
for ($j = 0; $j < 28; $j = $j + 1) {
    $days[$j]['name'] = $j + 1;
    if ($j + 1 == $this_day) {
        $days[$j]['selected'] = 'selected';
    } else {
        $days[$j]['selected'] = '';
    }
}
if ($i + 1 !== 2) {
    for (; $j < 30; $j = $j + 1) {
        $days[$j]['name'] = $j + 1; //except february
        if ($j + 1 == $this_day) {
            $days[$j]['selected'] = 'selected';
        } else {
            $days[$j]['selected'] = '';
        }
    }
    if ($i == 1 || $i == 3 || $i == 5 || $i == 7 || $i == 8 || $i == 10 || $i == 12) {
        $days[$j]['name'] = $j + 1;
    }
}

echo json_encode($days);
$dbh = null;
exit();
