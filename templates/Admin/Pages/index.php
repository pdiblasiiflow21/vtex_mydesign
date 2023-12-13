<?php

//@var \App\View\AppView $this
//@var \App\Model\Entity\Service[]|\Cake\Collection\CollectionInterface $pages

$user = $this->request->getAttribute('identity');
?>
<div class="pages index content">
	<?= $this->Flash->render() ?>

	<h3 class="mx-heading"><?= __('Páginas') ?></h3>

	<?php if ($user->role == 'administrator'):?>
	<div class="panel panel-default">
		<?= $this->Html->link(__('Nueva Página'), ['action' => 'add'], ['class' => 'btn btn-success']) ?>
	</div>
	<?php endif?>
	
	<div class="mt-2">
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width: 50%">Nombre</th>
					<th style="width: 12%">Creado</th>
					<th style="width: 12%">Modificado</th>
					<th class="actions text-center"><?= __('Acciones') ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($pages as $page): ?>
				<tr>
					<td><?= ucfirst(h($page->name)) ?></td>
					<td><?= h($page->created_at) ?></td>
					<td><?= h($page->modified_at) ?></td>
					<td class="actions text-center">
						<div class="mx-button-menu">
							<div class="mx-button-menu__group">
								<?= $this->Html->link(__('Editar'), ['action' => 'edit', $page->id], ['class' => 'mx-button-menu__btn']) ?>
								<span class="mx-button-menu__arrow"></span>
							</div>
							<ul>
								<li><a href="<?=$this->Url->build('/' . $page->slug)?>" target="_blank">Ver</a></li>
								<?php if ($user->role == 'administrator'):?>
									<li>
										<?= $this->Form->postLink(__('Eliminar'), [
											'action' => 'delete', $page->id
										], [
											'confirm' => __('Estas seguro que deseas eliminar # {0}?', $page->name),
										])?>
									</li>
								<?php endif?>
							</ul>
						</div>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>