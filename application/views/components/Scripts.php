<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 * @link   https://divshop.pro
**/

defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!--  Scripts everywhere  -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="<?php echo $this->config->base_url('assets/js/core/popper.min.js'); ?>"></script>
<script src="https://cdn-n.divshop.pro/js/libraries/notiflix.min.js"></script>
<script src="<?php echo $this->config->base_url('assets/js/plugins/charactercounter.js'); ?>"></script>
<script type="text/javascript">
    Notiflix.Notify.Init({width:"320px",cssAnimationStyle:"from-top"}); 
    function previousPage() {
        window.history.back();
    }
    function sleep(e) {
        return new Promise((o) => setTimeout(o, e));
    }
    function demoShowAlert() {
        sleep(2600).then(() => (Notiflix.Report.Failure("Oops, coś poszło nie tak :(", "Ta czynność jest zablokowana w wersji demonstracyjnej sklepu", "Rozumiem"), !1));
    }
</script>
<?php if($this->uri->rsegment('1') == "home" or $this->uri->segment('1') == "news" or $this->uri->rsegment('1') == "shop" or $this->uri->rsegment('1') == "service" or $this->uri->rsegment('1') == "payments" or $this->uri->rsegment('1') == "antybot" or $this->uri->rsegment('1') == "stats" or $this->uri->rsegment('1') == "bans" or $this->uri->rsegment('1') == "page" or $this->uri->rsegment('1') == "voucher"): ?>
    <?php if($this->uri->rsegment('1') == "antybot"): ?>
        <script src="https://www.google.com/recaptcha/api.js"></script>
    <?php endif; ?>
    <script src="<?php echo $this->config->base_url('assets/js/core/bootstrap-material-design.min.js'); ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.4/clipboard.min.js"></script>
    <script src="<?php echo $this->config->base_url('assets/js/material-kit.min.js'); ?>"></script>
    <?php if($settings['pagePreloader'] == 1): ?>
        <script type="text/javascript">
            $(document).ready(function() {
                setTimeout(function(){
                    $('body').addClass('loaded');
                }, 500);
            });
        </script>
    <?php endif; ?>
    <script type="text/javascript">
        <?php if($this->uri->rsegment('1') == "service"): ?>
            $('.btnBuyService').click(function() {
                var btn = $(this);
                $(btn).html('<i class="fas fa-spinner fa-spin"></i>&nbsp;&nbsp;Proszę czekać...');
                setTimeout(function() {
                    $(btn).html('<i class="fas fa-check"></i>&nbsp;&nbsp;Zakup usługę');
                }, 100000)
            });
            console.log('Pamiętaj! Jeżeli edytujesz to miejsce przez zbadaj element, twoja płatność może zostać zrealizowana nieprawidłowo lub nie zostać zrealizowana w ogóle.');
        <?php endif; ?>
        <?php if($this->uri->rsegment('1') == "payments" && $this->uri->rsegment('2') == "paypal"): ?>
            window.onload = function() {
                setTimeout(function() {
                    document.getElementById('paypalPayment').submit();
                }, 1000);
            }
        <?php endif; ?>
        <?php if($this->uri->rsegment('1') == "stats" or $this->uri->rsegment('1') == "bans"): ?>
            $('.divshop-table-element').hover(function(e) {
                element = $(e.target);
                if(element != this) {
                    element = $(e.target).closest('.divshop-table-element');
                }
                $('.divshop-table-element').css('filter', 'grayscale(100%)');
                $('.divshop-table-element').css('opacity', '0.5');
                element.css('filter', 'grayscale(0%)');
                element.css('opacity', '1');
            }, function(e) {
                $('.divshop-table-element').css('filter', 'grayscale(0%)');
                $('.divshop-table-element').css('opacity', '1');
            });
        <?php endif; ?>
        <?php if($this->uri->rsegment('1') == "service"): ?>
            $(document).ready(function() {
                $('#smsCode').characterCounter();
            });
        <?php endif; ?>
        <?php if($this->uri->rsegment('1') == "voucher"): ?>
            $(document).ready(function() {
                $('#voucherCode').characterCounter();
            });
        <?php endif; ?>
    </script>
