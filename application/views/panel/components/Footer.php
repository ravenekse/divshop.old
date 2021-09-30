<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 *
 * @link   https://divshop.pro
 **/
defined('BASEPATH') or exit('No direct script access allowed');
?>

<footer class="main-footer">
    <div class="row">
        <div class="col-md-12 col-lg-6">
            Proudly powered by <a href="https://divshop.pro/">DIVShop.pro</a> v<?php echo $divsAPI['divsVersion']; ?> with <i class="fa fa-heart"></i>
        </div>
        <div class="col-md-12 col-lg-6 d-flex justify-content-center divshop-footer-font-small">
            <ul class="nav" style="margin-top:-4px;">
                <a href="https://divshop.pro/docs/" target="_blank" class="pl-2 pr-2">• Dokumentacja</a>
                <a href="https://status.divshop.pro/" target="_blank" class="pl-2 pr-2">• Statusy</a>
                <a href="https://divshop.pro/devlog/#<?php echo preg_replace('/[\s.,-]+/', '-', strtolower($divsAPI['divsVersion'])); ?>" target="_blank" class="pl-2 pr-2">• Co nowego?</a>
            </div>
        </div>
    </div>
</footer>
<?php $this->load->view('panel/components/Modals'); ?>