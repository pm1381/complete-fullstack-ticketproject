<?php
namespace App\Controller\Admin;

use App\Controller\Refrence\SiteRefrenceController;
use App\Core\System;
use App\Core\Tools;
use App\Model\Sms;
use App\Model\User;

class SmsController extends SiteRefrenceController
{
    /**
     * Route("/admin/sms")
    */
    public function failedSms()
    {
        $this->data['smsResult'] = [];
        $sms = new Sms();
        $smsResult  = $sms->row(['smsId', 'smsMobile', 'userSum', 'userTheater', 'userCode', 'userId', 'userName', 'userTicketCount'])
            ->join(['user' => ['smsMobile' => 'userMobile']])->select();
        if (count($smsResult)) {
            $this->data['smsResult'] = $smsResult;
        }
        $this->render('admin/sms/list', $this->data);
    }

}