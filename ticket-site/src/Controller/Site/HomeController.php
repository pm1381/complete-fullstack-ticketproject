<?php
namespace App\Controller\Site;

use App\Controller\Refrence\SiteRefrenceController;
use App\Model\Chat;
use App\Model\User;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

class HomeController extends SiteRefrenceController
{
    /**
     * Route("/")
    */
    public function home()
    {
        $this->data['head']['title'] = 'گروه هنری روا';
        $this->data['head']['description'] = 'اجرای گروه هنری روا به ماسبت عید غدیر';

        $this->render('site/home', $this->data);
    }

}