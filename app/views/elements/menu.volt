{% if auth['id'] %}
	<div class="hidden-xs" id="pages-list" style="overflow:hidden;">
		<div class="panel panel-primary panel-primary-info">
			<ul class="list-group _small">
				<li class="list-group-item"><a class="" href="/">
					<span class="glyphicon glyphicon-home"></span>
						&nbsp;
						Início
				</a></li>
				{% if auth['role'] == 'administrador' %}
				<li class="list-group-item"><a class="" href="/pages/status">
					<span class="glyphicon glyphicon-grain"></span>
						&nbsp;
						Status
				</a></li>
				{% endif %}
				{#
				{% ifs
					auth['client'] is defined OR
					(
						auth['role'] is defined AND
						auth['role']=='vendedor'
					) OR
					(
						auth['role'] is defined AND
						auth['role']=='administrador'
					)
				%}
					<li class="list-group-item"><a class="" href="/dashboard/index">
						<span class="glyphicon glyphicon-dashboard"></span>
							&nbsp;
							Dashboard
					</a></li>
				{% endif %}
				#}
				<li class="list-group-item"><a class="" href="/conta/index">
					<span class="glyphicon glyphicon-cog"></span>
					&nbsp;
					Minha conta
					&nbsp;
					<!-- <span id="accountTasks" class="badge" style="float:right;">&hellip;</span> -->
				</a></li>

				{% if auth['role'] == 'cliente parceiro' and auth['vendedor'] or auth['role'] == 'cliente normal' and auth['vendedor'] is defined %}
				<li class="list-group-item"><a class="" href="/usuario/meuvendedor">
					<span class="glyphicon glyphicon-user"></span>
					&nbsp;
					Meu Vendedor
					&nbsp;
					<!-- <span id="accountTasks" class="badge" style="float:right;">&hellip;</span> -->
				</a></li>
				{% endif %}

				<!-- <li class="list-group-item"><a class="" href="#">
					<span class="glyphicon glyphicon-lock"></span>
					&nbsp;
					Meus dados de acesso
				</a></li> -->{% if (auth['mensagem_relacionado'] is defined and auth['mensagem_relacionado']) OR (auth['mensagem_naorelacionado'] is defined AND auth['mensagem_naorelacionado']) %}
				<li class="list-group-item"><a class="" href="/contato/search">
					<span class="glyphicon glyphicon-bell"></span>
					&nbsp;
					Mensagens
					<span id="msgtasks" class="badge" style="float:right;"></span>
				</a></li>{%endif%}
				{% if auth['role']=='administrador' %}
				<li class="list-group-item"><a class="" href="/cliente/index">
					<span class="glyphicon glyphicon-user"></span>
					&nbsp;
					Clientes
					<span id="clientstasks" class="badge" style="float:right;"></span>
				</a></li>{%endif%}

				{% if auth['ativacao_usuario'] is defined AND auth['ativacao_usuario'] %}
				<li class="list-group-item"><a class="" href="/usuario/index">
					<span class="glyphicon glyphicon-certificate"></span>
					&nbsp;
					Usuários
					{% if auth['role']=='administrador' %}<span id="userstasks" class="label label-warning" style="float:right;"></span>{% endif %}
				</a></li>
				{%endif%}

				<?php
				$usuarios_pendentes=Usuario::find(['columns' => 'id,name,email,status,codigo_cliente,cnpj_cpf_raw','conditions' => "codigo_cliente IS NULL AND on_view_empresa_cadastrar = 1 AND cnpj_cpf_raw IS NOT NULL AND status = 'confirmed'"]);
				$usuarios_pendentes = count($usuarios_pendentes);
				?>
				{% if auth['role'] == 'administrador' %}
				<li class="list-group-item"><a class="" href="/usuario/pendentes">
					<span class="glyphicon glyphicon-pencil"></span>
					&nbsp;
					Empresas para Cadastrar
					{% if auth['role']=='administrador' %}
						<?php if($usuarios_pendentes>0){
							echo '<span class="label label-danger" style="float:right;">'.$usuarios_pendentes.'</span>';
						} ?>
					{% endif %}
				</a></li>
				{%endif%}

				<li class="list-group-item"><a class="" href="/orcamento/index">
					<span class="glyphicon glyphicon-list-alt"></span>
					&nbsp;
					Orçamentos
					<span id="invoicestasks" class="label label-warning" style="float:right;"></span>
				</a></li>

				{% if auth['role'] == 'administrador' %}
				<li class="list-group-item"><a class="" href="/orcamento/reporthome">
					<span class="glyphicon glyphicon-list-alt"></span>
					&nbsp;
					Relatórios de Orçamentos
					<span id="invoicestasks" class="label label-warning" style="float:right;"></span>
				</a></li>
				{% endif %}

				{% if auth['role'] == 'administrador' %}
				<li class="list-group-item">
				<span class="label label-success">NOVO!</span>&nbsp;
				<a class="" href="/report/">
					<span class="glyphicon glyphicon-equalizer"></span>
					&nbsp;
					Relatórios Gráficos
					<span id="invoicestasks" class="label label-warning" style="float:right;"></span>
				</a></li>
				{% endif %}

				{% if auth['role'] == 'administrador' %}
				<li class="list-group-item">
					<a class="" href="/listasprontas/">
					<span class="glyphicon glyphicon-th-list"></span>
					&nbsp;
					Listas Prontas
					<span id="invoicestasks" class="label label-warning" style="float:right;"></span>
				</a></li>
				{% endif %}

				{% if auth['admin_produto'] is defined AND auth['admin_produto'] %}
				<li class="list-group-item"><a class="" href="/produto/admin">
					<span class="glyphicon glyphicon-tags"></span>
					&nbsp;
					Produtos
				</a></li>
				{% endif %}

				{% if auth['role']=='administrador' %}
					<li class="list-group-item">
						<a class="" href="/produto/produtoviewcount">
							<span class="glyphicon glyphicon glyphicon-signal"></span>&nbsp;
							&nbsp;Produtos - Acessos
						</a></li>
				{%endif%}

				{% if auth['role']=='administrador' %}
					<li class="list-group-item">
						<a class="" href="/produto/categoria">
							<span class="glyphicon glyphicon-filter"></span>
							&nbsp;Produtos - Categorias
						</a></li>
				{%endif%}

				{% if auth['role']=='administrador' %}
				<li class="list-group-item">
					<a class="" href="/produto/limbo">
					<span class="glyphicon glyphicon-eye-close"></span>
					&nbsp;Produtos - Limbo
				</a></li>
				{%endif%}
				
				{% if auth['email'] =='bruno@gpcabling.com.br' %}
				<li class="list-group-item"><a class="" href="/acl/index">
					<span class="glyphicon glyphicon-tasks"></span>
					&nbsp;
					Permissões
				</a></li>{% endif %}
				
				{% if auth['logs'] is defined AND auth['logs'] %}
				<li class="list-group-item">
					<a class="" href="/produto/logsearchquery/busca">
					<span class="glyphicon glyphicon-search"></span>
					&nbsp;
					Log de Busca
				</a></li>
				{%endif%}

				{% if auth['role']=='administrador' or auth['role']=='vendedor' %}
				<li class="list-group-item">
					<a class="" href="/produto/loguser">
					<span class="glyphicon glyphicon-search"></span>
					&nbsp;
					Produtos por usuários
				</a></li>
				{%endif%}
			</ul>
		</div>
	</div>
{% endif %}

{% if auth['email']=='bruno@gpcabling.com.br' %}
	<p style="color:red;">
	Using Phalcon Version — <b><? echo Phalcon\Version::get(); ?></b><br>
	Versão Atual do PHP — <b><? echo phpversion(); ?></b><br><br>
	</p>
{% endif %}


{#
<div class="list-group _small visible-xs-block">
	<a href="#" class="list-group-item" data-toggle="collapse" data-target="#categories-list">categorias</a>
</div>
#}

<div id="categories-list" class="list-group _small" style="overflow:hidden;">
<a href="/fabricante"  id="fabricante" class="list-group-item menu-visible" data-toggle-target="/fabricante">
Todos os Fabricantes
</a>
</div>

<div id="categories-list" class="list-group _small" style="overflow:hidden;">
<a href="/listasprontas/lists"  id="promo" class="list-group-item menu-visible" data-toggle-target="/promo">
<span class="glyphicon glyphicon-star"></span>
Listas Prontas
</a>
</div>

<div id="categories-list" class="list-group _small" style="overflow:hidden;">
	{% for category in this.categories %}
		{% if category['status']==1 %}
			<a href="/produto/category/{{category['id']}}"  id="category-00-{{category['id']}}" class="list-group-item menu-visible level-0 no-parent" data-toggle-target=".parent-00-{{category['id']}}">
				{{category['nome']}}
			</a>
		{% endif %}
	{% endfor %}
</div>


<div id="categories-list" class="list-group _small hidden-xs">
<!-- AddToAny BEGIN -->
Compartilhar:
<div class="a2a_kit a2a_kit_size_32 a2a_default_style">
<a class="a2a_button_facebook"></a>
<a class="a2a_button_twitter"></a>
<a class="a2a_button_email"></a>
<a class="a2a_button_whatsapp"></a>
<a class="a2a_button_linkedin"></a>
</div>
<script async src="https://static.addtoany.com/menu/page.js"></script>
<br><br>
</div>

{#
<!-- AddToAny END -->
<!--
<div class="panel panel-default">
	<ul class="list-group">
		<li class="list-group-item"><a href="#">Sugestões de lista</a></li>
		<li class="list-group-item"><a href="#">Fabricantes</a></li>
		<li class="list-group-item"><a href="#">Mapa do site</a></li>
	</ul>
</div>
-->
#}
