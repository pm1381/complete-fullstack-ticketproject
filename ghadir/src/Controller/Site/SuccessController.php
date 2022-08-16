<?php
namespace App\Controller\Site;

use App\Controller\Refrence\SiteRefrenceController;
use App\Core\System;
use App\Core\Tools;
use App\Model\Input;
use App\Model\Sms;
use App\Model\Theater;
use App\Model\User;

class SuccessController extends SiteRefrenceController
{
    /**
     * @Route("/pay/success/")
    */
    public function payResult()
    {
        if (! array_key_exists('order_id', $_GET) || $_GET['np_status'] == 'Unsuccessful') {
            header("Location: " . System::siteAddress());
            exit();
        }
        
        $this->data['errorMessage'] = '';
        $this->data['error'] = false;
        $trans_id = Input::get("trans_id");
        $order_id = Input::get("order_id");
        $amount = Input::get("amount");

        if ($order_id > 0 && $amount > 0) {
            $user = new User();
            $result = $user->row($this->userRow)->where(['userId' => $order_id, 'userFinishPay' => '0'])->limit(1)->select();
            if ($result['userFinishPay'] == '0') {
                $params = array(
                    'api_key'  => 'nex pay api key',
                    'trans_id' => $trans_id,
                    'amount' => $amount
                );
                $response = json_decode(Tools::manageCurl($params, [], 'https://nextpay.org/nx/gateway/verify'), true);
                if ($response['code'] == '0') {
                    $code = $this->newCodegenerator();
                    $userOrder = new User();
                    $userOrder->where(['userId' => Input::get('order_id'), 'userSms' => 0])->update(
                        [
                            'userFinishPay' => 1,
                            'userCode' => $code,
                            'userTrackId' => $response['Shaparak_Ref_Id']
                        ]
                    );
                    $user = new User();
                    $userResult = $user->row($this->userRow)->where(['userId' => Input::get('order_id')])->limit(1)->select();   
                    $this->data['userResult'] = $userResult;
                    if (count($userResult)) {
                        $this->updateTheaterStatus($userResult['userTheater'], $userResult['userTicketCount']);
                        if (! $userResult['userSms']) {
                            $this->handleUserSms($userResult);
                        }
                        $this->data['qrCode'] = 'http://api.qrserver.com/v1/create-qr-code/?data=' . $userResult['userCode'] . '&size=200x200';
                    }
                } else {
                    $this->data['errorMessage'] = 'خطایی در مرحله پرداخت رخ داد';
                    $this->data['error'] = true;
                }
            } else {
                $user = new User();
                $result = $user->row($this->userRow)->where(['userId' => $order_id, 'userFinishPay' => 1])->select();
                if (count($result)) {
                    $this->data['userResult'] = $result;
                    $this->data['qrCode'] = 'http://api.qrserver.com/v1/create-qr-code/?data=' . $result['userCode'] . '&size=200x200';
                } else {
                    $this->data['errorMessage'] = 'double spending error';
                    $this->data['error'] = true;
                }
            }
        } else {
            $this->data['errorMessage'] = 'پرداخت ناموفق لطفا دوباره وارد سایت شده و خرید خود را پیگیری کنید';
            $this->data['error'] = true;
        }
        
        $this->render('site/success', $this->data);
    }

    private function newCodeGenerator()
    {
        do {
            $code = Tools::randomCreator();
            $user = new User();
            $codeResult = $user->row(['userId'])->where(['userCode' => $code])->select();
        } while (count($codeResult) > 0);

        return $code;
    }

    private function updateTheaterStatus($userTheater, $userTicketCount)
    {
        $theater = new Theater();
        $thetaerResult = $theater->row($this->theaterRow)->where(['theaterId' => $userTheater])->limit(1)->select();
        $updateTheater = new Theater();
        $totalReserved = $thetaerResult['theaterReserved'] + $userTicketCount;
        $updateTheater->where(['theaterId' => $userTheater])->update(['theaterReserved' => $totalReserved]);
        $this->data['thetaerResult'] = $thetaerResult;    
    }

    private function handleUserSms($userResult)
    {
        $sms = new Sms();
        $smsResult = $sms->mobile($userResult['userMobile'])->userCode($userResult['userCode'])->send();
        if ($smsResult['return']['status'] != 200) {
            $sms->submit();
        }
    }

}