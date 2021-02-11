{% include "elements/message-texts.volt" %}

{{ content() }}

{% if auth['ativacao_usuario'] %}

{% if auth['role'] == 'administrador' %}
<div class="page-header">
	<h1>Usuários</h1>

	<!-- <a href="/usuario/pendingtable">Lista de usuários pendentes</a> -->
</div>



<div class="row">
	<div class="col-md-4">
	<div class="panel panel-primary">
	<div class="panel-heading">Geral</div>
	<div class="panel-body">
		<table class="table" style="margin-bottom:0;">
		<thead>
			<tr>
				<th>Total</th>
				<th>Sem Empresa - %</th>
				<th>Sem vendedor - %</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>{% if count_user is empty %}--{% else %}{{count_user[0]}}{% endif %}</td>
				<td>{% if count_user is empty %}--{% else %}{{count_user[1]}}{% endif %} - <span style="color:#CCC;">{% if count_user is empty %}--{% else %}{{count_user[2]}}%{% endif %}</span></td>
				<td>{% if count_user is empty %}--{% else %}{{count_user[3]}}{% endif %} - <span style="color:#CCC;">{% if count_user is empty %}--{% else %}{{count_user[4]}}%{% endif %}</span></td>
			</tr>
		</tbody>
		</table>
	</div>
	</div>
	</div>

	<div class="col-md-2">
	<div class="panel panel-success">
	<div class="panel-heading">Ativos</div>
	<div class="panel-body">
		<table><tr>
		<td><span style="margin:0;padding:0; font-size:24px;"><b>
		{% if count_user is empty %}--{% else %}{{count_user[5]}}{% endif %}
		</b></span></td>
		<td>&nbsp;</td>
		<td align="right"><span style="color:#5cb85c;font-size:18px;"><b>
		{% if count_user is empty %}--{% else %}{{count_user[6]}}%{% endif %}
		</b></span></td>
		</tr></table>
	</div>
	</div>
	</div>

	<div class="col-md-2">
	<div class="panel panel-warning">
	<div class="panel-heading">Confirmados</div>
	<div class="panel-body">
		<table><tr>
		<td><span style="margin:0;padding:0; font-size:24px;"><b>
		{% if count_user is empty %}--{% else %}{{count_user[7]}}{% endif %}
		</b></span></td>
		<td>&nbsp;</td>
		<td align="right"><span style="color:#ffc266;font-size:18px;"><b>
		{% if count_user is empty %}--{% else %}{{count_user[8]}}%{% endif %}
		</b></span></td>
		</tr></table>
	</div>
	</div>
	</div>

	<div class="col-md-2">
	<div class="panel panel-info">
	<div class="panel-heading">Pendentes</div>
	<div class="panel-body">
		<table><tr>
		<td><span style="margin:0;padding:0; font-size:24px;"><b>
		{% if count_user is empty %}--{% else %}{{count_user[9]}}{% endif %}
		</b></span></td>
		<td>&nbsp;</td>
		<td align="right"><span style="color:#5bc0de;font-size:18px;"><b>
		{% if count_user is empty %}--{% else %}{{count_user[10]}}%{% endif %}
		</b></span></td>
		</tr></table>
	</div>
	</div>
	</div>

	<div class="col-md-2">
	<div class="panel panel-danger">
	<div class="panel-heading">Inativos</div>
	<div class="panel-body">
		<table><tr>
		<td><span style="margin:0;padding:0; font-size:24px;"><b>
		{% if count_user is empty %}--{% else %}{{count_user[11]}}{% endif %}
		</b></span></td>
		<td>&nbsp;</td>
		<td align="right"><span style="color:#d9534f;font-size:18px;"><b>
		{% if count_user is empty %}--{% else %}{{count_user[12]}}%{% endif %}
		</b></span></td>
		</tr></table>
	</div>
	</div>
	</div>
</div>
{% endif %}

<div class="well well-sm text-right">
	<form class="form-inline" action="/usuario/search" method="GET">
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

{{ form("usuario/index") }}

<div id="user-status-alert" class="alert alert-danger in-tpl-message" role="alert" style="display:none;">
	<span class="glyphicon glyphicon-warning-sign"></span>
	&nbsp;
	{{ message['usuario/index'] }}
</div>

