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
                        <?php if($_SESSION['purchaseResponse']['method'] == "sms"): ?>
                            <?php if($_SESSION['purchaseResponse']['status'] == true): ?>
                                <i class="far fa-check-circle text-success fa-6x mt-3"></i>
                                <h3 class="title">Płatność zrealizowana</h3>
                                <p>
                                    Usługa <b><?php echo $_SESSION['purchaseResponse']['service']; ?></b> na serwerze <b><?php echo $_SESSION['purchaseResponse']['server']; ?></b> została pomyślnie zakupiona! Dziękujemy za zakup oraz wsparcie naszego serwera :)
                                    <br><br>
                                    ID Płatności: <b><?php echo $_SESSION['purchaseResponse']['payid']; ?></b>
                                </p>
                                <a href="<?php echo $this->config->base_url(); ?>" class="btn btn-divshop btn-sm">Powrót do strony głownej</a>
                            <?php else: ?>
                                <i class="far fa-times-circle text-danger fa-6x mt-3"></i>
                                <h3 class="title">Płatność nieudana</h3>
                                <p>
                                    Wystąpił błąd podczas zakupu usługi <b><?php echo $_SESSION['purchaseResponse']['service']; ?></b> na serwerze <b><?php echo $_SESSION['purchaseResponse']['server']; ?></b>! Prosimy zachować kod SMS i zgłosić się do Administratora.
                                    <br><br>
                                    Prosimy o zrobienie zdjęcia tej strony jako dowód
                                    <br><br>
                                    ID Płatności: <b><?php echo $_SESSION['purchaseResponse']['payid']; ?></b>
                                </p>
                                <a href="<?php echo $this->config->base_url(); ?>" class="btn btn-divshop btn-sm">Powrót do strony głownej</a>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php unset($_SESSION['purchaseResponse']); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('components/Footer'); ?>