{% if auth['role'] == 'administrador' %}

    <h1>Categorias</h1>
    <p>
        Adicione, remova ou atualize as categorias cadastradas no sistema
    </p>

    <div class="row">

        {{ this.flash.output() }}

        <form action="/produto/categoria" name="form_admin_produto_limbo" id="form-admin-produto-categoria" method="post">

            <div class="col-md-4 bs-border bs-border-right">
                <h3 class="mt-0">Adicionar Categoria</h3>

                <div class="form-group">
                    {{ form.render('nome') }}
                </div>

                <div class="form-group">
                    <button type="submit" name="bt_submit" class="btn btn-primary">Adicionar</button>
                </div>
            </div>

            <div class="col-md-8">
                <h3 class="mt-0">Categorias</h3>
                <div class="form-group has-feedback">
                    <input type="search" name="search_category" id="search_category" class="form-control" placeholder="Buscar categoria" value=""/>
                    <span id="search_category_input_clear" class="glyphicon glyphicon-remove form-control-feedback"></span>
                </div>

                <div id="search_category_selected" class="mb-10px"></div>

                <div id="search_category_list" style="height: 400px; overflow-y: auto">
                    <div class="pb-5px search_category_list_item clearfix categoria-item-1" data-search="Categoria Principal / Menu">
                        <label for="category-1" class="checkbox-inline font-size-16px pull-left pt-10px">
                            <input type="checkbox" name="categories[]" id="category-1" value="1">0. Categoria Principal / Menu
                        </label>
                    </div>

                    {% for category in categories %}
                        <div class="pb-5px search_category_list_item clearfix categoria-item-{{ category.id }}" data-search="{{ category.nome }}">

                            <label for="category-{{ category.id }}" class="checkbox-inline font-size-16px pull-left pt-10px">
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