<?php $this->load->view('components/Navbar'); ?>
	<div class="page-header header-filter header-small divshop-bg-header" data-parallax="true">
        <div class="container">
            <div class="row">
                <div class="col-md-8 ml-auto mr-auto">
                    <div class="brand text-center divshop-header-shop-title">
                        <h1><?php echo $pageTitle; ?></h1>
                        <h3 class="title text-center"><?php echo $pageSubtitle; ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
      
    <div class="main main-raised" style="padding-bottom: 120px;">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-12 ml-auto mr-auto">
                    <div class="row">
                        <div class="col-sm-12 col-md-6 text-center text-md-left">
                            <h3 class="divshop-section-title text-center text-md-left"><i class="fas fa-shopping-basket"></i>&nbsp;Usługa <b><?php echo $service['name']; ?></b></h3>
                        </div>
                        <?php if($modules[4]['moduleName'] == "vouchers" && $modules[4]['moduleEnabled'] == 1): ?>
                            <div class="col-sm-12 col-md-6 text-center text-md-right pt-md-3">
                                <a href="<?php echo $this->config->base_url('voucher'); ?>" class="btn btn-divshop btn-sm"><i class="fas fa-key"></i>&nbsp;Realizuj voucher</a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card">
                        <div class="row">
                            <div class="col-md-6">
                                <?php if($service['image'] != null): ?>
                                    <img class="divshop-service-purchase-image" src="<?php echo $service['image']; ?>" alt="<?php echo $service['name']; ?> image">
                                    <div style="border-bottom: 2px solid rgba(119,119,119,0.25);"></div>
                                <?php endif; ?>
                                <div class="divshop-service-info">
                                    <h4 class="title text-center mb-2"><?php echo $service['name']; ?></h4>
                                    <p>
                                        <?php echo $service['description']; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6 mt-5">
                                <?php $activePill = 0; ?>
                                <?php if($payments['sms']['userid'] == null && $payments['paypal']['address'] == null && ($payments['transfer']['shopid'] && $payments['transfer']['userid'] && $payments['transfer']['hash']) == null): ?>
                                    <h6 class="text-center"><i class="fas fa-exclamation-triangle"></i> Aby umożliwić zakup usługi, wymagane jest skonfigurowanie minimum jednej metody płatności!</h6>
                                <?php else: ?>
                                    <ul class="nav nav-pills nav-pills-divshop d-flex justify-content-center">
                                        <?php if(($service['smsConfig'] && $payments['sms']['userid']) != null && $settings['smsOperator'] != 0): ?>
                                            <li class="nav-item">
                                                <a class="nav-link <?php echo($activePill == 0) ? 'active' : ''; ?>" href="#divshopSMS" data-toggle="tab">
                                                    <i class="fas fa-mobile-alt"></i> 
                                                    SMS Premium
                                                </a>
                                            </li>
                                        <?php $activePill++; endif; ?>
                                        <?php if($service['paypalCost'] && $payments['paypal']['address'] != null): ?>
                                            <li class="nav-item">
                                                <a class="nav-link <?php echo($activePill == 0) ? 'active' : ''; ?>" href="#divshopPayPal" data-toggle="tab">
                                                    <i class="fab fa-paypal"></i> 
                                                    PayPal
                                                </a>
                                            </li>
                                        <?php $activePill++; endif; ?>
                                        <?php if($service['transferCost'] != null && ($payments['transfer']['shopid'] && $payments['transfer']['userid'] && $payments['transfer']['hash']) != null): ?>
                                            <li class="nav-item">
                                                <a class="nav-link <?php echo($activePill == 0) ? 'active' : ''; ?>" href="#divshopTransfer" data-toggle="tab">
                                                    <i class="fas fa-credit-card"></i>
                                                    Przelew
                                                </a>
                                            </li>
                                        <?php $activePill++; endif; ?>
                                    </ul>
                                    <div class="tab-content tab-space">
                                        <?php $activeTab = 0; ?>
                                        <?php if(($service['smsConfig'] && $payments['sms']['userid']) != null && $settings['smsOperator'] != 0): ?>
                                            <div class="tab-pane text-center <?php echo($activeTab == 0) ? 'active show' : ''; ?>" id="divshopSMS">
                                                <h5 class="title">Płatność przez SMS Premium</h5>
                                                <p>
                                                    Koszt SMS: <span class="badge badge-success" style="position:relative;top:-1px;"><?php echo number_format(getBruttoPrice($service['smsConfig']['smsNumber'], 1), 2, ',', ' ') . ' zł (w tym VAT)'; ?></span>

                                                    <br><br>
                                                    Aby zakupić usługę, wyślij SMS o treści <b><?php echo $service['smsConfig']['smsChannel']; ?></b> pod numer <b><?php echo $service['smsConfig']['smsNumber']; ?></b>.
                                                    <br>
                                                    Otrzymany kod wpisz w polu <b>Kod z SMS</b>.
                                                </p>
                                                <div class="col-md-8 col-sm-12 text-left mr-auto ml-auto">
                                                    <?php echo($settings['demoMode'] == 0) ? form_open(base_url('payments/sms')) : '<form action="" onsubmit="event.preventDefault(); demoShowAlert()">'; ?>
                                                        <!-- Pamiętaj! Jeżeli edytujesz to miejsce przez zbadaj element, twoja płatność może zostać zrealizowana nieprawidłowo lub nie zostać zrealizowana w ogóle. -->

                                                        <input type="hidden" name="serviceId" value="<?php echo $service['id']; ?>">
                                                        <input type="hidden" name="serviceName" value="<?php echo $service['name']; ?>">
                                                        <input type="hidden" name="serverId" value="<?php echo $server['id']; ?>">
                                                        <input type="hidden" name="serverName" value="<?php echo $server['name']; ?>">
                                                        <div class="form-group bmd-form-group">
                                                            <label for="nickName" class="bmd-label-floating">Twój nick z serwera <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="nickName" id="nickName">
                                                        </div>
                                                        <div class="form-group bmd-form-group">
                                                            <label for="smsCode" class="bmd-label-floating">Kod z SMS <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="smsCode" id="smsCode" maxlength="8">
                                                        </div>
                                                        <div class="form-check mt-5">
                                                            <label class="form-check-label">
                                                            <input class="form-check-input" type="checkbox" name="acceptRules">
                                                            Akceptuję <a href="https://microsms.pl/files/regulations/" style="font-weight:600;" target="_blank" rel="noopener noreferrer">regulamin</a> płatności MicroSMS.pl
                                                            <span class="form-check-sign">
                                                                <span class="check"></span>
                                                            </span>
                                                            </label>&nbsp;<span class="text-danger">*</span>
                                                        </div>
                                                        <small>Pola oznaczone <span class="text-danger">*</span> są wymagane</small>
                                                        <div class="text-center">
                                                            <button class="btn btn-divshop mt-3 btnBuyService"><i class="fas fa-check"></i>&nbsp;&nbsp;Zakup usługę</button>
                                                        </div>
                                                    <?php echo($settings['demoMode'] == 0) ? form_close() : '</form>'; ?>
                                                </div>
                                                <div class="col-md-10 col-sm-12 text-center mr-auto ml-auto mt-4">
                                                    <img src="<?php echo $this->config->base_url('assets/images/payments/microsms-banner.png') ?>" alt="MicroSMS.pl" class="divshop-microsms-banner img-fluid">
                                                    <p class="mt-4">
                                                        Płatności zapewnia firma <a href="https://microsms.pl" style="font-weight:600;" target="_blank" rel="noopener noreferrer">MicroSMS.pl</a>. Korzystanie ze sklepu jest jednoznaczne z akceptacją <a href="https://microsms.pl/files/regulations/" style="font-weight:600;" target="_blank" rel="noopener noreferrer">regulaminu</a>. Jeśli nie dostałeś kodu zwrotnego w ciągu 30 minut skorzystaj z <a href="https://microsms.pl/customer/complaint/" style="font-weight:600;" target="_blank" rel="noopener noreferrer">formularza reklamacyjnego</a>.
                                                    </p>
                                                </div>
                                            </div>
                                        <?php $activeTab++; endif; ?>

                                        <?php if(($service['paypalCost'] && $payments['paypal']['address']) != null): ?>
                                            <div class="tab-pane text-center <?php echo($activeTab == 0) ? 'active show' : ''; ?>" id="divshopPayPal">
                                                <h5 class="title">Płatność przez PayPal</h5>
                                                <p>
                                                    Koszt płatności przez PayPal: <span class="badge badge-success" style="position:relative;top:-1px;"><?php echo number_format($service['paypalCost'], 2, ',', ' '); ?> zł</span>
                                                    <br><br>
                                                    Aby zakupić usługę, w polu <b>Twój nick z serwera</b> wpisz swoją nazwę z serwera, a następnie kliknij <b>Przejdź dalej</b>
                                                </p>
                                                <div class="col-md-8 col-sm-12 text-left mr-auto ml-auto">
                                                    <?php echo($settings['demoMode'] == 0) ? form_open(base_url('payments/paypal')) : '<form action="" onsubmit="event.preventDefault(); demoShowAlert()">'; ?>
                                                        <!-- Pamiętaj! Jeżeli edytujesz to miejsce przez zbadaj element, twoja płatność może zostać zrealizowana nieprawidłowo lub nie zostać zrealizowana w ogóle. -->

                                                        <input type="hidden" name="serviceId" value="<?php echo $service['id']; ?>">
                                                        <input type="hidden" name="serviceName" value="<?php echo $service['name']; ?>">
                                                        <input type="hidden" name="serverId" value="<?php echo $server['id']; ?>">
                                                        <input type="hidden" name="serverName" value="<?php echo $server['name']; ?>">
                                                        <div class="form-group bmd-form-group">
                                                            <label for="nickName" class="bmd-label-floating">Twój nick z serwera <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="nickName" id="nickName">
                                                        </div>
                                                        <div class="form-check mt-5">
                                                            <label class="form-check-label">
                                                            <input class="form-check-input" type="checkbox" name="acceptRules">
                                                            Akceptuję <a href="https://www.paypal.com/pl/webapps/mpp/ua/legalhub-full?locale.x=pl_PL" style="font-weight:600;" target="_blank" rel="noopener noreferrer">regulamin</a> płatności PayPal
                                                            <span class="form-check-sign">
                                                                <span class="check"></span>
                                                            </span>
                                                            </label>&nbsp;<span class="text-danger">*</span>
                                                        </div>
                                                        <small>Pola oznaczone <span class="text-danger">*</span> są wymagane</small>
                                                        <div class="text-center">
                                                            <button class="btn btn-divshop btnBuyService mt-3"><i class="fas fa-check"></i>&nbsp;&nbsp;Przejdź dalej</button>
                                                        </div>
                                                    <?php echo($settings['demoMode'] == 0) ? form_close() : '</form>'; ?>
                                                </div>
                                                <div class="col-md-10 col-sm-12 text-center mr-auto ml-auto mt-4">
                                                    <img src="<?php echo $this->config->base_url('assets/images/payments/paypal-logo.png') ?>" alt="MicroSMS.pl" class="img-fluid">
                                                    <p class="mt-4">
                                                        Dokonanie płatności jest jednoznaczne z akceptacją <a href="https://www.paypal.com/pl/webapps/mpp/ua/legalhub-full?locale.x=pl_PL" style="font-weight:600;" target="_blank" rel="noopener noreferrer">regulaminów i umów</a>. W celu zgłoszenia reklamacji lub utworzenia sporu odwiedź <a href="https://www.paypal.com/pl/selfhelp/topic/DISPUTE_AND_CLAIM_INFORMATION" style="font-weight:600;" target="_blank" rel="noopener noreferrer">tę stronę</a>
                                                    </p>
                                                </div>
                                            </div>
                                        <?php $activeTab++; endif; ?>

                                        <?php if($service['transferCost'] != null && ($payments['transfer']['shopid'] && $payments['transfer']['userid'] && $payments['transfer']['hash']) != null): ?> 
                                            <div class="tab-pane text-center <?php echo($activeTab == 0) ? 'active show' : ''; ?>" id="divshopTransfer">
                                                <h5 class="title">Płatność przelewem</h5>
                                                <p>
                                                    Koszt płatności przez Przelew: <span class="badge badge-success" style="position:relative;top:-1px;"><?php echo number_format($service['transferCost'], 2, ',', ' '); ?> zł</span>
                                                    <br><br>
                                                    Aby zakupić usługę, w polu <b>Twój nick z serwera</b> wpisz swoją nazwę z serwera, a następnie kliknij <b>Przejdź dalej</b>
                                                </p>
                                                <div class="col-md-8 col-sm-12 text-left mr-auto ml-auto">
                                                    <?php echo($settings['demoMode'] == 0) ? form_open(base_url('payments/transfer')) : '<form action="" onsubmit="event.preventDefault(); demoShowAlert()">'; ?>
                                                        <!-- Pamiętaj! Jeżeli edytujesz to miejsce przez zbadaj element, twoja płatność może zostać zrealizowana nieprawidłowo lub nie zostać zrealizowana w ogóle. -->

                                                        <input type="hidden" name="serviceId" value="<?php echo $service['id']; ?>">
                                                        <input type="hidden" name="serviceName" value="<?php echo $service['name']; ?>">
                                                        <input type="hidden" name="serverId" value="<?php echo $server['id']; ?>">
                                                        <input type="hidden" name="serverName" value="<?php echo $server['name']; ?>">
                                                        <div class="form-group bmd-form-group">
                                                            <label for="nickName" class="bmd-label-floating">Twój nick z serwera <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="nickName" id="nickName">
                                                        </div>
                                                        <div class="form-check mt-5">
                                                            <label class="form-check-label">
                                                            <input class="form-check-input" type="checkbox" name="acceptRules">
                                                            Akceptuję <a href="https://microsms.pl/documents/regulamin_wplat_2020.pdf" style="font-weight:600;" target="_blank" rel="noopener noreferrer">regulamin</a> płatności MicroSMS.pl
                                                            <span class="form-check-sign">
                                                                <span class="check"></span>
                                                            </span>
                                                            </label>&nbsp;<span class="text-danger">*</span>
                                                        </div>
                                                        <small>Pola oznaczone <span class="text-danger">*</span> są wymagane</small>
                                                        <div class="text-center">
                                                            <button class="btn btn-divshop mt-3 btnBuyService"><i class="fas fa-check"></i>&nbsp;&nbsp;Przejdź dalej</button>
                                                        </div>
                                                    <?php echo($settings['demoMode'] == 0) ? form_close() : '</form>'; ?>
                                                </div>
                                                <div class="col-md-10 col-sm-12 text-center mr-auto ml-auto mt-4">
                                                    <img src="<?php echo $this->config->base_url('assets/images/payments/microsms-banner.png') ?>" alt="MicroSMS.pl" class="divshop-microsms-banner img-fluid">
                                                    <p class="mt-4">
                                                        Płatności zapewnia firma <a href="https://microsms.pl" style="font-weight:600;" target="_blank" rel="noopener noreferrer">MicroSMS.pl</a>. Korzystanie ze sklepu jest jednoznaczne z akceptacją <a href="https://microsms.pl/documents/regulamin_wplat_2020.pdf" style="font-weight:600;" target="_blank" rel="noopener noreferrer">regulaminu</a>. Formularz reklamacyjny dostępny jest pod <a href="https://microsms.pl/customer/complaint/" style="font-weight:600;" target="_blank" rel="noopener noreferrer">tym linkiem</a>
                                                    </p>
                                                </div>
                                            </div>
                                        <?php $activeTab++; endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('components/Footer'); ?>