{% set salvo 			= 0 %}
{% set so_precos 		= 0 %}
{% set em_negociacao 	= 0 %}
{% set aprovado 		= 0 %}
{% set perdido 			= 0 %}

<div class="page-header">
	<h1>Orçamentos</h1>
</div>

{% if auth['role'] == 'administrador' %}
<div class="row">
	<div class="col-md-2">
	<div class="panel panel-primary">
	<div class="panel-heading">Geral</div>
	<div class="panel-body">
		<table class="table" style="margin-bottom:0;">
		<thead>
			<tr>
				<th>Total</th>
				<th>&nbsp;</th>
				<th><span class="glyphicon" style="color:green;">&#xe006;</span> - %</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>{{count_orc[0]}}</td>
				<td>&nbsp;</td>
				<td>{{count_orc[11]}} - <span style="color:#CCC;">{{count_orc[12]}}%</span></td>
			</tr>
		</tbody>
		</table>
	</div>
	</div>
	</div>

	<div class="col-md-2">
	<div class="panel panel-warning">
	<div class="panel-heading">SALVO</div>
	<div class="panel-body">
		<table><tr>
		<td><span style="margin:0;padding:0; font-size:24px;"><b>
		{{count_orc[1]}}
		</b></span></td>
		<td>&nbsp;</td>
		<td align="right"><span style="color:#ffc266;font-size:18px;"><b>
		{{count_orc[2]}}%
		</b></span></td>
		</tr></table>
	</div>
	</div>
	</div>

	<div class="col-md-2">
	<div class="panel panel-default">
	<div class="panel-heading">SÓ PREÇOS</div>
	<div class="panel-body">
		<table><tr>
		<td><span style="margin:0;padding:0; font-size:24px;"><b>
		{{count_orc[3]}}
		</b></span></td>
		<td>&nbsp;</td>
		<td align="right"><span style="color:#cccccc;font-size:18px;"><b>
		{{count_orc[4]}}%
		</b></span></td>
		</tr></table>
	</div>
	</div>
	</div>

	<div class="col-md-2">
	<div class="panel panel-info">
	<div class="panel-heading">EM NEGOCIAÇÃO</div>
	<div class="panel-body">
		<table><tr>
		<td><span style="margin:0;padding:0; font-size:24px;"><b>
		{{count_orc[5]}}
		</b></span></td>
		<td>&nbsp;</td>
		<td align="right"><span style="color:#5bc0de;font-size:18px;"><b>
		{{count_orc[6]}}%
		</b></span></td>
		</tr></table>
	</div>
	</div>
	</div>

	<div class="col-md-2">
	<div class="panel panel-success">
	<div class="panel-heading">APROVADO</div>
	<div class="panel-body">
		<table><tr>
		<td><span style="margin:0;padding:0; font-size:24px;"><b>
		{{count_orc[7]}}
		</b></span></td>
		<td>&nbsp;</td>
		<td align="right"><span style="color:#5cb85c;font-size:18px;"><b>
		{{count_orc[8]}}%
		</b></span></td>
		</tr></table>
	</div>
	</div>
	</div>


	<div class="col-md-2">
	<div class="panel panel-danger">
	<div class="panel-heading">PERDIDO</div>
	<div class="panel-body">
		<table><tr>
		<td><span style="margin:0;padding:0; font-size:24px;"><b>
		{{count_orc[9]}}
		</b></span></td>
		<td>&nbsp;</td>
		<td align="right"><span style="color:#d9534f;font-size:18px;"><b>
		{{count_orc[10]}}%
		</b></span></td>
		</tr></table>
	</div>
	</div>
	</div>

