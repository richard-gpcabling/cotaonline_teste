{% set token = 0 %}


{{ content() }}

{{ this.flash.output() }}

{% if auth['edicao_produto'] %}

<!-- ROW START -->
<div class="row">
	<h1><b>{{produto.descricao_sys}}</b></h1>
	{% if hasCategories < 1 %}
	<p style="color:gray; font-size:24px;">
		<span class="glyphicon glyphicon-flag"></span>&nbsp;Produto no Limbo
	</p>
{% else %}

	{% endif %}


	<br>
	<a class="btn btn-link" href="/produto/resetParams" role="button">
	<span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;<b>Ir para a lista de produtos</b>
	</a>

	{% if produto.status == 1 %}
	<a role="button" class="btn btn-link" href="/produto/index/{{produto.codigo_produto}}"><b>
	<span class="glyphicon glyphicon-eye-open"></span>&nbsp;<b>Visualizar página deste produto</b>
	</a>
	{% else %}
	<span style="color:gray">
	<span class="glyphicon glyphicon-eye-open"></span>
	Visualização não disponível, status = 0 [Deve ser habilitado no DataFlex]
	</span>
	{% endif %}
</div>
<!-- ROW END -->

<br><br>

<!-- ROW START -->
<div class="row">
<div class="col-md-2">
	<div class="well well-sm bg-info text-center" style="min-height:110px;">
<label>Código do produto</label>
<h3 style="margin-bottom:0;"><b>{% if produto.codigo_produto %}{{produto.codigo_produto}}{% else %}&nbsp;{% endif %}</b></h3>
	</div>
</div>

<div class="col-md-3">
	<div class="well well-sm bg-info text-center" style="min-height:110px;">
	<label>Part Number</label>
	<h3 style="margin-bottom:0;"><b>{% if produto.ref %}{{produto.ref}}{% else %}Não possui{% endif %}</b></h3>
	</div>
</div>

<div class="col-md-2">
	<div class="well well-sm bg-info text-center" style="min-height:110px;">
	<label>Sigla do fabricante</label>
	<h3 style="margin-bottom:0;"><b>{% if produto.sigla_fabricante %}{{produto.sigla_fabricante}}{% else %}&nbsp;{% endif %}</b></h3>
	</div>
</div>

<div class="col-md-2">
	<div class="well well-sm 
	{% if produto.status == 0 %}
	bg-danger
	{% else %}
	bg-success
	{% endif %}
	text-center" style="min-height:110px;">
	<label>Status</label>
	<h3 style="margin-bottom:0;"><b>{{produto.status}}</b></h3>
	</div>
</div>

</div>
<!-- ROW END -->

<h1>Conteúdos</h1>

<!-- ROW START -->
<div class="row">

<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
	<li role="presentation" class="active">
		<a href="#descricao" aria-controls="descricao" role="tab" data-toggle="tab">
			{% if !produto_descricao.texto %}<span class="glyphicon glyphicon-flag" style="color:red;"></span>{% endif %} Descrição
		</a>
	</li>

	<li role="presentation">
		<a href="#fotos" aria-controls="fotos" role="tab" data-toggle="tab">
			{% if !img %}<span class="glyphicon glyphicon-flag" style="color:red;"></span>{% endif %} Fotos
		</a>
	</li>
	
	<li role="presentation">
		<a href="#documentos" aria-controls="documentos" role="tab" data-toggle="tab">
			{% if !doc %}<span class="glyphicon glyphicon-flag" style="color:red;"></span>{% endif %} Documentos
		</a>
	</li>

	<li role="presentation">
		<a href="#tags" aria-controls="tags" role="tab" data-toggle="tab">
			{% if !tags %}<span class="glyphicon glyphicon-flag" style="color:red;"></span>{% endif %} Tags
		</a>
	</li>

    <li role="presentation">
        <a href="#categories" aria-controls="categories" role="tab" data-toggle="tab">
            {% if hasCategories < 1 %}<span class="glyphicon glyphicon-flag" style="color:red;"></span>{% endif %} Categorias
        </a>
    </li>
