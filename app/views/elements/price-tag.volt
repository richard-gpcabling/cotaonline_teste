<? include_once dirname(__FILE__).'/../../app/views/elements/money_format_alt.php'; ?> 
{#

Sample usage:
{% set price = {'status': 1, 'amount': 234567.89, 'discount':false, 'markup':5.67} %}
{% include "elements/price-tag.volt" %}

#}
<!-- BEGIN PRICE TAG -->
{% if price['status'] == 0 %}

	<span class="--amount">Sob Consulta</span>

{% elseif price['status'] == 1 %}

	<span class="price-tag --regular">
		<?php
			if(0){ // <!> De bug is on the table
				echo('<code>');
					echo('<br>');
					echo('$price = ');
					var_export(    $price    );
					echo('<br>');
					echo('<br>');
					echo('$priceData = ');
					var_export(    $priceData    );
					echo('<br>');
				echo('</code>');
			}
			// <!> price algorithm bellow:
			if( !is_numeric($price['amount']) ){ // is JSON data
				$priceData = array();
				$priceData = json_decode($price['amount'] , true);
				$priceCusto = $priceData['custo'];
			}elseif(is_numeric($price['amount'])){ // is numeric
				$priceCusto = $price['amount'];
			}
			//echo "|".json_encode($price['markup'])."|";
			if( !is_numeric($price['markup']) ){ // is JSON data
				$markupData = array();
				$markupData = json_encode($price['markup']);
				$priceMarkup = $priceData['custo'];
			}elseif(is_numeric($price['markup'])){ // is numeric
				$priceMarkup = $price['markup'];
			}
			$priceDataFinal = (double) $priceCusto * (double)$priceMarkup;
			$priceDataFinalFormat = money_format_alt(  $priceDataFinal  );
		?>
		<span class="--currency" >R$</span>
		<span class="--amount" data-float="<?php echo(  $priceDataFinal  ); ?>"><?php echo(  $priceDataFinalFormat  ); ?></span>
	</span>

{% elseif price['status'] == 2 %}

	&mdash;

{% else %}

	<span class="price-tag --error text-danger">(erro)</span>

{% endif %}

{% if price['discount'] == '%' %}<span class="price-tag --discount">Sob Consulta</span>{% endif %} 
<!-- END PRICE TAG -->
