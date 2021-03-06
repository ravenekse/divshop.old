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
            <h1>Nowa strona</h1>
          </div>
          <?php if(file_exists(APPPATH . 'views/panel/components/TopAlerts.php')):
            $this->load->view('panel/components/TopAlerts');
          else:
            die('File <b>views/panel/components/TopAlerts.php</b> missing!');
          endif ?>
          <div class="row">
            <div class="col-md-10 col-sm-12 mr-auto ml-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center py-8 px-8 py-lg-15 px-lg-10">
                            <div class="col-xl-12 col-xxl-10">
                                <?php echo($settings['demoMode'] == 0) ? form_open_multipart(base_url('panel/add/page/create')) : '<form action="" onsubmit="event.preventDefault(); demoShowAlert()">'; ?>
                                    <div class="row justify-content-center">
                                        <div class="col-xl-12">
                                            <div class="form-group row">
                                              <label class="col-xl-3 col-lg-3 col-form-label">Nazwa strony <span class="text-danger" data-toggle="tooltip" title="Pole wymagane">*</span></label>
                                              <div class="col-lg-9 col-xl-9">
                                                <input type="text" class="form-control" name="pageTitle">
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <label class="col-xl-3 col-lg-3 col-form-label">Odno??nik</label>
                                              <div class="col-lg-9 col-xl-9">
                                                <input type="url" class="form-control" name="pageLink">
                                                <small>Umie???? tu link, na kt??ry ma przekierowywa?? przycisk w nawigacji. Je??li to pole zostanie puste wy??wietli si?? zawarto???? pola "Zawarto???? strony"</small>
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <label class="col-xl-3 col-lg-3 col-form-label">Ikona</label>
                                              <div class="col-lg-9 col-xl-9">
                                                <input type="text" class="form-control" name="pageIcon">
                                                <small>
                                                    Tutaj mo??esz wpisa?? klas?? ikony FontAwesome lub Bootstrap Icons. B??dzie si?? ona wy??wietla?? obok nazwy strony w nawigacji. Przyk??ady:
                                                    <br>
                                                    <b>fas fa-address-book</b> - FontAwesome 5
                                                    <br>
                                                    <b>bi bi-bookmarks</b> - Bootstrap Icons
                                                    <br>
                                                    Pe??ne listy ikon mo??na znale???? tutaj: <a href="https://fontawesome.com/icons?d=gallery" target="_blank">Ikony FontAwesome 5</a> lub <a href="https://icons.getbootstrap.com/" target="_blank">Ikony Bootstrap Icons</a>
                                                </small>
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <label class="col-xl-3 col-lg-3 col-form-label">Zawarto???? strony</label>
                                              <div class="col-lg-12 col-xl-12">
                                                <textarea name="pageContent" class="summernote"></textarea>
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Czy strona w????czona</label>
                                                <div class="col-3">
                                                    <label class="custom-switch mt-2">
                                                        <label for="pageActive" class="mr-2 mt-1">Off</label>
                                                        <input type="checkbox" name="pageActive" class="custom-switch-input">
                                                        <span class="custom-switch-indicator"></span>
                                                        <label for="pageActive" class="ml-2 mt-1">On</label>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-center">
                                                <button type="submit" id="btnAddSubmit" class="btn btn-primary btn-xs mt-4"><i class="bi bi-check"></i> Potwierd?? i dodaj</button>
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