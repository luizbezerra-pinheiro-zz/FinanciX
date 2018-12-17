<?php

$query = "SELECT * FROM financix_transactions WHERE MONTH(`date`) = ? AND DAY(`date`) = ? AND YEAR(`date`) = ? AND user_email = ? ORDER BY date DESC";
$sth = $dbh->prepare($query);
$requestSucceeded = $sth->execute(array($month, $day, $year, $user_email));
$result2 = $sth->fetchAll(PDO::FETCH_ASSOC);

for ($k = 0; $k < sizeof($result2); $k = $k + 1) {
    //Now we get the name of this category
    $query = "SELECT name FROM financix_categories WHERE id = ?";
    $sth = $dbh->prepare($query);
    $requestSucceeded = $sth->execute(array($result2[$k]['id_category']));
    $A = $sth->fetchAll(PDO::FETCH_ASSOC);
    $category_name = $A[0]['name']; //Now we have the category_name
    //Now we get the name of this subcategory

    if ($result2[$k]['id_subcategory']) {
        $query = "SELECT name FROM financix_subcategories WHERE id = ?";
        $sth = $dbh->prepare($query);
        $requestSucceeded = $sth->execute(array($result2[$k]['id_subcategory']));
        $A = $sth->fetchAll(PDO::FETCH_ASSOC);
        $subcategory_name = $A[0]['name']; //Now we have the subcategory_name
    } else {
        $subcategory_name = null;
    }
    $t[$k]['value'] =  (2*$result2[$k]['flag']-1)*$result2[$k]['value'];
    $t[$k]['category'] = $category_name;
    $t[$k]['subcategory'] = $subcategory_name;
    $t[$k]['description'] = $result2[$k]['description'];
    if($t[$k]['description']){
        $t[$k]['description'] = ' - '.$t[$k]['description'];
    }
    $t[$k]['id'] = $result2[$k]['id'];
}
?>