<?php endif; ?>
<?php if($this->uri->rsegment('1') == "admin" && $this->uri->rsegment('2') == "auth"): ?>
    <!--  Scripts for Admin login  -->
    <script src="<?php echo $this->config->base_url('assets/panel/modules/bootstrap/js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo $this->config->base_url('assets/panel/js/stisla.js'); ?>"></script>
    <script src="<?php echo $this->config->base_url('assets/panel/js/scripts.js'); ?>"></script>
    <script type="text/javascript">
        $('#btnAuthLogin').click(function() {
            var btn = $(this);
            $(btn).addClass('btn-progress');
            setTimeout(function() {
                $(btn).removeClass('btn-progress');
            }, 100000)
        });
    </script>
<?php endif; ?>
<?php if($this->uri->segment('1') == "panel"): ?>
    <!--  Scripts for ACP  -->
    <script src="<?php echo $this->config->base_url('assets/panel/modules/tooltip.js'); ?>"></script>
    <script src="<?php echo $this->config->base_url('assets/panel/modules/bootstrap/js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo $this->config->base_url('assets/panel/modules/nicescroll/jquery.nicescroll.min.js'); ?>"></script>
    <script src="<?php echo $this->config->base_url('assets/panel/modules/chart.min.js'); ?>"></script>
    <script src="<?php echo $this->config->base_url('assets/panel/modules/summernote/summernote-bs4.js'); ?>"></script>
    <script src="<?php echo $this->config->base_url('assets/panel/js/stisla.js'); ?>"></script>
    <script src="<?php echo $this->config->base_url('assets/panel/js/scripts.js'); ?>"></script>
    <script type="text/javascript">
        $('body').toggleClass(localStorage.theme);
        function changeTheme() {
            if(localStorage.theme != 'dark-theme') {
                $('body').toggleClass('dark-theme', true);
                localStorage.theme = 'dark-theme';
            } else {
                $('body').toggleClass('dark-theme', false);
                localStorage.theme = '';
            }
        }
        if($('body').hasClass('dark-theme')) {
            $('#btnChangeTheme').prop('checked', true);
        } else {
            $('#btnChangeTheme').prop('checked', false);
        }
    </script>
<?php endif; ?>
<?php if($this->uri->rsegment('1') == "dashboard"): ?>
    <script type="text/javascript">
        var ctx = document.getElementById("divshopPurchasesStatistics").getContext("2d");
        if($('body').hasClass('dark-theme')) {
            var drawBorderColor = '#21242c';
        } else {
            var drawBorderColor = '#f2f2f2';
        }
        divshopPurchasesStatistics = new Chart(ctx, {
            type: "line",
            data: {
                labels: ["Poniedziałek", "Wtorek", "Środa", "Czwartek", "Piątek", "Sobota", "Niedziela"],
                datasets: [{ label: "Zakupionych usług", data: [<?php echo $chartValues; ?>], borderWidth: 2, backgroundColor: "#6777ef", borderColor: "#6777ef", borderWidth: 2.5, pointBackgroundColor: "#ffffff", pointRadius: 4 }],
            },
            options: { legend: { display: !1 }, scales: { yAxes: [{ gridLines: { drawBorder: !1, color: drawBorderColor }, ticks: { beginAtZero: !0, stepSize: <?php echo $chartHighest; ?> } }], xAxes: [{ ticks: { display: !1 }, gridLines: { display: !1 } }] } },
        });
        $('#btnPanelNotesSave').click(function() {
            var btn = $(this);
            $(btn).addClass('btn-progress');
            setTimeout(function() {
                $(btn).removeClass('btn-progress');
            }, 100000)
        }); 
    </script>
