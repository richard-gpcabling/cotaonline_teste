<?
$a_total=0;
$b_total=0;
$c_total=0;
?>
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

<h3>Últimos 3 anos</h3>
<hr>
<table style="border: 1px solid;font-size:18px;">
	<thead>
	<tr style="border: 1px solid;">
		<th style="padding:10px; border: 1px solid;">Meses</th>
		{% for year in last_years %}
		<th style="padding:10px; border: 1px solid;">{{year}}</th>
		{% endfor %}
	</tr>
</thead>

{% for result in result_array %}

<?
if($result[$last_years[0]] != 'N/A'):$money_a='R$';$a = str_replace('.', '', $result[$last_years[0]]);$a = str_replace(',', '.',$a);$a = floatval($a);$a_total+=$a;else:$money_a='';$a='N/A';
endif;

if($result[$last_years[1]] != 'N/A'):$money_b='R$';$b = str_replace('.', '', $result[$last_years[1]]);$b = str_replace(',', '.',$b);$b = floatval($b);$b_total+=$b;else:$money='';$b='N/A';
endif;

if($result[$last_years[2]] != 'N/A'):$money_c='R$';$c = str_replace('.', '', $result[$last_years[2]]);$c = str_replace(',', '.',$c);$c = floatval($c);$c_total+=$c;else:$money_c='';$c='N/A';
endif;

$b_color='';
$c_color='';
$b_glyph='';
$c_glyph='';
if ($b>$a) {$b_color='green';$b_glyph='glyphicon-triangle-top';}/****/if ($b<$a) {$b_color='red';$b_glyph='glyphicon-triangle-bottom';}/****/if (gettype($b)=='string') {$b_color='#666';$b_glyph='glyphicon-minus';}
if ($c>$b) {$c_color='green';$c_glyph='glyphicon-triangle-top';}/****/if ($c<$b) {$c_color='red';$c_glyph='glyphicon-triangle-bottom';}/****/if (gettype($c)=='string') {$c_color='#666';$c_glyph='glyphicon-minus';}

?>

<tr style="border: 1px solid;">
	<td style="padding:10px; border: 1px solid;">
		{{result['month']}}
	</td>

	<td style="padding:10px; border: 1px solid;">
		<b style="color:#666;">
		{{money_a}} {{result[last_years[0]]}}
		</b>
	</td>

	<td style="padding:10px; border: 1px solid;">
		<b style="color:{{b_color}};">
		<span class="glyphicon {{b_glyph}}"></span>&nbsp;&nbsp;&nbsp;
		{{money_b}} {{result[last_years[1]]}}
		</b>
	</td>

	<td style="padding:10px; border: 1px solid;">
		<b style="color:{{c_color}};">
		<span class="glyphicon {{c_glyph}}"></span>&nbsp;&nbsp;&nbsp;
		{{money_c}} {{result[last_years[2]]}}
		</b>
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
		<b style="color:#666">
			R$ <? echo number_format($a_total, 2, ',', '.'); ?>
		</b>
	</td>

	<td style="padding:10px; border: 1px solid;">
		{% if b_total > a_total %}
			<b style="color:green">
		{% else %}
			<b style="color:red">
		{% endif %}
			R$ <? echo number_format($b_total, 2, ',', '.'); ?>
		</b>
	</td>

	<td style="padding:10px; border: 1px solid;">
		{% if c_total > b_total %}
			<b style="color:green">
		{% else %}
			<b style="color:red">
		{% endif %}
			R$ <? echo number_format($c_total, 2, ',', '.'); ?>
		</b>
	</td>
</tr>
</table>