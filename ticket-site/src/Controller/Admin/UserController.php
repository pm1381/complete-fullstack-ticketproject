<?php
namespace App\Controller\Admin;

use App\Controller\Refrence\SiteRefrenceController;
use App\Core\System;
use App\Core\Tools;
use App\Model\Input;
use App\Model\Sms;
use App\Model\User;

class UserController extends SiteRefrenceController
{
    /**
     * @Route("/admin/users")
    */
    public function list()
    {
        $where = [];

        $sortStyle = Input::get('sortStyle');
        $this->data['sortStyle'] = $sortStyle;
        switch ($sortStyle) {
        case 3:
            $where['userSms'] = 0;
            break;
        case 2:
            $where['userCode'] = 0;
            break;
        default:
            break;
        }

        $haveAlert = Input::get('finish');
        if ($haveAlert != "") {
            $this->data['haveAlert'] = true;
        }

        $user = new User();
        $userResult  = $user->row($this->userRow)->where($where)->select();
        if (count($userResult)) {
            $this->data['userResult'] = $userResult;
            $this->data['userCount'] = count($userResult);
        }
        $this->render('admin/user/list', $this->data);
    }

    /**
     *@Route("admin/users/sendSms/{id}/") 
    */
    public function sendSms($id)
    {
        $user = new User();
        $userResult = $user->row(['userMobile', 'userCode'])
            ->where(['userId' => $id])->limit(1)->select();

        $sms = new Sms();
        $smsResult = $sms->message(' گروه هنری روا تقدیم میکند \nبرای دریافت بارکد ورود به آدرس زیر مراجعه کنید \n')->mobile($userResult['userMobile'])
            ->userCode($userResult['userCode'])->send();

        $this->data['haveAlert'] = true;

        if ($smsResult['return']['status'] == 200) {
            $this->data['message'] = 'send successfully';
            System::redirect(System::siteAddress() . 'admin/users/?finish=true', 301);
        } else {
            $this->data['message'] = 'error in sending sms';
            $this->data['smsResult'] = $smsResult;
            $sms->submit();
            print_f($this->data['smsResult'], true);
        }

        $this->render('admin/user/list', $this->data);
    }
}