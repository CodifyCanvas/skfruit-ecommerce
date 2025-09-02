<?php
// controllers/public/category.php

// <== Include Required Files ==>
include('../../db_connect.php');
include('../../models/CRUD.php');
include('../../models/General-Model.php');

// <== Instantiate Models ==>
$MODEL = new CRUD($pdo);
$GENERALMODEL = new GeneralModel($pdo);

// <== Fetch All Categories with Their Product Count ==>
$categories = $GENERALMODEL->fetchCategoriesWithCountProducts($pdo);

// <== Refine Category Data for Output ==>
$refinedCategories = array_map(function ($cat) {
    // <== Normalize Image Path ==>
    
    // Remove any leading slash from the image path
    $image = ltrim($cat['image_path'], '/');

    // Remove 'SkFruit/' prefix if it exists (to make path relative)
    $image = preg_replace('#^SkFruit/#', '', $image);

    // <== Return Normalized Category Object ==>
    return [
        'id'            => (int) $cat['id'],              // <== Category ID ==>
        'name'          => $cat['category'],              // <== Category Name ==>
        'image'         => $image,                        // <== Cleaned Image Path ==>
        'icon'          => 'fas fa-jar',                  // <== Static Icon (can be dynamic later) ==>
        'product_count' => (int) $cat['product_count']    // <== Number of Products in Category ==>
    ];
}, $categories);

// <== Return JSON Response ==>
echo json_encode([
    'success' => true,
    'data'    => $refinedCategories
]);

exit;
