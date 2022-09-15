<?php

namespace App\Controller\Refrence;

use App\Core\Controller;
use App\Core\System;
use App\Core\Tools;

class SiteRefrenceController extends Controller
{
    // we are using all these tables in admin and site 

    // columns for user table
    protected $userRow = [
        'userId',
        'userName',
        'userSms',
        'userMobile',
        'userTicketCount',
        'userCode',
        'userTheater',
        'userActive',
        'userSum',
        'userPaymentLink',
        'userFinishPay',
        'userTrackId'
    ];

    // theater table 
    protected $theaterRow = [
        'theaterId',
        'theaterAddress',
        'theaterCapacity',
        'theaterName',
        'theaterShow',
        'theaterReserved'
    ];

    // credit card table for refunding process
    protected $cardRow = [
        'cardNumber',
        'cardUserId',
    ];

    // sms table
    protected $smsRow = [
        'smsId',
        'smsMobile'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->data['adminSidebar'] = System::adminSidebar();
        $this->data['currentPage'] = "";
    }
}