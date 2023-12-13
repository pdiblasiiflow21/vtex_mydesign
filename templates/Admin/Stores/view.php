<table class="table table-striped">
	<tr>
		<th class="col-2">App Key</th>
		<td><?=$data->appkey?></td>
	</tr>
	<tr>
		<th>App Token</th>
		<td class="overflow-wrap"><?=$data->apptoken?></td>
	</tr>
	<tr>
		<th>Ambiente Vtex</th>
		<td><?=$data->environment?></td>
	</tr>
	<tr>
		<th>Cuenta</th>
		<td><?=$data->account_name?></td>
	</tr>
	<tr>
		<th>Provincia</th>
		<td><?=$data->state?></td>
	</tr>
	<tr>
		<th>Ciudad</th>
		<td><?=$data->city?></td>
	</tr>
	<tr>
		<th>Calle y número</th>
		<td><?=$data->street . ' ' . $data->street_number?></td>
	</tr>
	<tr>
		<th>Código postal</th>
		<td><?=$data->city?></td>
	</tr>
</table>
<div class="mt-2">
	<?=$this->Html->link('Volver', ['action' => 'index'], ['class' => 'btn btn-primary'])?>
</div>