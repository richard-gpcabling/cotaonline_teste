
<div class="jumbotron well well-lg text-center">
	{% if auth['status'] =='confirmed' %}
	<h3><b>Seu cadastro está confirmado mas ainda não foi ativado.</b>
		<br>Quando estiver ativado, você poderá acessar seus orçamentos normalmente.<h3>
	{% endif %}

	<p><b>Voce não possui permissão para acessar este conteúdo.</b></p><br>
		<a href="mailto:contato-policom@gpcabling.com.br" style="font-weight:bold;"><span class="glyphicon glyphicon-envelope" style="font-size:0.66em;"></span> Entre em contato conosco para maiores detalhes.</a>
	</p>
	<br>
	<p>
		<a href="/" role="button" class="btn btn-primary btn-lg">
			<span class="glyphicon glyphicon-home"></span>
			&nbsp; 
			Home
		</a>
	</p>
</div>
