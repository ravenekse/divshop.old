<body id="app-page-break">
    <div class="page-break" id="break">
        <?php if ($pageBreak['title'] != null) { ?>
            <h1 class="page-break-shop-name"><?php echo $pageBreak['title']; ?></h1>
        <?php } ?>
        <?php if ($pageBreak['description'] != null) { ?>
            <p class="page-break-shop-description">
                <?php echo $pageBreak['description']; ?>
            </p>
        <?php } ?>
        <?php if ($pageBreak['title'] == null && $pageBreak['description'] == null) { ?>
            <h1 class="page-break-shop-name">Przerwa techniczna</h1>
        <?php } ?>
    </div>