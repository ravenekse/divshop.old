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
            <h1>Nowy voucher</h1>
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
                                <?php echo($settings['demoMode'] == 0) ? form_open_multipart(base_url('panel/add/voucher/create')) : '<form action="" onsubmit="event.preventDefault(); demoShowAlert()">'; ?>
                                    <div class="row justify-content-center">
                                        <div class="col-xl-8">
                                            <div class="form-group row">
                                              <label class="col-xl-3 col-lg-3 col-form-label">Usługa <span class="text-danger" data-toggle="tooltip" title="Pole wymagane">*</span></label>
                                              <div class="col-lg-9 col-xl-9">
                                                <select class="form-control selectric" name="voucherService">
                                                    <option disabled selected>Wybierz usługę</option>
                                                    <?php foreach ($services as $service) { ?>
                                                        <option value="<?php echo $service['id']; ?>">Usługa <?php echo $service['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <label class="col-xl-3 col-lg-3 col-form-label">Ilość voucherów <span class="text-danger" data-toggle="tooltip" title="Pole wymagane">*</span></label>
                                              <div class="col-lg-9 col-xl-9">
                                                <input type="number" class="form-control" name="voucherAmount" max="20">
                                                <small class="text-muted">Ilość voucherów (maks. 20)</small>
                                              </div>
                                            </div>
                                            <div class="d-flex justify-content-center">
                                              <button type="submit" id="btnAddSubmit" class="btn btn-primary btn-xs mt-4"><i class="bi bi-check"></i> Potwierdź i dodaj</button>
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