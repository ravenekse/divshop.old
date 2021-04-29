<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 * @link   https://divshop.pro
**/

defined('BASEPATH') OR exit('No direct script access allowed');

if($divsAPI['divsUpdate']['status'] == true): ?>
    <div class="alert alert-success alert-has-icon text-center alert-custom">
        <div class="alert-icon ml-3" style="position:relative;top:-2px;display:flex;align-items:center;">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="text-center mr-auto ml-auto">
            <?php echo $divsAPI['divsUpdate']['message']; ?> <a href="<?php echo $this->config->base_url('panel/updates'); ?>">Aktualizuj</a>
        </div>
    </div>
<?php endif; ?>
<?php if($settings['pageActive'] == 0): ?>
    <div class="alert alert-danger alert-has-icon text-center alert-custom">
        <div class="alert-icon ml-3" style="position:relative;top:-2px;display:flex;align-items:center;">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <div class="text-center mr-auto ml-auto">
            Sklep wyłączony. Aby go włączyć przejdź do <a href="<?php echo $this->config->base_url('panel/settings'); ?>">ustawień</a> i zmień pozycję <b>Strona włączona<b>
        </div>
    </div>
<?php endif; ?>
<?php if($this->uri->segment('2') == "admins"): ?>
    <?php if(isset($_SESSION['newUserInfo'])): ?>
        <div class="alert alert-warning alert-has-icon text-center alert-custom">
            <div class="alert-icon ml-3" style="position:absolute;top:50%;transform:translate(0, -50%);margin:0;">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="text-center mr-auto ml-auto">
                <?php echo $_SESSION['newUserInfo']; ?>
            </div>
        </div>
    <?php unset($_SESSION['newUserInfo']); endif; ?>
<?php endif; ?>