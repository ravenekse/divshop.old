<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 * @link   https://divshop.pro
**/

defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="<?php echo $this->config->base_url('panel'); ?>">
                <img class="divshop-acp-logo-dark" src="https://cdn-n.divshop.pro/images/divshop-logo-dark.png" alt="">
                <img class="divshop-acp-logo-light" src="https://cdn-n.divshop.pro/images/divshop-logo.png" alt="">
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <img class="divshop-acp-logo-dark" src="https://cdn-n.divshop.pro/images/divshop-div-dark.png" alt="">
            <img class="divshop-acp-logo-light" src="https://cdn-n.divshop.pro/images/divshop-div.png" alt="">
        </div>
        <ul class="sidebar-menu">
            <li>
                <a class="nav-link <?php echo($this->uri->rsegment('1') == "dashboard") ? 'item-active' : ''; ?>" href="<?php echo($this->uri->rsegment('1') == "dashboard") ? '' : $this->config->base_url('panel/dashboard'); ?>">
                    <div class="divshop-sidebar-item">
                        <i class="fas fa-desktop"></i> 
                        <span>Dashboard</span>
                    </div>
                </a>
            </li>
            <li>
                <a class="nav-link <?php echo($this->uri->rsegment('1') == "admins") ? 'item-active' : ''; ?>" href="<?php echo($this->uri->rsegment('1') == "admins") ? '' : $this->config->base_url('panel/admins'); ?>">
                    <div class="divshop-sidebar-item">
                        <i class="fas fa-users"></i> 
                        <span>Użytkownicy ACP</span>
                    </div>
                </a>
            </li>
            <li>
                <a class="nav-link <?php echo($this->uri->rsegment('1') == "servers") ? 'item-active' : ''; ?>" href="<?php echo($this->uri->rsegment('1') == "servers") ? '' : $this->config->base_url('panel/servers'); ?>">
                    <div class="divshop-sidebar-item">
                        <i class="fas fa-server"></i> 
                        <span>Serwery</span>
                    </div>
                </a>
            </li>
            <li>
                <a class="nav-link <?php echo($this->uri->rsegment('1') == "services") ? 'item-active' : ''; ?>" href="<?php echo($this->uri->rsegment('1') == "services") ? '' : $this->config->base_url('panel/services'); ?>">
                    <div class="divshop-sidebar-item">
                        <i class="fas fa-coins"></i> 
                        <span>Usługi</span>
                    </div>
                </a>
            </li>
            <?php if($modules[4]['moduleName'] == "vouchers" && $modules[4]['moduleEnabled'] == 1): ?>
                <li>
                    <a class="nav-link <?php echo($this->uri->rsegment('1') == "vouchers") ? 'item-active' : ''; ?>" href="<?php echo($this->uri->rsegment('1') == "vouchers") ? '' : $this->config->base_url('panel/vouchers'); ?>">
                        <div class="divshop-sidebar-item">
                            <i class="fas fa-ticket-alt"></i> 
                            <span>Vouchery</span>
                        </div>
                    </a>
                </li>
            <?php endif; ?>
            <?php if($modules[0]['moduleName'] == "news" && $modules[0]['moduleEnabled'] == 1): ?>
                <li>
                    <a class="nav-link <?php echo($this->uri->rsegment('1') == "news") ? 'item-active' : ''; ?>" href="<?php echo($this->uri->rsegment('1') == "news") ? '' : $this->config->base_url('panel/news'); ?>">
                        <div class="divshop-sidebar-item">
                            <i class="far fa-newspaper"></i> 
                            <span>Aktualności</span>
                        </div>
                    </a>
                </li>
            <?php endif; ?>
            <?php if($modules[6]['moduleName'] == "pages" && $modules[6]['moduleEnabled'] == 1): ?>
                <li>
                    <a class="nav-link <?php echo($this->uri->rsegment('1') == "pages") ? 'item-active' : ''; ?>" href="<?php echo($this->uri->rsegment('1') == "pages") ? '' : $this->config->base_url('panel/pages'); ?>">
                        <div class="divshop-sidebar-item">
                            <i class="far fa-file"></i> 
                            <span>Strony</span>
                        </div>
                    </a>
                </li>
            <?php endif; ?>
            <li>
                <a class="nav-link <?php echo($this->uri->rsegment('1') == "purchases") ? 'item-active' : ''; ?>" href="<?php echo($this->uri->rsegment('1') == "purchases") ? '' : $this->config->base_url('panel/purchases'); ?>">
                    <div class="divshop-sidebar-item">
                        <i class="fas fa-history"></i> 
                        <span>Historia zakupów</span>
                    </div>
                </a>
            </li>
            <li>
                <a class="nav-link <?php echo($this->uri->rsegment('1') == "logs") ? 'item-active' : ''; ?>" href="<?php echo($this->uri->rsegment('1') == "logs") ? '' : $this->config->base_url('panel/logs'); ?>">
                    <div class="divshop-sidebar-item">
                        <i class="fas fa-list"></i> 
                        <span>Logi</span>
                    </div>
                </a>
            </li>
            <li>
                <a class="nav-link <?php echo($this->uri->rsegment('1') == "failedlogins") ? 'item-active' : ''; ?>" href="<?php echo($this->uri->rsegment('1') == "failedlogins") ? '' : $this->config->base_url('panel/failedlogins'); ?>">
                    <div class="divshop-sidebar-item">
                        <i class="fas fa-user-times"></i> 
                        <span>Nieudane logowania</span>
                    </div>
                </a>
            </li>
            <div class="mt-2 mb-2">
                <hr>
            </div>
            <li>
                <a class="nav-link <?php echo($this->uri->rsegment('1') == "account") ? 'item-active' : ''; ?>" href="<?php echo($this->uri->rsegment('1') == "account") ? '' : $this->config->base_url('panel/account'); ?>">
                    <div class="divshop-sidebar-item">
                        <i class="fas fa-user-circle"></i> 
                        <span>Konto</span>
                    </div>
                </a>
            </li>
            <li>
                <a class="nav-link <?php echo($this->uri->rsegment('1') == "settings") ? 'item-active' : ''; ?>" href="<?php echo($this->uri->rsegment('1') == "settings") ? '' : $this->config->base_url('panel/settings'); ?>">
                    <div class="divshop-sidebar-item">
                        <i class="fas fa-cog"></i> 
                        <span>Ustawienia</span>
                    </div>
                </a>
            </li>
            <li>
                <a class="nav-link <?php echo($this->uri->rsegment('1') == "modules") ? 'item-active' : ''; ?>" href="<?php echo($this->uri->rsegment('1') == "modules") ? '' : $this->config->base_url('panel/modules'); ?>">
                    <div class="divshop-sidebar-item">
                        <i class="fas fa-layer-group"></i> 
                        <span>Moduły</span>
                    </div>
                </a>
            </li>
            <li>
                <a class="nav-link <?php echo($this->uri->rsegment('1') == "paysettings") ? 'item-active' : ''; ?>" href="<?php echo($this->uri->rsegment('1') == "paysettings") ? '' : $this->config->base_url('panel/paysettings'); ?>">
                    <div class="divshop-sidebar-item">
                        <i class="fas fa-money-bill-alt"></i> 
                        <span>Płatności</span>
                    </div>
                </a>
            </li>
        </ul>
    </aside>
</div>
