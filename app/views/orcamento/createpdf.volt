<div id="PDF">
{% set a = 0 %}
{% set b = 0 %}
{% set big_font = '11px' %}
{% set small_font = '9px' %}
{% set note_font = '6px' %}
{% set panel_padding = '1px 15px' %}
{% set pag = 0 %}

<div class="panel panel-primary" style="width:200px;float:left;margin:0 0 8px 0;height:110px;">
	<div class="panel-body" style="padding:25px 5px 5px 8px;">
		<img src="/img/logo-03.png"><br>
	</div>
</div>

<div class="panel panel-primary" style="width:468px;float:right;margin:0 0 8px 0;height:110px;">
	<div class="panel-body" style="padding:5px;">
	<span class="font-weight:bold;font-size:12px;">
		Proposta Comercial via CotaOnline nº {{orcamento_id}}
	</span><br>
	<span style="font-size:{{big_font}};">
	À <span style="font-weight:bold;">{{empresa.razao}}</span>, <br>
	inscrita sob o CNPJ <span style="font-weight:bold;">
		{% if empresa.cnpj|length == 14 %}
			{{maskNumber(empresa.cnpj,'##.###.###/####-##')}}
		{% else %}
			{{empresa.cnpj}}
		{% endif %}
		</span>
		e UF <span style="font-weight:bold;">{{empresa.destino}}</span>.
	<br>
	Solicitado por {{owner.name}} [{{owner.email}}]
	</span><br>
	<span style="font-size:{{small_font}};">
		<span style="font-weight:bold;">Tipo Fiscal</span> {{empresa.tipo}}<br>
		<span style="font-weight:bold;">Data desta Proposta</span> {{empresa.data}} |
		<span style="font-weight:bold;">Validade desta Proposta</span> {{empresa.validade}}
	</span>
	</div>
</div>

<div style="clear:both;">
</div>

{% for item in orcamento %}

{% set a = a+1 %}
{% set b = b+1 %}

