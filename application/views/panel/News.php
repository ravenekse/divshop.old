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
            <h1>Aktualności</h1>
          </div>
          <?php if(file_exists(APPPATH . 'views/panel/components/TopAlerts.php')):
            $this->load->view('panel/components/TopAlerts');
          else:
            die('File <b>views/panel/components/TopAlerts.php</b> missing!');
          endif ?>
          <div class="row">
            <div class="col-md-10 col-sm-12 mr-auto ml-auto">
                <div class="card">
                    <div class="card-header justify-content-center d-md-block d-lg-flex text-center">
                        <h4><i class="bi bi-newspaper"></i> Lista aktualności</h4>
                        <div class="ml-md-auto">
                            <a href="<?php echo $this->config->base_url('panel/add/news'); ?>" class="btn btn-success btn-sm btn-icon icon-left">
                                <i class="bi bi-plus-circle"></i> Dodaj newsa
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if(!$news): ?>
                            <h4 class="divshop-no-data"><i class="bi bi-exclamation-circle"></i> Aktualnie nie ma żadnych aktualności do wyświetlenia!</h4>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table text-center table-md d-md-table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">Tytuł</th>
                                            <th class="text-center">Obrazek</th>
                                            <th class="text-center">Treść</th>
                                            <th class="text-center">News widoczny?</th>
                                            <th class="text-center">Podgląd</th>
                                            <th class="text-center">Akcje</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($news as $news): ?>
                                            <tr>
                                              <td class="pt-3">#<?php echo $news['id']; ?></td>
                                              <td class="pt-3"><?php echo $news['title']; ?></td>
                                              <td class="pt-3">
                                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#divsNewsImageID<?php echo $news['id']; ?>">
                                                  <i class="fas fa-eye"></i>
                                                </button>
                                              </td>
                                              <td class="pt-3">
                                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#divsNewsContentID<?php echo $news['id']; ?>">
                                                  <i class="fas fa-eye"></i>
                                                </button>
                                              </td>
                                              <td class="pt-3"><?php echo($news['newsActive'] == 0) ? 'Nie' : 'Tak'; ?></td>
                                              <td class="pt-3">
                                                <a class="btn btn-primary btn-sm" href="<?php echo $this->config->base_url('news/' . $news['id'] . '-' . getNewsUrl($news['title'])); ?>" target="_blank">
                                                  <i class="fas fa-external-link-alt"></i>
                                                </a>
                                              </td>
                                              <td class="d-flex pt-3">
                                                <?php echo form_open(base_url('panel/edit/news')); ?>
                                                  <input type="hidden" name="newsId" value="<?php echo $news['id'] ?>">
                                                  <button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" title="Edytuj newsa">
                                                    <i class="bi bi-pencil"></i>
                                                  </button>
                                                <?php echo form_close(); ?>
                                                <?php echo form_open(base_url('panel/remove/news')); ?>
                                                  <input type="hidden" name="newsId" value="<?php echo $news['id'] ?>">
                                                  <button type="button" class="btn btn-danger btn-sm ml-1" data-toggle="tooltip" title="Usuń newsa" onclick="areYouSure(this);">
                                                    <i class="bi bi-trash"></i>
                                                  </button>
                                                <?php echo form_close(); ?>
                                              </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php echo $pagination; ?>
                        <?php endif; ?>
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