<?php endif; ?>
<?php if($this->uri->segment('2') == "admins"): ?>
    <script type="text/javascript">
        function notToday() {
            sleep(200).then(() => (Notiflix.Notify.Failure("Nie możesz usunąć konta na które jesteś właśnie zalogowany(-a)"), !1));
        }
        function youCanNot() {
            sleep(200).then(() => (Notiflix.Notify.Failure("Ze względu na bezpieczeństwo nie możesz usunąć administratora z ID 1"), !1));
        }
        function areYouSure(admin = null) {
            Notiflix.Confirm.Init({ width:"600px",}); 
            Notiflix.Confirm.Show( 
                'Czy napewno chcesz to zrobić?',
                'Potwierdzenie tej operacji usunie administratora i wszystkie powiązane z nim rzeczy!',
                'Tak, usuwamy!',
                'Anuluj',
                function(){
                    <?php if($settings['demoMode'] == 0): ?>
                        if(admin != null) {
                            Notiflix.Loading.Circle('Trwa usuwanie administratora...');
                            Notiflix.Loading.Remove(3000);
                            setTimeout(function(){ admin.form.submit(); }, 2500);
                        }
                    <?php else: ?>
                        Notiflix.Loading.Circle('Trwa usuwanie administratora...');
                        Notiflix.Loading.Remove(2500);
                        sleep(2600).then(() => {
                            Notiflix.Report.Failure('Oops, coś poszło nie tak :(', 'Ta czynność jest zablokowana w wersji demonstracyjnej sklepu', 'Rozumiem');
                            return false;
                        });
                    <?php endif; ?>
                }
            ); 
        }
    </script>
<?php endif; ?>
<?php if($this->uri->segment('2') == "servers"): ?>
    <script type="text/javascript">
        <?php foreach($servers as $server): ?>
            function showRconPassword<?php echo $server['id']; ?>() {
                var divsRconPassInput = document.getElementById('divsServerRconPass<?php echo $server['id']; ?>');
                var divsPassIconShow = document.getElementById('showPassword<?php echo $server['id']; ?>');
                var divsPassIconHide = document.getElementById('hidePassword<?php echo $server['id']; ?>');
                if(divsRconPassInput.type === "password") {
                    divsRconPassInput.type = "text";
                    divsPassIconShow.classList.add('d-none');
                    divsPassIconHide.classList.remove('d-none');
                } else {
                    divsRconPassInput.type = "password";
                    divsPassIconShow.classList.remove('d-none');
                    divsPassIconHide.classList.add('d-none');
                }
            }
        <?php endforeach; ?>
        function areYouSure(server = null) {
            Notiflix.Confirm.Init({ width:"600px",}); 
            Notiflix.Confirm.Show( 
                'Czy napewno chcesz to zrobić?',
                'Potwierdzenie tej operacji usunie serwer i wszystkie powiązane z nim usługi, vouchery oraz historię zakupów!',
                'Tak, usuwamy!',
                'Anuluj',
                function(){
                    <?php if($settings['demoMode'] == 0): ?>
                        if(server != null) {
                            Notiflix.Loading.Circle('Trwa usuwanie serwera...');
                            Notiflix.Loading.Remove(3000);
                            setTimeout(function(){ server.form.submit(); }, 2500);
                        }
                    <?php else: ?>
                        Notiflix.Loading.Circle('Trwa usuwanie serwera...');
                        Notiflix.Loading.Remove(2500);
                        sleep(2600).then(() => {
                            Notiflix.Report.Failure('Oops, coś poszło nie tak :(', 'Ta czynność jest zablokowana w wersji demonstracyjnej sklepu', 'Rozumiem');
                            return false;
                        });
                    <?php endif; ?>
                }
            ); 
        }
    </script>
