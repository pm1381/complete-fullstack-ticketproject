<?php

require_once ADMIN_TEMPLATE . 'top.php' ?>

<div class="container scannerPage">
    <div class="row">
        <div class="col">
            <video id="preview" width="100%"></video>
        </div>
    </div>
    <div class="buttons row d-flex">
        <div class="col-sm-12 col-md-5 startScan" id = "start">start</div>
        <div class="col-sm-12 col-md-5 stopScan" id = "stop">stop</div>
    </div>
    <div id="userResult" class="row"></div>
    <div class="userVerification">تایید ورود</div>
</div>

<?php require_once ADMIN_TEMPLATE . 'footer.php' ?>

