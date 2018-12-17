<?php

$query = "SELECT value FROM financix_transactions WHERE MONTH(`date`) = ? AND YEAR(`date`) = ? AND user_email = ? AND `id_category` = ? AND flag = 0";
$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($month, $year, $user_email, $id_category));
$a = $sth->fetchAll(PDO::FETCH_ASSOC);
$totalcat = 0;
for ($c = 0; $c<sizeof($a); $c=$c+1){
    $totalcat = $totalcat + $a[$c]['value'];
}
?>