<?php endif; ?>
<?php if($this->uri->segment('2') == "services"): ?>
    <script type="text/javascript">
        function areYouSure(service = null) {
            Notiflix.Confirm.Init({ width:"600px",}); 
            Notiflix.Confirm.Show( 
                'Czy napewno chcesz to zrobić?',
                'Potwierdzenie tej operacji usunie usługę i wszystkie powiązane z nią vouchery oraz historię zakupów!',
                'Tak, usuwamy!',
                'Anuluj',
                function(){
                    <?php if($settings['demoMode'] == 0): ?>
                        if(service != null) {
                            Notiflix.Loading.Circle('Trwa usuwanie usługi...');
                            Notiflix.Loading.Remove(3000);
                            setTimeout(function(){ service.form.submit(); }, 2500);
                        }
                    <?php else: ?>
                        Notiflix.Loading.Circle('Trwa usuwanie usługi...');
                        Notiflix.Loading.Remove(2500);
                        sleep(2600).then(() => {
                            Notiflix.Report.Failure('Oops, coś poszło nie tak :(', 'Ta czynność jest zablokowana w wersji demonstracyjnej sklepu', 'Rozumiem');
                            return false;
                        });
                    <?php endif; ?>
                }
            ); 
        }
    </script>
<?php endif; ?>
<?php if($this->uri->segment('2') == "news"): ?>
    <script type="text/javascript">
        function areYouSure(news = null) {
            Notiflix.Confirm.Init({ width:"600px",}); 
            Notiflix.Confirm.Show( 
                'Czy napewno chcesz to zrobić?',
                'Potwierdzenie tej operacji usunie newsa i wszystkie powiązane z nim rzeczy!',
                'Tak, usuwamy!',
                'Anuluj',
                function(){
                    <?php if($settings['demoMode'] == 0): ?>
                        if(news != null) {
                            Notiflix.Loading.Circle('Trwa usuwanie newsa...');
                            Notiflix.Loading.Remove(3000);
                            setTimeout(function(){ news.form.submit(); }, 2500);
                        }
                    <?php else: ?>
                        Notiflix.Loading.Circle('Trwa usuwanie newsa...');
                        Notiflix.Loading.Remove(2500);
                        sleep(2600).then(() => {
                            Notiflix.Report.Failure('Oops, coś poszło nie tak :(', 'Ta czynność jest zablokowana w wersji demonstracyjnej sklepu', 'Rozumiem');
                            return false;
                        });
                    <?php endif; ?>
                }
            ); 
        }
    </script>
<?php endif; ?>
<?php if($this->uri->segment('2') == "pages"): ?>
    <script type="text/javascript">
        function areYouSure(page = null) {
            Notiflix.Confirm.Init({ width:"600px",}); 
            Notiflix.Confirm.Show( 
                'Czy napewno chcesz to zrobić?',
                'Potwierdzenie tej operacji usunie stronę i wszystkie powiązane z nią rzeczy!',
                'Tak, usuwamy!',
                'Anuluj',
                function(){
                    <?php if($settings['demoMode'] == 0): ?>
                        if(page != null) {
                            Notiflix.Loading.Circle('Trwa usuwanie strony...');
                            Notiflix.Loading.Remove(3000);
                            setTimeout(function(){ page.form.submit(); }, 2500);
                        }
                    <?php else: ?>
                        Notiflix.Loading.Circle('Trwa usuwanie strony...');
                        Notiflix.Loading.Remove(2500);
                        sleep(2600).then(() => {
                            Notiflix.Report.Failure('Oops, coś poszło nie tak :(', 'Ta czynność jest zablokowana w wersji demonstracyjnej sklepu', 'Rozumiem');
                            return false;
                        });
                    <?php endif; ?>
                }
            ); 
        }
    </script>
