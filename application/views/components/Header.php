<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 *
 * @link   https://divshop.pro
 **/
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!doctype html>
<html lang="pl">
    <head>
        <!--  Metatags  -->
        <meta charset="<?php echo $settings['pageCharset']; ?>">
        <meta name="description" content="<?php echo $settings['pageDescription']; ?>">
        <meta name="keywords" content="<?php echo $settings['pageTags']; ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <meta name="author" content="DIVShop Team">
        <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
        <?php if ($this->uri->rsegment('1') == 'admin' && $this->uri->rsegment('2') == 'auth' or $this->uri->segment('1') == 'panel') { ?>
            <meta name="robots" content="noindex, nofollow">
        <?php } else { ?>
            <meta name="robots" content="index, follow">
        <?php } ?>
        <?php if ($this->uri->rsegment('1') == 'payments' && $this->uri->rsegment('2') == 'paypal') { ?>
            <meta http-equiv="Pragma" content="no-cache">
            <meta http-equiv="Expires" content="-1">
            <meta http-equiv="Cache-Control" content="no-cache">
        <?php } ?>

        <!--  Title and shortcut icon  -->
        <title>
            <?php if ($this->uri->segment('1') == 'panel' or $this->uri->rsegment('1') == 'admin') {
    echo $pageTitle;
} else {
    if (!$this->session->userdata('logged') && $settings['pageActive'] == 0) {
        echo $pageTitle;
    } elseif ($settings['pageActive'] == 0 && $this->session->userdata('logged')) {
        echo $pageTitle.' | '.$pageSubtitle.' | Strona wyłączona';
    } elseif ($settings['pageActive'] == 1) {
        echo $pageTitle.' | '.$pageSubtitle;
    }
}
            ?>
        </title>
        <?php if ($settings['pageFavicon'] != null) { ?>
            <link rel="shortcut icon" href="<?php echo $settings['pageFavicon']; ?>" type="image/x-icon">
            <link rel="apple-touch-icon" href="<?php echo $settings['pageFavicon']; ?>">
        <?php } ?>

        <!--  DIVShop everywhere stylesheets, fonts and icons -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" integrity="sha512-LpYyC7eFJzyRwTakq6AiaOLJ68ySmYwkFgMx7BTw+QITLSH0rEyxMHcd0gdYvvQH1Ymx+OMXV5ZiLtsWKlFwmA==" crossorigin="anonymous">
        <link rel="stylesheet" href="<?php echo $this->config->base_url('assets/css/notiflix.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo $this->config->base_url('assets/css/divshop.custom.min.css'); ?>">
        <?php if ($settings['pageCustomCSS'] != null) { ?>
            <link rel="stylesheet" href="<?php echo $settings['pageCustomCSS']; ?>">
        <?php } ?>

        <?php if ($this->uri->segment('1') == 'panel' or $this->uri->rsegment('1') == 'admin' && $this->uri->rsegment('2') == 'auth') { ?>
            <!--  DIVShop Admin Login & Admin Panel stylesheets  -->
            <link rel="preconnect" href="https://fonts.gstatic.com">
            <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet"> 
            <link rel="stylesheet" href="<?php echo $this->config->base_url('assets/panel/modules/bootstrap/css/bootstrap.min.css'); ?>">
            <?php if ($this->uri->segment('1') == 'panel') { ?>
                <link rel="stylesheet" href="<?php echo $this->config->base_url('assets/panel/modules/summernote/summernote-bs4.css'); ?>">
                <link rel="stylesheet" href="<?php echo $this->config->base_url('assets/panel/css/divshop.dark.css'); ?>">
            <?php } ?>
            <link rel="stylesheet" href="<?php echo $this->config->base_url('assets/panel/css/style.css'); ?>"> 
            <link rel="stylesheet" href="<?php echo $this->config->base_url('assets/panel/css/components.css'); ?>"> 
        <?php } ?>
        <?php if ($this->uri->rsegment('1') == 'home' or $this->uri->segment('1') == 'news' or $this->uri->rsegment('1') == 'shop' or $this->uri->rsegment('1') == 'service' or $this->uri->rsegment('1') == 'payments' or $this->uri->rsegment('1') == 'antybot' or $this->uri->rsegment('1') == 'stats' or $this->uri->rsegment('1') == 'bans' or $this->uri->rsegment('1') == 'page' or $this->uri->rsegment('1') == 'voucher') { ?>
            <!--  DIVShop stylesheets  -->
            <?php if ($settings['pagePreloader'] == 1) { ?>
                <link rel="stylesheet" href="<?php echo $this->config->base_url('assets/css/divshop-preloader.min.css'); ?>">
            <?php } ?>
            <link rel="preconnect" href="https://fonts.gstatic.com">
            <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&family=Roboto+Slab:wght@400;700&family=Roboto:wght@300;400;500;700&Material+Icons&display=swap" rel="stylesheet"> 
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Material+Icons">
            <link rel="stylesheet" href="<?php echo $this->config->base_url('assets/css/material-kit.css?v=2.2.0'); ?>">
            <link rel="stylesheet" href="<?php echo $this->config->base_url('assets/themes/divshop-material.core.css'); ?>">
            <link rel="stylesheet" href="<?php echo getPageTheme($settings['pageTheme']); ?>">
            <style>.page-header.divshop-bg-header{background-image:url('<?php echo($settings['pageBackground'] != null) ? $settings['pageBackground'] : 'https://cdn-n.divshop.pro/images/header.png' ?>');}</style>
        <?php } ?>
    </head>
    <noscript><style> body{background:#fff !important;color: #000 !important;}div{display:none !important;}</style>You need to enable JavaScript to run this app.</noscript>