<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$query = "SELECT value FROM financix_transactions WHERE MONTH(`date`) = ? AND YEAR(`date`) = ? AND user_email = ? AND flag = 0";
$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($month,$year, $user_email));
$a = $sth->fetchAll(PDO::FETCH_ASSOC);
$total = 0;
for ($i = 0; $i<sizeof($a); $i=$i+1){
    $total = $total + $a[$i]['value'];
}
