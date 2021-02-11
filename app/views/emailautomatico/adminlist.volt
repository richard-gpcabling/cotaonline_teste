<div class="page-header"><h1>Email Automático</h1></div>

{{ this.flash.output() }}

<div class="form-group">
    <a href="/emailautomatico/adminsave/0" class="btn btn-info"><span class="glyphicon glyphicon-plus"></span> Novo</a>
</div>

<table class="table table-bordered m-0">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Remetente</th>
            <th>Gatilho</th>
            <th>Assunto</th>
            <th class="text-center">Status</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
    {% for item in page.items %}
        <tr>
            <td>{{ item.nome }}</td>
            <td>{{ item.EmailAutomaticoRemetente.descricao }}</td>
            <td>{{ item.gatilho }}</td>
            <td>{{ item.assunto }}</td>
            <td class="text-center">
                {{ item.status }}
                {% if item.status === 'active' %}
                    <a href="/emailautomatico/adminupdatestatus/{{ item.id }}/inactive" class="text-danger"><span class="glyphicon glyphicon-remove"></span></a>
                {% else %}
                    <a href="/emailautomatico/adminupdatestatus/{{ item.id }}/active" class="text-success"><span class="glyphicon glyphicon-arrow-up"></span></a>
                {% endif %}
            </td>
            <td class="text-center">
                <a href="/emailautomatico/adminsave/{{ item.id }}"><span class="glyphicon glyphicon-pencil"></span></a>
            </td>
        </tr>
    {% endfor %}
    </tbody>
</table>

<div class="pull-right">
    {{ partial('elements/page-navigation') }}
</div>