{% include "elements/message-texts.volt" %}
{% set showprice = false %}

{% if session.get('auth') != null %} {# get session auth #}
    {% set user_id = auth['id'] %}
    {% if auth['client'] is defined %}
        {% if cliente_estado is defined AND  cliente_estado == '' %}
            {#{% include "elements/message-no-price.volt" %}#}
        {% else %}
            {% set showprice =true %}
        {% endif %}
    {% endif %}
{% endif %}



{#
###############################
Informação para administradores
###############################
#}
{% if auth['role'] == 'administrador' %}
    <p style="border-bottom:dotted 1px gray;">

        <a href="/produto/edit/{{ produto.codigo_produto }}" role="button" class="btn btn-success">
            <span class="glyphicon glyphicon-edit"></span>
            &nbsp;
            Editar Produto
        </a>
        &nbsp; &nbsp; &nbsp;
        <b style="color:orange;">Informações Para Administradores</b>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
        Total de visualizações
        &nbsp;==&nbsp;
        <b>{{ produto.view_count }}</b>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
        10 Usuários que mais acessaram
        &nbsp;==&nbsp;
        <b>Em desenvolvimento</b>
    </p>
{% endif %}

{#
###################
Header e ADD TO ANY
###################
#}
<div itemscope itemtype="http://schema.org/Product">
    <div class="page-header">
        <h1 itemprop="name">{{ produto.descricao_site }}</h1>

        <meta itemprop="itemCondition" itemtype="http://schema.org/OfferItemCondition" content="http://schema.org/NewCondition" />

        <div itemprop="brand" itemscope itemtype="http://schema.org/Brand" class="d-inline-block">
            <b>Fabricante</b>
            <a href="../../fabricante/index/{{ produto.sigla_fabricante }}" itemprop="url" target="_blank"><span itemprop="name">{{ produto.fabricante_nome }}</span></a>
        </div>

        <b>Código GP Cabling:</b> <span itemprop="sku">{{ produto.codigo_produto }}</span>
        <b>NCM:</b> {{ produto.ncm }}
        {% if auth['status'] =='active' %}
            <b>CST:</b> {{ produto.cst }}
        {% endif %}
        <b>Part Number:</b> <span itemprop="mpn">{{ produto.ref }}</span>

        {% if produto.categoria.breadcrumb %}
        <div class="pt-10px">
            {% set totalBreadcrumb = produto.categoria.breadcrumb|length - 1 %}
            <ol itemscope itemtype="http://schema.org/BreadcrumbList" class="list-unstyled list-inline m-0">
                {% for index,item in produto.categoria.breadcrumb %}
                    <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="p-0">

                        <a href="{{ item.link }}" title="{{ item.nome }}" itemprop="item">
                            <span itemprop="name">{{ item.nome }}</span>
                        </a>
                        <meta itemprop="position" content="{{ index + 1 }}"/>

                        {% if index < totalBreadcrumb %}
                            <span class="glyphicon glyphicon glyphicon-menu-right" aria-hidden="true"
                                  style="font-size:11px"></span>
                        {% endif %}
                    </li>
                {% endfor %}
            </ol>
        </div>
        {% endif %}

        <br><br>
        <!-- AddToAny BEGIN -->
        Compartilhar:
        <div class="a2a_kit a2a_kit_size_32 a2a_default_style">
            <a class="a2a_button_facebook"></a>
            <a class="a2a_button_twitter"></a>
            <a class="a2a_button_email"></a>
            <a class="a2a_button_whatsapp"></a>
            <a class="a2a_button_linkedin"></a>
        </div>
        <script async src="https://static.addtoany.com/menu/page.js"></script>
        <!-- AddToAny END -->
    </div>

    {#
     ###################
     Header e ADD TO ANY
     ###################
    #}

    <div class="row">
        <div class="col-md-8">
            {% if img %}
                <meta itemprop="image" content="{{ this.site_url }}/produto_imagem/{{ produto.codigo_produto }}/{{ img[0] }}" />

                <div class="fotorama" data-nav="thumbs" data-width="100%" data-minwidth="200"
                     data-maxwidth="1200" data-minheight="300" data-maxheight="465" data-fit="scaledown"
                     data-keyboard="true">
                    {% for index,item in img %}
                        <img src="../../produto_imagem/{{ produto.codigo_produto }}/{{ item }}">
                    {% endfor %}
                    <img src="../../fabricante_imagem/{{ produto.sigla_fabricante|lower }}.jpg">
                </div>
            {% else %}
                <div class="fotorama" data-nav="thumbs" data-width="100%" data-minwidth="400"
                     data-maxwidth="1200" data-minheight="400" data-maxheight="465" data-fit="scaledown"
                     data-keyboard="true">
                    <img src="../../img/no-image-placeholder.png" itemprop="image">
                    <img src="../../fabricante_imagem/{{ produto.sigla_fabricante|lower }}.jpg" data-fit="none">
                </div>
            {% endif %}
        </div>

        <div class="col-md-4">
            <small>
                <b>Fabricante</b> <a href="../../fabricante/index/{{ produto.sigla_fabricante }}"
                                     target="_blank">{{ produto.fabricante_nome }}</a>
                <b>Cód. GP Cabling</b> {{ produto.codigo_produto }}<br>
                <b>NCM</b> {{ produto.ncm }}
                <b>CST</b> {{ produto.cst }}
                <b>Part. Nº</b> {{ produto.ref }}<br>
                {% if auth['status'] =='active' %}
                    <b>Impostos</b> (Já inclusos)<br>
                    <b>ICMS</b> {{produto.icms}}%  <b>IPI</b> {{produto.ipi}}%  <b>ST</b> {{produto.st}}%
                    <b>PIS</b> {{impostos_fixos.pis}}% <b>COFINS</b> {{impostos_fixos.cofins}}% <b>IR/CSLL</b> {{impostos_fixos.ircsll}}%
                {% endif %}
            </small>

            {#
            #############################
            BLOC DE ADICIONAR AO CARRINHO
            #############################
            #}
            <div class="panel panel-default canvas-add-to-cart">
                <div id="cartCanvas" class="panel-body">
                    {% if produto.preco != 'Sob Consulta' %}
                        {% set price = {'status': 1, 'amount': produto.preco, 'discount':false, 'markup':1} %}
                    {% else %}
                        {% set price = {'status': 0, 'amount': false, 'discount':false, 'markup':1} %}
                    {% endif %}
                    <div class="text-right" style="margin:1em 0 2em;">
		                <span class="price-tag --highlight">
		                    {% include "elements/price-tag.volt" %}
                            {% if produto.moeda_de_venda == 'DOLAR' and produto.preco != 'Sob Consulta' %}
                                *
                            {% endif %}
		                </span>
                        <br>
                        {% if auth['status'] =='active' %}
                            <small>Faturado por <b>{{ produto.origem }}</b></small> |
                        {% endif %}
                        {% if produto.tipo_fiscal is defined and produto.tipo_fiscal != 'SV' %}
                            <small>Cotação por <b>{{ converteUnidadeVenda(produto.unidade_venda) }}</b></small>
                        {% else %}
                            <small>Cotação por <b>Licença</b></small>
                        {% endif %}
                    </div>
                    <form class="text-right" style="">
                        <div class="form-group form-group-lg text-right"
                             style="_display:inline-block; _white-space:nowrap; margin-bottom:0;">
                            <div class="text-right">
                                {% if auth['id'] %}
                                <label for="qtd" class="sr-only">Qtd</label>

                                <input type="number" min="0" max="1000000" class="form-control text-center" id="qtd"
                                       placeholder="qtd"
                                       style="display:inline-block; width:100px; vertical-align:top; margin-right:2px;"
                                       value="1">

                                <button id="btn{{produto.codigo_produto}}"
                                        type="button"
                                        onclick="disableButton(this);location.href='/cart/add/{{produto.codigo_produto}}/'+$('#qtd').val()+'/{{ requestURI() }}'"
                                        class="btn btn-primary btn-lg" style="vertical-align:top; margin-bottom:2px;">
                                    <span class="glyphicon glyphicon-shopping-cart"></span>&nbsp;Adicionar
                                </button>
                                {% else %}
                                <button onclick="location.href='/session/index'" type="button"
                                        class="btn btn-success btn-lg" style="vertical-align:top; margin-bottom:2px;">
                                    <span class="glyphicon glyphicon-lock"></span>&nbsp;Acesse para consultar
                                </button>
                                {% endif %}

                                {% if auth['status'] =='active' %}
                                    {% if produto.tipo_fiscal is defined and produto.tipo_fiscal == 'SV' %}
                                        <br>
                                        <small style="color:#5cb85c"><strong>Produto digital</strong></small>
                                    {% elseif produto.total_estoque == 0 %}
                                        <br>
                                        <small style="color:gray"><strong>Sob Consulta</strong></small>
                                    {% else %}
                                        <br>
                                        <small>
                                            <strong style="color:#5cb85c">Estoque Total Disponível:
                                                {{ produto.total_estoque }}
                                                {{ converteUnidadeVenda(produto.unidade_venda) }}{% if produto.total_estoque > 1 %}s
                                                {% endif %}
                                            </strong>
                                        </small>
                                    {% endif %}
                                {% else %}
                                    {% if tipo_fiscal.tipo_fiscal is defined and tipo_fiscal.tipo_fiscal == 'SV' %}
                                        <br>
                                        <small style="color:#5cb85c"><strong>Produto digital</strong></small>
                                    {% else %}
                                        <br>
                                        <small style="color:gray"><strong>Sob Consulta</strong></small>
                                    {% endif %}
                                {% endif %}

                                {% if produto.moeda_de_venda == 'dolar' and produto.preco != 'Sob Consulta' %}
                                    <br>
                                    <small>*Preço convertido do Dólar PTAX</small>
                                {% endif %}
                            </div>

                                {% if user_id is defined %}
                                    {% set cart_prod_info = cartInfo(user_id,produto.codigo_produto) %}
                                {% endif %}
                                
                                {% if cart_prod_info is defined %}
                                <br>
                                <hr style="margin:0; padding:5px;">
                                <button type="button"
                                class="btn btn-default btn-sm"
                                id="btn{{produto.codigo_produto}}"
                                onclick="disableButton(this);location.href='/cart/index'">
                                    No orçamento: {{cart_prod_info}} {% if cart_prod_info > 1 %}itens{% else %}item{% endif %}
                                </button>
                                <button type="button" 
			                        class="btn-sm btn-danger"
                                    style="border:none;" 
			                        id="btn{{ produto.codigo_produto }}-removeItem" 
			                        onclick="disableButton(this);location.href='/cart/remove/{{produto.codigo_produto}}/{{ requestURI() }}'">
			                        <span class="glyphicon glyphicon-trash"></span>
		                        </button>
                                {% endif %}
                        </div>
                    </form>
                </div>
            </div>

            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Documentos</h3>
                </div>
                <div class="list-group">
                    {% if doc %}
                        {% for item in doc %}
                            <a href="../../produto_documento/{{ produto.codigo_produto }}/{{ item }}" target="_blank"
                               class="list-group-item">
                                <span class="glyphicon glyphicon-book"></span>&nbsp;&nbsp;&nbsp;{{ item }}</a>
                        {% endfor %}
                    {% else %}
                        <span class="list-group-item">
		                Este produto ainda não possui documento anexado.
		                </span>
                    {% endif %}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h2 style="font-size:38px;">DESCRIÇÃO</h2>
            {% if produto.descricao %}
                <div itemprop="description">{{ produto.descricao }}</div>
            {% else %}
                Produto sem descrição.
            {% endif %}
        </div>
    </div>
</div>