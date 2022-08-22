<?php

namespace App\Model;

use App\Core\Generator;
use App\Core\Tools;

class User extends Generator
{
    public static $current;

    public function __construct()
    {
        $tableName = 'user';
        parent::__construct($tableName);
    }

    public static function current()
    {
        if (empty(self::$current)) {
            self::$current['id'] =  0;
            self::$current['mobile'] =  0;
            self::$current['sum'] = 0;
            self::$current['login'] = false;

            $cookie = new Cookie();
            $token = $cookie->name('userToken')->select();

            $user = new User();
            $result = $user->row(['userId', 'userMobile', 'userTicketCount', 'userCode', 'userTheater', 'userSum'])
                ->where(['userToken' => $token, 'userActive' => 1])
                ->limit(1)->select();
            if (count($result)) {
                self::$current['id']     = $result['userId'];
                self::$current['mobile'] = $result['userMobile'];
                self::$current['sum']    = $result['userSum'];
                self::$current['login']  = true;
            }
        }
        return self::$current;
    }
}