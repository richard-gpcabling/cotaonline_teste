{% if auth['role'] == 'administrador' %}

    <h1>Produtos no Limbo</h1>
    <p>
        Lista com rodutos ativos e marcados no DataFlex. Os produtos podem ser econtrados pela busca, estão presentes
        <br> nas páginas de fabricantes, suas páginas individuais existem e estão ativadas.
    </p>

    <div class="row">

        {{ this.flash.output() }}

        <form action="/produto/limbo" name="form_produto_limbo" id="form-produto-limbo" method="post">
            <div class="col-md-6 bs-border bs-border-right">
                <h3 class="mt-0">Produtos</h3>
                {% for product in products %}
                    <div class="pb-10px">
                        <label for="product-{{ product['codigo_produto'] }}" class="checkbox-inline font-size-16px">
                            <input type="checkbox" name="products[]" id="product-{{ product['codigo_produto'] }}" value="{{ product['codigo_produto'] }}">{{ product['codigo_produto'] ~ " - " ~ product['descricao_sys']|makeTrim(40, ' ', '...') }}
                        </label>
                    </div>
                {% endfor %}
            </div>

            <div class="col-md-6">
                <h3 class="mt-0">Categorias</h3>
                <div class="form-group has-feedback">
                    <input type="search" name="search_category" id="search_category" class="form-control" placeholder="Buscar categoria" value=""/>
                    <span id="search_category_input_clear" class="glyphicon glyphicon-remove form-control-feedback"></span>
                </div>

                <div id="search_category_selected" class="mb-10px"></div>

                <div id="search_category_list" style="height: 400px; overflow-y: auto">
                    {% for category in categories %}
                        <div class="pb-10px search_category_list_item" data-search="{{ category.nome }}">
                            <label for="category-{{ category.id }}" class="checkbox-inline font-size-16px">
                                <input type="checkbox" name="categories[]" id="category-{{ category.id }}" value="{{ category.id }}"> {{ category.nome_breadcrumb }}
                            </label>

                            <div class="pull-right">
                                <a href="{{ category.link }}" title="{{ category.nome }}" target="_blank"><i class="glyphicon glyphicon-link"></i></a>
                                &nbsp;&nbsp;
                                <button type="button" name="bt_remove_categoria" class="btn btn-sm btn-danger bt-remove-categoria" data-id="{{ category.id }}">Remover</button>
                            </div>
                        </div>
                    {% endfor %}
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="form-group mt-10px pt-10px bs-border bs-border-top">
                <button type="submit" name="bt_submit" class="btn btn-primary">Salvar</button>
            </div>
        </form>
    </div>
{% else %}

    <?
/* Redirect to a different page in the current directory that was requested */
$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$extra = '/usuario/index';
header("Location: http://$host$uri/$extra");
exit;
?>


{% endif %}