<?php

//Now we get the id of this category (the mother category)
$query = "SELECT id FROM financix_categories WHERE name = ?";
$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($category_name));
$result = $sth->fetchAll(PDO::FETCH_ASSOC);
$id_category = $result[0]['id']; //Now we have the id_category
//

//Now we get the id of this subcategory
$query = "SELECT id FROM financix_subcategories WHERE name = ? AND id_mother = ?";
$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($subcategory_name, $id_category));
$result = $sth->fetchAll(PDO::FETCH_ASSOC);
$id_subcategory = $result[0]['id']; //Now we have the id_category
//

//Now we need to change in financix_transactions all transaction that were with this subcategory
//We're going to update it with the subcategory: NULL
//Remind to the client: this php is used only after cleaning all subcategories

$query = "UPDATE financix_transactions SET id_subcategory=NULL WHERE id_subcategory = ? AND user_email = ?";
$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($id_subcategory, $user_email));

//Now as it is done we need just to delete this subcategory from financix_subcategories_users
$query = "DELETE FROM financix_subcategories_users WHERE id_subcategory = ? AND user_email = ?";
$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($id_subcategory, $user_email));
//
?>