</div>
{% endif %}

	<div class="well well-sm text-right">
		<form class="form-inline" action="/orcamento/index" method="GET">
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

	<input type="hidden" name="clientHidden" id="clientHidden" value="{% if client is defined %}{{client}}{% endif %}" >
	{# Capture company name #}
	{% set tagName = "" %}
	{% for orcamento in page.items %}
		{% if orcamento.cliente_nome is defined%}
			{% set tagName = orcamento.cliente_nome %}
		{% endif %}
	{% endfor %}
	<!--  {{tagName}}  -->

	<div id="panel-orcamentos" class="panel panel-default">
		<input type="hidden" id="hiddenPeriod" value="{% if period is defined %}{{period}}{% endif %}">
		<input type="hidden" id="hiddenStatus" value="{% if status is defined %}{{status}}{% endif %}">
		<div class="panel-heading">
			<div class="" style="float:right;">
				<?php if( isset($_GET['client']) ){ ?>
					<label>&emsp;Filtro:&nbsp;</label>
					<button type="button" class="btn btn-default btn-xs" onclick="Invoice.removeClient()">
							<span class="glyphicon glyphicon-remove small"></span>
							&nbsp;
							{% if tagName != "" %}
								{{tagName}}
							{% else %}
								nome do cliente
							{% endif %}
							&nbsp;
							<span class="glyphicon glyphicon-filter"></span>
					</button>
				<?php } ?>
				<label>&emsp;Status:&nbsp;</label>
				<select id="selectStatus" style="margin-right: 20px" onchange="Invoice.search();">
					<option value='total'>...</option>
					<option value="Salvo">Salvo</option>
					<option value="Só preços">Só preços</option>
					<option value="Em negociacao">Em negociação</option>
					<option value="Aprovado">Aprovado</option>
					<option value="Perdido">Perdido</option>
				</select>
				<label>Período:&nbsp;</label>
				<?php $dt = new DateTime(); ?>
				<select id="selectMonth" onchange="Invoice.search();">
					<option value='false'>...</option>
					<?php for($idx=1; $idx<=12; $idx++){ ?>
						<option value="<?php echo(  $dt->format('n-Y')  ); ?>" ><?php echo(  $dt->format('F/Y')  ); ?></option>
						<?php $dt->add(DateInterval::createFromDateString('-1 month')) ?>
					<?php } ?>
				</select>
				<a href="." id="bttClearFilter" class="btn btn-warning btn-xs" role="button" style="display:none; margin-left:2em;">
					<span class="glyphicon glyphicon-remove small"></span>
					&nbsp;
					Limpar
				</a>
			</div>
			<h2 class="panel-title">&nbsp;</h2>
		</div>
		{% if page.items|length > 0 %}
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-condensed">
					<thead>
					<tr class="with-pagination" colspan="8">
						<td colspan="8">
							{% include "elements/page-navigation.volt" %}
						</td>
					</tr>
						<tr class="active">
							<th>Status</th>
							<th>Cliente</th>
							<th>Usuário</th>
							<th class="text-center">Quando</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
					{% set total =0 %}
					{% set salvo =0 %}
					{% set so_precos =0 %}
					{% set em_negociacao =0 %}
					{% set aprovado =0 %}
					{% set finalizado =0 %}
						{% for orcamento in page.items %}
						<!-- begin row --> <!--Salvo, Enviado, Em negociação, Aprovado ou Finalizado.-->
							{% set total+=1 %}
							{% set statusClassName="" %}
							{% if orcamento.status == 'Salvo' %}
								{% set statusClassName="label-warning" %}
								{% set salvo+=1 %}
							{% elseif orcamento.status == 'Só preços' %}
								{% set statusClassName="bs-gradient" %}
								{% set so_precos+=1 %}
							{% elseif orcamento.status == 'Em negociação' %}
								{% set statusClassName="label-info" %}
								{% set em_negociacao+=1 %}
							{% elseif orcamento.status == 'Aprovado' %}
								{% set statusClassName="label-success" %}
								{% set aprovado+=1 %}
							{% elseif orcamento.status == 'Perdido' %}
								{% set statusClassName="label-danger" %}
								{% set perdido+=1 %}
							{% endif %}
							<tr class="text-center ">
								<td rowspan="2"  class="text-center" style="">
									<span class=" label {{statusClassName}} " {% if statusClassName=='bs-gradient' %} style="color:gray" {% endif %} >{{ orcamento.status }}</span> <br><br>
									
									{% if orcamento.tipo == 1 %}
									<span class="glyphicon" style="color:green;">&#xe006;</span>
									{% endif %}
								</td>
								<td class="clientName _actions text-left">
									<?php 
										$company=ClienteCore::findFirstByClienteUcode($orcamento->usuario->codigo_cliente);
										//$company_fat=ClienteCore::findFirstByClienteUcode($orcamento->usuario->codigo_cliente);
										if ($company && $company->nome != null){
											?>
											<div class="btn-group" role="group" style="white-space:nowrap;">
												<a href="/cliente/edit/<?php echo($company->clienteUcode); ?>" class="btn btn-default" style="float:none;" role="button">
														<span class="glyphicon glyphicon-pencil"></span>
														&nbsp;
														<?php echo($company->nome); ?>
													</a><a href="#" class="btn btn-default btn-xs" onclick="Invoice.addClient('<?php echo($company->clienteUcode); ?>');" style="float:none;">
														<span class="glyphicon glyphicon-filter"></span>
														&nbsp;
														Filtrar
													</a>
												</div>
											<?php
										} else {
											?>
											<span class="label label-danger">(sem empresa)</span>
											<?php
										}
									?><br>
									{% 	if auth['role'] =='administrador'  and company.clienteUcode != null
											or auth['role'] == 'vendedor' and company.clienteUcode != null %}
									<small>
									<table>
										<tr><td align="center">Ucode</td><td>&nbsp;&nbsp;--&nbsp;&nbsp;</td>
												<td align="center">Tabela custo</td><td>&nbsp;&nbsp;--&nbsp;&nbsp;</td>
												<td align="center">Mark Up Geral</td></tr>
										
										<tr><td align="center">{{ company.clienteUcode }}</td><td>&nbsp;&nbsp;--&nbsp;&nbsp;</td>
												{#<td align="center">{{ company_fat.tabela_custo }}</td><td>&nbsp;&nbsp;--&nbsp;&nbsp;</td>#}
												<td align="center">{{ company.mark_up_geral }}</td></tr>
									</table>
									</small>
									{% endif %}
								</td>
								<td align="left">
									<a href="/usuario/edit/{{ orcamento.usuario_id }}" class="btn btn-default" style="text-align:left;" >
										<span class="glyphicon glyphicon-pencil"></span>
										&nbsp;
										{{ orcamento.usuario.name }}
									</a><br>
									{% if auth['role'] == 'administrador' %}
									<?php
									$vendedor_id=Usuario::findFirstById($orcamento->usuario->id);
									$vendedor=Usuario::findFirstById($vendedor_id->vendedor);
									?>
									{% if vendedor %}
									<small><b>Vendedor: {{ vendedor.name }}</b></small>
									{% else %}
									<small><b>Sem vendedor</b></small>
									{% endif %}
									{% endif %}
								</td>
								<td class="text-center">
									{# orcamento.data_de_criacao #}
									<?php
									if ($orcamento->data_de_criacao){
										$dt = DateTime::createFromFormat('Y-m-d G:i:s', $orcamento->data_de_criacao);
										$dt->modify('-3 hours');
										echo $dt->format('d/m/Y\&\e\m\s\p\;G:i:s');
									}
									?>
								</td>
								<td class="actions">
								{% if orcamento.id > 1872 %}
									<a href="/orcamento/edit/{{orcamento.id}}" role="button" class="btn btn-primary">
										<span class="glyphicon glyphicon-pencil"></span>
										&nbsp; 
										detalhes
									</a>
								{% endif %}
								</td>
							</tr>
							<tr class="status-{{orcamento.status}}">
								<td colspan="7">
									<div style="overflow:auto; max-height:10em; font-size:75%; line-height:1.2em;">
										<table>
											<tr>
												<td colspan="8" style="font-weight:900; padding-bottom:0.5em;">
													Resumo: 

													{% if orcamento.OrcamentoItem|length == 0 %}
														nenhum produto, 
													{% elseif orcamento.OrcamentoItem|length == 1 %}
														1 produto, 
													{% else %}
														{{ orcamento.OrcamentoItem|length }} produtos, 
													{% endif %}

													{% set totalItens = 0 %}
													{% if orcamento.OrcamentoItem %}
														{% for produto in orcamento.OrcamentoItem %}
															{% if produto.quantidade > 0 %}
																{% set totalItens = totalItens + produto.quantidade %}
															{% endif %}
														{% endfor %}
													{% endif %}

													{% if totalItens == 0 %}
														nenhum item
													{% elseif totalItens == 1 %}
														1 item
													{% else %}
														{{ totalItens }} itens
													{% endif %}
												</td>
											</tr>
											{% if orcamento.OrcamentoItem %}
												{% for produto in orcamento.OrcamentoItem %}
													{% if produto.quantidade > 0 %}
														<tr>
															<td class="text-right" style="font-weight:bold;">{{produto.quantidade}}</td>
															<td>&nbsp;&nbsp;&nbsp;--&nbsp;{{produto.ProdutoCore.codigo_produto}}&nbsp;--&nbsp;&nbsp;&nbsp;</td>
															<td class="text-left">{{produto.ProdutoCore.descricao_sys}}</td>
														</tr>
													{% endif %}
												{% endfor %}
											{% endif %}
										</table>
									</div>
								</td>
							</tr>
						{% endfor %}
					</tbody>
					<tfoot>
						<tr>
							<td colspan="9" class="text-right" style="padding:15px 5px;">
								<span style="font-weight:bold;">
									&emsp;
									Total:
									<span class="label label-default" style="background-color:#000; font-size:110%;">{{page.total_items}}</span>
								</span>
							</td>
						</tr>
						<tr class="with-pagination">
							<td colspan="9">
								{% include "elements/page-navigation.volt" %}
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		{% else %}
			<div class="panel-body panel-no-margin">
				<h1 class="text-center"><b>Nenhum item.</b></h1>
			</div>
		{% endif %}
	</div>

	<script type="text/javascript">
	document.addEventListener( 'DOMContentLoaded', function () {
		Invoice.initialize();
	}, false );
	</script>
