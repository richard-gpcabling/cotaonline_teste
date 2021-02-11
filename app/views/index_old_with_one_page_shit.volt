{% set router_uri = router.getRewriteUri() %}
<?
if($_SERVER["SERVER_NAME"] == "gpcabling.com.br" OR $_SERVER["SERVER_NAME"] == "www.gpcabling.com.br"){
    $servername=TRUE;
} else {$servername=FALSE;}

//Verifica se está na Custom Frame
$router_array = explode("/", $router_uri);

if($router_array[1]=='customframe'){
    $in_custom_frame = TRUE;
} else {$in_custom_frame = FALSE;}
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
<? header('Location: https://solucoes.gpcabling.com.br/promocoes'); ?>

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

{% if router.getRewriteUri() == '/' %}

<!doctype html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="zxx"> <!--<![endif]-->
<head>
<?
if($_SERVER["HTTPS"] != "on" AND $servername){
    $this_page = "https://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    header('Location:'. $this_page);
}
?>

    <style type="text/css">
        .error {
            color: #fff;
        }

        input.error {
            border: 1px red solid;
        }
    </style>
    <!-- Google Tag Manager -->
    <script>(function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(), event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-NJXNVWX');</script>
    <!-- End Google Tag Manager -->

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="index, follow">
    <title>GP Cabling</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <link rel="stylesheet" href="one_page/css/bootstrap.min.css">
    <link rel="stylesheet" href="one_page/css/normalize.css">
    <link rel="stylesheet" href="one_page/css/font-awesome.min.css">
    <link rel="stylesheet" href="one_page/css/icomoon.css">
    <link rel="stylesheet" href="one_page/css/owl.carousel.min.css">
    <link rel="stylesheet" href="one_page/css/customScrollbar.css">
    <link rel="stylesheet" href="one_page/css/photoswipe.css">
    <link rel="stylesheet" href="one_page/css/default-skin.css">
    <link rel="stylesheet" href="one_page/css/prettyPhoto.css">
    <link rel="stylesheet" href="one_page/css/animate.css">
    <link rel="stylesheet" href="one_page/css/transitions.css">
    <link rel="stylesheet" href="one_page/css/main.css">
    <link rel="stylesheet" href="one_page/css/color.css">
    <link rel="stylesheet" href="one_page/css/responsive.css">
    <link rel="stylesheet" href="one_page/css/full-search.css">
    <link rel="icon" type="image/png" href="favicon.ico">
    <style>
        .tg-sliderholder{
        max-height: 650px;
        }
        .tg-galleryslider, .tg-galleryslider .item {
   max-height: 650px;
}

        .newsletter a:link {
            color: #767676;
        }

        /* visited link */
        .newsletter a:visited {
            color: #767676;
        }

        /* mouse over link */
        .newsletter a:hover {
            color: #767676;
        }

        /* selected link */
        .newsletter a:active {
            color: #767676;
        }

        h2, .h2, .tg-sectionheading h2 {color: #767676;}


.team-sec{float: left;width: 100%;}
.team .photo {width: 250px; height: 130px; display: inline-block; overflow: hidden; position: relative; }
.photo-shadow {position: absolute; z-index: -1; top: 12px; left: 16px; bottom: -10px; height: 250px; width: 246px; background: #fff; }
.col-item{position: relative;}
.team{margin-top: 20px;}
.team .photo img{width: 100%;    vertical-align: middle;position: relative;}
.social-connect{margin-top: 13px;}
.info{margin-top: 12px;}
.info .name{font-size: 18px;font-weight: 600;margin-bottom: 2px;}
.info .degination{font-size: 16px;font-weight: 300;font-style: italic;color: #8B8B8B;}
.social-connect a{ display: inline-block; border: 1px solid #E3E3E3; font-size: 14px; color: #919191; width: 150px; height: 24px; text-align: center; line-height: 24px;margin-right: 4px;}
.social-connect a .fa{margin: 0;}
.social-connect a:hover{background-color: #4EBEE9;color: #fff;}
.carousel-line{height: 320px; position: absolute; bottom: -40px; width: 110%; left: -5.5%;}
.carousel-line > .controls{position: absolute; bottom: -16px; left: 50%; margin-left: -50px; background: #fff; padding: 0px 20px; color: #000;}
.carousel-line > .controls > a{    color: #868686; font-size: 24px; font-weight: 300;}

    </style>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-1425179-14', 'auto');
        ga('send', 'pageview');

    </script>
</head>
<?php function firstImg($post_content)
{
    $matches = array();
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post_content, $matches);
    if (isset($matches[1][0])) {
        $first_img = $matches[1][0];
    }

    if (empty($first_img)) {
        return '';
    }
    return $first_img;
}

function limit_words($string)
{
    $string = strip_tags($string);

    if (strlen($string) > 80) {

        // truncate string
        $stringCut = substr($string, 0, 80);

        // make sure it ends in a word so assassinate doesn't become ass...
        $string = substr($stringCut, 0, strrpos($stringCut, ' ')) . '...';
    }
    return $string;
}

function queryEventos(){
    $con=mysqli_connect("wf-207-38-92-35.webfaction.com","one_page","59fa0740b500f","one_page");
    if (mysqli_connect_errno())
  {
  $rtn = "Failed to connect to MySQL: " . mysqli_connect_error();
  } else {
   $rtn = mysqli_query($con,"SELECT * FROM events WHERE status = 1 AND end >= CURDATE() ORDER BY begin ASC");
  }
    mysqli_close($con);
    return $rtn;
}
$rst = queryEventos();
?>
<body id="body" class="tg-home tg-homeone">
<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NJXNVWX"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->

<!--************************************
        PopUp Warning Start
*************************************-->
<!--        <div id="myModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Newsletter GP Cabling</h4>
                    </div>
                    <div class="modal-body">
                        <img src="http://www.gpcabling.com.br/img/XXX.jpg" width="100%"></img>
                    </div>
                </div>
            </div>
        </div>-->
<!--************************************
        PopUp Warning End
*************************************-->

<!--[if lt IE 8]>
<p class="browserupgrade">Você está usando um navegador <strong>desatualizado</strong>. Por favor <a
        href="http://browsehappy.com/">atualize seu navegador</a> para melhorar sua experiência nessa página.</p>
<![endif]-->
<!--************************************
        Wrapper Start
*************************************-->
<div id="tg-wrapper" class="tg-wrapper tg-haslayout">
    <!--************************************
            SideBar Navigation Start
    *************************************-->
    <div class="tg-sidenavholder">
        <div id="tg-sidenav" class="tg-sidenav">
            <a href="javascript:void(0);" class="tg-close"><i class="icon-cross"></i></a>
            <div id="tg-navscrollbar" class="tg-navscrollbar">
                <div class="tg-navhead">
                    <strong class="tg-logo">
                        <a href="index.html"><img src="images/logo-whiteX.png" alt="image description"></a>
                    </strong>
                </div>
                <nav id="tg-navtwo" class="tg-nav">
                    <div id="tg-sidenavigation" class="tg-navigation">
                        <ul>
                            <li><a href="#">Brasil (Português)</a></li>
                            <li><a href="#">United States (English)</a></li>
                            <li><a href="#">Latin America (Español)</a></li>
                        </ul>
                    </div>
                </nav>
                <div class="tg-sidenavbottom">
                    <img src="one_page/images/icon.png" alt="image description">
                    <ul class="tg-socialicons">
                        <li class="tg-facebook"><a href="javascript:void(0);"><i class="fa fa-facebook"></i></a></li>
                        <li class="tg-twitter"><a href="javascript:void(0);"><i class="fa fa-twitter"></i></a></li>
                        <li class="tg-linkedin"><a href="javascript:void(0);"><i class="fa fa-linkedin"></i></a></li>
                        <li class="tg-googleplus"><a href="javascript:void(0);"><i class="fa fa-google-plus"></i></a>
                        </li>
                        <li class="tg-rss"><a href="javascript:void(0);"><i class="fa fa-rss"></i></a></li>
                    </ul>
                    <p class="tg-copyrights">2017 Todos os direitos reservados GP Cabling<span></span></p>
                </div>
            </div>
        </div>
    </div>
    <!--************************************
            SideBar Navigation End
    *************************************-->
    <!--************************************
            Header Start
    *************************************-->
    <header id="tg-header" class="tg-header tg-haslayout" data-spy="affix" data-offset-top="10"
            style="padding: 0 0 19px 0;">
        <div class="container-fluid" style="border-bottom: 1px solid #ccc; margin-bottom: 10px; padding: 8px 0 8px 0;">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <div class="pull-left newsletter" style="font: normal 15px Arial; padding: 5px;"><a
                                    href="https://solucoes.gpcabling.com.br/lista"
                                    target="_blank" class="newsletter"><i class="fa fa-envelope-o" aria-hidden="true"></i> ASSINAR NEWSLETTER</a></div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                        <!-- <div id="fscroller" style="" class="pull-right"> -->
                        <div style="" class="pull-right">
                            <div style="font: normal 15px Arial; padding: 5px;">
                            <!-- <span id="phonePolicom"> -->
                            <span>
                                <b>SP</b> (11) 2065.0800 &middot; <b>RJ</b> (21) 3888.3727
                            </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="social pull-right">
                            <ul class="tg-socialicons">
                                <li class="tg-whatsapp">
                                    <a href="https://api.whatsapp.com/send?phone=551120650800" target="_blank">
                                        <i class="fa fa-whatsapp"></i>
                                    </a>
                                </li>

                                <li class="tg-facebook"><a href="https://www.facebook.com/GPolicom" target="_blank"><i
                                                class="fa fa-facebook"></i></a></li>
                                <li class="tg-youtube"><a href="https://www.youtube.com/gpcabling" target="_blank"><i
                                                class="fa fa-youtube"></i></a></li>
                                <li class="tg-twitter"><a href="https://twitter.com/gpcabling" target="_blank"><i
                                                class="fa fa-twitter"></i></a></li>
                                <li class="tg-googleplus"><a href="https://plus.google.com/106078670564347965774/posts"
                                                             target="_blank"><i class="fa fa-google-plus"></i></a></li>
                                <li class="tg-instagram"><a href="https://www.instagram.com/gpcabling"
                                                            target="_blank"><i
                                                class="fa fa-instagram"></i></a></li>
                                <li class="tg-linkedin"><a href="https://pt.linkedin.com/company/grupo-policom"
                                                           target="_blank"><i class="fa fa-linkedin"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="container" style="padding-top: 10px;">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <strong class="tg-logo" style="width:188px;">
                        <a href="/cotaonline"><img src="img/logo_rotativo_one_page.gif" alt="GP Cabling"></a>
                    </strong>
                    <div class="tg-navigationarea">
                        <!--                            <a class="tg-btn tg-btnbecommember" href="javascript:void(0);"><span class="tg-badge"></span>CADASTRE-SE</a>
                        --> <!--<a id="tg-btnopenclose" class="tg-btnopenclose" href="javascript:void(0);"><i
                            class="fa fa-globe"></i></a>-->
                        <!--<a class="tg-btnopenclose" href="#search"><i
                        class="fa fa-search"></i></a>-->
                        <a class="tg-btnopenclose" href="/cotaonline"><i
                                    class="fa fa-search"></i></a>
                        <nav id="tg-nav" class="tg-nav">
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                        data-target="#tg-navigation" aria-expanded="false">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                            </div>
                            <div id="tg-navigation" class="collapse navbar-collapse tg-navigation">
                                <ul>
                                    <li class="tg-active menu-item-has-children"><a href="#body">HOME</a></li>
                                    <li><a href="#empresa">EMPRESA</a></li>
                                    <li><a href="/agenda" class="external">AGENDA</a></li>
                                    <li><a href="#solution-center">SOLUTION CENTERS</a></li>
                                    <li><a href="/cotaonline" class="external">PRODUTOS</a></li>
                                    <li><a href="#noticias">NOTÍCIAS</a></li>
                                    <li><a href="/contato/index" class="external">CONTATO</a></li>
                                    <li><a href="#boletos">BOLETOS</a></li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!--************************************
            Header End
    *************************************-->
    <!--************************************
            Home Slider Start
    *************************************-->
    <div id="tg-homebanner" class="tg-homebanner tg-haslayout">
        <figure class="tg-themepostimg">
            <img src="one_page/images/banner/img-01-720h.jpg" alt="image description">

            <figcaption>
                <div class="tg-bannercontent" data-tilt>
                    <span class="tg-datetime" style="padding: 70px 50px 0;">
                        <img src="one_page/images/logo_cotaonline.png" alt="image description"
                             width="350px" height="100px"></span>
                    <h1 style="line-height: 33px;"><span style="font-size:26px;">Consulte agora nossos<span
                                    style="font-size:23px;">preços e estoque!</span></span>
                    </h1>
                    <div class="tg-speakerinfo" align="center">
                        <div class="tg-authorholder">

                            <div class="tg-authorcontent">
                                <div class="tg-speakername">
                                    <span class="tg-eventcatagory">&nbsp;</span>
                                    <h2></h2>
                                </div>
                            </div>
                        </div>
                        <div class="tg-btnwhite tg-navigation2X">
                            <a class="tg-btn" href="/cotaonline"><span
                                        style="font-size:19px">Acessar</span></a>
                        </div>
                    </div>
                </div>
            </figcaption>
        </figure>
    </div>
    <!--************************************
            Home Slider End
    *************************************-->
    <!--************************************
            Main Start
    *************************************-->
<main id="tg-main" class="tg-main tg-haslayout">
<!--************************************
About Us Start
*************************************-->
<section class="tg-sectionspace tg-haslayout" id="empresa">
<div class="container">
<div class="row">
<div class="tg-aboutus">
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
<div class="tg-textshortcode">
<div class="tg-sectionhead tg-textalignleft">
<div class="tg-sectionheading">
<span>Seja bem-vindo ao</span>
<h2>GP Cabling</h2>
</div>
<div class="tg-description">
<p>Fundado em 1995, o GP Cabling é referência no mercado nacional de
distribuição de produtos para Cabeamento Estruturado direcionados a
aplicações de dados, voz, vídeo e controles prediais, e para CFTV IP dos
principais fabricantes do mercado, reconhecidos internacionalmente...</p>
</div>
<!-- Large modal -->
<div class="tg-btnarea">
<button type="button" class="btnX btn-primaryX tg-btn" data-toggle="modal"
data-target=".bs-example-modal-lg" style="font-size:18px">Saiba Mais
</button>
</div>

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog"
aria-labelledby="myLargeModalLabel">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal"
aria-label="Close"><span aria-hidden="true">&times;</span>
</button>
<h4 class="modal-title" id="myModalLabel">Sobre o Grupo
Policom...</h4>
</div>
<div class="modal-body">
<img src="one_page/images/banner_empresa.jpg"> <br><br>
Fundado em 1995, o GP Cabling® é referência no mercado nacional
de distribuição de produtos para Cabeamento Estruturado direcionados
a aplicações de dados, voz, vídeo e controles prediais, e para CFTV
IP dos principais fabricantes do mercado, reconhecidos
internacionalmente.<br><br>

Com sede na capital paulista, é formado pelas empresas Policom SP
(São Paulo-SP), Policom Rio (Rio de Janeiro-RJ) e Policom Paraná (Curitiba-PR), mantendo representantes
regionais para áreas específicas, como interior dos Estados de São
Paulo e Minas Gerais. Em 2011, inaugurou o Policom Solution Center,
com área de 130 metros quadrados para treinamentos e showroom das
soluções de alta tecnologia comercializadas, que é também aberto à
visita dos clientes, mediante agendamento prévio.
<br><br>

As soluções que distribui são fabricadas por empresas líderes em
seus segmentos e reconhecidas internacionalmente, a exemplo de APC,
Avigilon, Brady, CommsCope (linhas SYSTIMAX e AMP), Cablena, CISCO,
Coopersalto, Dahua, Dätwyler, Digifort, Dutotec, Fluke Networks,
Micronet, Netscout, Nexans, NVT PHYBRIDGE, Pelco, Transition
Networks, Vault, Videotec., entre outras.<br><br>

Contando com estrutura logística em condições de atender a todo o
território nacional, o GP Cabling oferece suporte técnico e
comercial aos seus clientes. O seu corpo de profissionais participa
constantemente de cursos ministrados pelos fornecedores e parceiros,
mantendo-se atualizados para orientar corretamente os clientes com
relação às melhores soluções de conectividade. Atualmente, o Grupo
Policom possui diversos engenheiros, MBAs e RCDDs entre seus mais de
120 colaboradores.<br><br>

Tudo isso possibilita que o GP Cabling, além de comercializar
produtos e soluções completas, preste suporte operacional a seus
clientes, ações que são reconhecidas com premiações nacionais que
mostram a preferência e a capacitação de sua equipe, seja no
cumprimento de prazos, seja no suporte à venda e no treinamento aos
canais. Em 2007, o GP Cabling criou a Divisão Industrial
Networks, que objetiva o atendimento de empresas do setor industrial
e em 2008 iniciou forte atuação no setor de segurança, passando a
ofertar soluções em monitoramento e gravação para CFTV. Em 2016
iniciou sua atuação nas áreas de gestão de energia – com o
fornecimento de NoBreaks e cabos elétricos – e controle de acesso
IP.<br><br>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">
Fechar
</button>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
<div class="tg-videoarea">
<iframe src="https://www.youtube.com/embed/?listType=user_uploads&list=gpcabling"width="570" height="320"></iframe>
</div>
</div>
</div>
</div>
</div>
</section>
        <!--************************************
                About Us End
        *************************************-->

        <!--************************************
                News & Articles Start
        *************************************-->
<section class="tg-sectionspace tg-haslayout" id="noticias" style="background-color: whitesmoke;">
<div class="container">
<div class="row">
<div class="tg-aboutus">
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
<div class="tg-textshortcode">
<div class="tg-sectionhead tg-textalignleft">
<div class="tg-sectionheading">
<span>Notícias do nosso Blog</span>
<h2>Cabling News</h2>
</div>
<div class="tg-description">
<p>
Ver as últimas notícias sobre o GP Cabling.
</p>
</div>
<!-- Large modal -->
<div class="tg-btnarea">
<a href="http://cablingnews.com.br" target="_blank">
<button type="button" class="btnX btn-primaryX tg-btn" style="font-size:18px">
    Acessar
</button>
</a>
</div>

</div>
</div>
</div>

</div>
</div>
</div>
</section>

        {#
        <section class="tg-sectionspace tg-haslayout" id="noticias" style="background-color: whitesmoke;">
            <div class="container">
                <div class="row">
                    <div class="tg-newsarticles">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="tg-textshortcode">
                                <div class="tg-sectionhead tg-textalignleft">
                                    <div class="tg-sectionheading">
                                        <span>Notícias do nosso Blog</span>
                                        <h2>Cabling News</h2>
                                    </div>
                                    <div class="tg-description">
                                        <img src="http://mypolicom.com.br/cablingnews/logo_cnonline.png" alt="">
                                    </div>
                                    <div class="tg-btnarea" style="margin-top: -30px;">
                                        <a class="tg-btn" href="http://www.cablingnews.com.br/" target="_blank"><span
                                                    style="font-size:16px;">Confira outras matérias</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="tg-articles">

                                <?php
                                $feed = file_get_contents('http://www.cablingnews.com.br/?feed=rss2');
                                $rss = new SimpleXmlElement($feed);
                                $i = 0;
                                foreach ($rss->channel->item as $entrada):
                                    ?>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <article class="tg-article"
                                                 style="background-color: #fff; padding: 10px;">
                                            <figure><a href="<?php echo $entrada->link ?>"
                                                       target="_blank">
                                                    <img src="<?php echo firstImg($entrada->children("content", true)); ?>"
                                                         alt="image description" height="152"
                                                         style="height: 152px;"></a>
                                            </figure>
                                            <div class="tg-contentbox">
                                                <div class="tg-title" align="center">
                                                    <h2><a href="<?php echo $entrada->link ?>"
                                                           target="_blank"><?php echo limit_words($entrada->title); ?></a>
                                                    </h2>
                                                </div>

                                                <a class="tg-btnreadmore"
                                                   href="<?php echo $entrada->link ?>"
                                                   target="_blank">Saiba
                                                    mais</a>
                                            </div>
                                        </article>
                                    </div>

                                    <?php
                                    $i++;
                                    if ($i == 2) break;
                                endforeach;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        #}
        <!--************************************
                News & Articles End
        *************************************-->
        <!--************************************
                Signup Start
        *************************************-->
        <section class="tg-haslayout tg-bgsignup">
            <div class="container">
                <div class="row">
                    <div class="tg-signupnewsletter">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="tg-signupdetail">
                                <div class="tg-sectionhead tg-textalignleft">
                                    <div class="tg-sectionheading">
                                        <span>Descubra qual o nobreak ideal para o seu negócio</span>
                                        <h2>Simulador de Nobreak</h2>
                                    </div>
                                </div>
                                <div class="tg-btnwhite">
                                    <a class="tg-btn" href="http://www.gpcabling.com.br/simuladordenobreak/"
                                       target="_blank"
                                       style="font-size:16px">Iniciar Simulação</a>
                                </div>

                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 hidden-sm hidden-xs">
                            <figure class="tg-newsletterimg"><img src="one_page/images/note.png" alt="image description">
                            </figure>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--************************************
                Signup End
        *************************************-->

        <!--************************************
                Event Counter Start
        *************************************-->
        <!--         <section class="tg-haslayout">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="tg-counterarea">
                                <div class="tg-eventinfo">
                                    <figure class="tg-themepostimg">
                                        <img src="one_page/images/img-01.jpg" alt="image description">
                                        <figcaption>
                                            <i class="icon-calendar-full"></i>
                                            </a>
                                            <time class="tg-timedate" datetime="2017-12-12"></time>
                                            <h2>GP Cabling,<span>referência nacional</span></h2>
                                        </figcaption>
                                    </figure>
                                </div>
                                <div id="tg-upcomingeventcounter" class="tg-upcomingeventcounter">
                                    <div class="tg-eventcounter"><span>22</span><span> Anos</span></div>
                                    <div class="tg-eventcounter"><span>150</span><span> Colaboradores</span></div>
                                    <div class="tg-eventcounter"><span>60</span><span> Representantes</span></div>
                                    <div class="tg-eventcounter"><span>400</span><span> Parceiros</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section> -->
        <!--************************************
                Event Counter End
        *************************************-->
        <!--************************************
                Brands Start
        *************************************-->
        {#
        <section id="tg-sponsers" class="tg-sectionspace tg-haslayout">
            <div class="container">
                <div class="row">
                    <div class="col-xs-offset-0 col-xs-12 col-sm-offset-0 col-sm-12 col-md-offset-2 col-md-8 col-lg-offset-2 col-lg-8">
                        <div class="tg-sectionhead">
                            <div class="tg-sectionheading">
                                <span>As melhores marcas estão aqui</span>
                                <h2>Nossos Parceiros</h2>
                            </div><!--
                            <div class="tg-description">
                                <p>Consectetur adipisicing elit sed do eiusmod tempor incididunt labore dolore magna
                                    aliqua enim ad minim veniam quis nostrud exercitation ullamcoaris nisi ut aliquipa
                                    ex ea commodo consequat aute irure.</p>
                            </div>-->
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <ul class="tg-brands">
                            <li class="tg-brand">
                                <figure><a href="/fabricante/index/CM"><img src="one_page/images/brands/img-01.png"alt="image description"></a></figure>
                            </li>
                            <li class="tg-brand">
                                <figure><a href="/fabricante/index/NX"><img src="one_page/images/brands/img-17.png"alt="image description"></a></figure>
                            </li>
                            <li class="tg-brand">
                                <figure><a href="/fabricante/index/FL"><img src="one_page/images/brands/img-18.png"alt="image description"></a></figure>
                            </li>
                            <li class="tg-brand">
                                <figure><a href="/fabricante/index/FK"><img src="one_page/images/brands/img-19.png"alt="image description"></a></figure>
                            </li>
                            <li class="tg-brand">
                                <figure><a href="/fabricante/index/BD"><img src="one_page/images/brands/img-06.png"
                                                                alt="image description"></a></figure>
                            </li>
                            <li class="tg-brand">
                                <figure><a href="/fabricante/index/PE"><img src="one_page/images/brands/img-03.png"
                                                                alt="image description"></a></figure>
                            </li>
                            <li class="tg-brand">
                                <figure><a href="/fabricante/index/DH"><img src="one_page/images/brands/img-20.png"
                                                                alt="image description"></a></figure>
                            </li>
                            <li class="tg-brand">
                                <figure><a href="/fabricante/index/GI"><img src="one_page/images/brands/img-02.png"
                                                                alt="image description"></a></figure>
                            </li>
                        </ul>
                        <div class="tg-btnarea" align="center"></br></br>
                            <a class="tg-btn" href="/fabricante/"><span style="font-size:16px">Ver todos Parceiros</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        #}
        <!--************************************
                Brands End
        *************************************-->
        <!--************************************
        Gallery Start
*************************************-->
        <div class="tg-gallerymain" id="solution-center" style="max-height:650px">
            <div class="tg-containerholder">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="tg-sectionhead tg-textalignleft">
                                <div class="tg-sectionheading">
                                    <span>Show room e Centros de Treinamento</span>
                                    <h2>Policom Solution Centers</h2>
                                </div>
                                <div class="tg-description">
                                    <p>Os Policom Solution Centers SP e Rio são espaços
                                        exclusivos que ajudam nossos clientes e parceiros a fechar novo negócios. Agende
                                        sua visita pelo e-mail contato@gpcabling.com.br</p>
                                </div>
                            </div>
                            <div class="tg-gallerytabs">
                                <ul class="tg-gallerynav" role="tablist">
                                    <li role="presentation" class="active">
                                        <a href="https://photos.app.goo.gl/4C6O4e1WpYZwkZig1" aria-controls="home"
                                           role="tab" data-toggle="tab">
                                            <figure class="tg-themepostimg psc"
                                                    url="https://photos.app.goo.gl/4C6O4e1WpYZwkZig1"
                                                    style="cursor:pointer;">
                                                <a href="https://photos.app.goo.gl/4C6O4e1WpYZwkZig1" target="_blank">
                                                    <time datetime="2017-12-12">Policom Solution Center SP</time>
                                                </a>
                                                <img src="one_page/images/gallery/thumb/img-SP.jpg" alt="image description">
                                            </figure>
                                        </a>
                                    </li>
                                    <li role="presentation">
                                        <a href="https://photos.app.goo.gl/ZAIL3NWDv9NuuoLw1" aria-controls="messages"
                                           role="tab" data-toggle="tab">
                                            <a href="https://photos.app.goo.gl/ZAIL3NWDv9NuuoLw1" target="_blank">
                                                <figure class="tg-themepostimg psc"
                                                        url="https://photos.app.goo.gl/ZAIL3NWDv9NuuoLw1"
                                                        style="cursor:pointer;">
                                                    <a href="https://photos.app.goo.gl/ZAIL3NWDv9NuuoLw1"
                                                       target="_blank">
                                                        <time datetime="2017-12-12">Policom Solution Center Rio</time>
                                                    </a>
                                                    <img src="one_page/images/gallery/thumb/img-RJ.jpg" alt="image description">
                                                </figure>
                                            </a>
                                    </li>
                                    <!-- <li role="presentation">
                                         <a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">
                                             <figure class="tg-themepostimg">
                                                 <time datetime="2017-12-12">Curitiba</time>
                                                 <img src="one_page/images/gallery/thumb/img-04.jpg" alt="image description">
                                             </figure>
                                         </a>
                                     </li>-->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tg-sliderholder">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 pull-right">
                    <div class="tg-gallerytabcontent tab-content">
                        <div role="tabpanel" class="tab-pane active" id="home">
                            <div class="tg-backslider">
                                <div class="tg-galleryslider owl-carousel">
                                    <div class="item">
                                        <figure class="tg-themepostimg">
                                            <img src="one_page/images/gallery/img-SP01.jpg" alt="image description"
                                            style="max-height:650px">
                                        </figure>
                                    </div>
                                    {#<div class="item">
                                        <figure class="tg-themepostimg">
                                            <img src="one_page/images/gallery/img-SP02.jpg" alt="image description">
                                        </figure>
                                    </div>#}
                                </div>
                            </div>
                            <div class="tg-forntslider">
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="profile">
                            <div class="tg-backslider">
                                <div class="tg-galleryslider owl-carousel">
                                    <div class="item">
                                        <figure class="tg-themepostimg">
                                            <img src="one_page/images/gallery/img-PR01.jpg" alt="image description">
                                        </figure>
                                    </div>
                                </div>
                            </div>
                            <div class="tg-forntslider">
                                <div class="tg-gallerthumbslider owl-carousel">
                                    <div class="item my-gallery">
                                        <figure>
                                            <a href="images/gallery/full/img-PR01.jpg" data-size="1024x1024">
                                                <i class="icon-magnifier"></i>
                                                <img src="one_page/images/gallery/img-PR01.jpg" alt="Image description"/>
                                            </a>
                                        </figure>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="messages">
                            <div class="tg-backslider">
                                <div class="tg-galleryslider owl-carousel">
                                    <div class="item">
                                        <figure class="tg-themepostimg">
                                            <img src="one_page/images/gallery/img-RJ01.jpg" alt="image description">
                                        </figure>
                                    </div>
                                    <div class="item">
                                        <figure class="tg-themepostimg">
                                            <img src="one_page/images/gallery/img-RJ02.jpg" alt="image description">
                                        </figure>
                                    </div>
                                </div>
                            </div>
                            <div class="tg-forntslider">
                                <div class="tg-gallerthumbslider owl-carousel">
                                    <div class="item my-gallery">
                                        <figure>
                                            <a href="images/gallery/full/img-RJ01.jpg" data-size="1024x1024">
                                                <i class="icon-magnifier"></i>
                                                <img src="one_page/images/gallery/img-RJ01.jpg" alt="Image description"/>
                                            </a>
                                        </figure>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--                        <div role="tabpanel" class="tab-pane" id="settings">
                                                    <div class="tg-backslider">
                                                        <div class="tg-galleryslider owl-carousel">
                                                            <div class="item">
                                                                <figure class="tg-themepostimg">
                                                                    <img src="one_page/images/gallery/img-10.jpg" alt="image description">
                                                                </figure>
                                                            </div>
                                                            <div class="item">
                                                                <figure class="tg-themepostimg">
                                                                    <img src="one_page/images/gallery/img-09.jpg" alt="image description">
                                                                </figure>
                                                            </div>
                                                            <div class="item">
                                                                <figure class="tg-themepostimg">
                                                                    <img src="one_page/images/gallery/img-08.jpg" alt="image description">
                                                                </figure>
                                                            </div>
                                                            <div class="item">
                                                                <figure class="tg-themepostimg">
                                                                    <img src="one_page/images/gallery/img-07.jpg" alt="image description">
                                                                </figure>
                                                            </div>
                                                            <div class="item">
                                                                <figure class="tg-themepostimg">
                                                                    <img src="one_page/images/gallery/img-06.jpg" alt="image description">
                                                                </figure>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tg-forntslider">
                                                        <div class="tg-gallerthumbslider owl-carousel">
                                                            <div class="item my-gallery">
                                                                <figure>
                                                                    <a href="images/gallery/img-05.jpg" data-size="1024x1024">
                                                                        <i class="icon-magnifier"></i>
                                                                        <img src="one_page/images/gallery/img-05.jpg" alt="Image description"/>
                                                                    </a>
                                                                </figure>
                                                            </div>
                                                            <div class="item my-gallery">
                                                                <figure>
                                                                    <a href="images/gallery/img-04.jpg" data-size="1024x1024">
                                                                        <i class="icon-magnifier"></i>
                                                                        <img src="one_page/images/gallery/img-04.jpg" alt="Image description"/>
                                                                    </a>
                                                                </figure>
                                                            </div>
                                                            <div class="item my-gallery">
                                                                <figure>
                                                                    <a href="images/gallery/img-03.jpg" data-size="1024x1024">
                                                                        <i class="icon-magnifier"></i>
                                                                        <img src="one_page/images/gallery/img-03.jpg" alt="Image description"/>
                                                                    </a>
                                                                </figure>
                                                            </div>
                                                            <div class="item my-gallery">
                                                                <figure>
                                                                    <a href="images/gallery/img-02.jpg" data-size="1024x1024">
                                                                        <i class="icon-magnifier"></i>
                                                                        <img src="one_page/images/gallery/img-02.jpg" alt="Image description"/>
                                                                    </a>
                                                                </figure>
                                                            </div>
                                                            <div class="item my-gallery">
                                                                <figure>
                                                                    <a href="images/gallery/img-01.jpg" data-size="1024x1024">
                                                                        <i class="icon-magnifier"></i>
                                                                        <img src="one_page/images/gallery/img-01.jpg" alt="Image description"/>
                                                                    </a>
                                                                </figure>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                        -->                    </div>

                </div>
            </div>
        </div>
        <!--************************************
                Gallery End
        *************************************-->
        <!--************************************
                Confrences Start
        *************************************-->
        <!--************************************
                Brands Start
        *************************************-->
<section class="tg-sectionspace tg-haslayout" id="treinamentos" style="background-color: white;">
<div class="container">
<div class="row">
<div class="tg-aboutus">
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
<div class="tg-textshortcode">
<div class="tg-sectionhead tg-textalignleft">
<div class="tg-sectionheading">
<span>Eventos</span>
<h2>Agenda</h2>
</div>
<div class="tg-description">
<p>
Invista na sua carreira e amplie seus conhecimentos.
</p>
<p>
Veja a agenda atualizada de treinamentos e eventos de alto nível do GP Cabling e parceiros.
</p>
</div>
<!-- Large modal -->
<div class="tg-btnarea">
<a href="https://solucoes.gpcabling.com.br/agenda" target="_blank">
<button type="button" class="btnX btn-primaryX tg-btn" style="font-size:18px">
    Ver Agenda
</button>
</a>
</div>

</div>
</div>
</div>

</div>
</div>
</div>
</section>
        <!--************************************
                Brands End
        *************************************-->
        <!--************************************
                Confrences End
        *************************************-->

<!--************************************
EMITIR BOLETOS
*************************************-->
<section class="tg-sectionspace tg-haslayout" id="boletos" style="background-color: whitesmoke;">
<div class="container">
<div class="row">
<div class="tg-aboutus">
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
<div class="tg-textshortcode">
<div class="tg-sectionhead tg-textalignleft">
<div class="tg-sectionheading">
<span>EMITIR BOLETOS</span>
<h2>Emitir Boletos</h2>
</div>
<div class="tg-description">
<p>
Siga as indicações abaixo imprimir boletos emitidos pelo GP Cabling
</p>
</div>
<!-- Large modal -->
<div class="tg-btnarea">
<a href="/customframe/index/emitir_boletos" target="_blank">
<button type="button" class="btnX btn-primaryX tg-btn" style="font-size:18px">
    Acessar
</button>
</a>
</div>

</div>
</div>
</div>

</div>
</div>
</div>
</section>
    </main>
    <!--************************************
            Main End
    *************************************-->
    <!--************************************
            Footer Start
    *************************************-->
    <footer id="tg-footer" class="tg-footer tg-haslayout">

        <!--************************************
                Bottom Bar Start
        *************************************-->
        <div class="container">
            <div class="row">
                <div class="center-block text-center">
                    {#
                    <a href="https://profiles.dunsregistered.com/DunsRegisteredProfileAnywhere.aspx?key1=3121777&amp;PaArea=1"
                       target="_blank">
                        <img src="one_page/images/selo_duns.jpg" width="110px">
                    </a><br>
                    <small style="font-size:14px"><strong>COMUNICADO IMPORTANTE</strong><br>Infelizmente o Grupo
                        Policom
                        vem sendo vítima de alguma quadrilha para aplicar golpes no mercado. Esta quadrilha está
                        usando
                        o nome do GP Cabling para possíveis compras fraudadas. Pedimos que entrem em contato
                        conosco
                        caso recebam algum pedido de compras suspeito, em nosso nome.
                    </small>
                    <br><br><br>
                    #}
                    <a href="https://solucoes.gpcabling.com.br/bndes" target=_"blank">
                    <img src="one_page/images/cartao-bndes.png">
                    </a>

                </div>

                <br><br>
            </div>
        </div>
        <br>
        <div class="tg-footerbar" style="background-color: whitesmoke;">
            <a id="tg-btnscrolltop" class="tg-btnscrolltop" href="javascript:void(0);"><i
                        class="icon-chevron-up"></i></a>
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <p class="tg-copyrights text-left">2017 - Todos os direitos reservados ao GP Cabling</p>
                    </div>
                    <div class="col-md-6">
                        <ul class="tg-socialicons">
                                <li class="tg-whatsapp">
                                    <a href="https://api.whatsapp.com/send?phone=551120650800" target="_blank">
                                        <i class="fa fa-whatsapp"></i>
                                    </a>
                                </li>

                            <li class="tg-facebook"><a href="https://www.facebook.com/GPolicom" target="_blank"><i
                                            class="fa fa-facebook"></i></a></li>
                            <li class="tg-youtube"><a href="https://www.youtube.com/gpcabling" target="_blank"><i
                                            class="fa fa-youtube"></i></a></li>
                            <li class="tg-twitter"><a href="https://twitter.com/gpcabling" target="_blank"><i
                                            class="fa fa-twitter"></i></a></li>
                            <li class="tg-googleplus"><a href="https://plus.google.com/106078670564347965774/posts"
                                                         target="_blank"><i class="fa fa-google-plus"></i></a></li>
                            <li class="tg-instagram"><a href="https://www.instagram.com/gpcabling" target="_blank"><i
                                            class="fa fa-instagram"></i></a></li>
                            <li class="tg-linkedin"><a href="https://pt.linkedin.com/company/grupo-policom"
                                                       target="_blank"><i class="fa fa-linkedin"></i></a></li>


                            <!--<li class="tg-rss"><a href="javascript:void(0);"><i class="fa fa-rss"></i></a></li>-->
                        </ul>
                    </div>


                </div>
            </div>
        </div>
         <?php foreach($rst as $event): ?>
 <!-- Modal -->
                                                                <div class="modal fade" id="evento_modal_<?php echo $event['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="Evento_<?php echo $event['id']; ?>">
                                                                  <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                      <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                        <h4 class="modal-title" id="myModalLabel"><?php echo utf8_encode($event['event_name']); ?></h4>
                                                                      </div>
                                                                      <div class="modal-body">
                                                                      <img src="http://admineventos.gpcabling.com.br/storage/<?php echo $event['banner']; ?>" alt="<?php echo utf8_encode($event['event_name']); ?>">
                                                                    <h3 style="margin-top:20px;"><?php echo utf8_encode($event['event_name']); ?></h2>
                                                                    <h6 style="margin-top:10px;">De <?php echo date('d/m/Y H:i', strtotime($event['begin'])); ?> <br> até <?php echo date('d/m/Y H:i', strtotime($event['end'])); ?></h3>
                                                                    <?php echo $event['description']; ?>
                                                                      </div>
                                                                      <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                                                      </div>
                                                                    </div>
                                                                  </div>
                                                                </div>
                                                                <?php endforeach; ?>

        <!--************************************
                Bottom Bar End
        *************************************-->
    </footer>
    <!--************************************
            Footer End
    *************************************-->
    <!--************************************
            Wrapper End
    *************************************-->
    <script src="one_page/js/vendor/jquery-library.js"></script>
    <script src="one_page/js/vendor/bootstrap.min.js"></script>
    <script src="https://maps.google.com/maps/api/js?key=AIzaSyCR-KEWAVCn52mSdeVeTqZjtqbmVJyfSus&language=en"></script>
    <script src="one_page/js/jquery.singlePageNav.min.js"></script>
    <script src="one_page/js/customScrollbar.min.js"></script>
    <script src="one_page/js/owl.carousel.min.js"></script>
    <script src="one_page/js/photoswipe.min.js"></script>
    <script src="one_page/js/photoswipe-ui-default.js"></script>
    <script src="one_page/js/prettyPhoto.js"></script>
    <script src="one_page/js/tilt.jquery.js"></script>
    <script src="one_page/js/countdown.js"></script>
    <script src="one_page/js/gmap3.js"></script>
    <script src="one_page/js/main.js"></script>
    <!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script> -->
    <script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery.validate/1.7/jquery.validate.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {

        $('#form-contato').submit(function(e) {
    e.preventDefault();
}).validate({
                ignore: [],
                rules: {
                    nome: {
                        required: true, minlength: 2
                    },
                    empresa: {
                        required: true, minlength: 2
                    },
                    cpfcnpj: {
                        required: true, minlength: 2
                    },
                    celular: {
                        required: true, minlength: 8
                    },
                    telefone: {
                        required: true, minlength: 8
                    },
                    email: {
                        required: true, email: true
                    },
                    mensagem: {
                        required: true, minlength: 2
                    },
                    "hiddenRecaptcha": {
                        required: function () {
                            if (grecaptcha.getResponse() == '') {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    },

                },
                messages: {
                    nome: {
                        required: 'Preencha o campo nome', minlength: 'No mínimo 2 letras'
                    },
                    empresa: {
                        required: 'Preencha o campo empresa', minlength: 'No mínimo 2 letras'
                    },
                    cpfcnpj: {
                        required: 'Preencha o campo CPF/CNPJ', minlength: 'No mínimo 2 letras'
                    },
                    mensagem: {
                        required: 'No campo mensagem informe o motivo do contato',
                        minlength: 'No mínimo 2 letras'
                    },
                    email: {
                        required: 'Informe o seu email', email: 'Ops, informe um email válido'
                    },
                    celular: {
                        required: 'Informe um número de celular para contato'
                    },
                    telefone: {
                        required: 'Informe um número de telefone para contato'
                    },
                    hiddenRecaptcha: {
                        required: 'Valide o captcha'
                    },

                },
                submitHandler: function (form) {
                    var
                        dados = $('#form-contato').serialize();
                    var
                        $btn = $('#form-contato-btn');

                    $.ajax({
                        type: "POST",
                        url: "one_page/phpmailer/envia_email.php",
                        data: dados,
                        beforeSend: function () {
                            $btn.button('loading');
                        },
                        success: function (data) {
                            $("#form-contato-rtn-msg").append("<div class='alert alert-info' role='alert'>" + data + "</div>");
                            $btn.button('reset');
                        }
                    });


                            //PEGAR VALOR DOS CAMPOS
        var email = $('#email').val();
        var nome = $('#nome').val();
        var empresa = $('#empresa_nome').val();
        var cpfcnpj = $('#cpfcnpj').val();
        var telefone = $('#telefone').val();

        var data = 'email=' + email + '&nome=' + nome+ '&empresa=' + empresa+ '&cnpj=' + cpfcnpj+ '&telefone=' + telefone;


        $.ajax({
            type: 'POST',
            url: 'one_page/polichimp/subscribe.php',
            data: data,
        });

                    return false;
                }
});
        });

        $('.psc').click(function () {
            var url = $(this).attr('url');
            window.open(url);
        });


        var images = new Array('images/logo.png', 'images/logo_paris.png');
        var index = 1;

        function rotateImage() {
            $('#policomLogo').fadeOut('fast', function () {
                $(this).attr('src', images[index]);

                $(this).fadeIn('fast', function () {
                    if (index == images.length - 1) {
                        index = 0;
                    } else {
                        index++;
                    }
                });
            });
        }

        var phones = new Array('<b>POLICOM SP</b>  (11) 2065.0800', '<b>POLICOM RJ</b>  (21) 3888.3727', '<b>POLICOM PR</b>  (41) 3371.1430', '<b>PARIS CABOS</b>  (11) 2172.1877');
        var inicio = 1;

        function rotatePhones() {
            $('#phonePolicom').fadeOut('fast', function () {
                $(this).html(phones[inicio]);

                $(this).fadeIn('fast', function () {
                    if (inicio == phones.length - 1) {
                        inicio = 0;
                    } else {
                        inicio++;
                    }
                });
            });
        }

        $(document).ready(function () {
            $(function () {
                $('a[href="#search"]').on('click', function (event) {
                    event.preventDefault();
                    $('#search').addClass('open');
                    $('#search > form > input[type="search"]').focus();
                });

                $('#search, #search button.close').on('click keyup', function (event) {
                    if (event.target == this || event.target.className == 'close' || event.keyCode == 27) {
                        $(this).removeClass('open');
                    }
                });


                //Do not include! This prevents the form from submitting for DEMO purposes only!
                $('form').submit(function (event) {
                    event.preventDefault();
                    return false;
                })
            });

            setInterval(rotateImage, 12000);
            setInterval(rotatePhones, 5000);
        });
    </script>
    <script type="text/javascript">
        $(window).on('load', function () {
            $('#myModal').modal('show');
        });
    </script>

    <div id="search">
        <button type="button" class="close"> ×</button>
        <form>
            <input type="search" value="" placeholder="Qual produto está procurando?" style="height: 100px;"/>
            <button type="submit" class="btn btn-primary btn-lg"> Pesquisar</button>
        </form>
    </div>
</div>
<script type="text/javascript" async src="https://d335luupugsy2.cloudfront.net/js/loader-scripts/f2f99657-915e-40a1-8e68-62230b4f7fdb-loader.js"></script>
</body>
</html>


{#
***********************
***********************
Demais páginas do site.
***********************
***********************
#}
{% endif %}

{% if router.getRewriteUri() != '/' AND !in_custom_frame %}
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <?
        if($_SERVER["HTTPS"] != "on" AND $servername){
            $this_page = "https://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
            header('Location:'. $this_page);
        }
        ?>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="index, follow">
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

<script type="text/javascript">
    tinyMCE.init({
        // General options
        selector:'#TypeHere',
        mode : "#TypeHere", theme : "advanced",
        plugins : "paste,autolink,lists,pagebreak,style,layer,table,save,advhr,advimage," +
                    "advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace," +
                    "print,contextmenu,directionality,fullscreen,noneditable,visualchars,nonbreaking," +
                    "xhtmlxtras,template,wordcount,advlist,visualblocks",

        // Theme options
        theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
        theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
        theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft,visualblocks",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,
        add_unload_trigger: false,

        theme_advanced_default_font_size : '10pt',
        theme_advanced_default_font_family : 'Verdana',
        theme_advanced_fonts : "Andale Mono=andale mono,monospace;Arial=arial,helvetica,sans-serif;Arial Black=arial black,sans-serif;Book Antiqua=book antiqua,palatino,serif;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier,monospace;Georgia=georgia,palatino,serif;Helvetica=helvetica,arial,sans-serif;Impact=impact,sans-serif;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco,monospace;Times New Roman=times new roman,times,serif;Trebuchet MS=trebuchet ms,geneva,sans-serif;Verdana=verdana,geneva,sans-serif;Webdings=webdings;Wingdings=wingdings,zapf dingbats",
        theme_advanced_font_sizes : '8pt,9pt,10pt,11pt,12pt,14pt,16pt,18pt,20pt,22pt,24pt,28pt,36pt',
        paste_retain_style_properties: 'font-size,font-family,color',
        paste_remove_styles_if_webkit: false,

        powerpaste_word_import: 'merge',
        powerpaste_html_import: 'clean',
        powerpaste_allow_local_images: false,

        // Example content CSS (should be your site CSS)
        // content_css : "css/content.css",

        // Drop lists for link/image/media/template dialogs
        template_external_list_url : "lists/template_list.js",
        external_link_list_url : "lists/link_list.js",
        external_image_list_url : "lists/image_list.js",
        media_external_list_url : "lists/media_list.js",

        // Style formats
        style_formats : [
            {title : 'Bold text', inline : 'b'},
            {title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
            {title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
            {title : 'Example 1', inline : 'span', classes : 'example1'},
            {title : 'Example 2', inline : 'span', classes : 'example2'},
            {title : 'Table styles'},
            {title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
        ],

        // Replace values for the template plugin
        template_replace_values : {
            username : "Some User",
            staffid : "991234"
        }
    });
</script>

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
        <script src="/js/utils.js"></script>
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
<!-- BEGIN MAIN CONTENTS -->
{{ content() }}
<!-- END MAIN CONTENTS -->
{% endif %}
