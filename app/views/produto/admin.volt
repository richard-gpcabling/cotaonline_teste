{{ content() }}

{% if auth['admin_produto'] %}
	<div class="page-header"><h1>Produtos Ativos</h1></div>
	
	<div class="well well-sm text-right">
		<form class="form-inline" action="/produto/admin" method="GET">
			<div class="form-group">
				<input type="text" name="query" class="form-control" style="width:20em;" placeholder="procurar por…" value="{%if userquery is defined %}{{userquery}}{%endif%}">
			</div>
			<button type="submit" class="btn btn-default">
				<span class="glyphicon glyphicon-search"></span>
				&nbsp;
				Busca
			</button>
		</form>
	</div>


	{% if(0) %}{# disabled feature #}
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><a href="#" data-toggle="collapse" data-target="#customerAdvancedSearch" aria-expanded="false" aria-controls="collapseExample">Busca avançada</a></h3>
		</div>
		<div id="customerAdvancedSearch" class="panel-body collapse" data-fix-element-classes="true">

			<!-- begin form -->
			{{ form("produto/admin") }}
				{% for element in form %}
						{% if is_a(element, 'Phalcon\Forms\Element\Hidden') %}
							{{ element }}
						{% else %}
							<div class="form-group">
								{{ element.label() }}
								{{ element }} <!-- class form-control -->
							</div>
						{% endif %}
				{% endfor %}

				<div class="control-group text-right">
					<button type="submit" class="btn btn-info">
						<span class="glyphicon glyphicon-search"></span>
						&nbsp; 
						Procurar
					</button>
				</div>
			</form>
			<!-- end form -->

		</div>
	</div>
	{% endif %}


	{% if page.items|length > 0 %}
		<div id="panel-table-products" class="panel panel-default">
			<div class="panel-body panel-no-margin table-responsive">
				<table id="tb-user-status" class="table table-striped table-condensed">
					<thead>
						<tr>
							<th>Código</th>
							<th>Descrição</th>
							<th>Sigla do fabricante</th>
							<th>Part Number</th>
							<th>{% if auth['edicao_produto'] %}Editar{% endif%}</th>
						</tr>
					</thead>
					<tbody>
						{% for produto in page.items %}
							<tr>
								<td>{{ produto.codigo_produto }}</td>
								<td>
									<a href="/produto/index/{{produto.codigo_produto}}" target="_blank">{{produto.descricao_sys}}</a>
								</td>
								<td>{{ produto.sigla_fabricante }}</td>
								<td>{{ produto.ref }}</td>

								<td>
									{% if auth['edicao_produto'] %}
										<a href="/produto/edit/{{ produto.codigo_produto }}" role="button" class="btn btn-success">
											<span class="glyphicon glyphicon-edit"></span>
											&nbsp; 
											Editar
										</a>
									{%endif%}
								</td>
							</tr>
						{% endfor %}
					</tbody>
					<tfoot class="with-pagination">
						<tr>
							<th colspan="12">
								{% include "elements/page-navigation.volt" %}
							</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	{% endif %}
{% else %}
	{% include "elements/message-no-permission.volt" %}
{% endif %}
