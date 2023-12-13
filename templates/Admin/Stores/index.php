<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Store[]|\Cake\Collection\CollectionInterface $stores
 */
?>

<h3 class="h3 mb-3">Tiendas</h3>
<?= $this->Html->link('Nueva tienda', ['action' => 'add'], ['class' => 'if-btn']) ?>
<table class="table table-strip mt-4">
	<thead>
		<tr>
			<th>Id</th>
			<th>Cuenta</th>
			<th>Ambiente Vtex</th>
			<th>AppKey</th>
			<th class="text-center">Acciones</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($stores as $store): ?>
		<tr>
			<td><?= $this->Number->format($store->id) ?></td>
			<td><?= h($store->account_name) ?></td>
			<td><?= h($store->environment) ?></td>
			<td><?= h($store->appkey) ?></td>
			<td class="actions">
				<div class="d-flex justify-content-center gap-3">
					<a href="<?=$this->Url->build(['action' => 'edit', $store->id])?>" class="btn"><i class="fa-solid fa-pen-to-square"></i></a>
					<?= $this->Form->postLink('<i class="fa-solid fa-trash"></i>', ['action' => 'delete', $store->id], ['confirm' => __('¿Estás segura de que quieres eliminar # {0}?', $store->id), 'class' => 'btn', 'escape' => false]) ?>
					<a href="<?=$this->Url->build(['action' => 'view', $store->id])?>" class="btn"><i class="fa-solid fa-eye"></i></a>
				</div>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>