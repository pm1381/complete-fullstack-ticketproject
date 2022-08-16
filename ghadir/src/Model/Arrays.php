<?php
namespace App\Model;

class Arrays 
{

    protected static $nights = [];

    public static function nights()
    {
        if (empty($nights)) {
            $theater = new Theater();
            $result = $theater
                ->row(['theaterId', 'theaterAddress', 'theaterCapacity', 'theaterName', 'theaterShow'])
                ->where([])
                ->select();
            if (count($result)) {
                foreach ($result as $key => $value) {
                    $nights[$value['theaterId']] = [
                        'id' => $value['theaterId'],
                        'address' => $value['theaterAddress'],
                        'capacity' => $value['theaterCapacity'],
                        'title' => $value['theaterName'],
                        'show' => $value['theaterShow']
                    ];
                }
            }    
        }
        return $nights;
    }

    public static function userPageSort()
    {
        return 
        [
            '3' => ['title' => 'بدون پیامک' , 'id' => 3],
            '2' => ['title' => 'بدون کد ورود', 'id' => 2],
            '1' => ['title' => 'درهم' , 'id' => 1]
        ];
    }

    public static function specialCols()
    {
        return 
        [
            'AVG', 'SUM', 'MAX', 'MIN', 'COUNT'
        ];
    }
}
?>