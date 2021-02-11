
{% include "elements/message-texts.volt" %}
{{ content() }}

<div class="page-header"><h1>Obrigado!</h1></div>

<div class="alert alert-info in-tpl-message" role="alert">
	<span class="glyphicon glyphicon-hourglass"></span>
	&nbsp;
	{{ message['cart/save'] }}

</div>

<script type="text/javascript">
 document.addEventListener( 'DOMContentLoaded', function () {
	Header.update();
 }, false );
</script>