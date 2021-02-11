{#
	Sample usage:
		<div class="alert alert-info" role="alert">
			<span class="glyphicon glyphicon-warning-sign"></span>
			&nbsp;
			{% include "elements/message-texts.volt" %}
			{{ message['register/associate'] }}
		</div>
#}{% set message  = {
	'cart/save'                      : 'Recebemos sua <b>solicitação de orçamento</b>, em breve entraremos em contato.',
	'contato/search'                 : 'Ocorreu um erro!',
	'elements/message-no-permission' : 'Conteúdo restrito',
	'produto/index:1'                : 'Produto não disponível',
	'produto/index:2'                : 'Produto não existe',
	'register/associate'             : 'Não encontramos uma empresa com os dados informados.
	<h2>Por favor, <a href="/register/cnpjraw">clique aqui para enviar seu CNPJ ou CPF.</a></h2>
<form action="/register/cnpjraw">
<div class="btn-toolbar">
	<button type="submit" class="btn btn-success">
		<span class="glyphicon glyphicon-ok"></span>
		&nbsp; 
		Cadastrar
	</button>
</div>
</form>
	',
	'register/index:1'               : 'Por favor insira seu nome completo.',
	'register/index:2'               : 'Por favor insira seu email.',
	'register/index:3'               : 'Por favor insira uma senha válida.',
	'register/index:4'               : 'As senhas são diferentes.',
	'session/retrieve:1'             : 'Por favor insira seu email.',
	'session/retrieve:2'             : 'Por favor insira o código enviado por email.',
	'session/retrieve:3'             : 'Por favor insira uma senha válida.',
	'session/retrieve:4'             : 'As senhas são diferentes.',
	'usuario/index'                  : 'Ocorreu um erro!',
	'' : ''
} %}