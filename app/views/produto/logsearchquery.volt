<h1>Log de Busca</h1>

<!-- ROW START -->
<div class="row">

    <!-- Nav tabs -->
    <ul class="nav nav-tabs">
        <li{{ action === 'busca' ? ' class="active"' : '' }}>
            <a href="/produto/logsearchquery/busca">
                Últimas Buscas
            </a>
        </li>

        <li{{ action === 'termos' ? ' class="active"' : '' }}>
            <a href="/produto/logsearchquery/termos">
                Termos
            </a>
        </li>

        <li{{ action === 'usuarios' ? ' class="active"' : '' }}>
            <a href="/produto/logsearchquery/usuarios">
                Usuários
            </a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">

        <!-- Busca -->
        <div class="tab-pane{{ action === 'busca' ? ' active' : '' }}" id="busca">
            {% if action === 'busca' %}
                <div id="panelCategoryProducts" class="panel panel-default">
                    <div class="panel-body table-responsive">
                        <h5>Lista de termos buscados pelos usuários.</h5>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Termo</th>
                                    <th>Nome</th>
                                    <th>E-mail</th>
                                    <th>Data e Hora</th>
                                </tr>
                            </thead>
                            <tbody>
                            {% for busca in page.items %}
                                <tr>
                                    <td scope="row">{{ busca.content }}</td>
                                    <td>
                                        <a href="/usuario/edit/{{ busca.userid }}" target="_blank">{{ busca.name }}</a>
                                    </td>
                                    <td>
                                        <a href="/usuario/edit/{{ busca.userid }}" target="_blank">{{ busca.email }}</a>
                                    </td>
                                    <td>{{ date('d/m/Y H:i', strtotime(busca.timestamp)) }}</td>
                                </tr>
                            {% endfor %}
                            </tbody>
                        </table>
                        <div class="pull-right">
                            {{ partial('elements/page-navigation') }}
                        </div>
                    </div>
                </div>
            {% endif %}
        </div>

        <!-- Termos -->
        <div class="tab-pane{{ action === 'termos' ? ' active' : '' }}" id="termos">
            {% if action === 'termos' %}
                <div id="panelCategoryProducts" class="panel panel-default">
                    <div class="panel-body table-responsive">
                        <h5>Todos os termos buscados no site</h5><br>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Termo</th>
                                    <th>Quantidade</th>
                                </tr>
                            </thead>
                            <tbody>
                            {% for termo in page.items %}
                                <tr>
                                    <td>{{ termo.content }}</td>
                                    <td>{{ termo.count }}</td>
                                </tr>
                            {% endfor %}
                            </tbody>
                        </table>
                        <div class="pull-right">
                            {{ partial('elements/page-navigation') }}
                        </div>
                    </div>
                </div>
            {% endif %}
        </div>

        <!-- Usearch -->
        <div class="tab-pane{{ action === 'usuarios' ? ' active' : '' }}" id="usuarios">
            {% if action === 'usuarios' %}
                <div id="panelCategoryProducts" class="panel panel-default">
                    <div class="panel-body table-responsive">
                        <h5>Usuários cadastrados que utilizaram a busca do site</h5><br>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Quantidade</th>
                            </thead>
                            <tbody>
                                {% for user in page.items %}
                                    <tr>
                                        <td scope="row">
                                            <a href="/usuario/edit/{{ user.user_id }}" target="_blank">{{ user.name }}</a>
                                        </td>
                                        <td>
                                            <a href="/usuario/edit/{{ user.user_id }}" target="_blank">{{ user.email }}</a>
                                        </td>
                                        <td>{{ user.count }}</td>
                                    </tr>
                                {% endfor %}
                            </tbody>
                        </table>
                        <div class="pull-right">
                            {{ partial('elements/page-navigation') }}
                        </div>
                    </div>
                </div>
            {% endif %}
        </div>

    </div>

</div>
<!-- ROW END -->