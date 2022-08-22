<?php

use App\Core\System;
use App\Core\Tools;
use App\Model\Arrays;
use App\Model\Form;
?>
<div class="adminSidebar">
    <?php foreach ($this->data['adminSidebar'] as $key => $value) { ?>
        <a href="<?php echo $value['url'] ?>" class="menuItem"><?php echo $value['title'] ?></a>
    <?php } ?>
</div>
