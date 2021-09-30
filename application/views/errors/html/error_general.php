<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 *
 * @link   https://divshop.pro
 **/
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Something went wrong</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600&display=swap">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <style>
        *{box-sizing:border-box;-webkit-box-sizing:border-box;margin:0;padding:0}a{color:#e6e6e6;transition:.3s all}a:active,a:focus,a:hover{color:#f6f6f6}body{color:#fff;font-family:Barlow,sans-serif;font-weight:600;background:#181527}.error-container{max-width:1140px;min-height:100vh;display:flex;flex-direction:column;justify-content:center;margin:0 auto;padding:0 30px}.error-box{text-align:center}.error-box .error-title{font-size:26px}.error-box .error-subtitle{font-size:20px;margin-top:6px;margin-bottom:24px}.error-box a.error-show-backtrace{display:inline-block;font-weight:600;color:#fff;text-align:center;text-decoration:none;cursor:pointer;-webkit-user-select:none;-moz-user-select:none;user-select:none;padding:10px 18px 14px 18px;font-size:16px;border-radius:40px;background:#2c3e50}.error-box .error-backtrace-box{margin-top:30px;border:2px solid #2c3e50;padding:30px 30px;border-radius:2px;display:none;text-align:left;font-weight:400;width:70%;margin-left:auto;margin-right:auto}.error-box .error-name{font-family:Consolas,monospace;margin-bottom:30px;font-size:19px;font-weight:600}.error-backtrace-box.backtrace-show{display:block}.error-box .error-backtrace{margin-top:30px}.error-box .error-code{font-weight:500;font-size:12px;margin-top:30px}.error-footer{display:flex;padding:20px 30px;background:#120f1c}.error-footer .error-footer-left{width:50%;float:left;padding-right:20px}.error-footer .error-footer-right{width:50%;float:right;padding-left:20px}.error-footer .error-footer-title{font-size:16px}.error-footer .error-footer-desc{font-weight:400}@media only screen and (max-width:879px){.error-footer{display:grid}.error-footer .error-footer-left,.error-footer .error-footer-right{width:100%;padding:0}.error-footer .error-footer-right{padding-top:30px}}@media only screen and (max-width:719px){.error-box .error-backtrace-box{width:100%}}@media only screen and (max-width:399px){.error-box .error-title{font-size:22px}.error-box .error-subtitle{font-size:18px}}@media only screen and (max-width:289px){.error-box .error-title{font-size:18px}.error-box .error-subtitle{font-size:14px}}
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-box">
            <h1 class="error-title"><i class="fa fa-exclamation-triangle"></i>&nbsp;Oops, napotkaliśmy nieoczekiwany błąd</h1>
			<h4 class="error-subtitle">Wystąpił problem podczas interpretowania kodu sklepu 😔</h4>
			<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === true) { ?>
				<a class="error-show-backtrace" onclick="showBacktrace();">Pokaż szczegóły</a>
				<div class="error-backtrace-box" id="error-backtrace-box">
					<h4 class="error-name"><?php echo $heading; ?></h4>
					<?php echo $message; ?>
					<p class="error-code">ERROR CODE: DIVSHOP_GENERAL_ERROR</p>
				</div>
			<?php } ?>
        </div>
	</div>
	<script type="text/javascript">
		function showBacktrace() {
			var error = document.getElementById('error-backtrace-box');
			error.classList.toggle('backtrace-show');
		}
	</script>
	<div class="error-footer">
		<div class="error-footer-left">
			<h1 class="error-footer-title">Dlaczego widzę ten błąd<i class="fa fa-question"></i></h1>
			<p class="error-footer-desc">
				Powodem wyświetlenia tego błędu może być błędna konfiguracja sklepu, lub administracja serwisu wprowadza zmiany na stronie. Wpadnij później, być może już wtedy sklep będzie poprawnie działać 😊
			</p>
		</div>
		<div class="error-footer-right">
			<h1 class="error-footer-title">Kto może mi pomóc<i class="fa fa-question"></i></h1>
			<p class="error-footer-desc">
				Jeżeli jesteś właścicielem tej strony, zawsze możesz się skontaktować z  <a href="https://divshop.pro/contact" target="_blank" rel="noopener noreferrer">supportem sklepu</a> lub sprawdzić czy ten błąd nie został opisany w naszej <a href="https://divshop.pro/docs" target="_blank" rel="noopener noreferrer">dokumentacji</a> (szukaj po "Message"). 
			</p>
		</div>
	</div>
</body>
</html>
<?php exit() ?>