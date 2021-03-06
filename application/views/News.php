<body>
    <?php $this->load->view('components/Navbar'); ?>
    <div class="page-header header-filter header-small divshop-bg-header" data-parallax="true"
        <?php if($news['image'] != null): 
                echo 'style="background-image: url(' . $news['image'] . ') !important;"';
            elseif($news['image'] == null && $settings['pageBackground'] != null):
                echo 'style="background-image: url(' . $settings['pageBackground'] .') !important;"';
            elseif($news['image'] == null && $settings['pageBackground'] == null):
                echo 'style="background-image: url(https://cdn-n.divshop.pro/images/header.png) !important;"';
            endif;?>>
        <div class="container">
            <div class="row">
                <div class="col-md-8 ml-auto mr-auto">
                    <div class="brand text-center divshop-header-shop-title">
                        <h1><?php echo $news['title']; ?></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="main main-raised" style="padding-bottom: 60px;padding-top: 60px;">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-lg-10 mr-auto ml-auto">
                    <?php echo $news['content']; ?>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('components/Footer'); ?>