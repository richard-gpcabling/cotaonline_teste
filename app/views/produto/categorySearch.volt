<?
//TODO - Verificar erro de imagem não encontrada
error_reporting(0);
?>

{% set counterResults = 0 %}

{% if category != null %}

	<script type="application/ld+json">
		{{ schema.jsonld|json_encode}}
	</script>

	{# CATEGORY DETAILS #}
	<div class="page-header">
		<h1>
			{{ category.nome }}
		</h1>

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

	{# CATEGORIES #}
	<div class="well well-sm filter-wrapper{{ subcategories.has_selection ? ' bg-info' : '' }}">
		<h2>Filtrar por categoria</h2>
		{% for subcategory in subcategories.items %}
			<span style="display:inline-block; white-space: nowrap;" class=" {{ subcategory.selected ? 'with-selection' : 'without-selection' }} group-subcategories">
			<input type="checkbox"
				   name="{{ subcategory.nome }}"
				   id="check-{{ subcategory.id }}"
				   class="category-filter"
				   data-id="{{ subcategory.id }}"
					{% if subcategory.selected %}
						checked="checked"
					{% endif %}
					style="font-size:1.5em; cursor:pointer;"
				   data-may-check-all="true">
			<label for="check-{{ subcategory.id }}" class="text-label" style="margin:0.5em 0.5em 0.5em 0.1em; cursor:pointer;">
				{{ subcategory.nome }}
			</label>
		</span>
		{% endfor %}

		<small style="display:inline-block; ---float:right; white-space:nowrap; margin-top:10px;"><a href="javascript:checkAll(true,'.group-subcategories');Product.filter().execute();">(marcar todos)</a></small>
		{% if subcategories.has_selection %}
			<small style="display:inline-block; ---float:right; white-space:nowrap; margin-top:10px;"><a href="javascript:checkAll(false,'.group-subcategories');Product.filter().execute();">(desmarcar todos)</a></small>
		{%endif%}
	</div>

	{# FILTERS #}
	{% for key,filter in filters %}
		{# Bug confirmed https://github.com/phalcon/cphalcon/issues/12482 #}
		<?php $father = $filter->father; ?>

		<?php $items = $filter->items; ?>

		<div class="well well-sm filter-wrapper{{ filter.has_selection is defined ? ' bg-info' : '' }}">
			<h2>{{ father.nome }}</h2>
			{% for item in items %}
				<span style="display:inline-block; white-space: nowrap;" class=" {{ item.selected ? 'with-selection' : 'without-selection' }} group-filters-{{ key }}">
				<input type="checkbox"
					   name="{{ item.nome }}"
					   id="check-filter-{{ item.id }}"
					   class="category-filter"
					   data-id="{{ item.id }}"
					   data-parent="{{ item.parent }}"
						{% if item.selected %}
							checked="checked"
						{% endif %}
						style="font-size:1.5em; cursor:pointer;"
					   data-may-check-all="true">
				<label for="check-filter-{{ item.id }}" class="text-label" style="margin:0.5em 0.5em 0.5em 0.1em; cursor:pointer;">
					{{ item.nome }}
				</label>
			</span>
			{% endfor %}

			<small style="display:inline-block; ---float:right; white-space:nowrap; margin-top:10px;"><a href="javascript:checkAll(true,'.group-filters-{{ key }}');Product.filter().execute();">(marcar todos)</a></small>
			{% if item.selected == 1 %}
				<small style="display:inline-block; ---float:right; white-space:nowrap; margin-top:10px;"><a href="javascript:checkAll(false,'.group-filters-{{ key }}');Product.filter().execute();">(desmarcar todos)</a></small>
			{%endif%}
		</div>
	{% endfor %}

	{# Main table #}
	{% if page.items|length > 0 %}
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
						<th>&nbsp;</th>
						<th>Nome</th>
						<th>
							<select id="selectmanufacturer" class="form-control" onchange="Product.filter()">
								<option value="0">Fabricante</option>
								{% for index,fabricante in manufacturers %}
									<option value="{{ fabricante.sigla }}"
											{% if manufacturer == fabricante.sigla %}
												selected="selected"
											{% endif %}
									>{{ fabricante.nome }}</option>
								{% endfor %}
							</select>
						</th>
						<th>Código GP Cabling</th>
						<th>Part Number</th>
						<th>Unidade</th>

						{% if show_price %}
							{% if auth and auth['custo_pagina'] and auth['status'] =='active' %}

							{% endif %}
						{% endif %}
						<th>Preço</th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					{% for produto in page.items %}
						{% if produto.status == 1 %}
						{% if produto.promo == 1 %}
							<tr class="success">
						{% else %}
							<tr>
						{% endif %}
								{% set counterResults = counterResults + 1 %}
								<td class="no-padding">
									<a href="/produto/index/{{ produto.codigo_produto }}" class="link-table-item">
										{% if produto.description.imagem %}
											<img src="{{ produto.description.imagem.relativeUrl }}" class="img-product-table-thumb" {{ produto.description.imagem.htmlPadding }} {{ produto.description.imagem.htmlAttrSize }} alt="{{ produto.descricao_sys }}">
										{% else %}
											<img src="/img/no-image-placeholder.png" class="img-product-table-thumb "alt="{{ produto.descricao_sys }}">
										{% endif %}
									</a>
								</td>
								<td style="max-width:500px;">
									{% if produto.promo == 1 %}
										<span class="label label-success">Produto em Promoção*</span>
									{% endif %}

									<a href="/produto/index/{{ produto.codigo_produto }}" class="link-table-item">
										{% if produto.descricao_site != 'NULL' %}
											{{ produto.descricao_site }}
										{% else %}
											{{ produto.descricao_sys }}
										{% endif %}
									</a>

									{% if produto_documents[produto.codigo_produto] is defined %}
										{{ partial('elements/documents-dropdown', [
												'documents': produto_documents[produto.codigo_produto]
											])
										}}
									{% endif %}

									{% if auth and auth['status'] == 'active' %}
										{% if produto.estoque.total_estoque > 0 %}
											{% if produto.tipo_fiscal == 'SV' %}
												<br><small style="color:#5cb85c"><strong>Produto digital</strong></small>
											{% else %}
												<br>
												<small>
													<strong  style="color:#5cb85c">
														Estoque Total Disponível:
														{{ produto.estoque.total_estoque|number_format(0, '', '.') }}

														{% if produto.estoque.total_estoque > 1 %}
															{% set unidadeVenda = produto.unidade_venda ~ "s" %}
														{% else %}
															{% set unidadeVenda = produto.unidade_venda %}
														{% endif %}

														{{ unidadeVenda }}
													</strong>
												</small>
											{% endif %}
										{% else %}
											{% if produto.tipo_fiscal == 'SV' %}
												<br><small style="color:#5cb85c"><strong>Produto digital</strong></small>
											{% else %}
												<br><small style="color:gray"><strong>Sob consulta</strong></small>
											{% endif %}
										{% endif %}
									{% endif %}

									{% if produto.origem %}
										<br><small><b style="color:gray">Produto faturado por {{produto.origem}}
										| ICMS (Já incluso): {{produto.icms}}%
										</b></small>
									{% endif %}

									{% if produto.moeda_venda != 'real' and auth %}
										<br><small>¹Preço convertido do dólar PTAX</small>
									{% endif %}

									{% if produto.promo == 1 %}
										<br><small>*Produtos em promoção estão sujeitos à disponibilidade de estoque</small>
									{% endif %}
								</td>
								<td>
								<a href="/fabricante/index/{{ produto.sigla_fabricante }}">
									{{ produto.nome_fabricante }}
								</a>
								</td>
								<td>{{ produto.codigo_produto }}</td>
								<td>{{ produto.ref }}</td>
								<td>{{converteUnidadeVenda(produto.unidade_venda)}}</td>
								<td>
								{% if produto.price and produto.price != 'Sob Consulta' %}
									<span class="price-tag --regular">
									R$ {{ produto.price|number_format(2, ',', '.') }}
									</span>
								{% else %}
									Sob Consulta
								{% endif %}
								</td>

								<td>
								{% if auth['id'] %}
								{% set user_id = auth['id'] %}
                                <label for="qtd" class="sr-only">Qtd</label>

                                <input type="number" min="0" max="1000000" class="form-control text-center" id="qtd{{produto.codigo_produto}}" placeholder="qtd{{produto.codigo_produto}}" style="display:inline-block; width:80px; vertical-align:top; margin-right:2px;" value="1">

								<button type="button"
								id="btn{{produto.codigo_produto}}"
								onclick="disableButton(this);location.href='/cart/add/{{produto.codigo_produto}}/'+$('#qtd{{produto.codigo_produto}}').val()+'/{{ requestURI() }}'"
                                class="btn btn-primary btn-md" style="vertical-align:top; margin-bottom:2px;">
                                    <span class="glyphicon glyphicon-shopping-cart"></span>&nbsp;Adicionar
                                </button>

                                {% set cart_prod_info = cartInfo(user_id,produto.codigo_produto) %}
                                {% if cart_prod_info %}
                                <br>
                                <hr style="margin:5px 0;">
                                <button type="button"
                                class="btn btn-default btn-xs"
                                id="btn{{produto.codigo_produto}}"
                                onclick="disableButton(this);location.href='/cart/index'">
                                    No orçamento: {{cart_prod_info}} {% if cart_prod_info > 1 %}itens{% else %}item{% endif %}
                                </button>
                                <button type="button"
			                        class="btn btn-danger btn-xs"
                                    style="border:none;"
			                        id="btn{{ produto.codigo_produto }}-removeItem"
			                        onclick="disableButton(this);location.href='/cart/remove/{{produto.codigo_produto}}/{{ requestURI() }}'">
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
						{% endif %}
					{% endfor %}
					</tbody>

					<tfoot class="with-pagination">
					<tr>
						{% if page.total_pages > 1 %}
							<th colspan="9">
								{% include "elements/page-navigation.volt" %}
							</th>
						{% endif %}
					</tr>
					</tfoot>
				</table>

			</div>
		</div>
	{% endif %}
	{# END page.items|length > 0 #}

	{# PAGINATION #}
	{% if page.items|length == 0 or counterResults == 0 %}
		<div class="well bg-danger">
			<h4 class="no-margin">Não há itens neste filtro.</h4>
		</div>
		<script type="text/javascript">
			document.getElementById('panelCategoryProducts').style.display='none';
		</script>
	{% endif %}

{% else %}
	{# NO RESULTS #}
	<div class="page-header">
		<h1>Sem resultados.<span style="font-weight:500;color:hsl(0,0%,75%);">Zero.</span></h1>
	</div>
{% endif %}
{# END category != null #}