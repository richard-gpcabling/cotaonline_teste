<h1>Usuários Pendentes</h1>

<b>Total {{pending_user|length}}</b>
<br><br>


<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
	<li role="presentation" class="active">
		<a href="#usuarios" aria-controls="usuarios" role="tab" data-toggle="tab">
			Usuários
		</a>
	</li>

	<li role="presentation">
		<a href="#links" aria-controls="links" role="tab" data-toggle="tab">
			Links de Ativação
		</a>
	</li>
</ul>


<!-- Tab panes -->
<div class="tab-content">

<!-- Usuarios -->
<div role="tabpanel" class="tab-pane active" id="usuarios">
<div class="panel panel-default">
	<div class="panel-body">
	{% if !pending_user %}
	<b>Sem usuários pendentes</b>
	{% endif %}

<table style="border: 1px solid;">
	<thead>
	<tr style="border: 1px solid;">
		<th style="padding:5px; border: 1px solid;">Nome</th>
		<th style="padding:5px; border: 1px solid;">Email</th>
		<th style="padding:5px; border: 1px solid;">Confirm Code</th>
		<th style="padding:5px; border: 1px solid;">Data de Criação</th>
	</tr>
</thead>

{% for user in pending_user %}
<tr>
	<td style="padding:5px; border: 1px solid;">{{user.name}}</td>
	<td style="padding:5px; border: 1px solid;">{{user.email}}</td>
	<td style="padding:5px; border: 1px solid;">{{user.confirm_code}}</td>
	<td style="padding:5px; border: 1px solid;">{{user.created_at}}</td>
</tr>
{% endfor %}

</table>

	</div>
</div>
</div>


<!-- Links -->
<div role="tabpanel" class="tab-pane" id="links">
<div class="panel panel-default">
	<div class="panel-body">
	{% if !pending_user %}
	<b>Sem usuários pendentes</b>
	{% endif %}

<table style="border: 1px solid;">
	<thead>
	<tr style="border: 1px solid;">
		<th style="padding:5px; border: 1px solid;">Links</th>
	</tr>
</thead>

{% for user in pending_user %}
<tr style="border: 1px solid;">
	<td style="padding:5px; border: 1px solid;">http://www.gpcabling.com.br/register?action=confirm&email={{user.email}}&code={{user.confirm_code}}</td>
</tr>
{% endfor %}

</table>

	</div>
</div>
</div>


</div>