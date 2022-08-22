<?php

use App\Model\MongoModels\User;

require __DIR__ . '/vendor/autoload.php';

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

// mongodb+srv://<username>:<password>@<cluster-address>/test?retryWrites=true&w=majority
//phpinfo();

$user = new User();

$user->option([])->where(['title' => 'salam' ])->findData();

var_dump($user->getCount());

foreach ($user->getResult() as $x) {
    // echo gettype($x);
    var_dump($x);
}
