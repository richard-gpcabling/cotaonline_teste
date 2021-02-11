<?php
$id = $user->id;
$usuario=Usuario::find(['columns' => 'id,status,cnpj_cpf_raw','conditions' => "id=$id"]);
$user_actual_cnpj_cpf=$usuario[0]['cnpj_cpf_raw'];
$user_status=$usuario[0]['status'];

function limpaCnpj($str){
preg_match_all('!\d+!', $str, $matches);
$matches = implode("", $matches[0]);
return $matches;
}

?>
{{ content() }}

<div class="page-header">
	<h1>
		{{user.name}}
		<small>{{user.email}}</small>
	</h1>
</div>
<div class="well">
	<h3>
	{% if user.codigo_cliente =='' OR  user.codigo_cliente is null %}
		Cliente n&atilde;o associado
	{% else %}
	 <?php
	 	$empresa=ClienteCore::findFirstByClienteUcode($user->codigo_cliente);
	 ?>
	 {% if empresa %}
	 	<a href="/cliente/details/{{user.codigo_cliente}}" >
	 	{{empresa.nome}}</a>
	 {%else%}
		Cliente n&atilde;o associado
	 {%endif%}
	{% endif %}
	{% if auth['role']=='administrador' or (auth['role']=='vendedor' and auth['id']==user.vendedor) %}
		<button type="button" class="btn btn-warning" style="float:right" onclick="User.changeCompany.toggle(this)">alterar</button>
		{{partial('register/associate')}}
	{% endif %}
	</h3>
</div>
</div>

{% if auth['role']=='administrador' and user_actual_cnpj_cpf != NULL or auth['role']=='vendedor' and user_actual_cnpj_cpf != NULL %}
<div class="well">
	<p>CNPJ ou CPF - Fornecido <b><?php echo limpaCnpj($user_actual_cnpj_cpf) ?></b></p>
</div>
{% endif %}

{% if user_status == 'confirmed' and user_actual_cnpj_cpf != NULL %}
<div class="well">
	<h3><?php echo limpaCnpj($user_actual_cnpj_cpf) ?></h3>
	<h3>CNPJ ou CPF - Fornecido</h3>
	<p>Aguarde, seu cadastro está sendo processado. E, em breve, será aprovado.</p>
</div>
{% endif %}

{{ form("usuario/save", 'role': 'form') }}
	<div class="well">
		<fieldset>

			{% for element in form %}
				{% if is_a(element, 'Phalcon\Forms\Element\Hidden') %}
					{{ element }}
				{% else %}
					<div class="form-group">
						{{ element.label() }}
						{{ element.render(['class': 'form-control']) }}
					</div>
				{% endif %}
			{% endfor %}

		</fieldset>
	</div>

	<div class="btn-toolbar">
		<a class="btn btn-link" href="/usuario/index" role="button">
			<span class="glyphicon glyphicon-certificate"></span>
			&nbsp;
			ir para a lista de usuários
		</a>
		<button type="submit" class="btn btn-success">
			<span class="glyphicon glyphicon-floppy-disk"></span>
			&nbsp;
			Salvar
		</button>
	</div>

</form>

{% if log_produtos != "NOT" and role == "administrador" or log_produtos != "NOT" and role == "vendedor"%}
<h3>Log de acesso aos produtos</h3>
<div>
	<table class="table table-striped table-bordered">
		
		<thead>
		<tr>
			<th>Codigo Produto</th>
			<th>Nome</th>
			<th>Acessos no dia</th>
			<th>Data<br>Ano - Mês - Dia</th>
		</tr>
		</thead>
		<tbody>
		{% for item in log_produtos %}
		<tr>
			<td>
			{{item.codigo_produto}}
			</td>

			<td>
			<a href="/produto/index/{{item.codigo_produto}}" target="_blank">
			{{maskNumber(item.descricao_site,'##################################################...')}}
			</a>
			</td>

			<td>
			{{item.view_count}}
			</td>

			<td>
			{{maskNumber(item.date,'#### -- ## -- ##')}}
			</td>
		</tr>
		{% endfor %}
		</tbody>
	</table>
{% endif %}

<script type="text/javascript">

document.addEventListener( 'DOMContentLoaded', function () {
	User.changeUserType();
	$('#vendedorLabel').val('{{vendedor.name}}').attr('disabled','disabled');
	$('#usuario_tipo').on('change',function(){
		User.changeUserType();
	});
	$('#step-wrapper').hide();
// 	Client.edit.selectClientType($('#tipo').val());
// 	Client.edit.initialize();
//	Menu.hide();
}, false );
</script>
