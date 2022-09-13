<?php

use App\Core\Tools;
use App\Model\Arrays;
use App\Model\Form;

require_once 'top.php' ?>
<div class="container" id="hero">
    <div class="row justify-content-center pt-5">
        <div class="col-10 col-md-6 pt-3">
            <div class="card">
                <div class="card-body">
                    <div class="buyForm form-group">
                        <?php $formArray = Tools::checkArray($this->data, 'form') ?>
                        <div class="showNights row mb-3">
                            <div class="col-12">
                                <label for="shows" class="label text-right">شب های اجرا</label>
                                <select class="form-control" name="shows" id="shows" onchange="showTheaterDetails(this.value)">
                                    <option class="optionData" data-name="انتخاب کنید" data-id="-1" value="-1">انتخاب کنید</option>'
                                    <?php echo Form::option(Arrays::nights(), Tools::checkArray($formArray, 'userTheater')) ?>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="buyOptions row mb-3">
                            <div class="count col-6">
                                <label for="ticketCount" class="label text-right">تعداد بلیط</label>
                                <input class="ticketCount form-control" type="number" value="<?php echo Tools::checkArray($formArray, 'userTicketCount') ?>" name="ticketCount" onchange="ticketCountChange(this.value)">
                            </div>
                            <div class="priceTag col-6 rounded bg-warning">
                                <div class="label text-right">مجموع کل</div>
                                <div class="priceNum text-right"><span class="payable"><?php echo Tools::checkArray($formArray, 'userSum') ?></span><span> هزار تومان </span></div>
                            </div>
                        </div>
                        <hr>
                        <div class="theaterDescription row bg-success mb-3 rounded p-2">
                            <div class="theaterTitle col-12 text-right"></div>
                            <div class="desc col-12 text-right"></div>
                        </div>
                        <div class="username row mb-3">
                            <div class="col-12">
                                <label for="nameInput" class="label text-right">نام</label>
                                <input type="text" class="nameInput form-control" name="nameInput" value="<?php echo Tools::checkArray($formArray, 'userName') ?>">
                            </div>
                        </div>
                        <div class="phoneNumber row mb-3">
                            <div class="col-12">
                                <label for="mobileInput" class="label text-right">شماره همراه</label>
                                <input type="tel" class="mobileInput form-control" name="mobileInput" placeholder="09123456789" value="<?php echo Tools::checkArray($formArray, 'userMobile') ?>">
                            </div>
                        </div>
                        <div id="payment" class="btn btn-success buyButton">ثبت بلیط</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>

<?php require_once 'footer.php' ?>