<?php

$query = "SELECT value FROM financix_transactions WHERE MONTH(`date`) = ? AND YEAR(`date`)=? AND user_email = ? AND flag = 1";
$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($month,$year, $user_email));
$a = $sth->fetchAll(PDO::FETCH_ASSOC);
$total = 0;
for ($i = 0; $i<sizeof($a); $i=$i+1){
    $total = $total + $a[$i]['value'];
}
