<!-- {{settings}} -->
{##########################################################################
***************************************************************************
LAYOU PARA PÁGINA CUSTOM
***************************************************************************
***************************************************************************
##########################################################################}
{% if settings == '10' %}
{{conteudo}}

{##########################################################################
***************************************************************************
LAYOU PARA AS PÁGINAS ALEATÓRIAS POR FABRICANTE E DEFAULT
***************************************************************************
***************************************************************************
##########################################################################}
{% else %}
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<?
if($_SERVER["HTTPS"] != "on" AND $servername){
$this_page = "https://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
header('Location:'. $this_page);
}
?>
<!-- Using Phalcon Version <?php echo Phalcon\Version::get(); ?> -->
<!-- <?php echo 'Versão Atual do PHP: ' . phpversion(); ?> -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
{% if format is defined and format =='pdf' %}
{# does nothing, no need for title #}
{% else %}
{{ get_title() }}
{% endif %}
<?php if(1){ // compressed for production ?> 
<link rel="stylesheet" href="/vendor/bootstrap-3.3.7-dist/css/bootstrap.min.css">
<link rel="stylesheet" href="/vendor/bootstrap-3.3.7-dist/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="/vendor/lightbox2-dist/css/lightbox.min.css">
<?php }else{ // source for development ?> 
<link rel="stylesheet" href="/vendor/bootstrap-3.3.7/css/bootstrap.css">
<link rel="stylesheet" href="/vendor/bootstrap-3.3.7/css/bootstrap-theme.css">
<link rel="stylesheet" href="/vendor/lightbox2/css/lightbox.css">
<?php } ?> 
<link rel="stylesheet" href="/fonts/roboto.css">
<link rel="stylesheet" href="/css/policom.css">
<link rel="stylesheet" href="/vendor/badge-checkbox.css">
<link rel="icon" type="image/png" href="/favicon.ico">

<meta name="author" content="GP Cabling">

<!--MailChimp connection-->
<script id="mcjs">!function(c,h,i,m,p){m=c.createElement(h),p=c.getElementsByTagName(h)[0],m.async=1,m.src=i,p.parentNode.insertBefore(m,p)}(document,"script","https://chimpstatic.com/mcjs-connected/js/users/2e2a915a2e34fe037bac1d89a/c8f718d879b1f1b2a3454adc5.js");
</script>

<!--Start of Zendesk Chat Script-->
<script type="text/javascript">
window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
$.src="https://v2.zopim.com/?4r8j4Bp7T7bJJBptNYmEz9mbLSJ1DXTz";z.t=+new Date;$.
type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");
</script>
<!--End of Zendesk Chat Script-->
</head>
<body id="#body">
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-100342209-1', 'auto');
ga('send', 'pageview');
</script>

<!-- BEGIN MAIN CONTENTS -->
{###################################
************************************
Conteúdo do Custom Frame
************************************
###################################}

{# Se list está desativado #}
{% if list == 0 %}
<div class="container" style="padding:0;margin:0;">
{% for produto in conteudo %}
<div>
	<a href="/produto/index/{{produto['codigo_produto']}}" target="_blank">
	<center>
	<img src="{{produto['image']}}" class="img-responsive" style="max-width:150px;">
	<br>
	<b style="color:#337ab7;">{{produto['descricao_sys']}}</b><br>
	</a>
	<small style="color:gray; text-decoration:none;">
		Código de produto: {{produto['codigo_produto']}}
	</small>
	</center>
</div>
<hr style="margin:20px 0;">
{% endfor %}
<br><br><br><br><br><br><br><br><br><br>
</div>
</div>

{# Se list está ativado #}
{% else %}
<div class="container" style="padding:0; margin:0;">
{% for produto in conteudo %}
<div>
	<a href="/produto/index/{{produto['codigo_produto']}}" target="_blank">
	<div style="float:left; max-width:70px; margin-right:5px;">
		<img src="{{produto['image']}}" class="img-responsive" style="max-width:50px;">
	</div>

	<div style="float:left; max-width:180px;">
		<b style="color:#337ab7;">{{produto['descricao_sys']}}</b><br>
	<small style="color:gray; text-decoration:none;">
		Código de produto: {{produto['codigo_produto']}}
	</small>
	</div>
	</a>
	<div style="clear:both;"></div>
</div>
<hr style="margin:20px 0;">
{% endfor %}
<br><br><br><br><br><br><br><br><br><br>
</div>

{% endif %}

<!-- END MAIN CONTENTS -->

<!-- BEGIN SCRIPTS -->
<script src="/vendor/jquery-3.1.1.min.js"></script>

<link  href="/vendor/fotorama/fotorama.css" rel="stylesheet"> <!-- 3 KB -->
<script src="/vendor/fotorama/fotorama.js"></script> <!-- 16 KB -->

<script>$(document).ready(function(){$('[data-toggle="tooltip"]').tooltip();});</script>
<?php if(1){ // compressed for production ?> 
<script src="/vendor/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
<script src="/vendor/lightbox2-dist/js/lightbox.min.js"></script>
<script src="/vendor/bootstrap-confirmation.js"></script>
<?php }else{ // source for development ?> 
<script src="/vendor/bootstrap-3.3.7/js/bootstrap.js"></script>
<script src="/vendor/lightbox2/js/lightbox.js"></script>
<?php } ?> 
<script src="/js/utils.js"></script>
<!-- END SCRIPTS -->
</body>
</html>
{% endif %}