<?php
namespace App\Controller\Site;

use App\Controller\Refrence\SiteRefrenceController;
use App\Core\Tools;
use App\Model\User;

class BuyController extends SiteRefrenceController
{
    /**
     * Route("/buy/")
    */
    public function form()
    {
        $user = new User();

        $result = $user->row($this->userRow)->where(['userId' => User::current()['id'] ])
            ->select();
        if (count($result)) {
            $this->data['form'] = $result;
        }
        $this->render('site/buy', $this->data);
    }

}