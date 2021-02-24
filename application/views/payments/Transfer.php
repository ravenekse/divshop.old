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
      
    <div class="main main-raised" style="padding-bottom: 80px;padding-top: 80px;">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-md-8 col-lg-4 mr-auto ml-auto">
                    <?php if($pageCode == "waitingForPaymentVerify"): ?>
                        <div class="mr-auto ml-auto mb-0 text-center">
                            <div style="width:80px; height:80px;" class="mr-auto ml-auto text-center d-flex justify-content-center notiflix-loading-icon with-message"><svg xmlns="http://www.w3.org/2000/svg" id="NXLoadingHourglass" fill="#32c682" width="80px" height="80px" fill-rule="evenodd" clip-rule="evenodd" image-rendering="optimizeQuality" shape-rendering="geometricPrecision" text-rendering="geometricPrecision" version="1.1" viewBox="0 0 200 200" xml:space="preserve"><style>@-webkit-keyframes NXhourglass5-animation{0%{-webkit-transform:scale(1,1);transform:scale(1,1)}16.67%{-webkit-transform:scale(1,.8);transform:scale(1,.8)}33.33%{-webkit-transform:scale(.88,.6);transform:scale(.88,.6)}37.5%{-webkit-transform:scale(.85,.55);transform:scale(.85,.55)}41.67%{-webkit-transform:scale(.8,.5);transform:scale(.8,.5)}45.83%{-webkit-transform:scale(.75,.45);transform:scale(.75,.45)}50%{-webkit-transform:scale(.7,.4);transform:scale(.7,.4)}54.17%{-webkit-transform:scale(.6,.35);transform:scale(.6,.35)}58.33%{-webkit-transform:scale(.5,.3);transform:scale(.5,.3)}83.33%,to{-webkit-transform:scale(.2,0);transform:scale(.2,0)}}@keyframes NXhourglass5-animation{0%{-webkit-transform:scale(1,1);transform:scale(1,1)}16.67%{-webkit-transform:scale(1,.8);transform:scale(1,.8)}33.33%{-webkit-transform:scale(.88,.6);transform:scale(.88,.6)}37.5%{-webkit-transform:scale(.85,.55);transform:scale(.85,.55)}41.67%{-webkit-transform:scale(.8,.5);transform:scale(.8,.5)}45.83%{-webkit-transform:scale(.75,.45);transform:scale(.75,.45)}50%{-webkit-transform:scale(.7,.4);transform:scale(.7,.4)}54.17%{-webkit-transform:scale(.6,.35);transform:scale(.6,.35)}58.33%{-webkit-transform:scale(.5,.3);transform:scale(.5,.3)}83.33%,to{-webkit-transform:scale(.2,0);transform:scale(.2,0)}}@-webkit-keyframes NXhourglass3-animation{0%{-webkit-transform:scale(1,.02);transform:scale(1,.02)}79.17%,to{-webkit-transform:scale(1,1);transform:scale(1,1)}}@keyframes NXhourglass3-animation{0%{-webkit-transform:scale(1,.02);transform:scale(1,.02)}79.17%,to{-webkit-transform:scale(1,1);transform:scale(1,1)}}@-webkit-keyframes NXhourglass1-animation{0%,83.33%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}to{-webkit-transform:rotate(180deg);transform:rotate(180deg)}}@keyframes NXhourglass1-animation{0%,83.33%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}to{-webkit-transform:rotate(180deg);transform:rotate(180deg)}}#NXLoadingHourglass *{-webkit-animation-duration:2.2s;animation-duration:2.2s;-webkit-animation-iteration-count:infinite;animation-iteration-count:infinite;-webkit-animation-timing-function:cubic-bezier(0,0,1,1);animation-timing-function:cubic-bezier(0,0,1,1)}</style><g data-animator-group="true" data-animator-type="1" style="-webkit-animation-name:NXhourglass1-animation;animation-name:NXhourglass1-animation;-webkit-transform-origin:50% 50%;transform-origin:50% 50%;transform-box:fill-box"><g id="NXhourglass2" fill="inherit"><g data-animator-group="true" data-animator-type="2" style="-webkit-animation-name:NXhourglass3-animation;animation-name:NXhourglass3-animation;-webkit-animation-timing-function:cubic-bezier(.42,0,.58,1);animation-timing-function:cubic-bezier(.42,0,.58,1);-webkit-transform-origin:50% 100%;transform-origin:50% 100%;transform-box:fill-box" opacity=".4"><path id="NXhourglass4" d="M100 100l-34.38 32.08v31.14h68.76v-31.14z"></path></g><g data-animator-group="true" data-animator-type="2" style="-webkit-animation-name:NXhourglass5-animation;animation-name:NXhourglass5-animation;-webkit-transform-origin:50% 100%;transform-origin:50% 100%;transform-box:fill-box" opacity=".4"><path id="NXhourglass6" d="M100 100L65.62 67.92V36.78h68.76v31.14z"></path></g><path d="M51.14 38.89h8.33v14.93c0 15.1 8.29 28.99 23.34 39.1 1.88 1.25 3.04 3.97 3.04 7.08s-1.16 5.83-3.04 7.09c-15.05 10.1-23.34 23.99-23.34 39.09v14.93h-8.33a4.859 4.859 0 1 0 0 9.72h97.72a4.859 4.859 0 1 0 0-9.72h-8.33v-14.93c0-15.1-8.29-28.99-23.34-39.09-1.88-1.26-3.04-3.98-3.04-7.09s1.16-5.83 3.04-7.08c15.05-10.11 23.34-24 23.34-39.1V38.89h8.33a4.859 4.859 0 1 0 0-9.72H51.14a4.859 4.859 0 1 0 0 9.72zm79.67 14.93c0 15.87-11.93 26.25-19.04 31.03-4.6 3.08-7.34 8.75-7.34 15.15 0 6.41 2.74 12.07 7.34 15.15 7.11 4.78 19.04 15.16 19.04 31.03v14.93H69.19v-14.93c0-15.87 11.93-26.25 19.04-31.02 4.6-3.09 7.34-8.75 7.34-15.16 0-6.4-2.74-12.07-7.34-15.15-7.11-4.78-19.04-15.16-19.04-31.03V38.89h61.62v14.93z"></path></g></g></svg></div>
                            <p id="NotiflixLoadingMessage" class="loading-message">Trwa przetwarzanie płatności. Nie odświeżaj strony - odświeży się automatycznie</p>
                        </div>
                        <script>
                            setTimeout(function() {
                                location.reload();
                            }, 5000);
                        </script>
                    <?php elseif($pageCode == "paymentSuccessful"): ?>
                        <div class="text-center">
                            <i class="far fa-check-circle text-success fa-6x mt-3"></i>
                            <h3 class="title">Płatność zrealizowana</h3>
                            <p>
                                Usługa <b><?php echo $paymentInfo['service']; ?></b> na serwerze <b><?php echo $paymentInfo['server']; ?></b> została pomyślnie zakupiona! Dziękujemy za zakup oraz wsparcie naszego serwera :)
                                <br><br>
                                ID Płatności: <b><?php echo $paymentInfo['payid']; ?></b>
                            </p>
                            <a href="<?php echo $this->config->base_url(); ?>" class="btn btn-divshop btn-sm">Powrót do strony głownej</a>
                        </div>
                    <?php elseif($pageCode == "paymentFailed"): ?>
                        <div class="text-center">
                            <i class="far fa-times-circle text-danger fa-6x mt-3"></i>
                            <h3 class="title">Płatność nieudana</h3>
                            <p>
                                Płatność przebiegła pomyślnie, ale podczas realizacji usługi wysąpił nieoczekiwany błąd. Skontaktuj się z administracją serwera w celu rozwiązania problemu!
                                <br><br>
                                Prosimy o zrobienie zdjęcia tej strony jako dowód
                                <br><br>
                                ID Płatności: <b><?php echo $paymentInfo['payid']; ?></b>
                            </p>
                            <a href="<?php echo $this->config->base_url(); ?>" class="btn btn-divshop btn-sm">Powrót do strony głownej</a>
                        </div>
                    <?php elseif($pageCode == "endedRconError"): ?>
                        <div class="text-center">
                            <i class="far fa-times-circle text-danger fa-6x mt-3"></i>
                            <h3 class="title">Płatność nieudana</h3>
                            <p>
                                Płatność przebiegła pomyślnie, ale podczas realizacji usługi wysąpił nieoczekiwany błąd. Skontaktuj się z administracją serwera w celu rozwiązania problemu!
                                <br><br>
                                Prosimy o zrobienie zdjęcia tej strony jako dowód
                                <br><br>
                                ID Płatności: <b><?php echo $paymentInfo['payid']; ?></b>
                            </p>
                            <a href="<?php echo $this->config->base_url(); ?>" class="btn btn-divshop btn-sm">Powrót do strony głownej</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('components/Footer'); ?>