<?php

//First we get the user_id to always associate the new category with the user
$access_token = $_POST['access_token'];

$query = "SELECT user_id FROM oauth_access_tokens WHERE access_token = ?";
$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($access_token));
$result = $sth->fetchAll(PDO::FETCH_ASSOC);
$username = $result[0]['user_id'];  //We get the user_id from the requet

//Now we need to get the user_email (PRIMARY KEY of oauth_users)

$query = "SELECT email FROM oauth_users WHERE username = ?";
$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($username));
$result = $sth->fetchAll(PDO::FETCH_ASSOC);
$user_email = $result[0]['email']; //Now we have the user_email
//

?>