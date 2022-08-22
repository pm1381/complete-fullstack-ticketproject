<?php

namespace App\Model\MongoModels;

use App\Core\MongoGenerator;
use App\Core\Tools;

class User extends MongoGenerator
{

    public function __construct()
    {
        parent::__construct('user');
    }

}