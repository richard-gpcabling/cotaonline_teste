<h1>Lista dos produtos mais acessados</h1>

<div class="p-15px"></div>

<div>
	<table class="table table-striped table-bordered">
		<thead>
		<tr>
			<th>Codigo Produto</th>
			<th>Nome</th>
			<th>Total de Acessos</th>
			<th>Link [abre em nova janela]</th>
		</tr>
		</thead>
		<tbody>
		{% for produto in page.items %}
			<tr>
				<td scope="row">
					{{ produto.codigo_produto }}
				</td>
				<td>
					{{ produto.descricao_sys|makeTrim(40, ' ', '...') }}
				</td>
				<td>
					{{ produto.view_count }}
				</td>
				<td>
					&nbsp;&nbsp;
					<a href="/produto/index/{{ produto.codigo_produto }}" target="_blank">
						<span class="glyphicon glyphicon-link font-size-20px" aria-hidden="true"></span></a>
					&nbsp;&nbsp;
				</td>
			</tr>
		{% endfor %}
		</tbody>
	</table>

	<div class="pull-right">
		{{ partial('elements/page-navigation') }}
	</div>
</div>