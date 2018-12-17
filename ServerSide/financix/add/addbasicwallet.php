<?php

$query = "INSERT INTO financix_wallets (name, currency, user_email) VALUES(?,?,?)";
$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($wallet_name, $currency, $user_email));

?>
