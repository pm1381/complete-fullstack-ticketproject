<?php

use App\Core\System;
use App\Model\Arrays;
require_once 'top.php' ?>

<?php require_once 'adminHeader.php' ?>
<div class="adminDataShow">
    <?php require_once 'adminSidebar.php' ?>
    <div class="px-4 py-2 mainContent">
        <table cellspacing="0" cellpadding="0" border="0" class="table table-striped table-bordered">
            <tbody>
                <tr class="title">
                    <td width="150px" class="titleOptions">نام</td>
                    <td width="120px" class="titleOptions">اجرای شب</td>
                    <td class="titleOptions">ظرفیت</td>
                    <td class="titleOptions">پر شده</td>
                    <td class="titleOptions">آدرس سالن</td>
                </tr>
                <?php foreach ($this->data['theaterResult'] as $theater) {
                    $theater['theaterShow'] += 1;
                    echo
                    '
                        <tr>
                            <td class="options">' . $theater['theaterName'] . '</td>
                            <td class="options">' . $theater['theaterShow']  . '</td>
                            <td class="options">' . $theater['theaterCapacity'] . '</td>
                            <td class="options">' . $theater['theaterReserved'] . '</td>
                            <td class="options">' . $theater['theaterAddress'] . '</td>
                        </tr>
                    ';
                } ?>
                <?php if (count($this->data['theaterResult']) == 0) { ?>
                    <?php echo '<tr style="text-align:center">موردی یافت نشد</tr>' ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once 'footer.php' ?>