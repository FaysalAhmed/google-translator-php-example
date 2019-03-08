<?php
require 'vendor/autoload.php';

use Google\Cloud\Translate\TranslateClient;

// translator key will be here
$translate = new TranslateClient([
    'key' => '',
]);

// database information will be here
$host    = '127.0.0.1';
$db      = '';
$user    = '';
$pass    = '';
$charset = '';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int) $e->getCode());
}

$query = ''; // collect text from database
$stmt  = $pdo->query($query);
while ($row = $stmt->fetch()) {
    echo $row['id'] . "\n";
    echo $row['name_en'] . "\n";
    $result = $translate->translate($row['name_en'], [
        'target' => 'ja',
    ]);
    echo $result['text'] . "\n";
    $stmt2 = $pdo->prepare(''); // make update query 
    $stmt2->execute([$result['text'], $row['id']]);
}
