{% include "elements/message-texts.volt" %}
{{ content() }}

<div class="page-header"><h1>Meu Vendedor</h1></div>

<div class="row">

<div class="col-md-2" style='border:1px solid #CCC; padding:4px;'>
{% if vendedor_descricao.foto %}
<img src="/{{ vendedor_descricao.foto }}" width="100%">
{% else %}
<small>Sem foto</small>
<img src="/equipe/sem_foto.jpg" width="100%">
{% endif %}


</div>

<div class="col-md-10">
	<h3>{{ vendedor.name }}</h3>
	<h2><a href="mailto:{{ vendedor.email }}">{{ vendedor.email }}</a></h2>
	<br>

	{% if vendedor_descricao.celular %}
	<p><i>Telefone Celular</i> <b>{{ vendedor_descricao.celular }}</b></p>
	{% endif %}

	<p>
		{% if vendedor_descricao.telefone_fixo %}
		<i>Telefone Fixo</i> <b>{{ vendedor_descricao.telefone_fixo }}</b>
		{% endif %}

		{% if vendedor_descricao.ramal %}
		 • <i>Ramal</i> <b>{{ vendedor_descricao.ramal }}</b>
		{% endif %}
	</p>

</div>

</div>