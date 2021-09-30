<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 *
 * @link   https://divshop.pro
 **/
defined('BASEPATH') or exit('No direct script access allowed');
?>


<?php $this->load->view('components/Preloader'); ?>
<nav class="navbar navbar-expand-lg navbar-divshop-dark navbar-transparent fixed-top navbar-color-on-scroll">
    <div class="container">
        <div class="navbar-translate">
            <a href="<?php echo $this->config->base_url(); ?>">
                <img class="divshop-navbar-logo" src="<?php echo $settings['pageLogo']; ?>" alt="<?php echo $settings['pageTitle']; ?>">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" aria-expanded="false" aria-label="Przełącznik nawigacji">
                <span class="sr-only">Przełącznik nawigacji</span>
                <span class="navbar-toggler-icon"></span>
                <span class="navbar-toggler-icon"></span>
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="<?php echo($this->uri->rsegment('1') == 'home') ? 'active' : ''; ?> nav-item">
                    <a href="<?php echo($this->uri->rsegment('1') == 'home') ? '#' : $this->config->base_url(); ?>" class="nav-link">
                        <i class="material-icons">home</i>&nbsp;Strona główna
                    <div class="ripple-container"></div></a>
                </li>
                <?php if ($modules[0]['moduleName'] == 'news' && $modules[0]['moduleEnabled'] == 1 && count($news) >= 1) { ?>
                    <li class="<?php echo($this->uri->rsegment('1') == 'shop') ? 'active' : ''; ?> nav-item">
                        <a href="<?php echo($this->uri->rsegment('1') == 'shop') ? $this->config->base_url('shop') : $this->config->base_url('shop'); ?>" class="nav-link">
                            <i class="material-icons">shopping_basket</i>&nbsp;Sklep
                        <div class="ripple-container"></div></a>
                    </li>
                <?php } ?>
                <?php if ($modules[4]['moduleName'] == 'vouchers' && $modules[4]['moduleEnabled'] == 1) { ?>
                    <li class="<?php echo($this->uri->rsegment('1') == 'voucher') ? 'active' : ''; ?> nav-item">
                        <a href="<?php echo($this->uri->rsegment('1') == 'voucher') ? '#' : $this->config->base_url('voucher'); ?>" class="nav-link">
                            <i class="material-icons">confirmation_number</i>&nbsp;Voucher
                        </a>
                    </li>
                <?php } ?>
                <?php if ($modules[2]['moduleName'] == 'stats' && $modules[2]['moduleEnabled'] == 1) { ?>
                    <li class="<?php echo($this->uri->rsegment('1') == 'stats') ? 'active' : ''; ?> nav-item">
                        <a href="<?php echo($this->uri->rsegment('1') == 'stats') ? '#' : $this->config->base_url('stats'); ?>" class="nav-link">
                            <i class="material-icons">show_chart</i>&nbsp;Statystyki
                        <div class="ripple-container"></div></a>
                    </li>
                <?php } ?>
                <?php if ($modules[1]['moduleName'] == 'bans' && $modules[1]['moduleEnabled'] == 1) { ?>
                    <li class="<?php echo($this->uri->rsegment('1') == 'bans') ? 'active' : ''; ?> nav-item">
                        <a href="<?php echo($this->uri->rsegment('1') == 'bans') ? '#' : $this->config->base_url('bans'); ?>" class="nav-link">
                            <i class="material-icons">lock</i>&nbsp;Bany
                        </a>
                    </li>
                <?php } ?>
                <?php if ($modules[3]['moduleName'] == 'antibot' && $modules[3]['moduleEnabled'] == 1) { ?>
                    <li class="<?php echo($this->uri->rsegment('1') == 'antybot') ? 'active' : ''; ?> nav-item">
                        <a href="<?php echo($this->uri->rsegment('1') == 'antybot') ? '#' : $this->config->base_url('antybot'); ?>" class="nav-link">
                            <i class="material-icons">verified</i>&nbsp;AntyBot
                        </a>
                    </li>
                <?php } ?>
                <?php if ($modules[6]['moduleName'] == 'pages' && $modules[6]['moduleEnabled'] == 1) { ?>
                    <?php if ($pages && $pages[0]['pageActive'] != 0) { ?>
                        <li class="dropdown nav-item">
                            <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false">
                                <i class="material-icons">public</i>&nbsp;Strony
                                <div class="ripple-container"></div>
                            </a>
                            <div class="dropdown-menu">
                                <?php foreach ($pages as $page) { ?>
                                    <?php if ($page['pageActive'] == 1) { ?>
                                        <?php if ($page['link'] == null) { ?>
                                            <a class="dropdown-item divshop-navbar-dropdown" href="<?php echo($this->uri->rsegment('1') == getPageUrl($page['title'])) ? '#' : $this->config->base_url('page/'.getPageUrl($page['title'])); ?>">
                                                <?php if ($page['icon'] != null) { ?>
                                                    <i class="<?php echo $page['icon']; ?>"></i>
                                                <?php } ?>
                                                <span class="divshop-navbar-dropdown-item"><?php echo $page['title'] ?></span>
                                            </a>
                                        <?php } else { ?>
                                            <a class="dropdown-item divshop-navbar-dropdown" href="<?php echo $page['link']; ?>">
                                                <?php if ($page['icon'] != null) { ?>
                                                    <i class="<?php echo $page['icon']; ?>"></i>
                                                <?php } ?>
                                                <span class="divshop-navbar-dropdown-item"><?php echo $page['title']; ?></span>
                                            </a>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </li>
                    <?php } ?>
                <?php } ?>
                <?php if ($this->session->userdata('logged')) { ?>
                    <div class="pl-2 pr-2"></div>
                    <li class="nav-item">
                        <small class="divshop-admin-mode">Witaj, <?php echo $_SESSION['name']; ?> <a href="<?php echo $this->config->base_url('admin/logout?fromLogout') ?>" class="text-danger" style="font-weight:700;">(Wyloguj)</a></small>
                        <a href="<?php echo $this->config->base_url('panel'); ?>" class="nav-link divshop-admin-mode-btn">
                            <i class="material-icons">lock_open</i>&nbsp;Powrót do ACP
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>