<?php endif; ?>
<?php if($this->uri->segment('2') == "vouchers"): ?>
    <script type="text/javascript">
        function areYouSure(voucher = null) {
            Notiflix.Confirm.Init({ width:"600px",}); 
            Notiflix.Confirm.Show( 
                'Czy napewno chcesz to zrobić?',
                'Potwierdzenie tej operacji usunie voucher i wszystkie powiązane z nim rzeczy!',
                'Tak, usuwamy!',
                'Anuluj',
                function(){
                    <?php if($settings['demoMode'] == 0): ?>
                        if(voucher != null) {
                            Notiflix.Loading.Circle('Trwa usuwanie vouchera...');
                            Notiflix.Loading.Remove(3000);
                            setTimeout(function(){ voucher.form.submit(); }, 2500);
                        }
                    <?php else: ?>
                        Notiflix.Loading.Circle('Trwa usuwanie vouchera...');
                        Notiflix.Loading.Remove(2500);
                        sleep(2600).then(() => {
                            Notiflix.Report.Failure('Oops, coś poszło nie tak :(', 'Ta czynność jest zablokowana w wersji demonstracyjnej sklepu', 'Rozumiem');
                            return false;
                        });
                    <?php endif; ?>
                }
            ); 
        }
    </script>
<?php endif; ?>
<?php if($this->uri->segment('2') == "settings" || $this->uri->segment('2') == "paysettings"): ?>
    <script type="text/javascript">
        function saveSettings(settings = null) {
            <?php if($settings['demoMode'] == 0): ?>
                if(settings != null) {
                    Notiflix.Loading.Circle('Trwa zapisywanie ustawień...');
                    Notiflix.Loading.Remove(3000);
                    setTimeout(function(){ settings.form.submit(); }, 2500);
                }
            <?php else: ?>
                Notiflix.Loading.Circle('Trwa zapisywanie ustawień...');
                Notiflix.Loading.Remove(2500);
                sleep(2600).then(() => {
                    Notiflix.Report.Failure('Oops, coś poszło nie tak :(', 'Ta czynność jest zablokowana w wersji demonstracyjnej sklepu', 'Rozumiem');
                    return false;
                });
            <?php endif; ?>
        }
    </script>
<?php endif; ?>
<?php if($this->uri->segment('2') == "modules"): ?>
    <script type="text/javascript">
        function changeModuleStatus(mod = null) {
            <?php if($settings['demoMode'] == 0): ?>
                if(mod != null) {
                    Notiflix.Loading.Circle('Trwa zmiana ustawień modułu...');
                    Notiflix.Loading.Remove(3000);
                    setTimeout(function(){ mod.form.submit(); }, 2500);
                }
            <?php else: ?>
                Notiflix.Loading.Circle('Trwa zmiana ustawień modułu...');
                Notiflix.Loading.Remove(2500);
                sleep(2600).then(() => {
                    Notiflix.Report.Failure('Oops, coś poszło nie tak :(', 'Ta czynność jest zablokowana w wersji demonstracyjnej sklepu', 'Rozumiem');
                    return false;
                });
            <?php endif; ?>
        }
    </script>
<?php endif; ?>

