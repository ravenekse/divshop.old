<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 * @link   https://divshop.pro
**/

defined('BASEPATH') OR exit('No direct script access allowed');
?>

<footer class="footer">
    <div class="container">
        <div class="copyright float-right">
            Proudly powered by <a href="https://divshop.pro/">DIVShop.pro</a> v<?php echo $divsAPI['divsVersion']; ?> with <i class="fa fa-heart"></i>
        </div>
    </div>
</footer>
<?php $this->load->view('components/Scripts'); ?>
</body>