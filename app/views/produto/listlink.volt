<h1>Lista de links dos produtos ativos</h1>
<b>Total {{listlink|length}}</b>
<br><br>

<table style="border: 1px solid;">
	<thead>
	<tr style="border: 1px solid;">
		<th style="padding:5px; border: 1px solid;">Codigo Produto</th>
		<th style="padding:5px; border: 1px solid;">URL</th>
		<th style="padding:5px; border: 1px solid;">Link</th>
	</tr>
</thead>

{% for produto in listlink %}
<tr style="border: 1px solid;">
	<td style="padding:5px; border: 1px solid;">
		{{produto.codigo_produto}}
	</td>
	<td style="padding:5px; border: 1px solid;">
		http://www.gpcabling.com.br/produto/index/{{produto.codigo_produto}}
	</td>
	<td style="padding:5px; border: 1px solid;">
		&nbsp;&nbsp;
		<a href="http://www.gpcabling.com.br/produto/index/{{produto.codigo_produto}}" target="_blank">
			<span class="glyphicon glyphicon-link" aria-hidden="true" style="font-size:20px;"></span></a>
		&nbsp;&nbsp;
	</td>
</tr>
{% endfor %}

</table>