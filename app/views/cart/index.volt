{{ content() }}


{% if auth['client'] == null OR auth['client'] == '' %}
<? $ucode_client = FALSE; ?>
{% else %}
<? $ucode_client = TRUE; ?>
{% endif %}


{% if cart|length == 0 %}
<div class="jumbotron well well-lg text-center">
	<h1><b class="text-danger">Seu carrinho está vazio.</b></h1>
	<br>
	<p>
		Adicione algum item ao orçamento.
	</p>
	<br>
	<p>
		<a href="/" role="button" class="btn btn-primary btn-lg">
			<span class="glyphicon glyphicon-home"></span>
			&nbsp; 
			Home
		</a>
	</p>
</div>

{% else %}

<div class="page-header">
<h1>Orçamento</h1>
</div>

<form action="/cart/save" method="POST" onkeydown="return event.key != 'Enter';">
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Produtos</h3>
	</div>

<div class="table-responsive">

<table class="table table-striped table-condensed">
<thead>
	<tr>
	<th></th>
	<th style="max-width:120px;">Quantidade</th>
	<th style="min-width:500px;">Nome</th>
	<th>Valor Unitário</th>
	<th>Qtd</th>
	<th>Sub-Total</th>
	</tr>
</thead>

<tbody>
{% for item in cart %}
<tr class="{{item.id}} individual-product" style="border-bottom:#CCC double;">
	<td style="padding:18px 5px;">
		<? //TODO - Fazer o delete por modal ?>
		{#
		<button type="button" 
			class="btn btn-danger" 
			id="btn{{ item.codigo_produto }}-removeItem"
			style="border:none;"
			onclick="disableButton(this);location.href='/cart/remove/{{item.codigo_produto}}/{{ requestURI() }}'">
			<span class="glyphicon glyphicon-trash"></span>
		</button>
		#}
		
		<button
			type="button" class="btn btn-danger" data-toggle="modal" data-target="#imgmodal{{item.codigo_produto}}">
  			<span class="glyphicon glyphicon-trash"></span>
		</button>

		{#
		<button
			type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#imgmodal{{token}}">
  			Deletar
		</button>
		#}
		
		<div class="modal fade" id="imgmodal{{item.codigo_produto}}"
				tabindex="-1" role="dialog" aria-labelledby="imgmodal{{item.codigo_produto}}">
			<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="imgmodal{{item.codigo_produto}}">Remover produto</h4>
				</div>
				<div class="modal-body">
						<b>{{item.descricao}} X {{item.quantidade}}</b><br><br>
						<p>Deseja remover o produto do carrinho?</p>
						<p>Esta ação não poderá ser desfeita.</p>
				</div>
				<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
				<button type="button" 
					class="btn btn-danger" 
					style="border:none;"
					onclick="location.href='/cart/remove/{{item.codigo_produto}}/{{ requestURI() }}'">
					<span class="glyphicon glyphicon-trash"></span> REMOVER
				</button>
				</div>
			</div>
			</div>
		</div>
	</td>

	<td style="padding:18px 0;">
		<input type="number" min="0" max="100000"
			name="quantidade[]"
			maxlength="3"
			class="form-control text-center" id="qtd{{item.codigo_produto}}"
			placeholder="Qtd"
			style="display:inline-block; width:112px; vertical-align:top; margin-right:2px;" value="{{item.quantidade}}">
		<br>
		<button data-helper-role="add"
		id="btn{{item.codigo_produto}}"
		name="myButton"
		onclick="disableButton(this);location.href='/cart/add/{{item.codigo_produto}}/'+$('#qtd{{item.codigo_produto}}').val()+'/{{ requestURI() }}/1'"
		type="button" class="btn btn-success btn-xs"
		style="width:112px; margin-top:8px;">
			Atualizar
		</button>
	</td>

	<td style="padding:18px 0;">
		<a href="/produto/index/{{item.codigo_produto}}">
			<span style="font-size:18px;">
				[{{item.codigo_produto}}] {{item.descricao}}
			</span>
		</a>
		<hr style="margin:5px;">
		<span style="font-size:12px;">Faturado por {{item.origem}} |
		{{item.tipo_fiscal}} |
		<b>NCM</b> 	{{item.ncm}}
		<b>CST</b> 	{{item.cst}}
		<br>
		Impostos (Já inclusos) = 
		<b>ICMS</b> {{item.icms}} %
		<b>IPI</b> 	{{item.ipi}} %
		<b>ST</b> 	{{item.st}} %
		</span>
	</td>

	<td>
		<span class="price-tag --regular">
			R$ {{item.valor_unitario|number_format(2, ',', '.')}}
		</span>
	</td>

	<td>
		x {{item.quantidade}}
	</td>

	<td>
		<span class="price-tag --regular">
			{% set subtotal = item.valor_unitario * item.quantidade %}
			R$ {{ subtotal|number_format(2, ',', '.') }}
		</span>
	</td>
</tr>

{% endfor %}

</tbody>
</table>

<div class="panel-footer text-right" style="padding-right:30px;">
	Total: <big>R$ <span id="totalprice" class="price">{{ total|number_format(2, ',', '.') }}</span></big>
</div>

</div>
</div>



<div class="panel panel-default">
	<div class="panel-heading">
		<label for="obs" class="control-label">Observações</label>
	</div>
	
	<div class="panel-body">
		<textarea id="obs" name="obs" class="form-control alert-info" rows="5"></textarea>
	</div>
</div>



<div style="float:right;
	{% if error is defined %}
		border:15px solid red;
	{% else %}
		border-bottom:10px solid #337ab7;
	{% endif %}
	">
	<select id="tipo" name="submit_type" class="form-control btn-lg" style="height:55px;">
		<option value="EMPTY">Comprar ou salvar orçamento</option>
		<option value="0">Salvar orçamento</option>
		<option value="1">Confirmar pedido de compra</option>
	</select>
</div>

<div style="clear:both;">
</div>

<div class="btn-toolbar">
	&emsp;
	<button type="submit" class="btn btn-primary btn-lg">
		<span class="glyphicon glyphicon-ok"></span>
		&nbsp; 
		ENVIAR
	</button>
	<br><br>
	
	<a href="/cotaonline" role="button" class="btn btn-link btn-lg">
	
	<span class="glyphicon glyphicon-home"></span>
	&nbsp; 
	Adicionar mais produtos
	</a>
</div>
</form>

<script type="text/javascript">
	document.addEventListener( 'DOMContentLoaded', function () {
	Client.edit.selectClientType($('#tipo').val());
	Client.edit.initialize();
	}, false );
</script>

{% endif %}
