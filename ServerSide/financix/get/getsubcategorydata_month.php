<?php

$query = "SELECT id FROM financix_subcategories WHERE `name` = ? AND `id_mother` = ?";
$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($subcategory_name, $id_category));
$as = $sth->fetchAll(PDO::FETCH_ASSOC);
$id_subcategory = $as[0]['id'];


$query = "SELECT value FROM financix_transactions WHERE MONTH(`date`) = ? AND YEAR(`date`) = ? AND user_email = ? AND `id_category` = ? AND `id_subcategory` = ? AND flag = 0";
$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($month, $year, $user_email, $id_category, $id_subcategory));

$a = $sth->fetchAll(PDO::FETCH_ASSOC);
$totalsubcat = 0;
for ($k = 0; $k < sizeof($a); $k = $k + 1) {
        $totalsubcat = $totalsubcat + $a[$k]['value'];
}
?>
