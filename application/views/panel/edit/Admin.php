<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      <?php 
        if(file_exists(APPPATH . 'views/panel/components/Sidebar.php') && file_exists(APPPATH . 'views/panel/components/Navbar.php')):
          $this->load->view('panel/components/Navbar');
          $this->load->view('panel/components/Sidebar');
        elseif(! file_exists(APPPATH . 'views/panel/components/Navbar.php')):
          die('File <b>views/panel/components/Navbar.php</b> missing!');
        elseif(!file_exists(APPPATH . 'views/panel/components/Sidebar.php')):
          die('File <b>views/panel/components/Sidebar.php</b> missing!');
        endif; 
      ?>
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Edycja administratora <?php echo $admin['name'] ?></h1>
          </div>
          <?php if(file_exists(APPPATH . 'views/panel/components/TopAlerts.php')):
            $this->load->view('panel/components/TopAlerts');
          else:
            die('File <b>views/panel/components/TopAlerts.php</b> missing!');
          endif ?>
          <div class="row">
            <div class="col-md-10 col-sm-12 mr-auto ml-auto">
                <div class="card">
                    <div class="card-header">
                        <nav class="w-100">
                            <div class="nav nav-tabs d-flex justify-content-center" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active" id="divsAccountMainSettings-tab" data-toggle="tab" href="#divsAccountMainSettings" role="tab" aria-controls="divsAccountMainSettings" aria-selected="true">Główne ustawienia konta</a>
                                <a class="nav-item nav-link" id="divsAccountPasswordSettings-tab" data-toggle="tab" href="#divsAccountPasswordSettings" role="tab" aria-controls="divsAccountPasswordSettings">Zmiana hasła</a>
                            </div>
                        </nav>
                    </div>
                    <div class="card-body">
                        <?php echo($settings['demoMode'] == 0) ? form_open_multipart(base_url('panel/edit/admin/save')) : '<form action="" onsubmit="event.preventDefault(); demoShowAlert()">'; ?>
                            <input type="hidden" name="adminId" value="<?php echo $admin['id']; ?>">
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="divsAccountMainSettings" role="tabpanel" aria-labelledby="divsAccountMainSettings-tab">
                                    <div class="row justify-content-center py-8 px-8 py-lg-15 px-lg-10">
                                        <div class="col-xl-12 col-xxl-10">
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
                                                                    <div id="imagePreview" style="background-image: url(<?php echo($admin['image'] == null) ? $this->config->base_url('assets/images/default_avatar.png') : $admin['image']; ?>);">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <small id="removeImage" style="cursor:pointer;display:none;"><b>Usuń zdjęcie</b></small>
                                                            <br>
                                                            <small class="mt-2">Aby nie zmieniać obrazka, zostaw powyższe pole bez zmian</small>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Nazwa administratora <span class="text-danger" data-toggle="tooltip" title="Pole wymagane">*</span> </label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <input type="text" class="form-control" name="adminName" value="<?php echo $admin['name']; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Adres e-mail administratora</label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <input type="text" class="form-control" name="adminEmail" value="<?php echo $admin['email']; ?>">
                                                        </div>
                                                    </div> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="divsAccountPasswordSettings" role="tabpanel" aria-labelledby="divsAccountPasswordSettings-tab">
                                    <div class="row justify-content-center py-8 px-8 py-lg-15 px-lg-10">
                                        <div class="col-xl-12 col-xxl-10">
                                            <div class="row justify-content-center">
                                                <div class="col-xl-8">
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Nowe hasło <span class="text-danger" data-toggle="tooltip" title="Pole wymagane">*</span> </label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <input type="password" class="form-control" name="adminPassNew1">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label">Potwierdź nowe hasło <span class="text-danger" data-toggle="tooltip" title="Pole wymagane">*</span> </label>
                                                        <div class="col-lg-9 col-xl-9">
                                                            <input type="password" class="form-control" name="adminPassNew2">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <button type="submit" id="btnEditSubmit" class="btn btn-primary btn-xs mt-4"><i class="bi bi-check"></i> Potwierdź i zapisz</button>
                                </div>
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