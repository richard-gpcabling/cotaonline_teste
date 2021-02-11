{% set session = session.get('auth') %}
{% set user_id = auth['id'] %}
{% set cart_header_info = cartInfo(user_id,0) %}

<?
	$header_elements = StyleElements::find(["conditions" =>"status = 1 AND type = 'header'",'order'=>'[order] asc']);
?>

{% if  session != null %}
	<script type="text/javascript">
		document.addEventListener( 'DOMContentLoaded', function () {
			Header.update();
		}, false );
	</script>
{% endif %}

<div class="container-fluid" style="padding-top:15px; margin-bottom:20px;
background-image:linear-gradient(white 70%,#e8e8e8); border-bottom:2px solid #d6d6d6;">
	<div class="row">
		<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
			<a href="/" style="padding:0;margin-left:0px;">
				<img alt="Brand" src="/img/logo_rotativo_cotaonline.gif" style="width:100%; max-width:192px;">
			</a>
			<button type="button" class="btn btn-primary navbar-toggle collapsed" data-toggle="collapse"
			data-target="#bs-navbar-collapse,#pages-list,#h-list" aria-expanded="false" id="toggle">
				<span class="glyphicon glyphicon-menu-hamburger"></span>
				&nbsp;
				Menu
			</button>
		</div>
		
		<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
			<form action="/produto/customSearch" method="GET" class="form-inline">
				<input name="searchquery" type="text" class="form-control" placeholder="procurar por…" style="width:70%;">
				<button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span>&nbsp;Buscar</button>
			</form>
		<div id="h-list" style="overflow:hidden;">
			<ul class="nav navbar-nav navbar-left fixed-top" id="nav-topo">
				{% for item in header_elements %}
				<li><a href="{{item.content}}" {% if item.blank == 1 %} target="_blank" {% endif %}>
					{{item.string}}
				</a></li>
				{% endfor %}
				<li> 
					<a href="https://api.whatsapp.com/send?phone=551120650800" target="_blank" style="text-decoration:none;"><img src="/img/wapp_icon.png" style="height:20px;"> +55 11 20650800
					</a>
				</li>
			</ul>
		</div>
		</div>
		
		<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
			{% if auth['id'] %}
						<span class="">Bem vindo,
							<a href="/conta/index" role="button" class="btn btn-link">{{auth['name']}}</a></span>
							<a href="/session/end" role="button" class="btn btn-link"><span class="text-danger">
								<span class="glyphicon glyphicon-lock"></span>&nbsp;Sair</span>
							</a>
				{% endif %}
				{% if not auth['id'] %}
							<a role="button" class="btn btn-success btn-acess" href="/session/index">
								<span class="glyphicon glyphicon-lock" style="font-size:12px;"></span>&nbsp;Acessar
							</a>
				{% endif %}
			
			{% if cart_header_info > 0 %}
			<div>
				<a href="/cart/index" role="button" class="btn btn-primary navbar-btn" id='orcamento'>
					<span class="glyphicon glyphicon-shopping-cart"></span>&nbsp;Orçamento
					&nbsp;&nbsp;&nbsp;
					<span class="badge" style="float:right;display:;">{{cart_header_info}}</span>
				</a>
			</div>
			{% endif %}

		</div>
	</div>
</div>


{% if auth['id']%}
{% if auth['client'] == null or auth['client'] == "" %}
<div style="margin:15px;">
	{% include "elements/message-no-price.volt" %}
</div>
{% endif %}
{% endif %}

{% if auth['status'] =='confirmed' %}
<div class="alert alert-info" role="alert" style="font-size:20px; margin:15px;">
<b>Seu cadastro está confirmado!</b> Nossa equipe está processando seu cadastro e, quando estiver ativo, você terá acesso à todos os preços no site.<br> Por enquanto, você poderá navegar no site e salvar orçamentos sem visualizar os preços.
</div>
{% endif %}


{% if auth['id'] == null and router.getRewriteUri() != '/session' and router.getRewriteUri() != '/register' and router.getRewriteUri() != '/session/index' %}
<div class="well bg-primary" role="alert" style="font-size:20px; margin:15px;">
  <h3>Cadastre-se já! — Benefícios para cadastrados</h3>
 	<h2>- Visualizar preços dos produtos - Quantidade em estoque - Salvar orçamentos - Suporte de especialistas no seu projeto<br>
  E muito mais...<h2>

  <a class="btn btn-warning btn-lg" href="/register" role="button">Cadastre-se</a>
  <a class="btn btn-success btn-lg" href="/session" role="button">Já possuo cadastro</a>
</div>

{% endif %}