<?php
namespace App\Controller\Admin;

use App\Controller\Refrence\SiteRefrenceController;
use App\Core\System;
use App\Core\Tools;
use App\Model\Card;
use App\Model\Input;
use App\Model\Sms;
use App\Model\User;

class RefundController extends SiteRefrenceController
{
    /**
     * Route("/admin/sms")
    */
    public function refund()
    {
        $where = [];
        $searchInput = Input::get('searchInput');

        $this->updateWhere($searchInput, $where);
        
        $refund = new Card();
        $this->data['refundResult'] = [];
        $refundResult  = $refund->row(['cardId', 'cardActive', 'cardNumber', 'userSum', 'userCode', 'userName', 'userMobile', 'userTicketCount'])
            ->join(['user' => ['cardUserId' => 'userId']])->where($where)->select();
        if (count($refundResult)) {
            $this->data['refundResult'] = $refundResult;
        }

        $this->render('admin/refund/list', $this->data);
    }

    private function updateWhere($searchInput, &$where)
    {
        if (strlen($searchInput) == 11) {
            $where['userMobile'] = $searchInput;    
        } elseif (strlen($searchInput) == 5) {
            $where['userCode'] = $searchInput;
        } elseif (strlen($searchInput) == 16) {
            $where['cardNumber'] = $searchInput;
        }
    }

}