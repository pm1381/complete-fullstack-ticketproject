<?php

use App\Core\System;

?>

<div class="d-flex align-items-center justify-content-around adminHeader">
    <p style="margin: 0;">today <?php echo date('y/m/d') ?> <span class="timeHeader"><?php echo date('h-i-s') ?></span></p>
    <h1 class="adminH1">admin panel</h1>
    <a href="<?php echo System::siteAddress();?>"><button type="button" class="btn btn-primary adminHeaderButton">site button</button></a>
</div>