<?php if($this->uri->segment('2') == "add" or $this->uri->segment('2') == "account" or $this->uri->segment('2') == "settings" or $this->uri->segment('2') == "paysettings"): ?>
    <script type="text/javascript">
        $('#btnAddSubmit').click(function() {
            var btn = $(this);
            $(btn).addClass('btn-progress');
            setTimeout(function() {
                $(btn).removeClass('btn-progress');
            }, 100000)
        });

        <?php if($this->uri->segment('2') == "add" && $this->uri->segment('3') == "server"): ?>
            $('#customFile').change(function() {
                var i = $(this).prev('label').clone();
                var file = $('#customFile')[0].files[0].name;
                $(this).prev('label').text(file);
                $('#removeImage').fadeIn(250);
            });
            $('#removeImage').click(function() {
                $('#customFile').prev('label').text('Wybierz plik');
                $('#removeImage').hide();
                $('#customFile').val(null);
            });
            function useOtherVersion() {
                var e = $("#otherServerVersion");
                var t = $("#otherVersionField");
                t.hide(),
                e.change(function () {
                    e.is(":checked") ? t.show() : t.hide();
                });
            }
            function showRconPassword() {
                var divsRconPassInput = document.getElementById('divsServerRconPass');
                var divsPassIconShow = document.getElementById('showPassword');
                var divsPassIconHide = document.getElementById('hidePassword');
                if(divsRconPassInput.type === "password") {
                    divsRconPassInput.type = "text";
                    divsPassIconShow.classList.add('d-none');
                    divsPassIconHide.classList.remove('d-none');
                } else {
                    divsRconPassInput.type = "password";
                    divsPassIconShow.classList.remove('d-none');
                    divsPassIconHide.classList.add('d-none');
                }
            }
        <?php endif; ?>
        <?php if($this->uri->segment('2') == "add" && $this->uri->segment('3') == "admin"): ?>
            function readURL(input) {
                if(input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview').css('background-image', 'url('+e.target.result +')');
                        $('#imagePreview').hide();
                        $('#imagePreview').fadeIn(250);
                        $('#removeImage').fadeIn(250);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $("#imageUpload").change(function() {
                readURL(this);
            });
            $('#removeImage').click(function() {
                $('#imagePreview').css('background-image', 'url(<?php echo $this->config->base_url('assets/images/default_avatar.png'); ?>)');
                $('#imagePreview').hide();
                $('#imagePreview').fadeIn(250);
                $('#removeImage').hide();
                $('#imageUpload').val(null);
            });
        <?php endif; ?>
        <?php if($this->uri->segment('2') == "account"): ?>
            function readURL(input) {
                if(input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview').css('background-image', 'url('+e.target.result +')');
                        $('#imagePreview').hide();
                        $('#imagePreview').fadeIn(250);
                        $('#removeImage').fadeIn(250);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $("#imageUpload").change(function() {
                readURL(this);
            });
            $('#removeImage').click(function() {
                $('#imagePreview').css('background-image', 'url(<?php echo $admin['image']; ?>)');
                $('#imagePreview').hide();
                $('#imagePreview').fadeIn(250);
                $('#removeImage').hide();
                $('#imageUpload').val(null);
            });
        <?php endif; ?>
        <?php if($this->uri->segment('2') == "add" && $this->uri->segment('3') == "service"): ?>
            $('#customFile').change(function() {
                var i = $(this).prev('label').clone();
                var file = $('#customFile')[0].files[0].name;
                $(this).prev('label').text(file);
                $('#removeImage').fadeIn(250);
            });
            $('#removeImage').click(function() {
                $('#customFile').prev('label').text('Wybierz plik');
                $('#removeImage').hide();
                $('#customFile').val(null);
            });
        <?php endif; ?>
        <?php if($this->uri->segment('2') == "add" && $this->uri->segment('3') == "news"): ?>
            $('#customFile').change(function() {
                var i = $(this).prev('label').clone();
                var file = $('#customFile')[0].files[0].name;
                $(this).prev('label').text(file);
                $('#removeImage').fadeIn(250);
            });
            $('#removeImage').click(function() {
                $('#customFile').prev('label').text('Wybierz plik');
                $('#removeImage').hide();
                $('#customFile').val(null);
            });
        <?php endif; ?>
        <?php if($this->uri->segment('2') == "settings"): ?>
            $('#customFile').change(function() {
                var i = $(this).prev('label').clone();
                var file = $('#customFile')[0].files[0].name;
                $(this).prev('label').text(file);
                $('#removeLogo').fadeIn(250);
            });
            $('#removeLogo').click(function() {
                $('#customFile').prev('label').text('Wybierz plik');
                $('#removeLogo').hide();
                $('#customFile').val(null);
            });
            function readURL(input) {
                if(input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview').css('background-image', 'url('+e.target.result +')');
                        $('#imagePreview').hide();
                        $('#imagePreview').fadeIn(250);
                        $('#removeImage').fadeIn(250);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $("#imageUpload").change(function() {
                readURL(this);
            });
            $('#removeImage').click(function() {
                $('#imagePreview').css('background-image', 'url(<?php echo $this->config->base_url('assets/images/icon-undefined.png'); ?>)');
                $('#imagePreview').hide();
                $('#imagePreview').fadeIn(250);
                $('#removeImage').hide();
                $('#imageUpload').val(null);
            });
            function showRecaptchaSecret() {
                var divsRecaptchaSecretInput = document.getElementById('divsRecaptchaSecret');
                var divsRecaptchaSecretIconShow = document.getElementById('showSecret');
                var divsRecaptchaSecretIconHide = document.getElementById('hideSecret');
                if(divsRecaptchaSecretInput.type === "password") {
                    divsRecaptchaSecretInput.type = "text";
                    divsRecaptchaSecretIconShow.classList.add('d-none');
                    divsRecaptchaSecretIconHide.classList.remove('d-none');
                } else {
                    divsRecaptchaSecretInput.type = "password";
                    divsRecaptchaSecretIconShow.classList.remove('d-none');
                    divsRecaptchaSecretIconHide.classList.add('d-none');
                }
            }
        <?php endif; ?>
        <?php if($this->uri->segment('2') == "paysettings"): ?>
            function showTransferHash() {
                var divsPaymentTransferHash = document.getElementById('divsPaymentTransferHash');
                var divsPaymentTransferHashIconShow = document.getElementById('showHash');
                var divsPaymentTransferHashIconHide = document.getElementById('hideHash');
                if(divsPaymentTransferHash.type === "password") {
                    divsPaymentTransferHash.type = "text";
                    divsPaymentTransferHashIconShow.classList.add('d-none');
                    divsPaymentTransferHashIconHide.classList.remove('d-none');
                } else {
                    divsPaymentTransferHash.type = "password";
                    divsPaymentTransferHashIconShow.classList.remove('d-none');
                    divsPaymentTransferHashIconHide.classList.add('d-none');
                }
            }
        <?php endif; ?>
    </script>
<?php endif; ?>

<?php if($this->uri->segment('2') == "edit"): ?>
    <script type="text/javascript">
        $('#btnEditSubmit').click(function() {
            var btn = $(this);
            $(btn).addClass('btn-progress');
            setTimeout(function() {
                $(btn).removeClass('btn-progress');
            }, 100000)
        });

        <?php if($this->uri->segment('2') == "edit" && $this->uri->segment('3') == "server"): ?>
            $('#customFile').change(function() {
                var i = $(this).prev('label').clone();
                var file = $('#customFile')[0].files[0].name;
                $(this).prev('label').text(file);
                $('#removeImage').fadeIn(250);
            });
            $('#removeImage').click(function() {
                $('#customFile').prev('label').text('Wybierz plik');
                $('#removeImage').hide();
                $('#customFile').val(null);
            });
            function useOtherVersion() {
                var e = $("#otherServerVersion");
                var t = $("#otherVersionField");
                t.hide(),
                e.change(function () {
                    e.is(":checked") ? t.show() : t.hide();
                });
            }
            function showRconPassword() {
                var divsRconPassInput = document.getElementById('divsServerRconPass');
                var divsPassIconShow = document.getElementById('showPassword');
                var divsPassIconHide = document.getElementById('hidePassword');
                if(divsRconPassInput.type === "password") {
                    divsRconPassInput.type = "text";
                    divsPassIconShow.classList.add('d-none');
                    divsPassIconHide.classList.remove('d-none');
                } else {
                    divsRconPassInput.type = "password";
                    divsPassIconShow.classList.remove('d-none');
                    divsPassIconHide.classList.add('d-none');
                }
            }
        <?php endif; ?>
        <?php if($this->uri->segment('2') == "edit" && $this->uri->segment('3') == "admin"): ?>
            function readURL(input) {
                if(input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview').css('background-image', 'url('+e.target.result +')');
                        $('#imagePreview').hide();
                        $('#imagePreview').fadeIn(250);
                        $('#removeImage').fadeIn(250);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $("#imageUpload").change(function() {
                readURL(this);
            });
            $('#removeImage').click(function() {
                $('#imagePreview').css('background-image', 'url(<?php echo $admin['image']; ?>)');
                $('#imagePreview').hide();
                $('#imagePreview').fadeIn(250);
                $('#removeImage').hide();
                $('#imageUpload').val(null);
            });
        <?php endif; ?>
        <?php if($this->uri->segment('2') == "edit" && $this->uri->segment('3') == "service"): ?>
            $('#customFile').change(function() {
                var i = $(this).prev('label').clone();
                var file = $('#customFile')[0].files[0].name;
                $(this).prev('label').text(file);
                $('#removeImage').fadeIn(250);
            });
            $('#removeImage').click(function() {
                $('#customFile').prev('label').text('Wybierz plik');
                $('#removeImage').hide();
                $('#customFile').val(null);
            });
        <?php endif; ?>
        <?php if($this->uri->segment('2') == "edit" && $this->uri->segment('3') == "news"): ?>
            $('#customFile').change(function() {
                var i = $(this).prev('label').clone();
                var file = $('#customFile')[0].files[0].name;
                $(this).prev('label').text(file);
                $('#removeImage').fadeIn(250);
            });
            $('#removeImage').click(function() {
                $('#customFile').prev('label').text('Wybierz plik');
                $('#removeImage').hide();
                $('#customFile').val(null);
            });
        <?php endif; ?>
    </script>
<?php endif; ?>

<?php if(isset($_SESSION['messageSuccessSmall'])): ?>
    <script type="text/javascript">
        Notiflix.Notify.Success("<?php echo $_SESSION['messageSuccessSmall']; ?>");
    </script>
<?php endif; unset($_SESSION['messageSuccessSmall']); ?>

<?php if(isset($_SESSION['messageDangerSmall'])): ?>
    <script type="text/javascript">
        Notiflix.Notify.Failure("<?php echo $_SESSION['messageDangerSmall']; ?>");
    </script>
<?php endif; unset($_SESSION['messageDangerSmall']); ?>

<?php if(isset($_SESSION['messageSuccessBig'])): ?>
    <script type="text/javascript">
        Notiflix.Report.Success( 
            'Udało się!', 
            "<?php echo $_SESSION['messageSuccessBig']; ?>", 
            'Zamknij'
        ); 
    </script>
<?php endif; unset($_SESSION['messageSuccessBig']); ?>

<?php if(isset($_SESSION['messageDangerBig'])): ?>
    <script type="text/javascript">
        Notiflix.Report.Failure( 
            'Oops, coś poszło nie tak :(', 
            '<?php echo $_SESSION['messageDangerBig']; ?>', 
            'Rozumiem'
        );
    </script>
<?php endif; unset($_SESSION['messageDangerBig']); ?>

<?php if($this->uri->rsegment('1') == "modules"): ?>
    <script type="text/javascript">
        function changeModuleStatus(f = null) {
            <?php if($settings['demoMode'] == 0): ?>
                if(f != null) {
                    Notiflix.Loading.Circle('Trwa zmiana ustawień modułu...');
                    Notiflix.Loading.Remove(3000);
                    setTimeout(function(){ f.form.submit(); }, 2500);
                }
            <?php else: ?>
                Notiflix.Loading.Circle('Trwa zmiana ustawień modułu...');
                Notiflix.Loading.Remove(2500);
                sleep(2600).then(() => {
                    Notiflix.Report.Failure('Oops, coś poszło nie tak :(', 'Ta czynność jest zablokowana w wersji demonstracyjnej sklepu', 'Rozumiem');
                    return false;
                });
            <?php endif; ?>
        }
    </script>
<?php endif; ?>

<?php if(isset($_SESSION['divsUpdateAvailable'])): ?>
    <script type="text/javascript">
        $(window).on('load',function(){
            $('#divsUpdateAvailable').modal('show');
        });
    </script>
<?php endif; unset($_SESSION['divsUpdateAvailable']); ?>