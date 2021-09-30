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
            <h1>Nowy administrator</h1>
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
                                <?php echo($settings['demoMode'] == 0) ? form_open_multipart(base_url('panel/add/admin/create')) : '<form action="" onsubmit="event.preventDefault(); demoShowAlert()">'; ?>
                                    <div class="row justify-content-center">
                                        <div class="col-xl-8">
                                            <div class="form-group row">
                                              <label class="col-xl-3 col-lg-3 col-form-label">Avatar</label>
                                              <div class="col-lg-9 col-xl-8 col-md-12">
                                                <div class="avatar-upload">
                                                  <div class="avatar-edit">
                                                    <input type="file" id="imageUpload" name="adminImage" accept=".png, .jpg, .jpeg, .gif">
                                                    <label for="imageUpload"></label>
                                                  </div>
                                                  <div class="avatar-preview">
                                                    <div id="imagePreview" style="background-image: url(<?php echo $this->config->base_url('assets/images/default_avatar.png'); ?>);">
                                                    </div>
                                                  </div>
                                                </div>
                                                <small id="removeImage" style="cursor:pointer;display:none;"><b>Usuń zdjęcie</b></small>
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <label class="col-xl-3 col-lg-3 col-form-label">Nazwa administratora <span class="text-danger" data-toggle="tooltip" title="Pole wymagane">*</span> </label>
                                              <div class="col-lg-9 col-xl-9">
                                                <input type="text" class="form-control" name="adminName">
                                                <small class="text-muted">Nazwa administratora używana przy logowaniu do ACP</small>
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <label class="col-xl-3 col-lg-3 col-form-label">Adres e-mail administratora</label>
                                              <div class="col-lg-9 col-xl-9">
                                                <input type="text" class="form-control" name="adminEmail">
                                                <small class="text-muted">Adres e-mail wykorzystywany do celów administracyjnych</small>
                                              </div>
                                            </div>
                                            <p class="text-muted font-size-12" style="line-height:21px;">
                                              <b>UWAGA!</b> Po kliknięciu przyciski <b>Potwierdź i dodaj</b> hasło do nowego konta zostanie wygenerowane automatycznie. Pokaże się ono również automatycznie, ale zniknie po kolejnym przeładowaniu strony. Pamiętaj że nowe hasło należy jak najszybciej przekazać nowemu administratorowi.
                                            </p>
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