</ul>

<!-- Tab panes -->
<div class="tab-content">

<!-- Descricao -->
<div role="tabpanel" class="tab-pane active" id="descricao">
<div class="panel panel-default">
	<div class="panel-body">
	{% if !produto_descricao.texto %}
	<h1>Produto sem descrição</h1>
	{% endif %}
		<form action="../updatetext/{{produto.codigo_produto}}" method="post" enctype="multipart/form-data">
			<textarea id="TypeHere" class="tinymce" name="descricao_texto" style="min-height:500px;">
				{{produto_descricao.texto}}
			</textarea><br>
			<button type="submit" class="btn btn-success">
				Atualizar Conteúdo
			</button>
		</form>
	</div>
</div>
</div>

<!-- Images -->
<div role="tabpanel" class="tab-pane" id="fotos">
<div id="panelCategoryProducts" class="panel panel-default">
<div class="panel-body panel-no-margin table-responsive">

<div class="panel panel-default">
  <div class="panel-body">
    <form action="../uploadimg/{{produto.codigo_produto}}" method="post" enctype="multipart/form-data">
 			<input type="file" name="files[]" multiple><br>
			<button type="submit" class="btn btn-success">
				Enviar Imagen(s)
			</button>
		</form>
  </div>
</div>

	<table class="table table-striped table-condensed" id="tb-visualizados">
	<thead>
	<th>Thumb</th>
	<th>Nome do arquivo</th>
	<th>Tamanho</th>
	<th>Deletar</th>
	</thead>

	<tbody>
	{% if img %}
	{% for item in img %}
	{% set token += 1 %}
	<tr>
		<td>
			<img src="/public/produto_imagem/{{produto.codigo_produto}}/{{item}}" style="max-height:150px;">
		</td>
		<td>
			{{item}}
		</td>
		<td>
			<?
			$file_size = filesize("../public/produto_imagem/".$produto->codigo_produto."/".$item);
			$file_size_in_k = round($file_size/1024,2);
			echo $file_size_in_k." Kb";
			?>
		</td>
		<td>
			<button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#imgmodal{{token}}">
  		Deletar
			</button>
			<div class="modal fade" id="imgmodal{{token}}" tabindex="-1" role="dialog" aria-labelledby="imgmodal{{token}}">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title" id="imgmodal{{token}}">Deletar Imagem</h4>
			      </div>
			      <div class="modal-body">
							<b>{{item}}</b><br><br>
							<p>Deseja deletar o arquivo permanentemente?</p>
							<p>Esta ação não poderá ser desfeita.</p>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
							<a href="/produto/unlink/img/{{ produto.codigo_produto }}/{{ item }}/{{delete_security}}"
							role="button" class="btn btn-danger">
								DELETAR!
							</a>
			      </div>
			    </div>
			  </div>
			</div>
		</td>
	</tr>
	{% endfor %}
	{% else %}
	<tr>
		<td colspan="4">
			<h1>Produto não possui imagem</h1>
		</td>
	</tr>
	{% endif %}
	</tbody>
	</table>
</div>	
</div>
</div>


<!-- Documents -->
<div role="tabpanel" class="tab-pane" id="documentos">
<div id="panelCategoryProducts" class="panel panel-default">
<div class="panel-body panel-no-margin table-responsive">

<div class="panel panel-default">
  <div class="panel-body">
    <form action="../uploaddoc/{{produto.codigo_produto}}" method="post" enctype="multipart/form-data">
 			<input type="file" name="files[]" multiple><br>
			<button type="submit" class="btn btn-success">
				Enviar Documento(s)
			</button>
		</form>
  </div>
