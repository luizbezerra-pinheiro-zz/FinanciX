<?php

header('Cache-Control: no-cache, must-revalidate');

header('Expires: Mon, 01 Jul 1980 05:00:00 GMT');

header('Content-type: application/json; charset=utf-8');

header('Access-Control-Allow-Origin:*'); ///<-Attention, ne pas oublier cette ligne!!!

require 'database.class.php';

$dbh = Database::connect();

//Case where everything is fine (password1==password2 && login unique and everything exists)

if (!isset($_POST['password1'], $_POST['password2'], $_POST['username'], $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['wallet_name'])) {
    echo '{"error":"Missing information"}';
    $dbh = null;
    exit();
}

if (!isset($_POST['currency'])) {
    echo '{"error":"You have to choose a currency"}';
    $dbh = null;
    exit();
}


$password1 = $_POST['password1'];
$password2 = $_POST['password2'];
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);

$query = "SELECT * FROM oauth_users WHERE username = ?";

$sth = $dbh->prepare($query);

$requestSucceeded = $sth->execute(array($username));
$result = $sth->fetchAll(PDO::FETCH_ASSOC);

if (sizeof($result) !== 0) {
    echo '{"error":"Login already exists"}';
    $dbh = null;
    exit();
}

if ($password1 === $password2) {
    $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_SPECIAL_CHARS);
    $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
    $query = "INSERT INTO oauth_users (username, password, first_name, last_name, email) VALUES(?, SHA1(?), ?, ?, ?)";
    $sth = $dbh->prepare($query);
    $requestSucceeded = $sth->execute(array($username, $password1, $first_name, $last_name, $email));

    $user_email = $email;


    require '../add/addbasiccategories.php';


    $wallet_name = $_POST['wallet_name'];
    $currency = $_POST['currency'];
    
    require '../add/addbasicwallet.php';

    if ($requestSucceeded) {
        echo '{"success":"You`re registered and logged in!"}';
    } else {
        echo '{"error":"Fail to register! Try again."}';
    }

    $dbh = null;
} else {
    echo '{"error":"Wrong password combination"}';
    $dbh = null;
    exit();
}




