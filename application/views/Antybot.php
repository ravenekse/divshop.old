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
      
    <div class="main main-raised" style="padding-bottom: 80px; padding-top: 60px;">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="col-md-6 mr-auto ml-auto text-center">
                        <h3 class="title">Weryfikacja Antybot</h3>
                        <p>
                            Aby wejść na serwer musisz potwierdzić, że nie jesteś botem. Wystarczy, że w poniższym polu wpiszesz swój nick z gry, rozwiążesz zadanie reCaptcha i klikniesz <b>Zweryfikuj</b>. Prawda, że proste?
                        </p>
                        <?php echo($settings['demoMode'] == 0) ? form_open(base_url('antybot/verify')) : '<form action="" onsubmit="event.preventDefault(); demoShowAlert()">'; ?>
                            <div class="col-md-8 text-left mr-auto ml-auto mt-3">
                                <div class="form-group bmd-form-group">
                                    <label for="playerUsername" class="bmd-label-floating">Twój nick z serwera</label>
                                    <input type="text" class="form-control" name="playerUsername" id="playerUsername">
                                </div>
                                <div class="g-recaptcha d-flex justify-content-center" data-sitekey="<?php echo $settings['recaptchaSiteKey']; ?>"></div>
                                <div class="text-center">
                                    <button class="btn btn-divshop btn-sm mt-3"><i class="fas fa-check"></i>&nbsp;&nbsp;Zweryfikuj</button>
                                </div>
                            </div>
                        <?php echo($settings['demoMode'] == 0) ? form_close() : '</form>'; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('components/Footer'); ?>