<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      <?php
        if (file_exists(APPPATH.'views/panel/components/Sidebar.php') && file_exists(APPPATH.'views/panel/components/Navbar.php')) {
            $this->load->view('panel/components/Navbar');
            $this->load->view('panel/components/Sidebar');
        } elseif (!file_exists(APPPATH.'views/panel/components/Navbar.php')) {
            exit('File <b>views/panel/components/Navbar.php</b> missing!');
        } elseif (!file_exists(APPPATH.'views/panel/components/Sidebar.php')) {
            exit('File <b>views/panel/components/Sidebar.php</b> missing!');
        }
      ?>
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Ustawienia</h1>
          </div>
          <?php if (file_exists(APPPATH.'views/panel/components/TopAlerts.php')) {
          $this->load->view('panel/components/TopAlerts');
      } else {
          exit('File <b>views/panel/components/TopAlerts.php</b> missing!');
      } ?>
          <div class="row">
            <div class="col-md-12 col-sm-12 mr-auto ml-auto">
                <div class="card">
                    <div class="card-body">
                        <?php echo($settings['demoMode'] == 0) ? form_open_multipart(base_url('panel/paysettings/save')) : '<form action="" onsubmit="event.preventDefault(); demoShowAlert()">'; ?>
                            <div class="row justify-content-center py-8 px-8 py-lg-15 px-lg-10">
                                <div class="col-xl-12 col-xxl-10">
                                    <div class="row justify-content-center">
                                        <div class="col-xl-8">
                                            <div class="form-group row">
                                              <label class="col-xl-3 col-lg-3 col-form-label">Operator płatności SMS <span class="text-danger" data-toggle="tooltip" title="Pole wymagane">*</span></label>
                                              <div class="col-lg-9 col-xl-9">
                                                <select class="form-control selectric" name="paymentsSmsOperator">
                                                    <option disabled <?php echo($smsOperator == null) ? 'selected' : ''; ?> >Wybierz operatora</option>
                                                    <option value="1" <?php echo($smsOperator['name'] == 'MicroSMS.pl') ? 'selected' : ''; ?>>MicroSMS.pl</option>
                                                </select>
                                              </div>
                                            </div>
                                            <?php if ($smsOperator != null) { ?>
                                                <?php if ($smsOperator['name'] == 'MicroSMS.pl') { ?>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">ID Użytkownika MicroSMS <span class="text-danger" data-toggle="tooltip" title="Pole wymagane">*</span></label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <input type="number" class="form-control" name="paymentsMicrosmsUserId" value="<?php echo $payments['sms']['userid']; ?>">
                                                            <small class="text-muted">Identyfikator użytkownika w serwisie MicroSMS.pl</small>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            <?php } ?>
                                            <div class="mt-5 mb-5" style="display: grid;"></div>
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Adres e-mail PayPal</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input type="emial" class="form-control" name="paymentsPaypalAddress" value="<?php echo $payments['paypal']['address']; ?>">
                                                    <small class="text-muted">Adres e-mail powiązany z Twoim kontem PayPal</small>
                                                </div>
                                            </div>
                                            <div class="mt-5 mb-5" style="display: grid;"></div>
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">ID Sklepu</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input type="number" class="form-control" name="paymentsTransferShopId" value="<?php echo $payments['transfer']['shopid']; ?>">
                                                    <small class="text-muted">Identyfikator sklepu w serwisie MicroSMS.pl</small>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">ID Użytkownika MicroSMS</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <input type="number" class="form-control" name="paymentsTransferUserId" value="<?php echo $payments['transfer']['userid']; ?>">
                                                    <small class="text-muted">Identyfikator użytkownika w serwisie MicroSMS.pl</small>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Hash sklepu</label>
                                                <div class="col-lg-9 col-xl-9">
                                                    <div class="input-group">
                                                        <input type="password" class="form-control" id="divsPaymentTransferHash" name="paymentsTransferHash" value="<?php echo $payments['transfer']['hash']; ?>">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text show-password-btn" onclick="showTransferHash();" data-toggle="tooltip" title="Kliknij, aby pokazać lub ukryć hash">
                                                                <i class="bi bi-eye" id="showHash"></i>
                                                                <i class="bi bi-eye-slash d-none" id="hideHash"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">Tajny ciąg znaków wymagany do tworzenia płatności</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <button type="button" class="btn btn-primary btn-xs mt-4" onclick="saveSettings(this);"><i class="bi bi-check"></i> Zapisz ustawienia</button>
                            </div>
                        <?php echo($settings['demoMode'] == 0) ? form_close() : '</form>'; ?>
                    </div>
                </div>
            </div>
          </div>
        </section>
      </div>
      <?php $this->load->view('panel/components/Footer'); ?>
    </div>
  </div>

  <?php $this->load->view('components/Scripts'); ?>
</body>