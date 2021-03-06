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
                <div class="col-sm-8 col-md-8 col-lg-4 mr-auto ml-auto">
                    <h3 class="divshop-section-title"><i class="fas fa-server"></i>&nbsp;Status serwerów</h3>
                    <div class="card">
                        <div class="card-body">
                            <div class="divshop-server">
                                <div class="divshop-server-info-container">
                                    <h4 class="divshop-server-name">Serwer <?php echo xss_clean($server['name']); ?></h4>
                                    <span class="divshop-server-ip-address badge badge-default divshop-copy-btn" id="divshop-copy<?php echo $server['id']; ?>" data-clipboard-text="<?php echo($server['showPort'] == 0) ? $server['ip'] : $server['ip'] . ':' . $server['port']; ?>" data-toggle="tooltip" title="Kliknij, aby skopiować IP" onclick="divshopCopyIP<?php echo $server['id']; ?>()"><?php echo($server['showPort'] == 0) ? $server['ip'] : $server['ip'] . ':' . $server['port']; ?></span>
                                    <script type="text/javascript">
                                        function divshopCopyIP<?php echo $server['id']; ?>() {
                                            new ClipboardJS('#divshop-copy<?php echo $server['id']; ?>');
                                            var button = document.getElementById('divshop-copy<?php echo $server['id']; ?>');
                                            button.innerHTML = "<i class='fas fa-check'></i> Skopiowano";
                                            setTimeout(function(){
                                                button.innerHTML = "<?php echo($server['showPort'] == 0) ? $server['ip'] : $server['ip'] . ':' . $server['port']; ?>";
                                            }, 5000);
                                        }
                                    </script>
                                </div>
                                <?php if(isset($server['status'])): ?>
                                    <div class="progress-container progress-success pt-3">
                                        <div class="progress divshop-players-bar">
                                            <div class="progress-bar divshop-players-bar progress-bar-danger" id="players-progress-box<?php echo $server['id']; ?>" role="progressbar" aria-valuenow="<?php echo $server['status']['percent']; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                                        </div>
                                    </div>
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
                                    <div class="progress-container progress-success pt-3">
                                        <div class="progress divshop-players-bar">
                                            <div class="progress-bar divshop-players-bar progress-bar-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                                        </div>
                                    </div>
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
                            </div>
                        </div>
                    </div>
                    <?php if($modules[5]['moduleName'] == "lastbuyers" && $modules[5]['moduleEnabled'] == 1): ?>
						<h3 class="divshop-section-title"><i class="fas fa-shopping-basket"></i>&nbsp;Ostatnie zakupy</h3>
						<div class="card">
							<div class="card-body divshop-avatars">
								<?php if(!$lastBuyers): ?>
									<h6 class="text-center"><i class="fas fa-exclamation-triangle"></i> Aktualnie nikt nic nie kupił na tym serwerze</h6>
								<?php else: ?>
									<?php foreach($lastBuyers as $lastBuyer): ?>
										<?php 
											$buyerInfo = array(
												'buyerName'      =>   "<span class='divshop-last-buy-info'>Kupujący: </span>" . xss_clean($lastBuyer['buyerName']) . "<br>",
												'purchased'      =>   "<span class='divshop-last-buy-info'>Usługa: </span>" . xss_clean($lastBuyer['service']) . "<br>",
												'server'         =>   "<span class='divshop-last-buy-info'>Serwer: </span>" . xss_clean($lastBuyer['server']) . "<br>",
												'purchaseDate'   =>   "<span class='divshop-last-buy-info'>Data zakupu: </span>" . formatDate($lastBuyer['date'])
											);
										?>
										<?php if($lastBuyer['status'] == "success"): ?>
											<img src="<?php echo 'https://mc-heads.net/avatar/' . xss_clean($lastBuyer['buyerName']) . '/50'; ?>" class="divshop-buyer-avatar" alt="<?php echo xss_clean($lastBuyer['buyerName']); ?>'s avatar" data-toggle="tooltip" data-html="true" title="<?php echo $buyerInfo['buyerName'] . $buyerInfo['purchased'] . $buyerInfo['server'] . $buyerInfo['purchaseDate']; ?>">
										<?php endif; ?>
									<?php endforeach; ?>
								<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>
                </div>
                <div class="col-md-12 col-lg-8 ml-auto mr-auto <?php echo($settings['pageSidebarPosition'] == 0) ? 'order-first order-lg-last' : 'order-first order-lg-first'; ?>">
                    <div class="row">
                        <div class="col-sm-12 col-md-6 text-center text-md-left">
                            <h3 class="divshop-section-title text-center text-md-left"><i class="fas fa-shopping-basket"></i>&nbsp;Sklep serwera <b><?php echo $server['name']; ?></b></h3>
                        </div>
                        <?php if($modules[4]['moduleName'] == "vouchers" && $modules[4]['moduleEnabled'] == 1): ?>
                            <div class="col-sm-12 col-md-6 text-center text-md-right pt-md-3">
                                <a href="<?php echo $this->config->base_url('voucher'); ?>" class="btn btn-divshop btn-sm"><i class="fas fa-key"></i>&nbsp;Realizuj voucher</a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if(!$services): ?>
                        <h6 class="text-center"><i class="fas fa-exclamation-triangle"></i> Aktualnie nie ma żadnych usług do wyświetlenia</h6>
                    <?php else: ?>
                        <?php foreach($services as $service): ?>
                            <?php if($service['serviceActive'] != 0): ?>
                                <div class="col-md-12">
                                    <div class="card divshop-service">
                                        <?php if($service['image'] != null): ?>
                                            <div class="divshop-service-image" style="background: url('<?php echo $service['image']; ?>');">
                                                <img class="divshop-service-image-medium" src="<?php echo $service['image']; ?>" alt="<?php echo xss_clean($service['name']); ?>">
                                            </div>
                                        <?php endif; ?>
                                        <div class="divshop-service-content" <?php echo($service['image'] == null) ? 'style="flex:0 0 100%;max-width:100%;"' : ''; ?>>
                                            <h3 class="card-title divshop-service-title"><?php echo character_limiter(xss_clean($service['name']), 40); ?></h3>
                                            <p class="card-description divshop-service-description">
                                                <?php echo character_limiter($service['description'], 170); ?>
                                            </p>
                                            <div class="divshop-service-footer">
                                                <ul class="divshop-service-prices">
                                                    <?php if($service['smsConfig'] != null && $settings['smsOperator'] != 0): ?>
                                                        <li class="divshop-service-price">
                                                            <span class="divshop-service-cost"><?php echo getBruttoPrice($service['smsConfig']['smsNumber'], $service['smsConfig']['operator']); ?> zł</span>
                                                            <span class="divshop-service-pay-method">SMS</span>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if($service['paypalCost'] && $payments['paypal']['address'] != null): ?>
                                                        <li class="divshop-service-price">
                                                            <span class="divshop-service-cost"><?php echo number_format($service['paypalCost'], 2, ',', ' '); ?> zł</span>
                                                            <span class="divshop-service-pay-method">PayPal</span>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if($service['transferCost'] != null && ($payments['transfer']['shopid'] && $payments['transfer']['userid'] && $payments['transfer']['hash']) != null): ?>
                                                        <li class="divshop-service-price">
                                                            <span class="divshop-service-cost"><?php echo number_format($service['transferCost'], 2, ',', ' '); ?> zł</span>
                                                            <span class="divshop-service-pay-method">Przelew</span>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                                <a href="<?php echo $this->config->base_url('shop/' . $server['id'] . '-' . getShopUrl($server['name']) . '/' . 'service/' . $service['id'] . '-' . getServiceUrl($service['name'])); ?>" class="btn btn-success btn-link btn-link-custom btn-sm">Wybierz</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('components/Footer'); ?>