</div>

	<table class="table table-striped table-condensed" id="tb-visualizados">
	<thead>
	<th>Nome do arquivo</th>
	<th>Tamanho</th>
	<th>Deletar</th>
	</thead>

	<tbody>
	{% if doc %}
	{% for item in doc %}
	{% set token += 1 %}
	<tr>
		<td>
			{{item}}
		</td>
		<td>
			<?
			$file_size = filesize("../public/produto_documento/".$produto->codigo_produto."/".$item);
			$file_size_in_k = round($file_size/1024,2);
			echo $file_size_in_k." Kb";
			?>
		</td>
		<td>
			<button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#docmodal{{token}}">
  		Deletar
			</button>
			<div class="modal fade" id="docmodal{{token}}" tabindex="-1" role="dialog" aria-labelledby="docmodal{{token}}">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title" id="docmodal{{token}}">Deletar Documento</h4>
			      </div>
			      <div class="modal-body">
							<b>{{item}}</b><br><br>
							<p>Deseja deletar o arquivo permanentemente?</p>
							<p>Esta ação não poderá ser desfeita.</p>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
							<a href="/produto/unlink/doc/{{ produto.codigo_produto }}/{{ item }}/{{delete_security}}"
							role="button" class="btn btn-danger">
								DELETAR!
							</a>
			      </div>
			    </div>
			  </div>
			</div>
		</td>
	</tr>
	{% endfor %}
	{% else %}
	<tr>
		<td colspan="4">
			<h1>Produto não possui documento</h1>
		</td>
	</tr>
	{% endif %}
	</tbody>
	</table>
</div>	
</div>
</div>

<!-- Tags -->
<div role="tabpanel" class="tab-pane" id="tags">
<div class="panel panel-default">
	<div class="panel-body">
	{% if !tags %}
	<h1>Produto sem Tags</h1>
	{% endif %}
		<form action="../updatesearchtags/{{produto.codigo_produto}}" method="post" enctype="multipart/form-data">
			<textarea name="search_tags" style="min-height:500px;min-width:700px;">{{tags}}</textarea><br>
			<button type="submit" class="btn btn-success">
				Atualizar Tags
			</button>
		</form>
	</div>
</div>
</div>

    <div role="tabpanel" class="tab-pane" id="categories">
        <div class="panel panel-default">
            <div class="panel-body">
                {{ form('produto/saveCategories/' ~ produto.codigo_produto, 'method': 'post', 'id': 'form-produto-categories-edit') }}
                {{ hidden_field('codigo_produto', 'value': produto.codigo_produto) }}
                <div class="clearfix">
                    <div class="form-group has-feedback">
                        <input type="search" name="search_category" id="search_category" class="form-control" placeholder="Buscar categoria" value=""/>
                        <span id="search_category_input_clear" class="glyphicon glyphicon-remove form-control-feedback"></span>
                    </div>

                    <div id="search_category_selected" class="mb-10px"></div>

                    <div id="search_category_list" style="height: 400px; overflow-y: auto">
                        {% for category in categories %}
                            <div class="pb-5px search_category_list_item clearfix categoria-item-{{ category.id }}" data-search="{{ category.nome }}">

                                <label for="category-{{ category.id }}" class="checkbox-inline font-size-13px pt-10px">
                                    <input type="checkbox" name="categories[]" id="category-{{ category.id }}" value="{{ category.id }}"{{ category.checked ? ' checked="checked"' : '' }}> {{ category.nome_breadcrumb }}
                                </label>
                            </div>
                        {% endfor %}
                    </div>
                </div>

                <button type="submit" class="btn btn-success">
                    Salvar categorias
                </button>
                {{ end_form() }}
            </div>
        </div>
    </div>

</div>
<!-- ROW END -->




<script type="text/javascript">
	document.addEventListener( 'DOMContentLoaded', function () {
	Client.edit.selectClientType($('#tipo').val());
	Client.edit.initialize();
	}, false );
</script>

{% else %}
	{% include "elements/message-no-permission.volt" %}
{% endif %}

</div>