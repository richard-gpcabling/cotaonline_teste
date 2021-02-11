{% set showprice = false  %}
{% if session.get('auth') != null %} {# get session auth #}
	{% if auth['client'] is defined %}
		{% if
			(cliente_estado is defined and cliente_estado == '') OR
			(cliente_estado is NOT defined ) OR
			(cliente_tipo is NOT defined ) OR
			(cliente_tipo is defined and cliente_tipo == '')
		%}
			{#{% include "elements/message-no-price.volt" %}#}
		{% else %}
			{% set showprice = true %}
		{% endif %}
	{% endif %}
{% endif %}

<!-- BEGIN BANNER -->
<div id="carousel-home" class="carousel slide" data-ride="carousel">
	<ol class="carousel-indicators">
		<li data-target="#carousel-home" data-slide-to="0" class="active"></li>
		<li data-target="#carousel-home" data-slide-to="1"></li>
		<li data-target="#carousel-home" data-slide-to="2"></li>
		<li data-target="#carousel-home" data-slide-to="3"></li>
		<li data-target="#carousel-home" data-slide-to="4"></li>
		<li data-target="#carousel-home" data-slide-to="5"></li>
		<!--<li data-target="#carousel-home" data-slide-to="6"></li>-->
	</ol>
	<div class="carousel-inner" role="listbox" style="border-radius:4px; box-shadow: 0 1px 2px rgba(0,0,0,0.25);">
		<div class="item active">
			<a href="/fabricante/index/AP" >
				<img src="/img/01_banners_edge_computing.jpg" alt="zero">
			</a>
		</div>

		<div class="item">
			<a href="/fabricante/index/AP">
				<img src="/img/02_banners_micro_data_center.jpg" alt="zero um">
			</a>
		</div>

		<div class="item">
			<a href="/produto/category/17">
				<img src="/img/03_banners_fluke_eletrica.jpg" alt="zero dois">
			</a>
		</div>

		<div class="item">
			<a href="/produto/category/14/128,129,130/AP">
				<img src="/img/04_banners_racks_apc.jpg" alt="zero tres">
			</a>
		</div>

		<!--<div class="item">
			<a href="/produto/category/379">
				<img src="/img/05_banners_baterias.jpg" alt="zero quinta">
			</a>
		</div>-->

		<div class="item">
			<a href="/produto/category/2/171/0">
				<img src="/img/06_banners_lan_active.jpg" alt="zero sexta">
			</a>
		</div>

		<div class="item">
			<a href="/fabricante/index/AP">
				<img src="/img/07_banners_apc.jpg" alt="zero setima">
			</a>
		</div>
		<!-- footer text -->
	</div>
	<!-- Left and right controls -->
	  <a class="left carousel-control" href="#carousel-home" data-slide="prev">
	    <span class="glyphicon glyphicon-chevron-left"></span>
	    <span class="sr-only">Previous</span>
	  </a>
	  <a class="right carousel-control" href="#carousel-home" data-slide="next">
	    <span class="glyphicon glyphicon-chevron-right"></span>
	    <span class="sr-only">Next</span>
	  </a>
	<!--
	<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
		<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
		<span class="sr-only">Previous</span>
	</a>
	<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
		<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
		<span class="sr-only">Next</span>
	</a>
	-->
</div>
<!-- END BANNER -->

<br>
<? $a=0; ?>

<h1>Produtos em Promo</h1>

{% for item in produto %}
<? if ($a==0 OR $a % 4 == 0): echo '<div class="row">'; endif;?>
  <div class="col-md-3">
		<div class="thumbnail" style="min-height:270px; padding-top:25px;">
			<a href="/produto/index/{{item.codigo_produto}}">
				<img src="{{produto_img[item.codigo_produto]}}" style="max-height:130px;">
				<div class="text-center" style="padding-top:15px;">
					<p style="font-size:18px;">{{item.descricao_sys}}</p>
				</div>
			</a>

			{% if produto_documents[item.codigo_produto] is defined %}
				<div class="d-flex justify-content-end">
					{{ partial('elements/documents-dropdown', [
							'documents': produto_documents[item.codigo_produto]
						])
					}}
				</div>
			{% endif %}
    </div>
  </div>
<? $a++; ?>
<? if ($a==0 OR $a % 4 == 0): echo '</div>'; endif;?>
{% endfor %}

{#
<h1>Soluções</h1>

<div class="row">
<div class="col-sm-4">
	<div class="card card-price">
	<div class="card-img">
	<a href="#">
	<img src="/img/homecards/infraestrutura.png" class="img-responsive">
	<div class="card-caption">
	<span class="h2">Infraestrutura</span>
	</div>
	</a>
	</div>

	<div class="card-body">
	<div class="lead">Solução Completa em</div>
	<ul class="details">
	<li>Lorem Ipsum, texto frio</li>
	<li>All good things come to those who wait.</li>
	<li><b>Destaque:</b>Lorem Ipsum, texto frio</li>
	</ul>
	<a href="#" class="btn btn-primary btn-lg btn-block buy-now">
	Saiba Mais
	</span>
	</a>
	</div>
	</div>
</div>

<div class="col-sm-4">
	<div class="card card-price">
	<div class="card-img">
	<a href="#">
	<img src="/img/homecards/cabeamento.png" class="img-responsive">
	<div class="card-caption">
	<span class="h2">Cabeamento</span>
	</div>
	</a>
	</div>

	<div class="card-body">
	<div class="lead">Solução Completa em</div>
	<ul class="details">
	<li>Lorem Ipsum, texto frio</li>
	<li>All good things come to those who wait.</li>
	<li><b>Destaque:</b>Lorem Ipsum, texto frio</li>
	</ul>
	<a href="#" class="btn btn-primary btn-lg btn-block buy-now">
	Saiba Mais
	</span>
	</a>
	</div>
	</div>
</div>

<div class="col-sm-4">
	<div class="card card-price">
	<div class="card-img">
	<a href="#">
	<img src="/img/homecards/analise_cert.png" class="img-responsive">
	<div class="card-caption">
	<span class="h2">Análise e Certificação</span>
	</div>
	</a>
	</div>

	<div class="card-body">
	<div class="lead">Solução Completa em</div>
	<ul class="details">
	<li>Lorem Ipsum, texto frio</li>
	<li>All good things come to those who wait.</li>
	<li><b>Destaque:</b>Lorem Ipsum, texto frio</li>
	</ul>
	<a href="#" class="btn btn-primary btn-lg btn-block buy-now">
	Saiba Mais
	</span>
	</a>
	</div>
	</div>
</div>
</div>

<hr>

<div class="row">
<div class="col-sm-4">
	<div class="card card-price">
	<div class="card-img">
	<a href="#">
	<img src="/img/homecards/gerenciamento_inteligente.png" class="img-responsive">
	<div class="card-caption">
	<span class="h2">Gerenciamento Inteligente</span>
	</div>
	</a>
	</div>

	<div class="card-body">
	<div class="lead">Solução Completa em</div>
	<ul class="details">
	<li>Lorem Ipsum, texto frio</li>
	<li>All good things come to those who wait.</li>
	<li><b>Destaque:</b>Lorem Ipsum, texto frio</li>
	</ul>
	<a href="#" class="btn btn-primary btn-lg btn-block buy-now">
	Saiba Mais
	</span>
	</a>
	</div>
	</div>
</div>

<div class="col-sm-4">
	<div class="card card-price">
	<div class="card-img">
	<a href="#">
	<img src="/img/homecards/micro-data-center.png" class="img-responsive">
	<div class="card-caption">
	<span class="h2">Micro Data Center</span>
	</div>
	</a>
	</div>

	<div class="card-body">
	<div class="lead">Solução Completa em</div>
	<ul class="details">
	<li>Lorem Ipsum, texto frio</li>
	<li>All good things come to those who wait.</li>
	<li><b>Destaque:</b>Lorem Ipsum, texto frio</li>
	</ul>
	<a href="#" class="btn btn-primary btn-lg btn-block buy-now">
	Saiba Mais
	</span>
	</a>
	</div>
	</div>
</div>

<div class="col-sm-4">
	<div class="card card-price">
	<div class="card-img">
	<a href="#">
	<img src="/img/homecards/solucoes_corporativas.png" class="img-responsive">
	<div class="card-caption">
	<span class="h2">Soluções Corporativas</span>
	</div>
	</a>
	</div>

	<div class="card-body">
	<div class="lead">Solução Completa em</div>
	<ul class="details">
	<li>Lorem Ipsum, texto frio</li>
	<li>All good things come to those who wait.</li>
	<li><b>Destaque:</b>Lorem Ipsum, texto frio</li>
	</ul>
	<a href="#" class="btn btn-primary btn-lg btn-block buy-now">
	Saiba Mais
	</span>
	</a>
	</div>
	</div>
</div>
</div>
#}

{% if false and session.get('auth') != null %} {# get session auth #}
	{% if auth['client'] is defined%}
		<span class="small">
			&lowast; O valor deste produto não está disponível.
		</span>
	{% else %}
		<script type="text/javascript">
			document.addEventListener( 'DOMContentLoaded', function () {}, false );
		</script>
	{% endif %}
{% endif %}
