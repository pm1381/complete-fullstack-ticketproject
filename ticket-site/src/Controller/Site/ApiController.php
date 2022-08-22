<?php
namespace App\Controller\Site;

use App\Controller\Refrence\SiteRefrenceController;
use App\Core\System;
use App\Core\Tools;
use App\Model\Card;
use App\Model\Cookie;
use App\Model\Input;
use App\Model\Theater;
use App\Model\User;

class ApiController extends SiteRefrenceController
{
    /**
     * Route("api/showTheater/")
    */
    public function showTheater()
    {
        $data = [];
        $theaterId = Input::post('theaterId');
        $theater = new Theater();
        $result = $theater->row($this->theaterRow)
            ->where(['theaterId' => $theaterId])->limit(1)->select();
        if (count($result)) {
            $data = $result;
            return $this->readJson($data);
        }
        return $this->readJson([]);
    }

    /**
     * @Route("api/refundCheck")
    */
    public function refundValidation()
    {
        $data = [];
        $data['error'] = false;
        $where = [];
        $where['userFinishPay'] = 1;
        $number = Input::post('refundInput');

        $this->numberCheck($number, $where, $data);
        if (! $data['error']) {
            $user = new User();
            $result = $user->row($this->userRow)->where($where)->limit(1)->select();
            if (count($result) <= 0) {
                $data['error'] = true;
                $data['message'] = 'کاربری یافت نشد';
            } else {
                $data['ticketCount'] = $result['userTicketCount'];
                $data['name'] = $result['userName'];
                $data['theater'] = $result['userTheater'];
            }
        }

        $this->readJson($data);
    }

    private function numberCheck($number, &$where, &$data)
    {
        if (strlen($number) == 11) {
            $where['userMobile'] = $number;
        } elseif (strlen($number) == 5) {
            $where['userCode'] = $number;
        } else {
            $data['error'] = true;
            $data['message'] = 'ورودی باید 11 یا 5 رقم باشد';
        }
    }

    /**
     * @Route("api/addCreditCard/")
    */
    public function addCreditCard()
    {
        $data = [];
        $data['error'] = false;
        $userWhere = [];
        $creditCard = Input::post('creditCard');
        $userNumber = Input::post('userNumber');

        if (strlen($creditCard) != 16) {
            $data['error'] = true;
            $data['message'] = 'شماره کارت وارد شده معتبر نمیباشد';
        }
        $this->numberCheck($userNumber, $userWhere, $data);
        
        if (! $data['error']) {
            $user = new User();
            $userResult = $user->row(['userId'])->where($userWhere)->limit(1)->select();
            if (count($userResult)) {
                $card = new Card();
                $cardresult = $card->row($this->cardRow)->where(['cardUserId' => $userResult['userId']])->limit(1)->select();
                if (count($cardresult)) {
                    $res = $card->where(['cardUserId' => $userResult['userId']])->update(['cardNumber' => $creditCard]);
                    $data['message'] = 'شماره کارت با موفقیت تغییر کرد '. " /n " .  "شماره کارت پیشین : " . $cardresult['cardNumber'];
                } else {
                    $cardresult = $card->insert(['cardNumber' => $creditCard,'cardUserId' => $userResult['userId']]);
                    $data['message'] = 'با موفقیت ثبت شد';
                } 
            }
        }
        $this->readJson($data);
    }

    /**
     * @Route("api/qrValidation/")
     */
    public function qrValidate()
    {
        $phoneNumber = Input::post('phoneNumber');
        
        $data = [];
        $user = new User();
        $result = $user->row(['userCode'])->where(['userMobile' => $phoneNumber])->limit(1)->select();
        if (count($result)) {
            $data['code'] = $result['userCode'];
            $data['error'] = false;
            $data['qrCode'] = 'http://api.qrserver.com/v1/create-qr-code/?data=' . $result['userCode'] . '&size=200x200';
        } else {
            $data['error'] = true;
            $data['message'] = 'کاربر یافت نشد';
        }
        return $this->readJson($data);
    }

    private function buyInputsErrors($phoneNumber, $ticketCount, $userName, &$data)
    {
        if (! preg_match('/09\d{9}/', $phoneNumber)) {
            $data['error']      = true;
            $data['message']    = 'شماره تلفن مورد نظر نانعتبر است';
        }
        if ($userName == '') {
            $data['error']   = true;
            $data['message'] = 'لطفا فیلد نام را پر کنید';
        }
        if ($ticketCount <= 0) {
            $data['error']      = true;
            $ticketCount = 0;
            $data['message']    = 'تعداد بلیط مورد نظر نامعتبر است';
        }
    }

