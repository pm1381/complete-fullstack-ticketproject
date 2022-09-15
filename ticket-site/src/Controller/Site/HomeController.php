<?php
namespace App\Controller\Site;

use App\Controller\Refrence\SiteRefrenceController;

class HomeController extends SiteRefrenceController
{
    /**
     * Route("/")
    */
    public function home()
    {
        $this->data['head']['title'] = 'گروه هنری ';
        $this->data['head']['description'] = 'اجرای گروه هنری';
        $this->data['currentPage'] = "siteHome";

        $this->render('site/home', $this->data);
    }

}