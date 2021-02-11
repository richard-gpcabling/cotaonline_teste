<h1>
    Listas Prontas
</h1>
<p>
    Aqui, você poderá criar e editar as listas prontas.
</p>
    <a href="/listasprontas/newlist" role="button" class="btn btn-primary btn-lg">
        <span class="glyphicon glyphicon-floppy-saved"></span>&nbsp;&nbsp;Criar nova lista
    </a>
<br>
<br>
{% if listas is not defined or listas|length == 0 %}
    <br><br><h1>Ainda não há nenhuma lista criada</h1>
{% else %}

<h3>Listas</h3>
<div>
	<table class="table table-striped table-bordered">
		
		<thead>
		<tr>
            <th>Status</th>        
            <th>View</th>
            <th>Editar</th>
			<th>Título</th>
			<th>Slug [link]</th>
            <th>Descrição</th>
			<th>Produtos</th>
			<th style="min-width:180px;">1. Data de Criação <br> 2. Criado por</th>
            <th style="min-width:180px;">1. Data de Update<br> 2. Editado por</th>
		</tr>
		</thead>
		<tbody>
{% for item in listas %}
<tr>
    <td>
    {% if item.status == 0 %}
        <span class="label label-danger">Inativo</span>
    {% else %}
        <span class="label label-success">Ativo</span>
    {% endif %}
    </td>

    <td>
    {% if item.status == 0 %}
        <span class="label label-danger">Inativo</span>
    {% else %}
        <span class="label label-success">Ativo</span>
    {% endif %}
    </td>

    <td>
    <a href="/listasprontas/edit/{{item.slug}}" role="button" class="btn btn-warning btn-xs">
        <span class="glyphicon glyphicon-pencil"></span>&nbsp;&nbsp;Editar
    </a>
    </td>

    <td>
    <b>{{item.titulo}}</b>
    </td>

    <td>
    <a href="/listasprontas/list/{{item.slug}}" target="_blank">{{item.slug}}</a>
    </td>

    <td style="max-width:350px;">
    {{item.descricao}}
    </td>

    <td style="min-width:350px;">
    {% for name, value in prod_array[item.slug] %}
        <a href="/produto/index/{{name}}" target="_blank">{{value}}</a> <br>
    {% endfor %}
    </td>

    <td>
    1. {{item.created_at}} <br>
    2. {{criado_por[item.slug]}}
    </td>

    <td>
    1. {{item.updated_at}}<br>
    2. {{atualizado_por[item.slug]}}
    </td>
</tr>
{% endfor %}
		</tbody>
	</table>
{% endif %}
