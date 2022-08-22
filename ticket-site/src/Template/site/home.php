<?php

use App\Core\System;

require_once 'top.php' ?>
<div class="containter d-flex align-items-center homePage" id="hero">
    <div class="buttonsFirstPage">
        <a class="homeBuButton" href="<?php echo System::siteAddress() . 'buy/' ?>">خرید بلیط</a>
        <a class="homeBuButton" href="<?php echo  System::siteAddress() . 'qrCode/' ?>">مشاهده بارکد ورود</a>
        <a class="homeBuButton" href="<?php echo System::instaAddress() ?>"> صفحه اجتماعی</a>
        <a class="homeBuButton" href="<?php echo System::siteAddress() . 'refund/' ?>"> عودت وجه</a>
    </div>
</div>
<?php require_once 'footer.php' ?>