{% if page.items|length > 0 %}
	<div class="panel panel-default">
		<div class="table-responsive">
			<table id="tb-user-status" class="table table-striped">
				<thead>
					<tr class="with-pagination" colspan="8">
						<td colspan="8">
							{% include "elements/page-navigation.volt" %}
						</td>
					</tr>
					<tr>
						<th class="text-center">Status</th>
						<th>

							<a href="#" data-toggle="tooltip" data-placement="bottom" title="Unidade de atendimento pode ser verificada pelas letras finais do códigos. PO=Policom SP; PC=Policom Paraná; PR= Policom Rio; PA= Paris">
									<span class="glyphicon glyphicon-question-sign"></span>
							</a>
							Nome e Empresa
						</th>
						<th>Vendedor</th>
						<th>Email</th>
						<th>Cadastro</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					{#{% for user in page.items if user.status != 'inactive' %}#}
					{% for user in page.items %}
						<tr id="row-user-{{user.id}}" data-row-status="{{user.status}}">
							<td id="user_status_{{user.id}}" class="text-center">
								{% if user.status == 'active' %}
									<span class="label label-success">ativo</span>
								{% elseif user.status == 'inactive' %}
									<span class="label label-danger">inativo</span>
								{% elseif user.status == 'confirmed' %}
									<span class="label label-warning">confirmado</span>
								{% elseif user.status == 'pending' %}
									<span class="label label-info">pendente</span>
								{% else %}
									<span class="label label-default">{{ user.status }}</span>
								{% endif %}
							</td>
							<td data-user-id="{{user.id}}">{{ user.name|lower|capitalize }} <br>
								{#{{ user.password }}<br>#}
								{% if user.codigo_cliente =='' OR  user.codigo_cliente is null %}
								<span class="label label-danger">Não associado</span>
								{% else %}
								<?php $empresa=ClienteCore::findFirstByClienteUcode($user->codigo_cliente); ?>
								{% if empresa %}
								<b>{{ user.codigo_cliente }}</b> - {{ empresa.nome }}
								{% endif %}
								{% endif %}
								<br>
								{% if user.cnpj_cpf_raw != null %}
								<b>CNPJ FORNECIDO:</b> {{ user.cnpj_cpf_raw }}
								{% endif %}
							</td>
							<td data-user-id="{{user.id}}">
								{% if user.id==user.vendedor %}
									{% if user.usuario_tipo=='1' %}
									<b>Administrador</b>
									{% endif %}

									{% if user.usuario_tipo=='2' %}
									<b>Vendedor</b>
									{% endif %}
								{% else %}
								<?php $vendedor=Usuario::findFirstById($user->vendedor); ?>
								{% if vendedor.name is defined %}
								{{ vendedor.name|lower|capitalize }}
								{% endif %}
								{% if user.vendedor =='' OR  user.vendedor is null %}
									{% if user.usuario_tipo=='1' %}
									<b>Administrador</b>
									{% endif %}

									{% if user.usuario_tipo=='2' %}
									<b>Vendedor</b>
									{% endif %}

									{% if user.usuario_tipo=='3' OR user.usuario_tipo=='4' %}
									<span class="label label-danger">Sem vendedor</span>
									{% endif %}
								{% endif %}
								{% endif %}
							</td>
							<td data-user-id="{{user.id}}" style="white-space:nowrap;">{{ user.email }}</td>
							<td data-user-id="{{user.id}}" style="white-space:nowrap;">
								{# user.created_at #}
								<?php
									$dt = DateTime::createFromFormat('Y-m-d G:i:s', $user->created_at);
									$dt->modify('-3 hours');
									echo $dt->format('d/m/Y\&\e\m\s\p\;G:i:s');
								?>
							</td>

							<td class="actions">
								{#
								<button
									type="button"
									class="btn btn-success toggle-status toggle-status-active"
									onclick="javascript:User.toggleStatus({{user.id}},'active',this);"
									data-loading-text="<span class='glyphicon glyphicon-refresh'></span>&emsp;alterando&hellip;"
								>
									<span class="glyphicon glyphicon-ok"></span>
									&nbsp;
									Ativar
								</button>
								<button
									type="button"
									class="btn btn-warning toggle-status toggle-status-inactive"
									onclick="javascript:User.toggleStatus({{user.id}},'inactive',this);"
									data-loading-text="<span class='glyphicon glyphicon-refresh'></span>&emsp;alterando&hellip;"
								>
									<span class="glyphicon glyphicon-remove"></span>
									&nbsp;
									Desativar
								</button>
								#}
								<a href="/usuario/edit/{{ user.id }}" role="button" class="btn btn-primary">
									<span class="glyphicon glyphicon-edit"></span>
									&nbsp;
									Editar
								</a>
							</td>
						</tr>
					{% endfor %}
				</tbody>
				<tfoot class="with-pagination">
					<tr>
						<th colspan="8">
							{% include "elements/page-navigation.volt" %}
						</th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
{% endif %}
</form><!--
<script type="text/javascript">

// document.addEventListener( 'DOMContentLoaded', function () {
// 	Client.edit.selectClientType($('#tipo').val());
// 	Client.edit.initialize();
//	Menu.hide();
// }, false );
</script> -->
{%else%}

	{% include "elements/message-no-permission.volt" %}

{%endif%}
