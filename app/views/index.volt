{% set router_uri = router.getRewriteUri() %}


<?
if($_SERVER["SERVER_NAME"] == "gpcabling.com.br" OR $_SERVER["SERVER_NAME"] == "www.gpcabling.com.br"){
    $servername=TRUE;
} else {
    $servername=FALSE;
}

//Verifica se está na Custom Frame
$router_array = explode("/", $router_uri);

if ($router_array[1]=='customframe' OR $router_array[1]=='rest') {
    $in_custom_frame = TRUE;
} else {
    $in_custom_frame = FALSE;
}
?>

{#
**************************
**************************
Redirect para o Anderson.
The sum of all nonsenses.
**************************
**************************
#}

{% if router.getRewriteUri() == "/avigilon" %}
<? header('Location: https://www.gpcabling.com.br/fabricante/index/AL'); ?>

{% elseif router.getRewriteUri() ==  "/agenda" %}
<? header('Location: https://solucoes.gpcabling.com.br/agenda'); ?>

{% elseif router.getRewriteUri() ==  "/apc" %}
<? header('Location: https://www.gpcabling.com.br/fabricante/index/AP'); ?>

{% elseif router.getRewriteUri() == "/brady" %}
<? header('Location: https://www.gpcabling.com.br/fabricante/index/BD'); ?>

{% elseif router.getRewriteUri() == "/cablena" %}
<? header('Location: https://www.gpcabling.com.br/fabricante/index/CA'); ?>

{% elseif router.getRewriteUri() == "/commscope" %}
<? header('Location: https://www.gpcabling.com.br/fabricante/index/CM'); ?>

{% elseif router.getRewriteUri() == "/cobrekabos" %}
<? header('Location: https://www.gpcabling.com.br/fabricante/index/CK'); ?>

{% elseif router.getRewriteUri() == "/coopersalto" %}
<? header('Location: https://www.gpcabling.com.br/fabricante/index/CO'); ?>

{% elseif router.getRewriteUri() == "/cisco" %}
<? header('Location: https://www.gpcabling.com.br/fabricante/index/CS'); ?>

{% elseif router.getRewriteUri() == "/digifort" %}
<? header('Location: https://www.gpcabling.com.br/fabricante/index/DG'); ?>

{% elseif router.getRewriteUri() == "/dahua" %}
<? header('Location: https://www.gpcabling.com.br/fabricante/index/DH'); ?>

{% elseif router.getRewriteUri() == "/dutotec" %}
<? header('Location: https://www.gpcabling.com.br/fabricante/index/DU'); ?>

{% elseif router.getRewriteUri() == "/tplink" %}
<? header('Location: https://www.gpcabling.com.br/fabricante/index/TP'); ?>

{% elseif router.getRewriteUri() == "/gerpip" %}
<? header('Location: https://www.gpcabling.com.br/fabricante/index/GI'); ?>

{% elseif router.getRewriteUri() == "/amp" %}
<? header('Location: https://www.gpcabling.com.br/fabricante/index/AM'); ?>

{% elseif router.getRewriteUri() == "/datwyler" %}
<? header('Location: https://www.gpcabling.com.br/fabricante/index/DT'); ?>

{% elseif router.getRewriteUri() == "/nexans" %}
<? header('Location: https://www.gpcabling.com.br/fabricante/index/NX'); ?>

{% elseif router.getRewriteUri() == "/fluke" %}
<? header('Location: https://www.gpcabling.com.br/fabricante/index/FK'); ?>

{% elseif router.getRewriteUri() == "/flukenetworks" %}
<? header('Location: https://www.gpcabling.com.br/fabricante/index/FL'); ?>

{% elseif router.getRewriteUri() == "/gpracks" %}
<? header('Location: https://www.gpcabling.com.br/fabricante/index/GP'); ?>

{% elseif router.getRewriteUri() == "/topsolution" %}
<? header('Location: https://www.gpcabling.com.br/produto/customSearch?searchquery=top+solution'); ?>

{% elseif router.getRewriteUri() == "/netscout" %}
<? header('Location: https://www.gpcabling.com.br/fabricante/index/NS'); ?>

{% elseif router.getRewriteUri() == "/pelco" %}
<? header('Location: https://www.gpcabling.com.br/fabricante/index/PE'); ?>

{% elseif router.getRewriteUri() == "/sms" %}
<? header('Location: https://www.gpcabling.com.br/fabricante/index/SM'); ?>

{% elseif router.getRewriteUri() == "/seventh" %}
<? header('Location: https://www.gpcabling.com.br/fabricante/index/SV'); ?>

{% elseif router.getRewriteUri() == "/systimax" %}
<? header('Location: https://www.gpcabling.com.br/fabricante/index/SY'); ?>

{% elseif router.getRewriteUri() == "/vault" %}
<? header('Location: https://www.gpcabling.com.br/fabricante/index/VA'); ?>

{% elseif router.getRewriteUri() == "/velcro" %}
<? header('Location: https://www.gpcabling.com.br/fabricante/index/VL'); ?>

{% elseif router.getRewriteUri() == "/promo" %}
<? header('Location: https://solucoes.gpcabling.com.br/promocoes-gpcabling'); ?>

{% elseif router.getRewriteUri() == "/maquinadefusao" %}
<? header('Location: https://www.gpcabling.com.br/produto/category/19'); ?>

{% elseif router.getRewriteUri() == "/fibraenergizada" %}
<? header('Location: https://www.gpcabling.com.br/produto/category/4/2:144/'); ?>
{% endif %}

{#
**********************************************************
**********************************************************
Regra custom de roteamento para index.
Dentro do if (#117) está a one-page incluindo headers, etc.
No else (#1615), estão as demais páginas.
**********************************************************
**********************************************************
#}

{% if in_custom_frame !== true %}
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <?
        if(isset($_SERVER["HTTPS"])){
        if($_SERVER["HTTPS"] != "on" AND $servername){
            $this_page = "https://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
            header('Location:'. $this_page);
        }
        }
        ?>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="index, follow">
        <meta name="google-site-verification" content="mmgAYEzgZbNJsaO_CORaUoekVKqOXcJQHIyhh9fgHoE" />
{% if produto.tags is not empty and produto.tags is defined %}
        <meta name="keywords" content="{{produto.tags}}">
{% else %}
        <meta name="keywords" content="GP Cabling">
{% endif %}

        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        {% if format is defined and format =='pdf' %}
            {# does nothing, no need for title #}
        {% else %}
            {{ get_title() }}
        {% endif %}
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script src="https://www.google.com/recaptcha/api.js?render=6LecfrEZAAAAAOUgMvabvSolNPNytS3-34r9IuID"></script>
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
        <link rel="stylesheet" href="/css/policom-min.css">
        <link rel="stylesheet" href="/vendor/badge-checkbox.css">
        <link rel="stylesheet" href="/css/especialistas.css">
        <link rel="icon" type="image/png" href="/favicon.ico">

        <meta name="author" content="GP Cabling">

        <!--Start of Zendesk Chat Script-->
        <script type="text/javascript">
        window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
        d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
        _.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
        $.src="https://v2.zopim.com/?4r8j4Bp7T7bJJBptNYmEz9mbLSJ1DXTz";z.t=+new Date;$.
        type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");
        </script>
        <!--End of Zendesk Chat Script-->

        <!--Start of Zendesk Chat RD STATION INTEGRATION Script-->
        <script type="text/javascript"  src="https://d335luupugsy2.cloudfront.net/js/integration/stable/rd-js-integration.min.js">
        </script>
        <script type="text/javascript">
        $zopim( function() {
          $zopim.livechat.setOnChatStart(postData);
          function postData(){
            var nome = $zopim.livechat.getName();
            var email = $zopim.livechat.getEmail();
            var phone = $zopim.livechat.getPhone();
            var data_array = [
              { name: 'email', value: email },
              { name: 'nome', value: nome },
              { name: 'telefone', value: phone },
              { name: 'token_rdstation', value: '6d5803728f4114ae9a3cb32e531a3b4f' },
              { name: 'identificador', value: 'zopim' }
            ];
            RdIntegration.post(data_array);
          }
        });
        </script>
        <!--End of Zendesk Chat RD STATION INTEGRATION Script-->

    <!--Tiny MCE-->
    {# New Version
    <script src="/vendor/tinymce/jquery.tinymce.min.js"></script>
    <script src="/vendor/tinymce/tinymce.min.js"></script>
    <script type="application/x-javascript">tinymce.init({selector:'#TypeHere'});</script>
    #}
    <script src="/vendor/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>

    {# GOOGLE CHARTs #}
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-100342209-1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-100342209-1');
    </script>

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-WB7FHL2');</script>
    <!-- End Google Tag Manager -->

    </head>
    <body id="#body">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WB7FHL2"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-100342209-1', 'auto');
      ga('send', 'pageview');
    </script>
        <!-- BEGIN MAIN CONTENTS -->
        {{ content() }}
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
        <script src="/js/utils-min.js"></script>
        
        <script type="text/javascript" async src="https://d335luupugsy2.cloudfront.net/js/loader-scripts/f2f99657-915e-40a1-8e68-62230b4f7fdb-loader.js"></script>
        <!-- END SCRIPTS -->
    </body>
</html>
{% endif %}

{#
************
************
CUSTOM FRAME
************
************
#}
{% if in_custom_frame %}
{{ content() }}
{% endif %}
