<?php
$config = require basePath('config/db.php');
$db = new Database($config);

$id = $_GET['id'] ?? '';

// inspect($id);

$params = [
    'id' => $id
];

$listing = $db->query('select * from listings where id =:id', $params)->fetch();


// inspect($listing);

loadView('listings/show', [
    'listing' => $listing
]);
