<?php

header('Cache-Control: no-cache, must-revalidate');

header('Expires: Mon, 01 Jul 1980 05:00:00 GMT');

header('Content-type: application/json; charset=utf-8');

header('Access-Control-Allow-Origin:*'); ///<-Attention, ne pas oublier cette ligne!!!

date_default_timezone_set("Europe/Paris");


require '../database.class.php';

$dbh = Database::connect();

require 'getuserdata.php';

$month = $_POST['month'];
$year = $_POST['year'];

$now2 = (new \DateTime('now'));
$now = $now2->format('Y-m-d');
$thismonth = $now2->format('m');

$query = "SELECT * FROM financix_transactions WHERE MONTH(`date`) = ? AND YEAR(`date`) = ? AND user_email = ? ORDER BY date DESC";
$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($month, $year, $user_email));
$result = $sth->fetchAll(PDO::FETCH_ASSOC);



if ($month == $thismonth) {
    for ($i = 0, $key = 0; $i < sizeof($result); $i = $i + 1) {
        $flag1 = 0;
        if ($i > 0) {
            while ($i < sizeof($result) && $flag1 == 0) {
                if ($result[$i]['date'] == $result[$i - 1]['date']) {
                    $i = $i + 1;
                } else {
                    $flag1 = 1;
                }
            }
        }
        if ($i < sizeof($result)) {
            $timestamp = strtotime($result[$i]['date']);
            $day = date('j', $timestamp); //the day of the transaction
            require 'getdaytransactions.php'; //$t
            $today = date('j', strtotime("now"));
            $yesterday = date('j', strtotime("-1 day"));
            if ($day === $today) {
                $x[$key]['day'] = 'Today';
            } else if ($day === $yesterday) {
                $x[$key]['day'] = 'Yesterday';
            } else {
                $x[$key]['day'] = 'Day ' . $day;
            }
            $x[$key]['transactions'] = $t;
            $key = $key + 1;
            $t = null;
        }
    }
} else {
    $key = 0;
    for ($i = 0; $i < sizeof($result); $i = $i + 1) {
        $flag = 0;
        if ($i > 0) {
            while ($i < sizeof($result) && $flag == 0) {
                if ($result[$i]['date'] == $result[$i - 1]['date']) {
                    $i = $i + 1;
                } else {
                    $flag = 1;
                }
            }
        }
        if ($i < sizeof($result)) {
            $timestamp = strtotime($result[$i]['date']);
            $day = date('j', $timestamp); //the day of the transaction
            require 'getdaytransactions.php'; //$t

            $x[$key]['day'] = 'Day ' . $day;
            $x[$key]['transactions'] = $t;
            $key = $key + 1;
        }
    }
}
echo json_encode($x);

$dbh = null;
exit();

