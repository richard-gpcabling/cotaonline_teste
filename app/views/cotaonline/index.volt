<!-- BEGIN BANNER -->
<div id="carousel-home" class="carousel slide" data-ride="carousel">
	<ol class="carousel-indicators">
		<li data-target="#carousel-home" data-slide-to="0" class="active"></li>
		<li data-target="#carousel-home" data-slide-to="1"></li>
		<li data-target="#carousel-home" data-slide-to="2"></li>
		<li data-target="#carousel-home" data-slide-to="3"></li>
		<li data-target="#carousel-home" data-slide-to="4"></li>
		<li data-target="#carousel-home" data-slide-to="5"></li>
		<li data-target="#carousel-home" data-slide-to="6"></li>
	</ol>
	<div class="carousel-inner" role="listbox" style="border-radius:4px; box-shadow: 0 1px 2px rgba(0,0,0,0.25);">
		<div class="item active">
			<a href="https://solucoes.gpcabling.com.br/programaderevendas?utm_source=Landpage&utm_medium=Banner&utm_campaign=Programa%20de%20Revenda&utm_term=quero%20vender%20commscope%20AMP%20Cabeamento%20estruturado&utm_content=banner_revenda_cota" target="_blank">
				<img src="/img/banner_revenda_commscope.jpg" alt="zero">
			</a>
		</div>

		<div class="item">
			<a href="https://solucoes.gpcabling.com.br/fibra_optica_energizada" target="_blank">
				<img src="/img/banner_powered_fiber.jpg" alt="zero">
			</a>
		</div>

		<div class="item">
			<a href="https://www.gpcabling.com.br/produto/category/2/null/CM" target="_blank">
				<img src="/img/banner_amp_commscope.jpg" alt="zero">
			</a>
		</div>

		<div class="item">
			<a href="/produto/category/1/null/CS">
				<img src="/img/banner_cisco.jpg" alt="zero um">
			</a>
		</div>

		<div class="item">
			<a href="/produto/category/14/null/AP">
				<img src="/img/banner_apc.jpg" alt="zero dois">
			</a>
		</div>

		<div class="item">
			<a href="/produto/category/6/null/DH">
				<img src="/img/banner_dahua.jpg" alt="zero tres">
			</a>
		</div>

		<div class="item">
			<a href="/produto/category/7/null/VA">
				<img src="/img/banner_vault.jpg" alt="zero quinta">
			</a>
		</div>
		<!-- footer text -->
	</div>
	<!-- Left and right controls -->
	  <a class="left carousel-control" href="#carousel-home" data-slide="prev">
	    <span class="glyphicon glyphicon-chevron-left"></span>
	    <span class="sr-only">Previous</span>
	  </a>
	  <a class="right carousel-control" href="#carousel-home" data-slide="next">
	    <span class="glyphicon glyphicon-chevron-right"></span>
	    <span class="sr-only">Next</span>
	  </a>
	<!--
	<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
		<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
		<span class="sr-only">Previous</span>
	</a>
	<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
		<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
		<span class="sr-only">Next</span>
	</a>
	-->
</div>
<!-- END BANNER -->

<br>

