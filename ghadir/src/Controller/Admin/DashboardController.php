<?php
namespace App\Controller\Admin;

use App\Controller\Refrence\SiteRefrenceController;
use App\Core\System;
use App\Core\Tools;
use App\Model\Theater;

class DashboardController extends SiteRefrenceController
{
    /**
     * Route("/admin/dashboard")
    */
    public function dashboard()
    {
        $theater = new Theater();
        $theaterResult  = $theater->row($this->theaterRow)->select();
        if (count($theaterResult)) {
            $this->data['theaterResult'] = $theaterResult;
        }
        $this->render('admin/dashboard', $this->data);
    }

}