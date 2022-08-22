<?php

use App\Core\Router;

$router = new Router();
$router->route('get',  BASE_URI . '/', "site@Home@home");
$router->route('get',  BASE_URI . '/buy/', 'site@Buy@form');
$router->route('get',  BASE_URI . '/pay/success/.*', 'site@Success@payResult');
$router->route('get',  BASE_URI . '/ticket/(.*)/?', 'site@Ticket@ticket');
$router->route('get',  BASE_URI . '/qrCode/', 'site@Profile@watch');
$router->route('post', BASE_URI . '/api/showTheater/', 'site@Api@showTheater');
$router->route('post', BASE_URI . '/api/qrValidation/', 'site@Api@qrValidate');
$router->route('post', BASE_URI . '/api/buyValidation/', 'site@Api@buyValidation');
$router->route('post', BASE_URI . '/api/refundCheck/', 'site@Api@refundValidation');
$router->route('post', BASE_URI . '/api/addCreditCard/', 'site@Api@addCreditCard');
$router->route('post', BASE_URI . '/api/payment/', 'site@Api@payment');
$router->route('get',  BASE_URI . '/refund/', 'site@Refund@refund');
// admin
$router->route('get',  BASE_URI . '/admin/checkByNumber/', "admin@NumberCheck@check");
$router->route('get',  BASE_URI . '/admin/api/numberCheck/', "admin@Api@numberCheck");
$router->route('get',  BASE_URI . '/admin/api/giveReserveCode/', 'admin@Api@randomCode');
$router->route('get',  BASE_URI . '/admin/api/refundManage/', 'admin@Api@refundManage');
$router->route('get',  BASE_URI . '/admin/api/userVerifyByNum/', "admin@Api@userVerify");
$router->route('get',  BASE_URI . '/admin/dashboard/', 'admin@Dashboard@dashboard');
$router->route('get',  BASE_URI . '/admin/users/\?.*', 'admin@User@list');
$router->route('get',  BASE_URI . '/admin/users/', 'admin@User@list');
$router->route('get',  BASE_URI . '/admin/users/sendSms/(.*)/', 'admin@User@sendSms');
$router->route('get',  BASE_URI . '/admin/sms/', 'admin@Sms@failedSms');
$router->route('get',  BASE_URI . '/admin/refund/', 'admin@Refund@refund');
$router->route('get',  BASE_URI . '/admin/refund/.*', 'admin@Refund@refund');
$router->route('get',  BASE_URI . '/admin/scanner/', 'admin@Scanner@scan');
// print_f($router->getRouters());
$router->dispatch($action);

// $router->route('get', BASE_URI . '/first/(.*)/?', "First@firstMethod");
// it means in CLASS FirstController , select SecondMethod method.
// $router->route('get', BASE_URI . '/second', "First@secondMethod");
// $router->route('get', BASE_URI . '/fourth', "First@bbb");

// $router->route(
//     'get', BASE_URI . '/', function () {
//         echo 'hello world';
//     }
// );

// $router->curlyBraceRoute(
//     'get', BASE_URI . '/users/{user}/country/{country}', function ($user, $country) {
//         echo 'new writing format user ' . $user . ' country = ' . $country;
//     }
// );

// $router->route(
//     'get', BASE_URI . '/about', function () {
//         echo 'about page';
//     }
// );

// $router->route(
//     'GET', BASE_URI . '/company/(.*)/?', function ($companyName) {
//     // /? means it can has final slash or not;
//         echo 'this is company name : ' . $companyName;
//     }
// );

// $router->route(
//     'post', BASE_URI . '/users/(.*)/city/(.*)', function ($user, $city) {
//         echo 'this user is from ' . $city . ' and his/her name is ' . $user;
//     }
// );
?>