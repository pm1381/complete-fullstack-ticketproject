<?php
namespace App\Controller\Site;

use App\Controller\Refrence\SiteRefrenceController;
use App\Core\System;
use App\Core\Tools;
use App\Model\User;

class TicketController extends SiteRefrenceController
{
    /**
     * @Route("/ticket/")
    */
    public function ticket($code)
    {
        $user = new User();
        $userResult = $user->row($this->userRow)
            ->where(['userCode' => $code])->limit(1)->select();

        if (count($userResult)) {
            $this->data['userResult'] = $userResult;
            $this->data['qrCode'] = 'http://api.qrserver.com/v1/create-qr-code/?data=' . $code . '&size=200x200';
            $this->render('site/ticket', $this->data);
        } else {
            header("Location: " . System::siteAddress());
            exit();
        }
    }
}