<div class="panel panel-primary" style="margin-bottom:1px;">
  <div class="panel-heading panel-info" style="padding:{{panel_padding}};">
	<table>
		<tr>
			<td rowspan="4" style="padding-right:8px;">
				<small>#{{a}}</small>
			</td>
			<td>
				{#<a href="/produto/index{{item.codigo_produto}}">#}
				<span style="font-size:12px;">
				{% if item.descricao_site == 'NULL' %}
					{{item.descricao_sys}}
				{% endif %}
				{% if item.descricao_site != 'NULL' %}
					{{item.descricao_site}}
				{% endif %}
				</span>
				{#</a>#}
			</td>
		<tr>
		<tr>
			<td>
				<small style="font-size:{{small_font}};">
				<span style="font-weight:bold;">Fabricante</span> {{item.fabricante_nome}} |
				{#<span style="font-weight:bold;">Unidade de Venda</span> {{ converteUnidadeVenda(item.unidade_venda) }} |#}
				<span style="font-weight:bold;">Código GP Cabling</span> {{item.codigo_produto}} |
				<span style="font-weight:bold;">Part Nº</span> {{item.ref}}
				</small>
			</td>
		</tr>
	</table>
	</div>
	<div class="panel-body" style="padding:{{panel_padding}};">
		<table>
		<tr>
		
		<td style="padding-right:10px;">
			<span style="font-size:{{small_font}};">
			<span style="font-weight:bold;">Faturado por {{origem[item.codigo_produto]}}</span><br>
			<span style="font-weight:bold;">NCM</span> {{item.ncm}}
			</span>
		</td>
		
		<td style="padding-right:30px;">
			<span style="font-size:{{small_font}};">
			Impostos e Taxas (já inclusos)
			<span style="font-weight:bold;">CST</span> {{impostos[item.codigo_produto].cst}} |
			<span style="font-weight:bold;">ICMS</span> {{impostos[item.codigo_produto].icms}}%
				{% if showPercentage(impostos[item.codigo_produto].icms,item.subtotal) != 0 %}
					(R$ {{showPercentage(impostos[item.codigo_produto].icms,item.subtotal)}})
				{% endif %} |
			<span style="font-weight:bold;">IPI</span> {{impostos[item.codigo_produto].ipi}}%
				{% if showPercentage(impostos[item.codigo_produto].ipi,item.subtotal) != 0 %}
					(R$ {{showPercentage(impostos[item.codigo_produto].ipi,item.subtotal)}})
				{% endif %} |
			<span style="font-weight:bold;">ST</span> {{impostos[item.codigo_produto].st}}
				{% if showPercentage(impostos[item.codigo_produto].st,item.subtotal) != 0 %}
				%	(R$ {{showPercentage(impostos[item.codigo_produto].st,item.subtotal)}})
				{% endif %}
			<br>
			<span style="font-weight:bold;">PIS</span> {{impostos_fixos.pis}}%
				{% if showPercentage(impostos_fixos.pis,item.subtotal) != 0 %}
					(R$ {{showPercentage(impostos_fixos.pis,item.subtotal)}})
				{% endif %} |
			<span style="font-weight:bold;">COFINS</span> {{impostos_fixos.cofins}}%
				{% if showPercentage(impostos_fixos.cofins,item.subtotal) != 0 %}
					(R$ {{showPercentage(impostos_fixos.cofins,item.subtotal)}})
				{% endif %} |
			<span style="font-weight:bold;">IR/CSLL</span> {{impostos_fixos.ircsll}}%
				{% if showPercentage(impostos_fixos.ircsll,item.subtotal) != 0 %}
					(R$ {{showPercentage(impostos_fixos.ircsll,item.subtotal)}})
				{% endif %}
			</span>
		</td>

		<td>
			<span style="font-size:{{note_font}}">
				Entrega à combinar&nbsp;&nbsp;&nbsp;<br>
				{% if item.moeda_venda == 'dolar' %}
					Preço convertido para o real do dólar PTAX
				{% else %}
					Preço em {{item.moeda_venda}}
				{% endif %}
			</span>
		</td>

		</tr>
		</table>
	</div>

	<div class="panel-footer text-right" style="padding:{{panel_padding}};">
  		<span style="font-size:{{small_font}};">Quantidade</span>
		  	<span style="font-weight:bold;font-size:{{big_font}};border-right:2px dotted black;">{{item.quantidade}}
			  {{ converteUnidadeVenda(item.unidade_venda) }}{% if item.quantidade > 1 %}s{% endif %}
			  &nbsp;&nbsp;&nbsp;</span>
		
		<span style="font-size:{{small_font}};">&nbsp;&nbsp;Valor Unitário</span>
			<span style="font-weight:bold;font-size:{{big_font}};border-right:2px dotted black;">R$ {{item.unitario}}&nbsp;&nbsp;&nbsp;</span>
		
		<span style="font-size:{{small_font}}">&nbsp;&nbsp;SubTotal</span>
			<span style="font-weight:bold;">R$ {{item.subtotal}}</span>
	</div>
</div>

{% if b%11 == 0 and pag == 0%}
	{% set pag = pag+1 %}
	{% set b = 0 %}
	<pagebreak>
{% endif %}

{% if b != 0 and b%13 == 0 and pag >= 1 %}
	{% set pag = pag+1 %}
	{% set b = 0 %}
	<pagebreak>
{% endif %}

{% endfor %}
<br><br>

<table>
<tr>
	<td valign="top" style="padding-right:200px;">
	Atenciosamente,<br>
	{{vendedor.name}}.<br>
	{{vendedor.email}}<br>
	{% if vendedor.telefone_fixo is defined %}
		{{vendedor.telefone_fixo}}
		{% else %}
		(11) 2065.0800
	{% endif %}
	{% if vendedor.ramal is defined  %}R: {{vendedor.ramal}}{% endif %}
	</td>

	<td valign="top">
	<span style="font-weight:bold;font-size:8px;">
		Notas
	</span>
	<ol style="font-size:7px;">
		<li>Preços ofertados em dólar comercial serão convertidos para real.</li>
		<li>Os preços poderão sofrer reajustes caso ocorram mudanças significativas na atual conjuntura fiscal e econômica do País, que coloquem em risco o equilíbrio financeiro da presente proposta. Neste caso, os valores e condições deverão ser revistos entre as partes.</li>
		<li>Os preços ofertados são válidos para a região onde está estabelecido o CNPJ constante nesta proposta comercial. Os preços estão calculados de acordo com a destinação da mercadoria, 'Consumo ou Revenda', informada pelo cliente.</li>
		<li>Havendo a necessidade de desmontar e montar rack no local de entrega, São Paulo e Grande São Paulo, será cobrado custo adicional de R$ 500,00 por rack.</li>
		<li>Todos os produtos com ncm = 'SERVICO', serão produtos faturados em Notas de Serviços separadamente da Nf-e de produtos.</li>
		<li>Os valores expressos nos campos 'Icms Normal' e 'Icms ST' estão inclusos nos preços e serão tributados de acordo com o Código de Situação Tributária (CST) informado.</li>
		<li>Orçamento sujeito à aprovação interna.</li>
		<li>Estoque sujeito à disponibilidade</li>
	</ol>
	</td>
</tr>
</table>
<br><br>
<table style="width:100%;">
<tr>
	{% if unidade_info.pe != empty %}
	<td>
		<span style="font-size:{{small_font}};">
		{{unidade_info.pe}}
		</span>
	</td>
	{% endif %}

	{% if unidade_info.po != empty %}
	<td style="padding-right:10px; padding-left:10px; border-left:1px dotted #808080;">
		<span style="font-size:{{small_font}};">
		{{unidade_info.po}}
		</span>
	</td>
	{% endif %}

	{% if unidade_info.pp != empty %}
	<td style="padding-right:10px; padding-left:10px; border-left:1px dotted #808080;">
		<span style="font-size:{{small_font}};">
		{{unidade_info.pp}}
		</span>
	</td>
	{% endif %}
</tr>
</table>

</div>
</div>