<!-- BEGIN LATEST PRODUCTS -->
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">
			<span>Destaques</span>
		</h3>
	</div>
	<div class="panel-body panel-no-margin table-responsive">
		<table class="table table-striped _table-condensed" id="tb-lancamentos">
			<thead>
				<tr>
					<th>&nbsp;</th>
					<th>Nome</th>
					<th>Fabricante</th>
					<th>Código GP Cabling</th>
					<th>Part Number</th>
					{# <th>Categoria</th> #}
					{% if showprice %}
						<th class="text-right">Preço&nbsp;</th>
					{% endif %}
					<th>Unidade</th>
				</tr>
			</thead>
			<tbody>
				{% for product in newProductsArray %}
					{% if product.status == 1 %}
						<tr class="">
							<?php
								$sql = "select codigo_produto, texto, anexo, imagem from produto_descricao where codigo_produto = '".$product->cod_produto."'";
								$di = \Phalcon\DI::getDefault();
								$db = $di['db'];
									// $this->flash->notice(json_encode($sql)); //debug
								$data = $db->query( $sql );
								$data->setFetchMode(\Phalcon\Db::FETCH_OBJ);
								$content = $data->fetchAll();
								if ($content && $content[0] && $content[0] != null && trim($content[0]->imagem) != '' ) {
									$valid_images = array('jpg','png','gif','JPG','PNG','GIF','jpeg','JPEG');
									if(is_dir($content[0]->imagem)){
										$images = [];
										$imagespath = $content[0]->imagem;
										foreach(scandir($imagespath) as $file){
											$ext = pathinfo($file, PATHINFO_EXTENSION);
											if(in_array($ext, $valid_images)){
												array_push($images, $file);
											}
										}
									} else {
										$images = [];
									}
								} else {
									$images = [];
								}
							?>
							<td class="no-padding" style="padding-bottom:1px;">
								<a href="/produto/index/{{product.cod_produto}}" class="">
									{% if images|length > 0 %}
										{% for key, image in images %}
											{% if key == 0 %}
												<img src="/{{imagespath}}/{{image}}" class="img-product-table-thumb" alt="foto do produto">
											{% endif %}
										{% endfor %}
									{% else %}
										<img src="/img/no-image-placeholder.png" class="img-product-table-thumb" alt="(sem foto do produto)">
									{% endif %}
								</a>
							</td>
							<td>
								<a href="/produto/index/{{product.cod_produto}}" class="link-table-item">
									{{product.descricao_sys}}
								</a>
									{% if auth['status'] =='active' %}
									<? $tipo_fiscal=ProdutoCore::findFirstByCodigoProduto($produto->cod_produto); ?> 
										{% if product.estoque != 0  %}
										{% set estoque_total = product.estoque %}
												{% if tipo_fiscal.tipo_fiscal == 'SV' %}
													<br><small style="color:#5cb85c"><strong>Produto digital</strong></small>
												{% else %}
												<br>
												<small>
													<strong  style="color:#5cb85c">
														Estoque Total Disponível:
														<?php echo number_format($estoque_total,0,'','.'); ?> {{produto.unidade_venda}}<?php if($estoque_total>1){echo "s";} ?>
													</strong>
												</small>
												{% endif %}
												
											{% else %}
												{% if tipo_fiscal.tipo_fiscal == 'SV' %}
													<br><small style="color:#5cb85c"><strong>Produto digital</strong></small>
												{% else %}
													<br><small style="color:gray"><strong>Sob consulta</strong></small>
												{% endif %}
											{% endif %}
										{% endif %}

										<?php $moeda=ProdutoMarkUp::findFirstById($produto->id); ?>
										{% if moeda.moeda_venda == 'DOLAR' and auth %}
										<br><small>*Preço convertido do dólar PTAX</small>
										{% endif %}
							</td>
							<td>
								<?php 
								$sql = "select * from fabricante where fabricante.sigla like '".$product->sigla_fabricante."'";
								$di = \Phalcon\DI::getDefault();
								$db = $di['db'];
									// $this->flash->notice(json_encode($sql)); //debug
								$data = $db->query( $sql );
								$data->setFetchMode(\Phalcon\Db::FETCH_OBJ);
								$myfabricante = $data->fetchAll();
								echo $myfabricante[0]->nome;
								?>											
							</td>
							<td>{{product.cod_produto}}</td>
							<td>{{product.ref}}</td>
							{# <td>
								{% if product.ProdutoCategoria01Item is defined %}
									{% for categoria in product.ProdutoCategoria01Item %}
										{{categoria.ProdutoCategoria01Estrutura.nome}}
									{% endfor %}
								{% else %}
									&mdash;
								{% endif %}
								&nbsp;
							</td> #}
							{% if showprice %}
								<td class="text-right">
									{% if product.custo_produto is defined AND auth['custo_pagina'] AND auth['status'] =='active' %}
										{# MARKUP Exception#}
										<?php
											// echo json_encode($product->sigla_fabricante);
											$sql = " select * FROM cliente_mark_up WHERE cliente_mark_up.codigo_policom like '".$cliente_codigo_policom."' AND cliente_mark_up.sigla_fabricante like '".$product->sigla_fabricante."' ";
											$di = \Phalcon\DI::getDefault();
											$db = $di['db'];
											// $this->flash->notice("sql=".json_encode($sql)); //debug
											$data = $db->query( $sql );
											$data->setFetchMode(\Phalcon\Db::FETCH_OBJ);
											$markupException = $data->fetchAll();
											// $this->flash->notice("sql=".json_encode($markupException)); //debug
											$individualMarkup =null;
											if(!!$markupException && $markupException[0]->mark_up!=null && $markupException[0]->mark_up!=""){ // MARKUP EXCEPTION
												$individualMarkup   = "produto_mark_up.p".(sprintf("%02d", $markupException[0]->mark_up)); 
											}
											// echo json_encode($product->cod_produto);
											$exceptionValue = null;
											if ($individualMarkup) {
												$sql = "select ".$individualMarkup." as markup FROM produto_core LEFT JOIN produto_mark_up ON produto_mark_up.codigo_produto=produto_core.codigo_produto WHERE produto_core.codigo_produto =  ".$product->cod_produto;
												$di = \Phalcon\DI::getDefault();
												$db = $di['db'];
													// $this->flash->notice(json_encode($sql)); //debug
												$data = $db->query( $sql );
												$data->setFetchMode(\Phalcon\Db::FETCH_OBJ);
												$exceptionValue = $data->fetchAll();
												// echo json_encode($exceptionValue);
											}
											$markupValue = $product->markup;
											if (!!$exceptionValue && $exceptionValue[0] ) {
												$markupValue = $exceptionValue[0]->markup;
											}
										?>
										{# END MARKUP Exception#}
										{% if product.desconto is not null %}
											{% set price = {'status': 1, 'amount': product.custo_produto, 'discount':'%', 'markup': markupValue} %}
										{% else %} 
											{% set price = {'status': 1, 'amount': product.custo_produto, 'discount':false, 'markup': markupValue} %}
										{% endif %} 
									{% else %}
										{% set price = {'status': 2, 'amount': false, 'discount':false, 'markup': product.markup} %}
									{% endif %}
									{% include "elements/price-tag.volt" %}
								</td>
							{% endif %}
							<td>{{product.unidade_venda}}</td>
						</tr>
					{% endif %}
				{% endfor %}
			</tbody>
		</table>
	</div>
</div>
<!-- END LATEST PRODUCTS -->


<!-- BEGIN MOST VIEWED -->
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">
			{#
			<input type="checkbox" class="check-all" checked="true" onclick="javascript:checkAll(this,'#tb-visualizados tbody');" />
			<button type="button" onclick="javascript://Cart.Product.add(483,$('#qtd').val());" class="btn btn-warning btn-xs btn-product-actions">
				<span class="glyphicon glyphicon-shopping-cart"></span>
				&nbsp;
				Adicionar ao carrinho
				<span id="msgtasks" class="badge" style="float:right;"></span>
			</button>
			#}
			{#
			<input type="checkbox" class="check-all" checked="true" onclick="javascript:checkAll(this,'#tb-visualizados tbody');" />
			<button type="button" onclick="javascript://Cart.Product.add(483,$('#qtd').val());" class="btn btn-warning btn-xs btn-product-actions">
				<span class="glyphicon glyphicon-shopping-cart"></span>
				&nbsp;
				Adicionar ao carrinho
				<span id="msgtasks" class="badge" style="float:right;"></span>
			</button>
			<div class="btn-group btn-group--product-actions">
				<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					…marcados <span class="caret"></span>
				</button>
				<ul class="dropdown-menu">
					<li><a href="#">Adicionar todos ao carrinho</a></li>
					<li class="bg-danger"><a href="#">Adicionar aos favoritos ⚠</a></li>
					<li role="separator" class="divider"></li>
					<li class="bg-danger"><a href="#">Editar ⚠</a></li>
				</ul>
			</div>
			#}
			<span>Todos os Produtos</span>
		</h3>
	</div>
	<div class="panel-body panel-no-margin table-responsive">
		<table class="table table-striped _table-condensed" id="tb-visualizados">
			<thead>
				<tr>
					{#  <th><input type="checkbox" checked="true" onclick="javascript://;" data-may-check-all="true" /></th>  #}
					<th><!-- thumbnail --></th>
					<th>Nome</th>
					<th>Fabricante</th>
					{#
					<th>
						<button type="button" class="btn btn-default btn-xs btn-thead" onclick="javascript:$('#tr-filter-brands').toggle();">
							<span class="glyphicon glyphicon-filter"></span>
							&nbsp;
							Fabricante…
						</button>
					</th>
					<th>
						<select class="form-control">
							<option selected="selected">Fabricante</option>
							<option></option>
							{% set cats = ['Tesla','Audi','Mercedes'] %}
							{% for name in cats %}
								<option value="{{ loop.index }}">{{ name }}</option>
							{% endfor %}
						</select>
					</th>
					#}
					<th>Código GP Cabling</th>
					<th>Part Number</th>
					{# <th>Categoria</th> #}
					{% if showprice %}
						<th class="text-right">Preço&nbsp;</th>
					{% endif %}
					<th>Unidade</th>
				</tr>
				{#
				<tr id="tr-filter-brands" style="display:none;">
					<td colspan="99" class="">
						<div id="canvas-filter-most-viewed" class="well well-sm bg-info" style="margin-bottom:0;">
							<span style="display:inline-block; white-space:nowrap;">
								<span class="glyphicon glyphicon-filter"></span>
								<span>Fabricante:</span>
								&emsp;
								<a href="javascript:checkAll(true,'#canvas-filter-most-viewed'); $('#').toggle(); ">Todos</a>
								&emsp;
							</span>
							{% set cats = ['Tesla','Audi','Mercedes'] %}
							{% for name in cats %}
								<span style="display:inline-block; white-space:nowrap;">
									<input type="checkbox" id="checkbox-product-{{ loop.index }}" checked="checked" data-may-check-all="true">
									<label for="checkbox-product-{{ loop.index }}">{{ name }}</label>
								</span>
								&emsp;
							{% endfor %}
						</div>
					</td>
				</tr>
				#}
			</thead>
			{% if page.items|length > 0 %}
			<tbody>
				{% for produto in page.items %}
					{% if produto.status == 1 %}
						<tr>
							{# <th><input type="checkbox" checked="true" data-may-check-all="true" /></th> #}
							<!--
							<td><span class="glyphicon glyphicon-star"></span></td>
							-->
							<?php
									// $this->flash->notice(json_encode($produto)); //debug
								$sql = "select codigo_produto, texto, anexo, imagem from produto_descricao where codigo_produto = '".$produto->cod_produto."'";
								$di = \Phalcon\DI::getDefault();
								$db = $di['db'];
									// $this->flash->notice(json_encode($sql)); //debug
								$data = $db->query( $sql );
								$data->setFetchMode(\Phalcon\Db::FETCH_OBJ);
								$content = $data->fetchAll();
								if ($content && $content[0] && $content[0] != null && trim($content[0]->imagem) != '' ) {
									$valid_images = array('jpg','png','gif','JPG','PNG','GIF','jpeg','JPEG');
									if(is_dir($content[0]->imagem)){
										$images = [];
										$imagespath = $content[0]->imagem;
										foreach(scandir($imagespath) as $file){
											$ext = pathinfo($file, PATHINFO_EXTENSION);
											if(in_array($ext, $valid_images)){
												array_push($images, $file);
											}
										}
									} else {
										$images = [];
									}
								} else {
									$images = [];
								}
								?>
								<td class="no-padding" style="padding-bottom:1px;">
									<a href="/produto/index/{{produto.cod_produto}}" class="link-table-item">
										{% if images|length > 0 %}
											{% for key, image in images %}
												{% if key == 0 %}
													<img src="/{{imagespath}}/{{image}}" class="img-product-table-thumb">
												{% endif %}
											{% endfor %}
										{% else %}
											<img src="/img/no-image-placeholder.png" class="img-product-table-thumb">
										{% endif %}
									</a>
								</td>
							<td>
								<a href="/produto/index/{{produto.cod_produto}}" class="link-table-item">
									{{produto.descricao_sys}}
								</a>
									{% if auth['status'] =='active' %}
									<? $tipo_fiscal=ProdutoCore::findFirstByCodigoProduto($produto->cod_produto); ?> 
										{% if produto.estoque != 0  %}
										{% set estoque_total = produto.estoque %}
												{% if tipo_fiscal.tipo_fiscal == 'SV' %}
													<br><small style="color:#5cb85c"><strong>Produto digital</strong></small>
												{% else %}
												<br>
												<small>
													<strong  style="color:#5cb85c">
														Estoque Total Disponível:
														<?php echo number_format($estoque_total,0,'','.'); ?> {{produto.unidade_venda}}<?php if($estoque_total>1){echo "s";} ?>
													</strong>
												</small>
												{% endif %}
											{% else %}
												{% if tipo_fiscal.tipo_fiscal == 'SV' %}
													<br><small style="color:#5cb85c"><strong>Produto digital</strong></small>
												{% else %}
													<br><small style="color:gray"><strong>Sob consulta</strong></small>
												{% endif %}
											{% endif %}
										{% endif %}

										<?php $moeda=ProdutoMarkUp::findFirstById($produto->id); ?>
										{% if moeda.moeda_venda == 'DOLAR' and auth %}
										<br><small>*Preço convertido do dólar PTAX</small>
										{% endif %}
							</td>
							<td>
								<?php 
								$sql = "select * from fabricante where fabricante.sigla like '".$produto->sigla_fabricante."'";
								$di = \Phalcon\DI::getDefault();
								$db = $di['db'];
									// $this->flash->notice(json_encode($sql)); //debug
								$data = $db->query( $sql );
								$data->setFetchMode(\Phalcon\Db::FETCH_OBJ);
								$myfabricante = $data->fetchAll();
								echo $myfabricante[0]->nome;
								?>
							</td>
							<td>{{produto.cod_produto}}</td>
							<td>{{produto.ref}}</td>
							{# <td>
								{% if produto.ProdutoCategoria01Item is defined %}
									{% for categoria in produto.ProdutoCategoria01Item %}
										{{categoria.ProdutoCategoria01Estrutura.nome}}
									{% endfor %}
								{% else %}
									&mdash;
								{% endif %}
								&nbsp;
							</td> #}
							{% if showprice %}
								<td class="text-right">
									{% if produto.custo_produto is defined AND auth['custo_pagina'] AND auth['status'] =='active' %}
										<?php
											// echo json_encode($produto->sigla_fabricante);
											$sql = " select * FROM cliente_mark_up WHERE cliente_mark_up.codigo_policom like '".$cliente_codigo_policom."' AND cliente_mark_up.sigla_fabricante like '".$produto->sigla_fabricante."' ";
											$di = \Phalcon\DI::getDefault();
											$db = $di['db'];
											// $this->flash->notice("sql=".json_encode($sql)); //debug
											$data = $db->query( $sql );
											$data->setFetchMode(\Phalcon\Db::FETCH_OBJ);
											$markupException = $data->fetchAll();
											// $this->flash->notice("sql=".json_encode($markupException)); //debug
											$individualMarkup =null;
											if(!!$markupException && $markupException[0]->mark_up!=null && $markupException[0]->mark_up!=""){ // MARKUP EXCEPTION
												$individualMarkup   = "produto_mark_up.p".(sprintf("%02d", $markupException[0]->mark_up)); 
											}
											// echo json_encode($produto->cod_produto);
											$exceptionValue = null;
											if ($individualMarkup) {
												$sql = "select ".$individualMarkup." as markup FROM produto_core LEFT JOIN produto_mark_up ON produto_mark_up.codigo_produto=produto_core.codigo_produto WHERE produto_core.codigo_produto =  ".$produto->cod_produto;
												$di = \Phalcon\DI::getDefault();
												$db = $di['db'];
													// $this->flash->notice(json_encode($sql)); //debug
												$data = $db->query( $sql );
												$data->setFetchMode(\Phalcon\Db::FETCH_OBJ);
												$exceptionValue = $data->fetchAll();
												// echo json_encode($exceptionValue);
											}
											$markupValue = $produto->markup;
											if (!!$exceptionValue && $exceptionValue[0] ) {
												$markupValue = $exceptionValue[0]->markup;
											}
										?>
										{% set price = {'status': 1, 'amount': produto.custo_produto, 'discount':false, 'markup':markupValue} %}
									{% else %}
										{% set price = {'status': 2, 'amount': false, 'discount':false, 'markup':product.markup} %}
									{% endif %}
									{% include "elements/price-tag.volt" %}
									&nbsp;
								</td>
							{% endif %}
							<td>{{product.unidade_venda}}</td>
						</tr>
					{% endif %}
				{% endfor %}
			</tbody>
			<tfoot class="with-pagination">
				<tr>
					<th colspan="9">
						{% include "elements/page-navigation.volt" %}
					</th>
				</tr>
			</tfoot>
			{% endif %}
		</table>
	</div>
</div>
<!-- END MOST VIEWED -->


{% if false and session.get('auth') != null %} {# get session auth #}
	{% if auth['client'] is defined%}
		<span class="small">
			&lowast; O valor deste produto não está disponível.
		</span>
	{% else %}
		<script type="text/javascript">
			document.addEventListener( 'DOMContentLoaded', function () {}, false );
		</script>
	{% endif %}	
{% endif %}
