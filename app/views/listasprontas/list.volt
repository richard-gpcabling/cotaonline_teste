<h1>{{titulo}}</h1>


<div id="contentContainer">
<div id="content">

<div class="page-header">
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
</div>

{% if produto %}
<? $a=0; ?>

<div id="panelCategoryProducts" class="panel panel-default">
<div class="panel-body panel-no-margin table-responsive">

<table class="table table-striped table-condensed" id="tb-visualizados">
	<thead>
		<tr>
			<th></th>
			<th>Nome</th>
			<th>Código GP Cabling</th>
			<th>Part Number</th>
			<th>Unidade</th>
			<th>Preço</th>
			<th></th>
		</tr>
	</thead>
<tbody>

{% for item in produto %}
<? $a++;?>

{% if item.promo == 1 %}
	<tr class="success">
{% else %}
	<tr>
{% endif %}

<td class="no-padding">
<a href="/produto/index/{{item.codigo_produto}}" class="link-table-item">
<img src='{{thumb[item.codigo_produto]}}' class="img-product-table-thumb" {{padding[item.codigo_produto]}}>
</a>
</td>

<td style="max-width:500px;">
	{% if item.promo == 1 %}
		<span class="label label-success">Produto em Promoção*</span>
	{% endif %}
	<a href="../../produto/index/{{item.codigo_produto}}">
	<b>
	{% if item.descricao_site != 'NULL' %}
		{{ item.descricao_site }}
	{% else %}
		{{ item.descricao_sys }}
	{% endif %}
	</b>
	</a><br>

	{% if produto_documents[item.codigo_produto] is defined %}
		{{ partial('elements/documents-dropdown', [
				'documents': produto_documents[item.codigo_produto]
			])
		}}
	{% endif %}

	{% if item.tipo_fiscal is defined and item.total_estoque > 0 and item.tipo_fiscal != 'SV' and auth %}
	<small><b style="color:#5cb85c">
		Estoque Total Disponível: <? echo number_format($item->total_estoque, 0, ',', '.'); ?>
		{{converteUnidadeVenda(item.unidade_venda)}}{% if item.total_estoque > 1 %}s{% endif %}
	</b></small>
	{% endif %}

	{% if item.tipo_fiscal is defined and item.tipo_fiscal == 'SV' and auth %}
	<small><b style="color:#5cb85c">Produto digital</b></small>
	{% endif %}

	{% if item.tipo_fiscal is defined and item.total_estoque == 0 and item.tipo_fiscal != 'SV' and auth %}
	<small><b style="color:gray">Sob consulta</b></small>
	{% endif %}

	{% if origem[item.codigo_produto] is defined %}
	<br><small><b style="color:gray">Produto faturado por {{origem[item.codigo_produto]}}
	| ICMS (Já incluso): {{icms[item.codigo_produto]}}%
	</b></small>
	{% endif %}

	{% if item.moeda_venda == 'dolar' and auth %}
		<br><small>*Preço convertido do dólar PTAX</small>
	{% endif %}

	{% if item.promo == 1 %}
		<br><small>*Produtos em promoção estão sujeitos à disponibilidade de estoque</small>
	{% endif %}
</td>

<td>
	{{ item.codigo_produto }}
</td>

<td>
	{{ item.ref }}
</td>

<td>
	{{converteUnidadeVenda(item.unidade_venda)}}
</td>

<td>
	{% if price is empty or price[item.codigo_produto] == 0 %}
		Sob Consulta
	{% endif %}

	{% if price[item.codigo_produto] is defined and price[item.codigo_produto] != 'Sob Consulta' and price[item.codigo_produto] != 0 %}
		{{ price[item.codigo_produto]|number_format(2, ',', '.') }}
	{% endif %}

	{% if price[item.codigo_produto] is defined and price[item.codigo_produto] == 'Sob Consulta' %}
		Sob consulta
	{% endif %}

	<?
	//dd($price);
	?>

	{#
	{% if price[item.codigo_produto] is defined and price[item.codigo_produto] != 'Sob Consulta' %}
	{% if price[item.codigo_produto] is defined and price[item.codigo_produto] != 0 %}
	<span class="price-tag --regular">
		R$ <? echo number_format($price[$item->codigo_produto], 2, ',', '.'); ?>

	</span>

	{% else %}
		Sob Consulta
	{% endif %}

	{% else %}
		{% if price is defined %}{{ price }}{% endif %}
	{% endif %}
	#}
</td>

<td>
{% if auth['id'] %}
{% set user_id = auth['id'] %}
<label for="qtd" class="sr-only">Qtd</label>

<input type="number" min="0" max="1000000" class="form-control text-center" id="qtd{{item.codigo_produto}}" placeholder="qtd{{item.codigo_produto}}" style="display:inline-block; width:80px; vertical-align:top; margin-right:2px;" value="1">

<button
	id="btn{{item.codigo_produto}}"
	type="button" class="btn btn-primary btn-md" style="vertical-align:top; margin-bottom:2px;"
 	onclick="disableButton(this);location.href='/cart/add/{{item.codigo_produto}}/'+$('#qtd{{item.codigo_produto}}').val()+'/{{ requestURI() }}'">
	<span class="glyphicon glyphicon-shopping-cart"></span>&nbsp;Adicionar
</button>

{% set cart_prod_info = cartInfo(user_id,item.codigo_produto) %}
{% if cart_prod_info %}
	<br>
	<hr style="margin:5px 0;">
	<button type="button"
	class="btn btn-default btn-xs"
	id="btn{{item.codigo_produto}}"
	onclick="disableButton(this);location.href='/cart/index'">
		No orçamento: {{cart_prod_info}} {% if cart_prod_info > 1 %}itens{% else %}item{% endif %}
	</button>
	<button type="button"
		class="btn btn-danger btn-xs"
		style="border:none;"
		id="btn{{ item.codigo_produto }}-removeItem"
		onclick="disableButton(this);location.href='/cart/remove/{{item.codigo_produto}}/{{ requestURI() }}'">
		<span class="glyphicon glyphicon-trash"></span>
	</button>
{% endif %}

{% else %}
<a href="/session/start" class="link-table-item">
	Acesse para consultar
</a>
{% endif %}
</td>

</tr>
{% endfor %}
{% endif %}
</tbody>
</table>
</div>
</div>
</div>
</div>