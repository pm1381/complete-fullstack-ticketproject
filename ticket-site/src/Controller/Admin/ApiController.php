<?php
namespace App\Controller\Admin;

use App\Controller\Refrence\SiteRefrenceController;
use App\Core\Tools;
use App\Model\Card;
use App\Model\Cookie;
use App\Model\Input;
use App\Model\User;

class ApiController extends SiteRefrenceController
{
    /**
     * Route("admin/api/numberCheck/")
    */
    public function numberCheck()
    {
        $data = [];
        $data['error'] = false;
        $number = Input::post('number');
        $userSearch = Input::post('userSearch');
        $where = [];

        if ($userSearch == 0) {
            $where['userVerifyTicket'] = 0;
        }
        if (strlen($number) == 11) {
            $where['userMobile'] = $number;
        } elseif (strlen($number) == 5) {
            $where['userCode'] = $number;
        } elseif ($userSearch == 1 && strlen($number) == 0) {
            $data['error'] = false;
            $data['message'] = 'need reload';
            $this->readJson($data);
            return;
        } else {
            $data['error'] = true;
            $data['message'] = 'ورودی باید 11 یا 5 رقم باشد';
            $this->readJson($data);
            return;
        }

        $this->checkUser($where, $data);
        $this->readJson($data);
    }

    private function checkUser($where, &$data)
    {
        $user = new User();
        $result = $user->row($this->userRow)->where($where)->limit(1)->select();
        if (count($result) <= 0) {
            $data['error'] = true;
            $data['message'] = 'کاربری یافت نشد';
        } else {
            $data['ticketCount'] = $result['userTicketCount'];
            $data['mobile'] = $result['userMobile'];
            $data['code'] = $result['userCode'];
            $data['name'] = $result['userName'];
            $data['theater'] = $result['userTheater'];
            $data['sum'] = $result['userSum'];
            $data['userId'] = $result['userId'];
        }
    }

    /**
     * @Route("admin/api/refundManage/")
    */
    public function refundManage()
    {
        $cardId = Input::post('cardId');
        $card = new Card();
        $res = $card->where(['cardId' => $cardId])->update(['cardActive' => 0]);
        $this->readJson([]);
    }

    /**
     * @Route("/admin/api/giveReserveCode/") 
    */
    public function randomCode()
    {
        $id = Input::post('id');
        $data = [];
        $data['error'] = false;

        $user = new User();
        $userResult  = $user->row($this->userRow)->where(['userId' => $id])->limit(1)->select();

        if (count($userResult)) {
            if ($userResult['userCode'] == 0) {
                $code = $this->newCodeGenerator();

                $updateUser = new User();
                $updateUser->where(['userId' => $id])->update(['userCode' => $code]);
                $data['message'] = ': عملیات موفق ' . 'کد جدید کاربر ' . $code;
            } else {
                $data['error'] = true;
                $data['message'] = 'کاربر از قبل دارای کد معتبر میباشد';    
            }
        } else {
            $data['error'] = true;
            $data['message'] = 'کاربری یافت نشد';
        }

        $this->readJson($data);
    }

    private function newCodeGenerator()
    {
        do {
            $code = Tools::randomCreator();
            $checkuser = new User();
            $codeResult = $checkuser->row(['userId'])
                ->where(['userCode' => $code])->select();
        } while (count($codeResult) > 0);

        return $code;
    }

    /**
     * @Route("/admin/api/userVerifyByNum/")
     */
    public function userVerify()
    {
        $number = Input::post('number');
        $user = new User();
        $user->where(['userMobile' => $number])->update(
            [
                'userVerifyTicket' => 1
            ]
        );
        $data['error'] = false;
        $data['message'] = 'ورود کاربر به مراسم تایید شد';
        $this->readJson($data);
    }
}