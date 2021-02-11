<h2>Datas dos arquivos</h2>
<p>Data dos arquivos originais enviados do ERP para o DataFlux</p>
<table class="table table-bordered table-striped">
<thead>
    <th>Nome do arquivo</th>
    <th>Descrição</th>
    <th>Data e Hora do Arquivo</th>
</thead>

{% for item in status  %}
<tr>
    <td>{{item.file_name}}</td>
    <td>{{item.descricao}}</td>
    <td>{{item.data_arquivo}}</td>
</tr>
{% endfor %}
</table>

<h2>Rotina</h2>
<p>Horários fixos das rotinas, nestes horários que a tabela utilizada pelo site está programada para ser atualizada</p>
<table class="table table-bordered">
<thead>
    <th>Horário</th>
    <th>Dia</th>
    <th>Descrição</th>
    <th>Arquivo</th>
</thead>

<tbody>
<tr>
    <td>6:35, 9:35, 11:35, 14:35 e 16:35</td>
    <td>De segunda à sexta</td>
    <td>Transfere a tabela de Clientes</td>
    <td>/home/datatrans/kierkegaard/cronjob/mysql_routine_cliente.sh</td>
</tr>

<tr>
    <td>6:40, 9:40, 12:40, 15:40 e 17:40</td>
    <td>De segunda à sexta</td>
    <td>Transfere a tabela de Produtos</td>
    <td>/home/datatrans/kierkegaard/cronjob/mysql_routine_produto_core.sh</td>
</tr>

<tr>
    <td>6:15, 9:15, 12:15, 15:15 e 17:15</td>
    <td>De segunda à sexta</td>
    <td>Transfere a tabela de Custo</td>
    <td>/home/datatrans/kierkegaard/cronjob/mysql_routine_produto_custo.sh</td>
</tr>

<tr>
    <td>De 4 às 20, nos minutos 03, 23 e 43</td>
    <td>De segunda à sexta</td>
    <td>Transfere a tabela de Estoque</td>
    <td>/home/datatrans/kierkegaard/cronjob/mysql_routine_produto_estoque.sh</td>
</tr>

<tr>
    <td>De 4 às 20, nos minutos 04, 24 e 44</td>
    <td>De segunda à sexta</td>
    <td>Transfere a tabela de Impostos Fixos</td>
    <td>/home/datatrans/kierkegaard/cronjob/mysql_routine_produto_imposto_fixo.sh</td>
</tr>
</tbody>

</table>

<h2>Log</h2>
<table class="table table-bordered">
<p>Horário real em que os scripts de transferência são realizados.<br>
¹ O primeiro número representa status de início de execução; o segundo, o status de termino de execução. Para status completo precisa ser 11, transferência iniciada com sucesso e terminada com sucesso.<br>
Se o registro <i>Tabelas e COUNT</i> for ERP STATUS = 0 significa que o arquivo enviado está corrompido.
</p>
<thead>
    <th></th>
    <th>ID</th>
    <th>Nome do Script</th>
    <th>Tabelas e COUNT</th>
    <th>Status¹</th>
    <th>Data de início</th>
    <th>Data de termino</th>
</thead>

{% for item in log  %}
<tr>
    <td>
        {% if item.passo == "10" OR item.passo == "00" OR item.passo == "01" %}
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true" style="font-size:20px; color:red;"></span>
        {% else %}
            <span class="glyphicon glyphicon-ok" aria-hidden="true" style="font-size:20px; color:green;"></span>
        {% endif %}
    </td>
    <td>{{item.id}}</td>
    <td>
        {{item.nome_do_script}}
    </td>
    <td style="max-width:450px;">
        {% if item.tabela_registro == "ERP STATUS = 0" %}
            <b style="color:red">
        {% endif %}
        {{item.tabela_registro}}
        {% if item.tabela_registro == "ERP STATUS = 0" %}
            </b>
        {% endif %}
    </td>
    <td>
        {% if item.passo == "10" OR item.passo == "00" OR item.passo == "01" %}
            <b style="color:red">
        {% endif %}
        {{item.passo}}
        {% if item.passo == "10" OR item.passo == "00" OR item.passo == "01" %}
            </b>
        {% endif %}
    </td>
    <td>
        <?
            $timestamp = strtotime($item->data_inicio);
            $data_inicio = $timestamp - (3 * 60 * 60);
            echo date("Y-m-d H:i:s", $data_inicio);
        ?>
    </td>
    <td>
        <?
            $timestamp = strtotime($item->data_termino);
            $data_termino = $timestamp - (3 * 60 * 60);
            echo date("Y-m-d H:i:s", $data_termino);
        ?>
    </td>
</tr>
{% endfor %}
</table>