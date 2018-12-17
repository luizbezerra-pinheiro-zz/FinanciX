<?php

//Now we get all subcategories in $result and put it in $x
require '../get/getsubcategories.php';

//Now we have $x with
$x[0]['category'] = $category_name;
$x[0]['subcategories'] = $result; //If this category has no subcategories this vector is undefined

if (sizeof($x[0]['subcategories']) === 0) {
} else {
    for ($i = 0; $i < sizeof($x[0]['subcategories']); $i++) {
        $subcategory_name = $x[0]['subcategories'][$i]['name'];
        require 'removesubcategory.php';
    }
}
//
//
?>