{{ content() }}

<div class="page-header"><h1>Email Automático</h1></div>

{{ this.flash.output() }}

{{ form("emailautomatico/adminsave/" ~ id, 'method': 'post', 'id': 'form-email-automatico-save', 'class': 'col-md-6') }}
{{ form.render('id') }}

<div class="form-group">
    {{ form.label('gatilho') }}
    {{ form.render('gatilho') }}
</div>

<div class="form-group">
    {{ form.label('nome') }}
    {{ form.render('nome') }}
    <span></span>
</div>

<div class="form-group">
    {{ form.label('remetente') }}
    {{ form.render('remetente') }}
</div>

<div class="form-group">
    {{ form.label('reply_to') }}
    {{ form.render('reply_to') }}
</div>

<div class="form-group">
    {{ form.label('cc') }}
    {{ form.render('cc') }}
    <button name="bt_add_cc_row" class="btn btn-sm btn-primary ml-10px bt-add-input" data-target="#cc">
        <span class="glyphicon glyphicon-plus"></span>
    </button>
    <div id="block-inputs-cc"></div>
</div>

<div class="form-group">
    {{ form.label('cco') }}
    {{ form.render('cco') }}
    <button name="bt_add_cc_row" class="btn btn-sm btn-primary ml-10px bt-add-input" data-target="#cco">
        <span class="glyphicon glyphicon-plus"></span>
    </button>
    <div id="block-inputs-cco"></div>
</div>

{% for gatilho in gatilhos %}
    <div class="gatilho-variables form-group" id="variables-{{ gatilho['id'] }}" class="hidden">
        <p><b>Variáveis disponíveis para a mensagem e assunto</b></p>
        {% for variable in gatilho['variaveis_possiveis'] %}
            <div class="copyclipboard">{{ variable }}</div>
        {% endfor %}
    </div>
{% endfor %}

<div class="form-group">
    {{ form.label('assunto') }}
    {{ form.render('assunto') }}
</div>

<div class="form-group">
    {{ form.label('mensagem') }}
    {{ form.render('mensagem') }}
</div>

<div class="checkbox">
    {{ form.render('usuario_recebe') }}
    {{ form.label('usuario_recebe') }}
</div>

<div class="form-group">
    {{ form.label('status') }}
    {{ form.render('status') }}
</div>

<div class="form-group">
    <button type="submit" class="btn btn-info pull-left">Salvar</button>
    <a href="/emailautomatico/adminlist" class="btn btn-default pull-right">Cancelar</a>
</div>

{{ end_form() }}