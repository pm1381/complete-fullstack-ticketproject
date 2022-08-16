<?php

namespace App\Model;

use App\Core\Generator;

class Theater extends Generator
{
    public static $current;

    public function __construct()
    {
        $tableName = 'theater';
        parent::__construct($tableName);
    }
}