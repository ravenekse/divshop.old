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
            <h1>Edycja usługi <?php echo $service['name'].' (ID: #'.$service['id'].')'; ?></h1>
          </div>
          <?php if (file_exists(APPPATH.'views/panel/components/TopAlerts.php')) {
          $this->load->view('panel/components/TopAlerts');
      } else {
          exit('File <b>views/panel/components/TopAlerts.php</b> missing!');
      } ?>
          <div class="row">
            <div class="col-md-10 col-sm-12 mr-auto ml-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center py-8 px-8 py-lg-15 px-lg-10">
                            <div class="col-xl-12 col-xxl-10">
                                <?php echo($settings['demoMode'] == 0) ? form_open_multipart(base_url('panel/edit/service/save')) : '<form action="" onsubmit="event.preventDefault(); demoShowAlert()">'; ?>
                                    <input type="hidden" name="serviceId" value="<?php echo $service['id']; ?>">
                                    <div class="row justify-content-center">
                                        <div class="col-xl-8">
                                            <div class="form-group row">
                                              <label class="col-xl-3 col-lg-3 col-form-label">Obrazek usługi</label>
                                              <div class="col-lg-9 col-xl-8 col-md-12">
                                                  <div class="custom-file">
                                                    <label class="custom-file-label" for="customFile">Wybierz plik</label>
                                                    <input type="file" class="custom-file-input" id="customFile" name="serviceImage" accept=".png, .jpg, .jpeg, .gif">
                                                  </div>
                                                  <small id="removeImage" style="cursor:pointer;display:none;"><b>Usuń zdjęcie</b></small>
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <label class="col-xl-3 col-lg-3 col-form-label">Nazwa usługi <span class="text-danger" data-toggle="tooltip" title="Pole wymagane">*</span></label>
                                              <div class="col-lg-9 col-xl-9">
                                                <input type="text" class="form-control" name="serviceName" value="<?php echo $service['name']; ?>">
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <label class="col-xl-3 col-lg-3 col-form-label">Opis usługi </label>
                                              <div class="col-lg-9 col-xl-9">
                                                <textarea name="serviceDescription" class="summernote"><?php echo $service['description']; ?></textarea>
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <label class="col-xl-3 col-lg-3 col-form-label">Serwer <span class="text-danger" data-toggle="tooltip" title="Pole wymagane">*</span></label>
                                              <div class="col-lg-9 col-xl-9">
                                                <select class="form-control selectric" name="serviceServer">
                                                    <option disabled selected>Wybierz serwer</option>
                                                    <?php foreach ($servers as $server) { ?>
                                                        <option value="<?php echo $server['id']; ?>" <?php echo($service['server'] == $server['id']) ? 'selected' : ''; ?>>Serwer <?php echo $server['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                              </div>
                                            </div>
                                            <?php if ($settings['smsOperator'] != 0 && $settings['smsOperator'] = 1) { ?>
                                              <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Kanał SMS <span class="text-danger" data-toggle="tooltip" title="Pole wymagane">*</span></label>
                                                <div class="col-lg-9 col-xl-9">
                                                  <input type="text" class="form-control" name="serviceSmsChannel" placeholder="Przykład: MSMS.DIVSHOP" value="<?php echo $service['smsConfig']['smsChannel']; ?>">
                                                </div>
                                              </div>
                                              <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">ID kanału SMS <span class="text-danger" data-toggle="tooltip" title="Pole wymagane">*</span></label>
                                                <div class="col-lg-9 col-xl-9">
                                                  <input type="number" class="form-control" name="serviceSmsChannelId" placeholder="Przykład: 0987" value="<?php echo $service['smsConfig']['smsChannelId']; ?>">
                                                </div>
                                              </div>
                                              <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Numer i koszt SMS <span class="text-danger" data-toggle="tooltip" title="Pole wymagane">*</span></label>
                                                <div class="col-lg-9 col-xl-9">
                                                  <select class="form-control selectric" name="serviceSmsNumber">
                                                      <option disabled selected>Wybierz numer</option>
                                                      <?php foreach (getSmsNumber(1) as $number => $cost) {
          echo '<option value="'.$number.'" '.(($service['smsConfig']['smsNumber'] == $number) ? 'selected' : '').'>'.$number.' - '.$cost.' zł ('.getBruttoPrice($number, 1).' zł z VAT)</option>';
      } ?>
                                                  </select>
                                                </div>
                                              </div>
                                            <?php } ?>
                                            <?php $paypal = json_decode($payments['paypal']['config'], true); if ($paypal['address'] != null) { ?>
                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">Koszt PayPal</label>
                                                    <div class="col-lg-9 col-xl-9">
                                                        <input type="text" class="form-control" name="servicePaypalCost" placeholder="Przykład: 10.99" value="<?php echo $service['paypalCost']; ?>">
                                                        <small>Jeżeli równa wartość np. 10 wpisz bez kropki i zer na końcu</small>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <?php $transfer = json_decode($payments['transfer']['config'], true); if (($transfer['transfer']['shopid'] && $transfer['transfer']['userid'] && $transfer['transfer']['hash']) != null) { ?>
                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">Koszt Przelewu</label>
                                                    <div class="col-lg-9 col-xl-9">
                                                        <input type="text" class="form-control" name="serviceTransferCost" placeholder="Przykład: 10.99" value="<?php echo $service['transferCost']; ?>">
                                                        <small>Jeżeli równa wartość np. 10 wpisz bez kropki i zer na końcu</small>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <div class="form-group row">
                                              <label class="col-xl-3 col-lg-3 col-form-label">Komendy do wykonania <span class="text-danger" data-toggle="tooltip" title="Pole wymagane">*</span></label>
                                              <div class="col-lg-9 col-xl-9">
                                                <textarea class="form-control" name="serviceCommands" id="serviceCommands" rows="3" style="min-height: 138px !important;max-height: 138px !important;" placeholder="Przykład: give {BUYER} apple 1; say Gracz {BUYER} dostał jabłko"><?php echo $service['commands']; ?></textarea>
                                                <small class="text-muted" id="serviceCommands">
                                                    Zamiast nicku gracza użyj <b>{BUYER}</b><br>
                                                    Komendy oddzielaj średnikiem i spacją bez przechodzenia do nowej lini oraz poprzedzającego slash'a<br>
                                                </small>
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Czy pokazywać usługę</label>
                                                <div class="col-3">
                                                    <label class="custom-switch mt-2">
                                                        <label for="serviceActive" class="mr-2 mt-1">Off</label>
                                                        <input type="checkbox" name="serviceActive" class="custom-switch-input" <?php echo($service['serviceActive'] == 0) ? '' : 'checked' ?>>
                                                        <span class="custom-switch-indicator"></span>
                                                        <label for="serviceActive" class="ml-2 mt-1">On</label>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-center">
                                                <button type="submit" id="btnEditSubmit" class="btn btn-primary btn-xs mt-4"><i class="bi bi-check"></i> Potwierdź i zapisz</button>
                                            </div>
                                        </div>
                                    </div>
                                <?php echo($settings['demoMode'] == 0) ? form_close() : '</form>'; ?>
                            </div>
                        </div>
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