<?php

use App\Core\System;
use App\Core\Tools;
use App\Model\Arrays;
use App\Model\Form;

require_once 'top.php' ?>
<div class="container" id="hero">
    <div class="row justify-content-center pt-5">
        <div class="col-10 col-md-6 pt-3 cards">
            <div class="refundForm1">
                <div class="card">
                    <div class="card-body">
                        <div class="buyForm form-group">
                            <div class="phoneNumber row mb-3">
                                <div class="col-12">
                                    <label for="mobileInput" class="label text-right">شماره همراه یا کد ورود</label>
                                    <input type="tel" class="mobileInput form-control" name="refundInput" placeholder="شماره همراه یا کد ورود">
                                </div>
                            </div>
                            <button id="refund" class="btn btn-success buyButton" type="submit">ثبت</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="refundForm2">
                <div class="card">
                    <div class="card-body">
                        <div class="buyForm form-group">
                            <div class="phoneNumber row mb-3">
                                <div class="col-12">
                                    <label for="mobileInput" class="label text-right">شماره کارت</label>
                                    <input type="tel" class="mobileInput form-control" name="creditCard">
                                </div>
                            </div>
                            <button id="credit-card" class="btn btn-success buyButton" type="submit">ثبت</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>

<?php require_once 'footer.php' ?>