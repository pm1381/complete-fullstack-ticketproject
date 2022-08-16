<?php
namespace App\Model;

use App\Core\Generator;
use App\Core\System;
use App\Core\Tools;

class Sms extends Generator
{
    protected $mobile;
    protected $code;
    protected $resultStatus = '';

    public function __construct()
    {
        $tableName = 'sms';
        parent::__construct($tableName);
    }

    public function mobile($value)
    {
        $this->mobile = $value;
        return $this;
    }

    public function userCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function submit()
    {
        $this->insert(
            [
                'smsMobile' => $this->mobile,
                'smsQrCode' => 'https://api.qrserver.com/v1/create-qr-code/?size=200x2000&data=' . $this->code,
                'smsResultStatus' => $this->resultStatus
            ]
        );
        return $this;
    }

    public function send()
    {
        $message = " گروه هنری روا تقدیم میکند \n";
        $message .= "برای دریافت بارکد ورود به آدرس زیر مراجعه کنید \n";
        $message .= System::siteAddress() ."ticket/" . $this->code;
        $encodedMessage = urlencode($message);

        $url = 'https://api.kavenegar.com/v1/6F6D344D356157502B686F326D5965796537705974777335783272794931386964666F43674F496F7172733D/sms/send.json?receptor=' . $this->mobile . '&sender=10004440044004&message=' . $encodedMessage;

        $response = json_decode(Tools::manageCurl([], [], $url), true);

        if ($response['return']['status'] == 200) {
            $user = new User();
            $user->where(['userCode' => $this->code, 'userMobile' => $this->mobile])->update(['userSms' => 1]);
        } else {
            $this->resultStatus = $response['return']['status'];
        }

        return $response;
    }
}
?>