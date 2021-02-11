<?php include_once dirname(__FILE__).'/../../app/views/elements/money_format_alt.php'; ?> 

{% if perm is empty %}
	{% set perm = FALSE %}
{% endif %}

{% if perm is true %}

<?php
$orc = Orcamento::findFirstById($invoice[0]->id);
//Checar se usuário é relacionado $userrelated
$userrelated = Usuario::findFirstById($invoice[0]->usuario_id);

if ($userrelated->vendedor != null){
	$userrelated=TRUE;
}
else{
	$userrelated=FALSE;
}
?>

	{{ content() }}

	<div class="page-header">
		<h1>
			{% if invoice[0].orcamento.tipo == 1 %}
			<span class="glyphicon" style="color:green;">&#xe006;</span>
			{% endif %}
			Orçamento #{{invoice[0].orcamento.id}}
		</h1>
		{% if invoice[0].orcamento.tipo == 1 %}
			<small style="color:green;">Confirmar pedido de compra</small>
		{% endif %}
		{% if invoice[0].orcamento.ucode == null %}
			<h2>Orcamento registrado sem associação à empresa</h2>
		{% endif %}
	</div>


	<form action="/orcamento/save" role="form" method="post">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">
					<span style="font-weight:100;">Solicitado por</span>
					<span style="font-weight:500;">{{invoice[0].orcamento.usuario.name}}</span>
					{% if invoice[0].cliente_nome is defined %} <span style="font-weight:100;"> | </span>
					{% if auth['role'] == 'vandedor' or auth['role'] == 'administrador' %}
						<a href="/cliente/details/{{invoice[0].cliente_id}}" style="font-weight:500;">{{invoice[0].cliente_nome}}</a>
					{% else %}
						<span style="font-weight:500;"> {{invoice[0].cliente_nome}}</span>
					{% endif %}
					{% endif %}
					<span style="font-weight:100;">em</span>
					<span style="font-weight:500;">
						<?php
							$dt = DateTime::createFromFormat('Y-m-d G:i:s', $invoice[0]->orcamento->data_de_criacao);
							$dt->modify('-3 hours');
							echo $dt->format('d/m/Y');
						?>
						<?php
							echo $dt->format('G:i');
						?>
					</span>
				</h3>
			</div>
			<div class="panel-body" style="background-color:#eee;">
				<div class="--well --no-margin">
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
					<a class="btn btn-link" href="/orcamento/index" role="button" style="float:left;">
						<span class="glyphicon glyphicon-list-alt"></span>
						&nbsp; 
						ir para a lista de orçamentos
					</a>
					<?/** $orcamento_ucode=Orcamento::findFirstById($invoice[0]->orcamento_id); **/?>

					{#{% if format != 'pdf' and orcamento_ucode.ucode != null %}#}
					{% if true %}
							<a href="/orcamento/createpdf/{{id}}" role="button" class="btn btn-info" target="_blank">
								<span class="glyphicon glyphicon-file"></span>
								&nbsp; 
								<? //TODO - Somente para novos orcamentos!! ?>
								Exportar para PDF
							</a>
					{% endif %}
					{% if auth['orcamento_edicaorelacionado']  OR auth['orcamento_edicaonaorelacionado'] %}
						<button type="submit" class="btn btn-success">
							<span class="glyphicon glyphicon-floppy-disk"></span>
							&nbsp; 
							Salvar
						</button>
					{% endif %}
				</div>
			</div>
		</div>
	</form>


	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title">Itens</h3>
		</div>
		<div class="panel-body panel-no-margin">
			<div class="table-responsive">
				<table class="table table-striped table-condensed no-margin">
					<thead>
						<tr>
							<th>Descrição</th>
							<th class="text-left">Quantidade</th>
							<th class="text-left">Valor Unitário</th>
							<th class="text-left">Subtotal</th>
						</tr>
					</thead>
					<tbody>
					{% set total = 0 %}
						{% for produto in invoice %}
							{% set isAlertDanger = (produto.quantidade < 1 ? ' alert-danger ' : '') %}
							{% if true or produto.quantidade > 0 %}
								<tr class="{{isAlertDanger}}">
									<td style="padding:18px 0 23px 18px;">
										<a href="/produto/index/{{produto.codigo_produto}}">
											<span style="font-size:18px;">
												[{{produto.codigo_produto}}] {{produto.descricao_site}}
											</span>
										</a>
										<hr style="margin:5px;">
										<span style="font-size:12px;">Faturado por {{origem[produto.codigo_produto]}} |
										{{empresa.tipo}} |
										<b>NCM</b> 	{{produto.ncm}}
										<b>CST</b> 	{{impostos[produto.codigo_produto].cst}}
										<br>
										Impostos (Já inclusos) = 
										<b>ICMS</b> 	{{impostos[produto.codigo_produto].icms}}%
										<b>IPI</b> 		{{impostos[produto.codigo_produto].ipi}}%
										<b>ST</b> 		{{impostos[produto.codigo_produto].st}} {% if impostos[produto.codigo_produto].st != 'Não possui' %}%{% endif %}
										<b>PIS</b>	 	{{impostos_fixos.pis}}%
										<b>COFINS</b> 	{{impostos_fixos.cofins}}%
										<b>IR/CSLL</b> 	{{impostos_fixos.ircsll}}%
										</span>
									</td>
									<td class="text-left">{{produto.quantidade}}</td>
									
									<td class="text-left">R$ {{produto.unitario}}</td>

									<td  class="text-left">
												<span class="price-tag --regular">
													{% if produto.subtotal == '0,00' %}
														Sob Consulta
													{% else %}
														<span class="--currency" >R$</span>
														<span class="--amount" data-float="<?php echo( $produto->subtotal  ); ?>">
														{{produto.subtotal}}
													{% endif %}
												</span>
												</span>
									</td>
								</tr>
							{% endif %}
						{% endfor %}
					</tbody>
					{% if produto.total > 0 %}
						<tfoot>
							<tr class="bg-info">
								<th class="text-right" colspan="3" style="vertical-align:baseline;">Total:</th>
								<td class="text-right" style="vertical-align:baseline;">
									&nbsp;
									<span class="price-tag --total">
										<span class="--currency" >R$</span>
										<span class="--amount" data-float="<?php echo(  $total  ); ?>">{{produto.total}}</span>
									</span>
								</td>
							</tr>
						</tfoot>
					{% endif %}
				</table>
			</div>
		</div>
	</div>
{% else %}
	{% include "elements/message-no-permission.volt" %}
{% endif %}