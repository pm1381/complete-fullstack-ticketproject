<?php

use App\Core\System;
use App\Model\Arrays;
require_once ADMIN_TEMPLATE . 'top.php' ?>

<?php require_once ADMIN_TEMPLATE . 'adminHeader.php' ?>
<div class="adminDataShow">
    <?php require_once ADMIN_TEMPLATE . 'adminSidebar.php' ?>
    <div class="px-4 pb-2 mainContent">
        <table cellspacing="0" cellpadding="0" border="0" class="table table-striped table-bordered">
            <tbody>
                <tr class="title">
                    <td width="70px" class="titleOptions">id</td>
                    <td width="150px" class="titleOptions">نام</td>
                    <td class="titleOptions">موبایل</td>
                    <td class="titleOptions">تعداد بلیط</td>
                    <td class="titleOptions">کد رزرو</td>
                    <td class="titleOptions">اجرای شب</td>
                    <td class="titleOptions">پرداختی</td>
                </tr>
                <?php foreach ($this->data['smsResult'] as $sms) {
                    echo
                    '
                        <tr>
                            <td class="options">' . $sms['smsId'] . '</td>
                            <td class="options">' . $sms['userName']  . '</td>
                            <td class="options">' . $sms['smsMobile'] . '</td>
                            <td class="options">' . $sms['userTicketCount'] . '</td>
                            <td class="options">' . $sms['userCode'] . '</td>
                            <td class="options">' . $sms['userTheater'] . '</td>
                            <td class="options">' . $sms['userSum'] . '</td>
                        </tr>
                    ';
                } ?>
                <?php if (count($this->data['smsResult']) == 0) { ?>
                    <?php echo '<tr style="text-align:center">موردی یافت نشد</tr>' ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once ADMIN_TEMPLATE . 'footer.php' ?>