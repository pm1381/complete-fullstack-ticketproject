<?php
namespace App\Controller\Admin;

use App\Controller\Refrence\SiteRefrenceController;
use App\Model\Card;
use App\Model\Input;

class ScannerController extends SiteRefrenceController
{
    /**
     * Route("/admin/scanner")
    */
    public function scan()
    {
        $this->render('admin/scanner/scan', $this->data);
    }

}