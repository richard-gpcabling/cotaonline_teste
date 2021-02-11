<?php
$usuario=Usuario::find(['columns' => 'id,name,email,status,codigo_cliente,cnpj_cpf_raw','conditions' => "codigo_cliente IS NULL AND cnpj_cpf_raw IS NOT NULL AND on_view_empresa_cadastrar = 1 AND status = 'confirmed'",'order'=>'id DESC']);

function limpaCnpj($str){
preg_match_all('!\d+!', $str, $matches);
$matches = implode("", $matches[0]);
return $matches;
}

function procuraCnpj($cnpj){
$empresa=ClienteCore::find(['columns' => 'cnpj_cpf','conditions' => "cnpj_cpf like '$cnpj'"]);
if (count($empresa)>0) {return TRUE;}
else{return FALSE;}
}
?>

{% include "elements/message-texts.volt" %}
{{ content() }}

{% if auth['ativacao_usuario'] %}
<div class="page-header"><h1>Empresas para cadastrar</h1></div>


{% for user in usuario  %}
<?php
$cnpj_cpf_raw = limpaCnpj($user->cnpj_cpf_raw);
?>

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">{{ user.name }} - {{ user.email }}</h3>
  </div>
  <div class="panel-body">
    <h4><?php echo $cnpj_cpf_raw; ?></h4>
    <?php
    if(procuraCnpj($cnpj_cpf_raw)){echo '<div class="alert alert-success" role="alert">
			<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
    		Registro encontrado parcialmente ou totalmente. <b>Usuário pronto para ser associado e ativado</b>.<br>
    		<a href="/usuario/edit/'.$user->id.'">Clique aqui para associar e ativar.</a>
    	</div>';}
    else {echo '<div class="alert alert-danger" role="alert">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
    		Empresa não cadastrada no DataFlex. <b>Cadastre no sistema interno e aguarde a atualização</b>.
    	</div>';}
    ?>
  </div>
</div>
{% endfor %}

{% else %}
{% include "elements/message-no-permission.volt" %}
{% endif %}