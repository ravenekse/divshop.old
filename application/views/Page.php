<body>
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
    
    <div class="main main-raised" style="padding-bottom: 60px;padding-top: 60px;">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-lg-10 mr-auto ml-auto">
                    <?php echo $page['content']; ?>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('components/Footer'); ?>