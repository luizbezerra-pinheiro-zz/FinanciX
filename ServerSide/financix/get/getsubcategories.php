<?php
//Now we get the id of this category
$query = "SELECT id FROM financix_categories WHERE name = ?";
$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($category_name));
$result = $sth->fetchAll(PDO::FETCH_ASSOC);
$id_category = $result[0]['id']; //Now we have the id_category
//
//First we get all the subcategories of this category and this user by getting all
//the financix_subcategories_users.id that can give us the category_id
//Now we need to get all the subcategories of this category and this user
$query = "SELECT financix_subcategories.name FROM financix_subcategories
        INNER JOIN financix_subcategories_users
        ON financix_subcategories.id = financix_subcategories_users.id_subcategory
        WHERE financix_subcategories.id_mother = ? AND financix_subcategories_users.user_email = ?;";

$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($id_category, $user_email));
$result = $sth->fetchAll(PDO::FETCH_ASSOC); //Here in result we have all childrens of id_category
?>