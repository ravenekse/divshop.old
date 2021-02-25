<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 * @link   https://divshop.pro
**/

defined('BASEPATH') OR exit('No direct script access allowed');
?>
<body style="background:url('https://cdn-n.divshop.pro/images/header.png') no-repeat fixed center;">
    <div id="app" class="login-app">
        <section class="section">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                        <div class="login-brand">
                            <img src="https://cdn-n.divshop.pro/images/divshop-logo.png" class="divshop-login-logo" alt="">
                        </div>
                        <div class="card card-divshop">
                            <div class="card-header">
                                <h4 class="mr-auto ml-auto font-weight-bold">Logowanie do ACP</h4>
                            </div>
                            <div class="card-body">
                                <?php echo form_open('admin/login'); ?>
                                    <div class="form-group">
                                        <label for="authAdminLogin">Login administratora</label>
                                        <input id="authAdminLogin" type="text" class="form-control" name="authAdminLogin" tabindex="1" autofocus>
                                    </div>
                                    <div class="form-group">
                                        <label for="authAdminPass" class="control-label">Hasło administratora</label>
                                        <input id="authAdminPass" type="password" class="form-control" name="authAdminPass" tabindex="2">
                                        <div class="d-block mb-5">
                                            <div class="float-right">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block" id="btnAuthLogin" tabindex="3">Zaloguj się</button>
                                    </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php $this->load->view('components/Scripts'); ?>