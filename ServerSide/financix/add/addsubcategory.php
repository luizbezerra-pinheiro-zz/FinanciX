<?php

header('Cache-Control: no-cache, must-revalidate');

header('Expires: Mon, 01 Jul 1980 05:00:00 GMT');

header('Content-type: application/json; charset=utf-8');

header('Access-Control-Allow-Origin:*'); ///<-Attention, ne pas oublier cette ligne!!!

require '../database.class.php';

$dbh = Database::connect();

if (!isset($_POST['name'])) {
    echo '{"error":"Missing subcategory\'s name"}';
    $dbh = null;
    exit();
}
if (!isset($_POST['access_token'])) {
    echo '{"error":"You must create an account to use this function}';
    $dbh = null;
    exit();
}
//The entries
$subcategory_name = $_POST['name'];
$access_token = $_POST['access_token'];
$name_mother = $_POST['name_mother'];

//First we get the $username and $user_email
require '../get/getuserdata.php';
//

//First we get the id_mother
    $query = "SELECT id FROM financix_categories WHERE name = ?";
    $sth = $dbh->prepare($query);
    $requestSucceeded = $sth->execute(array($name_mother));
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    $id_mother = $result[0]['id']; //Now we have the user_email

//If that subcategory was already created by other user we just get his id and id_mother

$query = "SELECT id FROM financix_subcategories WHERE name = ? AND id_mother = ?";

$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($subcategory_name, $id_mother));
$result = $sth->fetchAll(PDO::FETCH_ASSOC);

if (sizeof($result) !== 0) {
    $id_subcategory = $result[0]['id'];
}
//
else { //Otherwise we put it in the database and get the id and id_mother

    //
    //Now we add it
    $query = "INSERT INTO financix_subcategories (name, id_mother) VALUES(?, ?)";
    $sth = $dbh->prepare($query);
    $requestSucceeded = $sth->execute(array($subcategory_name, $id_mother));

    //Then we get the id WE COULD USE SELECT LAST_INSERT_ID(); NEED TO TEST
    $query = "SELECT id FROM financix_subcategories WHERE name = ?";
    $sth = $dbh->prepare($query);
    $requestSucceeded = $sth->execute(array($subcategory_name));
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    $id_subcategory = $result[0]['id'];
}

//With the new subcategory added (or not) in the db and having id_subcategory, user_email
//we can put it in financix_subcategories_users
//But first we need to check if this user doesn't have that subcategory yet
//So we need to verifie if is there any entry in financix_subcategories_users with
//category_id and user_email
$query = "SELECT id FROM financix_subcategories_users WHERE id_subcategory = ? AND user_email = ?";

$sth = $dbh->prepare($query);

$requestSucceeded = $sth->execute(array($id_subcategory, $user_email));
$result = $sth->fetchAll(PDO::FETCH_ASSOC);

if (sizeof($result) !== 0) {
    echo '{"error":"You already have this category!"}';
    $dbh = null;
    exit();
}
//Here and so on we are sure that this is really a new subcategory for that user
//First we add it
$query = "INSERT INTO financix_subcategories_users (id_subcategory, user_email) VALUES(?,?)";
$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($id_subcategory, $user_email));

echo '{"success":"New subcategory created with success!"}';
$dbh = null;
exit();





