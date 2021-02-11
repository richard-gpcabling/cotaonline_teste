<?php
$con = new PDO("mysql:host=wf-207-38-92-35.webfaction.com;dbname=kierkegaard_b2b",'kierkegaard_b2b','kiasfpoj@335');
$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id = $auth['id'];
$cnpj_cpf_raw = "";

$usuario=Usuario::find(['columns' => 'id,status,cnpj_cpf_raw','conditions' => "id=$id"]);

$user_actual_cnpj_cpf=$usuario[0]['cnpj_cpf_raw'];
$user_status=$usuario[0]['status'];

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $cnpj_cpf_raw = test_input($_POST["cnpj_cpf_raw"]);
  $update_user = "UPDATE usuario SET cnpj_cpf_raw='$cnpj_cpf_raw' WHERE id=$id";
	$stmt = $con->prepare($update_user);
  $stmt->execute();
  header('Location: /conta/index');
}
/*
print_r($usuario);
echo "<br>";
print_r($usuario[0]['cnpj_cpf_raw']);
*/
?>
{#
{% if user_status=='active'%}
<?php header('Location: /conta/index'); ?>
{% endif %}
#}
<div class="page-header"><h1>CNPJ - CPF</h1></div>

{% if user_actual_cnpj_cpf == null or user_actual_cnpj_cpf == '' or user_actual_cnpj_cpf == '0' %}
{#
<h2>Caso você não tenha encontrado sua empresa no campo de associação, insira seu CNPJ ou CPF no campo abaixo.<br>
<a href="/register/associate">Caso queria procurar sua empresa novamente, clique aqui.</a></h2>
<p>Depois de enviar, nós iremos cadastrar em nosso sistema interno e você será notificado.</p>
<br><br>
<p><b>Esse processamento pode levar até 24 horas.</b></p><br>
#}

<p><b>Por favor, insira seu CNPJ ou CPF no campo abaixo e clique em cadastrar.</b></p>
<form action="" method="post">
<div class="well">
	<fieldset>
				<div class="form-group">
					<label for="nome_completo">CNPJ ou CPF - Somente números</label>
					<input type="text" id="cnpj_cpf_raw" name="cnpj_cpf_raw" class="form-control" maxlength="20"
					value=<?php echo '"'. $user_actual_cnpj_cpf . '"' ?>/>
				</div>
	</fieldset>
</div>

<div class="btn-toolbar">
	<button type="submit" class="btn btn-success">
		<span class="glyphicon glyphicon-ok"></span>
		&nbsp; 
		Cadastrar
	</button>
</div>
</form>
{# <a href="/register/associate">Caso queria procurar sua empresa novamente, clique aqui.</a></p> #}
{% else %}
<h4><b>Você já forneceu um CNPJ ou CPF. Estamos processando seu cadastro.</b></h4>
<p>CNPJ ou CPF enviado: <b>{{ user_actual_cnpj_cpf }}</b>.</p>
{% endif %}



