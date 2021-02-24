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
      
    <div class="main main-raised" style="padding-bottom: 120px;">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="row">
                        <?php if(!$servers): ?>
                            <div class="mr-auto ml-auto mt-5">
                                <h6 class="text-center"><i class="fas fa-exclamation-triangle"></i> Aktualnie nie ma żadnych serwerów do wyświetlenia</h6>
                            </div>
                        <?php else: ?>
                            <?php foreach($servers as $server): ?>
                                <div class="col-md-4 mr-auto ml-auto">
                                    <div class="card">
                                        <div class="divshop-server-image" style="background: url('<?php echo $server['image']; ?>');"></div>
                                        <div class="card-body">
                                            <div class="text-center">
                                                <h4 class="title mt-2 mb-5">Serwer <?php echo $server['name']; ?></h4>
                                                <?php if(isset($server['status'])): ?>
                                                    <div class="divshop-server-status-container">
                                                        <span id="checking-status-box<?php echo $server['id']; ?>">
                                                            <span class="badge badge-info">
                                                                <span class="connecting-box">
                                                                    <i class="fas fa-spinner fa-spin"></i>&nbsp;
                                                                    Łączenie
                                                                </span>
                                                            </span>
                                                        </span>
                                                        <span class="badge badge-success status-checking" id="status-box<?php echo $server['id']; ?>"><i class="fas fa-check"></i> Online</span>
                                                        <span class="badge badge-info status-checking" id="players-box<?php echo $server['id']; ?>"><?php echo $server['status']['onlinePlayers'] . '/' . $server['status']['maxPlayers']; ?></span>
                                                        <?php if($server['serverVersion'] == null): ?>
                                                            <span class="badge badge-info status-checking" id="version-box<?php echo $server['id']; ?>"><?php echo $server['status']['version']; ?></span>
                                                        <?php else: ?>
                                                            <span class="badge badge-info status-checking" id="version-box<?php echo $server['id']; ?>"><?php echo $server['serverVersion']; ?></span>
                                                        <?php endif; ?>
                                                        <div class="progress-container progress-success pt-3">
                                                            <div class="progress divshop-players-bar">
                                                                <div class="progress-bar divshop-players-bar progress-bar-danger" id="players-progress-box<?php echo $server['id']; ?>" role="progressbar" aria-valuenow="<?php echo $server['status']['percent']; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                                                            </div>
                                                        </div>
                                                        <script type="text/javascript">
                                                            function checkConnection() {
                                                                var checking = document.getElementById('checking-status-box<?php echo $server['id']; ?>');
                                                                var status = document.getElementById('status-box<?php echo $server['id']; ?>');
                                                                var version = document.getElementById('version-box<?php echo $server['id']; ?>');
                                                                var players = document.getElementById('players-box<?php echo $server['id']; ?>');
                                                                var playersprogress = document.getElementById('players-progress-box<?php echo $server['id']; ?>');
                                                                setTimeout(function(){
                                                                    checking.classList.add('status-checked');
                                                                    status.classList.remove('status-checking');
                                                                    version.classList.remove('status-checking');
                                                                    players.classList.remove('status-checking');
                                                                    playersprogress.classList.remove('progress-bar-danger');
                                                                    playersprogress.classList.add('progress-bar-success');
                                                                    playersprogress.style.width = '<?php echo $server['status']['percent']; ?>%';
                                                                }, 2000);
                                                            }
                                                            checkConnection();
                                                        </script>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="divshop-server-status-container">
                                                        <span id="checking-status-box<?php echo $server['id']; ?>">
                                                            <span class="badge badge-info">
                                                                <span class="connecting-box">
                                                                    <i class="fas fa-spinner fa-spin"></i>&nbsp;
                                                                    Łączenie
                                                                </span>
                                                            </span>
                                                        </span>
                                                        <span class="badge badge-danger status-checking" id="status-box<?php echo $server['id']; ?>"><i class="fas fa-times"></i> Offline</span>
                                                        <span class="badge badge-info status-checking" id="players-box<?php echo $server['id']; ?>">0/0</span>
                                                        <div class="progress-container progress-success pt-3">
                                                            <div class="progress divshop-players-bar">
                                                                <div class="progress-bar divshop-players-bar progress-bar-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                                                            </div>
                                                        </div>
                                                        <script type="text/javascript">
                                                            function checkConnection() {
                                                                var checking = document.getElementById('checking-status-box<?php echo $server['id']; ?>');
                                                                var status = document.getElementById('status-box<?php echo $server['id']; ?>');
                                                                var players = document.getElementById('players-box<?php echo $server['id']; ?>');
                                                                setTimeout(function(){
                                                                    checking.classList.add('status-checked');
                                                                    status.classList.remove('status-checking');
                                                                    players.classList.remove('status-checking');
                                                                }, 2000);
                                                            }
                                                            checkConnection();
                                                        </script>
                                                    </div>
                                                <?php endif; ?>
                                                <a href="<?php echo $this->config->base_url('shop/' . $server['id'] . '-' . getShopUrl($server['name'])); ?>" class="btn btn-success btn-link btn-link-custom btn-sm">Przejdź do sklepu</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('components/Footer'); ?>