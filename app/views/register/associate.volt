
{% include "elements/message-texts.volt" %}

{{ content() }}



{% if edituser is empty %}
	<!-- <div class="page-header"><h1>Sua empresa</h1></div>-->
{% endif %}
<div id="step-wrapper">

	<div id="step-0" class="register-step" style="_display:none;">
		{% if edituser is defined %}
			<p class="lead">&nbsp;</p>
			<div class="input-group input-group--hide-addon" id="search-cnpj--loading--hide-addon">
				<input type="text" class="form-control" aria-label="CNPJ" id="search-cnpj" placeholder="Procure pelo CNPJ ou nome da empresa" onkeyup="Register.searchOnKey();">
				<span class="input-group-addon bg-warning" style="border-left-style:none;">
					<span class="glyphicon glyphicon-hourglass"></span>
				</span>
				<span class="input-group-btn">
					<button class="btn btn-default" type="button" onclick="javascript:Register.searchBtt();">procurar</button>
				</span>
			</div>
		{% else %}
			<p class="lead">Busque sua empresa e faça a associação à sua conta.<br><b>Somente assim você poderá ver os preços.</b></p>
			<p>CNPJ ou CPF — <b>Somente números</b></p>
			<div class="input-group input-group--hide-addon" id="search-cnpj--loading--hide-addon">
				<input type="text" class="form-control" aria-label="CNPJ" id="search-cnpj" placeholder="SOMENTE NÚMEROS. Digite o CNPJ ou nome da empresa" onkeyup="Register.searchOnKey();">
				<span class="input-group-addon bg-warning" style="border-left-style:none;">
					<span class="glyphicon glyphicon-hourglass"></span>
				</span>
				<span class="input-group-btn">
					<button class="btn btn-default" type="button" onclick="javascript:Register.searchBtt();">Procurar</button>
				</span>
			</div>
			<br>
			{% if false %}
			<div class="text-right">
				<small>Alternativamente, pule para o &nbsp;</small>
				<a class="btn btn-info" href="/cliente/new?associate=true" role="button">
					cadastro
					<span class="glyphicon glyphicon-step-forward"></span>
				</a>
			</div>
			{% endif %}
		{% endif %}
	</div>

	<div id="noresults" style="margin-top:1em; display:none;">
		<div class="alert alert-info in-tpl-message" role="alert">
			<span class="glyphicon glyphicon-warning-sign"></span>
			&nbsp;
			{{ message['register/associate'] }}
		</div>
	</div>

	<div id="searchResults" class="well register-step text-center" style="margin-top:2em; display:none;">
		{% if edituser is defined and edituser %}
			<form id="associateForm" action="/register/associate/{{user.id}}/change" method="POST">
		{% else %}
			<form id="associateForm" action="/register/associate" method="POST">
		{% endif %}
			<input type="hidden" id="hiddenClientId" value="" name="id" />
			{% if edituser is defined and edituser %}
				<h4 style="margin-bottom:1em;">Clique na empresa para alterar</h4>
			{% else %}
				<h4 style="margin-bottom:1em;">Sua empresa está listada abaixo?</h4>
			{% endif %}
			<ul class="list-unstyled" style="display:none;">
				<li class="resultModel" style="margin:1ex;">
					<button
						type="button"
						class="btn btn-primary confirmation ---btn-block"
						data-toggle="confirmation"
						{% if edituser is defined and edituser %}
							data-title="Deseja associar este cliente nesta empresa?"
						{% else %}
							data-title="Deseja se associar a esta empresa?"
						{% endif %}
						data-btn-ok-label="Sim"
						data-btn-cancel-label="N&atilde;o"
						data-btn-ok-class="btn btn-success"
						data-btn-cancel-class="btn btn-default"
						style="font-size:25px;"
					>
						&emsp;
						<span class="clientName">&hellip;</span>
					</button>
				</li>
			</ul>
			<ul class="list-unstyled resultItems"><!-- rendering results here --></ul>
		</form>

		<input type="hidden" id="page" name="page" value="1">
		{% include "elements/page-navigation.html" %}
		{% if false %}
		<p class="text-center">
			<a class="btn btn-success" href="/cliente/new?associate=true" role="button">
				nenhuma das empresas listadas acima
				<span class="glyphicon glyphicon-step-forward"></span>
			</a>
		</p>
		{% endif %}

	</div>


</div>
