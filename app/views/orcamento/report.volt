<h1>Relatório de orçamentos</h1>

<p>Somatório geral dos orçamentos por mês.</p>

<p>Apenas orçamentos com valor, não estão na soma os orçamentos sem valor, ou seja, em que o usuário não estava associado no momento do orçamento.</p>

<p><b>Selecione o ano</b></p>
<select name="year" onchange="location = this.value;" style="font-size:21px;">
	<option value="">...</option>
	<option value="/orcamento/reporthome">Últimos 3 anos</option>
	{% for y in years %}
		<option value="/orcamento/report/{{y}}">{{y}}</option>
	{% endfor %}
</select>
<br>
{% if current_year != 0 %}
	<h3>{{current_year}}</h3>
	<hr>
	<table style="border: 1px solid;font-size:18px;">
		<thead>
		<tr style="border: 1px solid;">
			<th style="padding:10px; border: 1px solid;">Mês</th>
			<th style="padding:10px; border: 1px solid;">Total</th>
		</tr>
	</thead>
	{% for a in result_array %}
	<tr style="border: 1px solid;">
		<td style="padding:10px; border: 1px solid;">
			{{a['month']}}
		</td>
		<td style="padding:10px; border: 1px solid;">
			R$ {{a['total']}}
		</td>
	</tr>
	{% endfor %}
	<tr style="border: 1px solid;padding:5px;">
		<td style="padding:1px;"></td><td></td>
	</tr>
	<tr style="border: 1px solid;">
		<td style="padding:10px; border: 1px solid;">
			<b>Total</b>
		</td>
		<td style="padding:10px; border: 1px solid;">
			R$ {{total_year}}
		</td>
	</tr>
	</table>
{% endif %}