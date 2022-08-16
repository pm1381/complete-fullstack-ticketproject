<?php
namespace App\Controller\Admin;

use App\Controller\Refrence\SiteRefrenceController;
use App\Core\Tools;
use App\Model\Theater;

class NumberCheckController extends SiteRefrenceController
{
    
    public function check()
    {
        $theater = new Theater();
        $theaterResult  = $theater->row($this->theaterRow)->select();
        if (count($theaterResult)) {
            $this->data['theaterResult'] = $theaterResult;
        }
        $this->render('admin/numCheck', $this->data);
    }

}