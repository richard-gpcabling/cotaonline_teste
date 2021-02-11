<!DOCTYPE html>
<html>
<head>
<style>
table, th, td {
		text-align: left;
    border: 1px solid black;
}

th,td{
	padding:10px;
}

</style>
</head>

<body>
<h2>Orçamento formalizado com sucesso - ID: {{orc_id}}</h2>
<p>Seu orçamento está salvo no seu painel e nossa equipe entrará em contato para prosseguirmos com seu orçamento.</p>


<h2>Seus dados</h2>
<p>{{seus_dados}}</p>

<p>{{vendedor}}</p>

<br>
<h2>Resumo do seu orçamento:</h2>

<table>
<thead>
<tr>
	<th>Qtd</th>
	<th>Codigo</th>
	<th>Nome</th>
	<th>Fabricante</th>
</tr>
</thead>

<tbody>
{{lista_prod}}
</tbody>

<tfoot>
  <tr>
    <th colspan="8">
		<p>{{orc_total}}</p>
		</th>
  </tr>
</tfoot>

</table>

<p>Muito obrigado,<br>
	Equipe COTAonline</p>
</body>

</html>