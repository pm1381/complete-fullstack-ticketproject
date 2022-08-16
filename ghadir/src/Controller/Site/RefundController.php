<?php
namespace App\Controller\Site;

use App\Controller\Refrence\SiteRefrenceController;
use App\Core\Tools;

class RefundController extends SiteRefrenceController
{
    public function refund()
    {
        $this->data['head']['title'] = 'گروه هنری روا';
        $this->data['head']['description'] = 'اجرای گروه هنری روا به مناسبت عید غدیر';
        $this->render('site/refund', $this->data);
    }

}