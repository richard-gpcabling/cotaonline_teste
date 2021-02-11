<h1>Acessos aos produtos por usuários logados</h1>

<div class="p-15px"></div>

<p>
    Nesta página, você verá os acessos aos produtos por usuários (apenas os produtos que o usuário visitou quando estava logado).
</p>

{% if page.items|length == 0 %}

<h2>Não há registro de acessos</h2>
{% else %}
<div>
	<table class="table table-striped table-bordered">
		<thead>
		<tr>
			<th>Nome</th>
            <th>Email</th>
			{% if auth['role'] == "administrador" %}
			<th>Vendedor</th>
			{% endif %}
            <th>Produto</th>
			<th>Total de Acessos</th>
			<th>Data<br>Ano - Mês - Dia</th>
		</tr>
		</thead>
		<tbody>
		{% for item in page.items %}
			<tr>
				<td scope="row">
                    <a href="/usuario/edit/{{item.usuario_id}}" target="_blank">
					{{ item.name }}
                    </a>
				</td>
				<td>
                    <a href="/usuario/edit/{{item.usuario_id}}" target="_blank">
					{{ item.email }}
                    </a>
				</td>
				
				{% if auth['role'] == "administrador" %}
				<td>
					{% if vendedor[item.vendedor] is defined %}
                    <a href="/usuario/edit/{{item.vendedor}}" target="_blank">
					{{ vendedor[item.vendedor] }}
                    </a>
					{% else %}
					Não há vendedor associado
					{% endif %}
				</td>
				{% endif %}

				<td>
					<a href="/produto/index/{{item.codigo_produto}}" target="_blank">
                    {{ item.codigo_produto }} - {{ item.descricao_sys|makeTrim(40, ' ', '...') }}
                    </a>
				</td>
				<td>
					{{ item.view_count }}
				</td>
				<td>
                    {{ maskNumber(item.date,'#### -- ## -- ##') }}
				</td>
			</tr>
		{% endfor %}
		</tbody>
	</table>

	<div class="pull-right">
		{{ partial('elements/page-navigation') }}
	</div>
</div>
{% endif %}