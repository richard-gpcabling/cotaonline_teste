<?
//TODO - Verificar erro de imagem não encontrada
error_reporting(0);
?>
{% if page.total_items > 1 %}
<div id="panelResults">
<div class="page-header">
	<h1>Resultados</h1>
	<div class="well bg-success">
		<h4>Total de {{ page.total_items }} resultado{% if page.total_items > 1 %}s{% endif %}
		{% if searchquery %} para <i style="color:green;">{{searchquery}}{% endif %}</i></b></h4>
	</div>
	
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
	<!-- AddToAny END -->
</div>

<div id="panelCategoryProducts" class="panel panel-default">
<div class="panel-body panel-no-margin table-responsive">

<table class="table table-striped table-condensed" id="tb-visualizados">
	<thead>
		<tr class="with-pagination" colspan="8">
			<td colspan="8">
				{% include "elements/page-navigation.volt" %}
			</td>
		</tr>
		<tr>
			<th></th>
			<th>Nome</th>
			<th>
				<select id="customSearchManufacturer" class="form-control" data-search-query="{{ searchquery }}">
				<option value="">Fabricante</option>
				{% for index,fabricante in fabricantes %}
					<option value="{{ index }}"
							{% if fabr == index %}
								selected="selected"
							{% endif %}
					>{{ fabricante }}</option>
				{% endfor %}
			</select></th>
			<th>Código GP Cabling</th>
			<th>Part Number</th>
			<th>Unidade</th>
			<th>Preço</th>
			<th></th>
		</tr>
	</thead>

<tbody>
{% for item in page.items %}

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
	{% if documents_products[item.codigo_produto] is defined %}
		{{ partial('elements/documents-dropdown', [
				'documents': documents_products[item.codigo_produto]
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

	{% if origem[item.codigo_produto] %}
	<br><small><b style="color:gray">Produto faturado por {{origem[item.codigo_produto]}}
	| ICMS (Já incluso): {{icms[item.codigo_produto]}}%
	</b></small>
	{% endif %}

	{% if item.moeda_venda is defined and item.moeda_venda == 'dolar' and auth %}
	<br><small>¹Preço convertido do dólar PTAX</small>
	{% endif %}

	{% if item.promo == 1 %}
		<br><small>*Produtos em promoção estão sujeitos à disponibilidade de estoque</small>
	{% endif %}
</td>

<td>
	<a href="../../fabricante/index/{{ item.fabricante_nome }}">
		{{ item.fabricante_nome }}
	</a>
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
	{% if price[item.codigo_produto] != 'Sob Consulta' %}
	{% if price[item.codigo_produto] != 0 %}
	<span class="price-tag --regular">
	R$ <? echo number_format($price[$item->codigo_produto], 2, ',', '.'); ?>

	</span>
	
	{% else %}
		Sob Consulta
	{% endif %}

	{% else %}
		{{ price[item.codigo_produto] }}
	{% endif %}
</td>

<td>
{% if auth['id'] %}
<label for="qtd" class="sr-only">Qtd</label>

<input type="number" min="0" max="1000000" class="form-control text-center" id="qtd{{item.codigo_produto}}" placeholder="qtd{{item.codigo_produto}}" style="display:inline-block; width:80px; vertical-align:top; margin-right:2px;" value="1">

<? //TODO - Consertar o redirect ?>
<button
	id="btn{{item.codigo_produto}}"
	type="button" class="btn btn-primary btn-md" style="vertical-align:top; margin-bottom:2px;"
	onclick="disableButton(this);location.href='/cart/add/{{item.codigo_produto}}/'+$('#qtd{{item.codigo_produto}}').val()+'/**cart**index'">
	<span class="glyphicon glyphicon-shopping-cart"></span>&nbsp;Adicionar
</button>
{% else %}
<a href="/session/start" class="link-table-item">
	Acesse para consultar
</a>
{% endif %}
</td>

</tr>
{% endfor %}
</tbody>
<tfoot class="with-pagination">
	<tr>
		<th colspan="10">
			{{ partial('elements/page-navigation') }}
		</th>
	</tr>
</tfoot>
</table>
</div>
</div>
</div>

{% else %}
<div class="well bg-alert">
	<h4>Nenhum resultado{% if searchquery %} para <i>{{searchquery}}{% endif %}</i>.</b></h4>
	<p>Por favor, tente outro termo de busca.</p>
</div>
{% endif %}