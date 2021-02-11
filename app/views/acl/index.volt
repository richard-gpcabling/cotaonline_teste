{% if auth['email'] =='bruno@gpcabling.com.br' %}
<div class="page-header"><h1>Permissões</h1></div>

<p class="text-muted">
	<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
	&nbsp;
	Mudanças nestas opções entrarão em vigor no próximo login de cada usuário.
	</p>

{% 
	set portions = [
		'c_p'    : 'Custo na página',
		'c_o'    : 'Custo no orçamento',
		'o_ur'   : 'Visualização de orçamentos de usuários relacionados',
		'o_unr'  : 'Visualização de orçamentos de usuários não relacionados',
		'o_eur'  : 'Edição de orçamentos de usuários relacionados',
		'o_eunr' : 'Edição de orçamentos de usuários não relacionados',
		'a_ep'   : 'Edição de produtos',
		'a_p'    : 'Administração de produtos',
		'e_me'   : 'Edição de cliente',
		'd_me'   : 'Visualização de dados de cliente',
		'c_cr'   : 'Lista de clientes relacionados',
		'c_cnr'  : 'Lista de clientes não relacionados',
		'm_ur'   : 'Mensagens de usuários relacionados',
		'm_unr'  : 'Mensagens de usuários não relacionados',
		'a_u'    : 'Ativação de usuários',
		'logs'   : 'Logs'
	]
%}{%
	set profiles = [
		'administrador' : 'Administrador',
		'vendedor'      : 'Vendedor',
		'parceiro'      : 'Cliente Parceiro',
		'cliente'       : 'Cliente Normal'
	]
%}<?php
	$profileData = array(
		'administrador' => $administrador,
		'vendedor'      => $vendedor,
		'parceiro'      => $parceiro,
		'cliente'       => $cliente
	);
?>

<div class="panel panel-default">
	<div class="table-responsive">
		<table id="tb-user-status" class="table table-striped">
			<thead>
				<tr>
					<th class="acl-item-th"></th>
					{% for codeProfile, titleProfile in profiles %}
						<th class="acl-item-th text-center">{{titleProfile}}</th>
					{% endfor %}
				</tr>
			</thead>
			<tbody>
				{% for codePortion, titlePortion in portions %}
					<tr>
						<td class="acl-item-title">
							{{ titlePortion }}
						</td>
						{% for codeProfile, titleProfile in profiles %}
							<?php
								$jsonStrProfileDataCodeProfile = json_encode( $profileData[$codeProfile][0] ) ;
								$jsonObjProfileDataCodeProfile = json_decode( $jsonStrProfileDataCodeProfile );
								$isEnabled = $jsonObjProfileDataCodeProfile->$codePortion;
							?>
							<td class="acl-item-profile">
								<label class="switch">
									<input type="checkbox" {% if isEnabled == '1' %} checked="checked" {% endif %} onclick="Acl.save(this,'{{ codeProfile }}','{{ codePortion }}')" >
									<div class="slider round"></div>
								</label>
								<label class="acl-item-mobile-text">
									&nbsp;
									{{ titleProfile }}
								</label>
							</td>
						{% endfor %}
					</tr>
				{% endfor %}
			</tbody>
		</table>
	</div>
</div>
{% else %}
	{% include "elements/message-no-permission.volt" %}
{% endif %}