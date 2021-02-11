{{ content() }}
{% if auth['cliente_relacionado'] OR auth['cliente_naorelacionado'] %}
<div class="page-header"><h1>Clientes</h1></div>

<div class="well well-sm text-right">
	<form class="form-inline" action="/cliente/index" method="GET">
		<div class="form-group">
			<input type="text" name="query" class="form-control" style="width:20em;" placeholder="procurar por…" value="{%if query is defined %}{{query}}{%endif%}">
		</div>
		<button type="submit" class="btn btn-default">
			<span class="glyphicon glyphicon-search"></span>&nbsp;Busca</button>
	</form>
</div>

{% if page.items|length > 0 %}
	<div class="panel panel-default">
		<div class="panel-body panel-no-margin table-responsive">
			<table id="tb-user-status" class="table table-striped">
				<thead>
				<tr>
					<th>Tipo</th>
					<th>Ucode</th>
					<th>Razão Social</th>
					<th>CNPJ ou CPF</th>
					<th>Tipo Fiscal</th>
					<th>Origem de Compra</th>
					<th>Destino</th>
					<th>Vendedor Interno ERP</th>
					<th>Mark Up Geral</th>
					<th></th>
				</tr>
				</thead>
				
				<tbody>
					{% for cliente in page.items %}
					<tr>
						<td>
							{% if cliente.canal == 0 %}
								<span class="label label-primary">C</span>
							{% else %}
								<span class="label label-success">R</span>
							{% endif %}
						</td>
						<td>{{ cliente.clienteUcode }}</td>
						<td width="400">{{ cliente.razao_social }}</td>
						<td width="200">{{ maskNumber(cliente.cnpj_cpf,'##.###.###/####-##') }}</td>
						<td>{{ cliente.tipo_fiscal }}</td>
						<td>
							{% if cliente.origem_de_compra != 'NULL' %}
								{{ cliente.origem_de_compra }}
							{% else %}
								Por produto
							{% endif %}
						</td>
						<td>{{ cliente.destino }}</td>
						<td>{{ cliente.vendedor }}</td>
						<td>{{ cliente.mark_up_geral }}</td>
						<td class="actions">
							<a href="/cliente/details/{{ cliente.clienteUcode }}" role="button" class="btn btn-success">
								<span class="glyphicon glyphicon-eye-open"></span>&nbsp;&nbsp;Detalhes
							</a>
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
{% endif %}

{%else%}
	{% include "elements/message-no-permission.volt" %}
{%endif%}