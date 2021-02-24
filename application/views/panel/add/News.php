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
            <h1>Nowy news</h1>
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
                                <?php echo($settings['demoMode'] == 0) ? form_open_multipart(base_url('panel/add/news/create')) : '<form action="" onsubmit="event.preventDefault(); demoShowAlert()">'; ?>
                                    <div class="row justify-content-center">
                                        <div class="col-xl-12">
                                            <div class="form-group row">
                                              <label class="col-xl-3 col-lg-3 col-form-label">Obrazek newsa</label>
                                              <div class="col-lg-9 col-xl-8 col-md-12">
                                                  <div class="custom-file">
                                                    <label class="custom-file-label" for="customFile">Wybierz plik</label>
                                                    <input type="file" class="custom-file-input" id="customFile" name="newsImage" accept=".png, .jpg, .jpeg, .gif">
                                                  </div>
                                                  <small id="removeImage" style="cursor:pointer;display:none;"><b>Usuń zdjęcie</b></small>
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <label class="col-xl-3 col-lg-3 col-form-label">Tytuł newsa <span class="text-danger" data-toggle="tooltip" title="Pole wymagane">*</span></label>
                                              <div class="col-lg-9 col-xl-9">
                                                <input type="text" class="form-control" name="newsTitle">
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <label class="col-xl-3 col-lg-3 col-form-label">Treść newsa <span class="text-danger" data-toggle="tooltip" title="Pole wymagane">*</span></label>
                                              <div class="col-lg-12 col-xl-12">
                                                <textarea name="newsContent" class="summernote"></textarea>
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Czy pokazywać newsa</label>
                                                <div class="col-3">
                                                    <label class="custom-switch mt-2">
                                                        <label for="newsActive" class="mr-2 mt-1">Off</label>
                                                        <input type="checkbox" name="newsActive" class="custom-switch-input">
                                                        <span class="custom-switch-indicator"></span>
                                                        <label for="newsActive" class="ml-2 mt-1">On</label>
                                                    </label>
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