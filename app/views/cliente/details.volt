
{{ content() }}

{% if auth['role'] == 'administrador' or auth['role'] == vendedor %}

<div class="page-header">
	<div class="col-md-11">
		<h1>{{cliente.nome}}</h1>
		<p style="font-size:18px;">
			<b>Ucode</b> {{cliente.clienteUcode}}
			&nbsp;&nbsp;&nbsp;
			<b>CNPJ ou CPF</b> {{maskNumber(cliente.cnpj_cpf,'##.###.###/####-##')}}
			&nbsp;&nbsp;&nbsp;
			<b>Razão Social</b> {{cliente.razao_social}} <br><br>
		</p>
	</div>
</div>

<br><br><br>

<div class="row">
	<div class="col-md-4">
		<div class="well well-sm bg-light text-center" style="min-height:110px;">
		<label>Canal</label>
		<h3 style="margin-bottom:0;">
			<b>
				{% if cliente.canal == 0 %}
					<span class="label label-primary">É Cliente</span>
				{% else %}
					<span class="label label-success">É Revenda</span>
				{% endif %}
			</b>
		</h3>
		</div>
	</div>

	<div class="col-md-4">
		<div class="well well-sm bg-light text-center" style="min-height:110px;">
		<label>Tipo Fiscal</label>
		<h3 style="margin-bottom:0;"><b>{{cliente.tipo_fiscal}}</b></h3>
		</div>
	</div>

	<div class="col-md-4">
		<div class="well well-sm bg-light text-center" style="min-height:110px;">
		<label>Possui Revendedor?</label>
		<h3 style="margin-bottom:0;">
			<b>
				{% if cliente.revendedor != 'N/A' %}<a href="/cliente/details/{{cliente.revendedor}}" target="_blank">{% endif %}
					{{cliente.revendedor}}
				{% if cliente.revendedor != 'N/A' %}</a>{% endif %}
			</b>
		</h3>
		</div>
	</div>
</div>

<div class="row">
<div class="col-md-4">
	<div class="well well-sm bg-info text-center" style="min-height:110px;">
	<label>Cadastrada em</label>
	<h3 style="margin-bottom:0;"><b>{{cliente.cadastrada_em}}</b></h3>
	</div>
</div>

<div class="col-md-4">
	<div class="well well-sm bg-info text-center" style="min-height:110px;">
	<label>Origem de Compra</label>
	<h3 style="margin-bottom:0;">
		<b>
			{% if cliente.origem_de_compra != 'NULL' %}
				{{ cliente.origem_de_compra }}
			{% else %}
				Por produto
			{% endif %}
		</b>
	</h3>
	</div>
</div>

<div class="col-md-4">
	<div class="well well-sm bg-info text-center" style="min-height:110px;">
	<label>Destino</label>
	<h3 style="margin-bottom:0;">
		<b>
			{{cliente.destino}}
		</b>
	</h3>
	</div>
</div>
</div>


<div class="row">
<div class="col-md-6">
	<div class="well well-sm bg-success text-center" style="min-height:110px;">
	<label>Mark Up Geral</label>
	<h3 style="margin-bottom:0;"><b>{{cliente.mark_up_geral}}</b></h3>
	</div>
</div>

<div class="col-md-6">
	<div class="well well-sm bg-success text-center" style="min-height:110px;">
	<label>Mark Up por Fabricantes</label>
	<h3 style="margin-bottom:0;">
		<b>
			{% if cliente.mark_up_fabricantes is type('array') %}
				{% for name, value in cliente.mark_up_fabricantes %}
				<a href="/fabricante/index/{{name|upper}}" target="_blank">{{name|upper}}</a> - {{value}}<br>
				{% endfor %}
			{% else %}
				Sem Exceções
			{% endif %}
		</b>
	</h3>
	</div>
</div>
</div>

{% if cliente.canal == 1 %}
<div class="row">
	<div class="col-md-12">
	<h3>Clientes desta revenda</h3>
	<p><b>Total: {{page.total_items}} clientes atendidos por esta revenda</b></p>
		<div class="panel panel-default">
		<div class="panel-body panel-no-margin table-responsive">
			<table id="tb-user-status" class="table table-striped">
				<thead>
				<tr>
					<th>Ucode</th>
					<th>Razão Social</th>
					<th>CNPJ ou CPF</th>
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
						<td>{{ cliente.clienteUcode }}</td>
						<td>{{ cliente.razao_social }}</td>
						<td>{{ maskNumber(cliente.cnpj_cpf,'##.###.###/####-##') }}</td>
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
							<a href="/cliente/details/{{ cliente.clienteUcode }}" role="button" class="btn btn-success" targe="_blank">
								<span class="glyphicon glyphicon-eye-open"></span>&nbsp;&nbsp;Detalhes
							</a>
						</td>
					</tr>
				{% endfor %}
				</tbody>
				<tfoot class="with-pagination">
				<tr>
					<th colspan="9">
						{{ partial('elements/page-navigation') }}
					</th>
				</tr>
				</tfoot>
			</table>
	</div>
	</div>
	</div>
</div>
{% endif %}



{% endif %}