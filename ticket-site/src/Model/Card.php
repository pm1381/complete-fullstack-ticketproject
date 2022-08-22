<?php

namespace App\Model;

use App\Core\Generator;

class Card extends Generator
{
    public function __construct()
    {
        $tableName = 'card';
        parent::__construct($tableName);
    }
}