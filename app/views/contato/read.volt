
{% if auth['mensagem_relacionado'] OR auth['mensagem_naorelacionado'] %}
	<div class="page-header">
		<h1>
			<span class="glyphicon glyphicon-envelope" style="color:hsl(0,0%,90%); vertical-align:top;"></span>
			{# date #}
			<?php
				$dt = DateTime::createFromFormat('Y-m-d G:i:s', $date);
				echo $dt->format('<b>d/m/Y </b>G:i:s');
			?>
		</h1>
	</div>

	<form role="form" method="post">
		<div class="well">
			<fieldset>
				{% for element in form %}
					{% if is_a(element, 'Phalcon\Forms\Element\Hidden') %}
						{{ element }}
					{% elseif is_a(element, 'Phalcon\Forms\Element\Check')  %}
						{{ element }}
						{{ element.label() }}
					{% else %}
						<div class="form-group">
							{{ element.label() }}
							{{ element.render(['class': 'form-control']) }}
						</div>
					{% endif %}
				{% endfor %}

			</fieldset>
		</div>

		<div class="btn-toolbar">
			<a class="btn btn-link" href="/contato/search" role="button">
				<span class="glyphicon glyphicon-bell"></span>
				&nbsp; 
				ir para a lista de mensagens
			</a>
			<a href="/contato/unread/{{msgid}}" role="button" class="btn btn-success">
				<span class="glyphicon glyphicon-eye-close"></span>
				&nbsp; 
				Marcar como não lida
			</a>
		</div>
	</form>
{% else %}
	{% include "elements/message-no-permission.volt" %}
{% endif %}