<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 *
 * @link   https://divshop.pro
 **/
defined('BASEPATH') or exit('No direct script access allowed');
$errors = 0;
?>
<nav class="navbar navbar-expand-lg main-navbar">
    <li style="list-style: none;">
        <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg">
            <i class="fas fa-bars"></i>
        </a>
    </li>
    <li style="list-style: none;">
        <label class="custom-switch mt-2" data-toggle="tooltip" title="Zmiana kolorystyki">
            <input type="checkbox" id="btnChangeTheme" onclick="changeTheme();" class="custom-switch-input">
            <span class="custom-switch-indicator"></span>
        </label>
    </li>
    <ul class="navbar-nav navbar-right ml-auto">
        <li class="dropdown dropdown-list-toggle">
            <a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg"><i class="far fa-bell"></i></a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right">
                <div class="dropdown-header">
                    Powiadomienia systemowe
                </div>
                <div class="dropdown-list-content dropdown-list-icons">
                    <?php if (substr(decoct(fileperms(APPPATH.'config/database.php')), -4) != 644) {
    $errors++ ?>
                        <div class="dropdown-item dropdown-item-unread">
                            <div class="dropdown-item-icon bg-danger text-white">
                                <i class="fas fa-exclamation"></i>
                            </div>
                            <div class="dropdown-item-desc font-weight-bold">
                                Niepoprawne chmody pliku bazy
                                <div class="text-muted">Plik <b>database.php</b> ma niepoprawnie ustawione chmody. Zalecane chmody dla tego pliku: 644</div>
                                <div class="time text-muted font-weight-bold text-uppercase">Krytyczne</div>
                            </div>
                        </div>
                    <?php
} ?>
                    <?php if (substr(decoct(fileperms(APPPATH.'config/config.php')), -4) != 644) {
        $errors++ ?>
                        <div class="dropdown-item dropdown-item-unread">
                            <div class="dropdown-item-icon bg-danger text-white">
                                <i class="fas fa-exclamation"></i>
                            </div>
                            <div class="dropdown-item-desc font-weight-bold">
                                Niepoprawne chmody pliku configu
                                <div class="text-muted">Plik <b>config.php</b> ma niepoprawnie ustawione chmody. Zalecane chmody dla tego pliku: 644</div>
                                <div class="time text-muted font-weight-bold text-uppercase">Krytyczne</div>
                            </div>
                        </div>
                    <?php
    } ?>
                    <?php if (!extension_loaded('mbstring')) {
        $errors++ ?>
                        <div class="dropdown-item dropdown-item-unread">
                            <div class="dropdown-item-icon bg-warning text-white">
                                <i class="fas fa-exclamation"></i>
                            </div>
                            <div class="dropdown-item-desc font-weight-bold">
                                Brak funkcji mbstring()
                                <div class="text-muted">Serwer ma wyłączoną funkcję PHP <b>mbstring()</b></div>
                                <div class="time text-muted font-weight-bold text-uppercase">Ważne</div>
                            </div>
                        </div>
                    <?php
    } ?>
                    <?php if (phpversion() < 7.0) {
        $errors++ ?>
                        <div class="dropdown-item dropdown-item-unread">
                            <div class="dropdown-item-icon bg-warning text-white">
                                <i class="fas fa-exclamation"></i>
                            </div>
                            <div class="dropdown-item-desc font-weight-bold">
                                Przestarzała wersja PHP
                                <div class="text-muted">Obecna wersja PHP jest przestarzała. Zalecana wersja PHP to <b>7.0 lub nowsza</b>. Obecna wersja: <?php echo phpversion(); ?></div>
                                <div class="time text-muted font-weight-bold text-uppercase">Ważne</div>
                            </div>
                        </div>
                    <?php
    } ?>
                    <?php if ($divsAPI['divsUpdate']['status']) {
        $errors++ ?>
                        <div class="dropdown-item dropdown-item-unread">
                            <div class="dropdown-item-icon bg-success text-white">
                                <i class="fas fa-exclamation"></i>
                            </div>
                            <div class="dropdown-item-desc font-weight-bold">
                                Dostępna jest nowa wersja sklepu
                                <div class="text-muted"><?php echo $divsAPI['divsUpdate']['message']; ?></div>
                                <div class="time text-muted font-weight-bold text-uppercase">Ważne</div>
                            </div>
                        </div>
                    <?php
    } ?>
                </div>
            </div>
        </li>
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image" src="<?php echo($this->session->userdata('avatar') != null) ? $this->session->userdata('avatar') : $this->config->base_url('assets/images/default_avatar.png'); ?>" class="rounded-circle mr-1" alt="<?php echo $this->session->userdata('name')."'s avatar"; ?>">
                <div class="d-sm-none d-lg-inline-block">
                    <?php if (getOnlyHour(time()) < 12) { ?>
                        Dzień dobry, 
                    <?php } elseif (getOnlyHour(time()) >= 12 && getOnlyHour(time()) < 19) { ?>
                        Dzień dobry, 
                    <?php } elseif (getOnlyHour(time()) >= 19) { ?>
                        Dobry wieczór,
                    <?php } ?>
                    <?php echo $this->session->userdata('name'); ?>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="<?php echo $this->config->base_url('panel/account'); ?>" class="dropdown-item has-icon">
                    <i class="far fa-user" style="position: relative; top: 2px;"></i> Moje konto
                </a>
                <a href="<?php echo $this->config->base_url('panel/settings'); ?>" class="dropdown-item has-icon"> 
                    <i class="fas fa-cog" style="position: relative; top: 2px;"></i> Ustawienia strony
                </a>
                <a href="<?php echo $this->config->base_url('panel/modules'); ?>" class="dropdown-item has-icon"> 
                    <i class="fas fa-layer-group" style="position: relative; top: 2px;"></i> Moduły
                </a>
                <a href="<?php echo $this->config->base_url('panel/updates'); ?>" class="dropdown-item has-icon"> 
                    <i class="fas fa-download" style="position: relative; top: 2px;"></i> Aktualizator
                </a>
                <div class="dropdown-divider"></div>
                <a href="<?php echo $this->config->base_url('admin/logout?fromLogout=acp'); ?>" class="dropdown-item has-icon text-danger"> 
                    <i class="fas fa-sign-out-alt" style="position: relative; top: 2px;"></i> Wyloguj się
                </a>
            </div>
        </li>
    </ul>
</nav>