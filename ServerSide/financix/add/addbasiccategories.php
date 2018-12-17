<?php

for ($i = 1; $i <= 20; $i++) {
    $query = "INSERT INTO financix_categories_users (id_category, user_email) VALUES(?,?)";
    $sth = $dbh->prepare($query);
    $requestSucceeded = $sth->execute(array($i, $user_email));
}

?>