    private function theaterErrors($showNight, $ticketCount, &$data)
    {
        $theater = new Theater();
        $result = $theater->row($this->theaterRow)->where(['theaterId' => $showNight])->limit(1)->select();
        if (count($result)) {
            $check = $result['theaterReserved'] + $ticketCount;
            if ($check > $result['theaterCapacity']) {
                $data['error']      = true;
                $left = $result['theaterCapacity'] - $result['theaterReserved'];
                $data['message'] = ' با عرض پوزش از ظرفیت سالن تنها ' . $left . ' بلیط باقی مانده است';
            }
        } else {
            $data['error']      = true;
            $data['message']    = 'سالن مورد نظر یافت نشد';
        }
    }

    private function userErrors($phoneNumber, $data)
    {
        $user = new User();
        $result = $user->row($this->userRow)->where(['userMobile' => $phoneNumber])->limit(1)->select();
        if (count($result) && $result['userCode'] > 0) {
            $data['error']      = true;
            $data['message']    = 'این شماره قبلا ثبت شده است';
        }
    }

    private function updateUser($phoneNumber, $ticketCount, $userName, $showNight, $priceNum)
    {
        $updateUser = new User();
        $updateUser->where(['userMobile' => $phoneNumber])->update(
            [
                'userTicketCount' => $ticketCount,
                'userName' => $userName,
                'userMobile' => $phoneNumber,
                'userTheater' => $showNight,
                'userSum' => $priceNum,
            ]
        );
    }

    private function insertUser($phoneNumber, $ticketCount, $userName, $showNight, $priceNum)
    {
        $token = Tools::createSalt();
        $cookie = new Cookie();
        $cookie->name('userToken')->content($token)
            ->expire(date('Y-m-d', strtotime('+1 years')))->add();    
        $user = new User();
        $user->insert(
            [
                'userTicketCount' => $ticketCount,
                'userName' => $userName,
                'userCode' => 0,
                'userMobile' => $phoneNumber,
                'userTheater' => $showNight,
                'userActive' => 1, // for return fund
                'userSum' => $priceNum,
                'userToken' => $token,
                'userPaymentLink' => 0
            ]
        );
    }

    /**
     * Route("api/buyValidation/")
    */
    public function buyValidation() 
    {
        $data = [];
        $data['error'] = false;
        $ticketCount = Input::post('ticketCount');
        $priceNum = Input::post('priceNum') * 10; //changing to rials
        $phoneNumber = Input::post('phoneNumber');
        $userName = Input::post('userName');
        $showNight = Input::post('showNight');

        $this->buyInputsErrors($phoneNumber, $ticketCount, $userName, $data);
        $this->theaterErrors($showNight, $ticketCount, $data);
        $this->userErrors($phoneNumber, $data);
        
        if (! $data['error']) {
            $checkUser = new User();
            $checkResult = $checkUser->row(['userId'])->where(['userMobile' => $phoneNumber])->limit(1)->select();
            if (count($checkResult) > 0) {
                $this->updateUser($phoneNumber, $ticketCount, $userName, $showNight, $priceNum);
            } else {
                $this->insertUser($phoneNumber, $ticketCount, $userName, $showNight, $priceNum);
            }
        }
        return $this->readJson($data);
    }

    /**
     * @Route("api/payment/")
    */
    public function payment()
    {
        // درگاه پرداخت
        $userMobile = Input::post('phoneNumber');
        $userId = User::current()['id'];
        $moneyAmount = Input::post('priceNum');

        $userPay = new User();
        $payer = $userPay->row(['userId', 'userName'])->where(['userMobile' => $userMobile])->limit(1)->select();

        $params = array (
            'api_key' => 'your nexPay api key',
            'amount' => $moneyAmount,
            'order_id' => $payer['userId'],
            'payer_name' => $payer['userName'],
            'customer_phone' => $userMobile,
            'callback_uri' => System::siteAddress() . '/pay/success/'
        );
        $response = json_decode(Tools::manageCurl($params, [], 'https://nextpay.org/nx/gateway/token'), true);

        $user = new User();
        $user->where(['userId' => $userId])->update(
            [
            'userPaymentLink' => $response['trans_id'],
            ]
        );
        $response['link'] = $response['trans_id'];
        $this->readJson($response);
    }
}