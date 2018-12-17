<?php

header('Cache-Control: no-cache, must-revalidate');

header('Expires: Mon, 01 Jul 1980 05:00:00 GMT');

header('Content-type: application/json; charset=utf-8');

header('Access-Control-Allow-Origin:*'); ///<-Attention, ne pas oublier cette ligne!!!

require '../database.class.php';

$dbh = Database::connect();

if (!isset($_POST['name'])) {
    echo '{"error":"Missing category\'s name"}';
    $dbh = null;
    exit();
}
if (!isset($_POST['access_token'])) {
    echo '{"error":"You must create an account to use this function}';
    $dbh = null;
    exit();
}
$category_name = $_POST['name'];

//First we get the $username and $user_email
require '../get/getuserdata.php';
//

//echo json_encode($user_id);
//


//If that category was already created by other user we just get the id

$query = "SELECT id FROM financix_categories WHERE name = ?";

$sth = $dbh->prepare($query);

$requestSucceeded = $sth->execute(array($category_name));
$result = $sth->fetchAll(PDO::FETCH_ASSOC);

if (sizeof($result) !== 0) {
    $id_category = $result[0]['id'];
}
//
else { //Otherwise we put it in the database and get the id
    //First we add it
    $query = "INSERT INTO financix_categories (name) VALUES(?)";
    $sth = $dbh->prepare($query);
    $requestSucceeded = $sth->execute(array($category_name));

    //Then we get the id WE COULD USE SELECT LAST_INSERT_ID(); NEED TO TEST
    $query = "SELECT id FROM financix_categories WHERE name = ?";
    $sth = $dbh->prepare($query);
    $requestSucceeded = $sth->execute(array($category_name));
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    $id_category = $result[0]['id'];
}

//With the new category added in the db and having id_category, user_email
//we can put it in financix_categories_users
//But first we need to check if this user doesn't have that category yet
//So we need to verifie if is there any entry in financix_categories_users with
//category_id and user_email

$query = "SELECT id FROM financix_categories_users WHERE id_category = ? AND user_email = ?";
$sth = $dbh->prepare($query);

$requestSucceeded = $sth->execute(array($id_category, $user_email));
$result = $sth->fetchAll(PDO::FETCH_ASSOC);

if (sizeof($result) !== 0) {
    echo '{"error":"You already have this category!"}';
    $dbh = null;
    exit();
}
//Here and so on we are sure that this is really a new category
//First we add it
$query = "INSERT INTO financix_categories_users (id_category, user_email) VALUES(?,?)";
$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($id_category, $user_email));

echo '{"success":"New category created with success!"}';
$dbh = null;
exit();





