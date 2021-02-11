<?php

$id = $auth['id'];
$usuario=Usuario::find(['columns' => 'id,status,codigo_cliente,cnpj_cpf_raw','conditions' => "id=$id"]);
$user_actual_cnpj_cpf=$usuario[0]['cnpj_cpf_raw'];
$user_codigo_cliente=$usuario[0]['codigo_cliente'];

?>

<div class="page-header">
	<h1>Minha conta</h1>
	<h3>
		<b>{{auth['name']}}</b>
		{{auth['email']}}
	</h3>
</div>


{% set debug = false %}
{% set session = session.get('auth') %}
{% if  debug == true or session['client'] == null %}
	<!-- This div only shows if user account is not linked to a client  -->
	{% if session['role'] =='cliente parceiro' OR session['role'] =='cliente normal' %}
	<!-- This div only shows if user role is a client  -->
	<div class="well">
		<h1><b>Faça parte</b></h1>
		<p>Voce ainda não associou seu usuário à uma empresa. </p>
		<div class="panel panel-default" style="background-color: #f5f5f5;">
			<div class="panel-body">
				<h4>Associando sua conta à sua empresa você poderá ver os preços</h4>
			</div>
		</div>
		<p class="text-right"><a href="/register/associate" role="button" class="btn btn-success btn-lg">
			<span class="glyphicon glyphicon-thumbs-up"></span>
			&nbsp;
			Associe-se agora!
			&nbsp;
		</a></p>
	</div>
	<!-- end div -->
	{% endif %}
{% endif %}


{% if debug or ( session['role'] == 'cliente parceiro' or session['role'] =='cliente normal' or session['role'] =='administrador' ) %}
	{% if  session['client']!= null %}
		<div class="well">
			<h2>Empresa</h2>
			<h3 style="margin-bottom:0;">
			<?php
				if ($auth && $auth['client']) {
					$sql = "select cliente_core.nome as nome  FROM cliente_core WHERE cliente_core.clienteUcode LIKE '".$auth['client']."'  ";
						$di = \Phalcon\DI::getDefault();
						$db = $di['db'];
							// $this->flash->notice(json_encode($sql)); //debug
						$data = $db->query( $sql );
						$data->setFetchMode(\Phalcon\Db::FETCH_OBJ);
						$name = $data->fetchAll();
						if ($name) {
							echo $name[0]->nome;
						}
				}
			?>
			{% if auth['role']=='administrador' %}
				{% include "register/associate.volt" %}
			{% endif %}
			</h3>
		</div>
		</div>
{% endif %}
{% endif %}

{% if user_codigo_cliente == null AND user_actual_cnpj_cpf != null %}
<div class="well">
	<h3>CNPJ ou CPF - Fornecido</h3>
	<h3>{{ user_actual_cnpj_cpf }}</h3>
	<p>Aguarde, seu cadastro está sendo processado. E, em breve, será aprovado.</p>
</div>
{% endif %}


<div class="well">
	<h3>Mudar senha</h3>
	<form action="/conta/changepwd" method="post">
		<div class="form-group">
			<label for="password">Nova senha</label>
			<input type="password" id="password" name="password" class="form-control" placeholder="Nova senha">
		</div>
		<div class="form-group">
			<label for="exampleInputPassword1">Confirme</label>
			<input type="password" id="repeatPassword" name="repeatPassword" class="form-control" placeholder="Confirmação">
		</div>
		<button type="submit" class="btn btn-primary">
			<span class="glyphicon glyphicon-ok"></span>
			&nbsp; 
			Salvar
		</button>
	</form>
</div>