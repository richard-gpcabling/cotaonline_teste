
{{ content() }}

{% if auth['mensagem_relacionado'] OR auth['mensagem_naorelacionado'] %}
	<div class="page-header"><h1>Mensagens</h1></div>

	<div id="message-status-alert" class="alert alert-danger in-tpl-message" role="alert" style="display:none;">
		<span class="glyphicon glyphicon-warning-sign"></span>
		&nbsp;
		{{ message['contato/search'] }}
	</div>

	{% if page.items|length > 0 %}
		<div class="panel panel-default">
			<div class="table-responsive">
				<table id="tb-message-status" class="table">
					<thead>
						<tr>
							<th>De</th>
							<th>Email</th>
							<th>Data</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						{% for message in page.items %}
							<tr id="row-message-{{message.id}}" class="{%if message.lida%}read{%else%}unread bg-info{%endif%}" onclick="javascript:void(document.location.href='/contato/read/{{ message.id }}');" style="cursor:pointer;">
								<td class="{%if message.lida%}bg-undefined{%else%}bg-info{%endif%}" data-message-id="{{message.id}}">{{ message.nome_completo }}</td>
								<td class="{%if message.lida%}bg-undefined{%else%}bg-info{%endif%}" data-message-id="{{message.id}}">{{ message.e_mail }}</td>
								<td class="{%if message.lida%}bg-undefined{%else%}bg-info{%endif%}" data-message-id="{{message.id}}">
									{# message.data_envio #}
									<?php
										$dt = DateTime::createFromFormat('Y-m-d G:i:s', $message->data_envio);
										echo $dt->format('d/m/Y\&\e\m\s\p\;G:i:s');
									?>
								</td>
								<td class="{%if message.lida%}bg-undefined{%else%}bg-info{%endif%} actions">
									{%if message.lida%}
										<a href="/contato/read/{{ message.id }}" role="button" class="btn btn-default">
											<span class="glyphicon glyphicon-eye-open"></span>
											&nbsp; 
											Ler
										</a>
									{%else%}
									<a href="/contato/read/{{ message.id }}" role="button" class="btn btn-primary">
										<span class="glyphicon glyphicon-eye-open"></span>
										&nbsp; 
										Ler
									</a>
									{%endif%}
								</td>
							</tr>
						{% endfor %}
					</tbody>
					<tfoot class="with-pagination">
						<tr>
							<th colspan="4">
								{% include "elements/page-navigation.volt" %}
							</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	{% endif %}
	</form><!-- 
	<script type="text/javascript">

	// document.addEventListener( 'DOMContentLoaded', function () {
	// 	Client.edit.selectClientType($('#tipo').val());
	// 	Client.edit.initialize();
	//	Menu.hide();
	// }, false );
	</script> -->
{%else%}

	{% include "elements/message-no-permission.volt" %}

{%endif%}