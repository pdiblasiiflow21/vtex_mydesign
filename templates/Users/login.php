<?php
/**
 * @var \App\View\AppView $this
 * @var array $additional_js
 * @var string $title
 * @var \App\Model\Entity\User $user
 */
?>
<div class="d-flex align-items-center">
	<div class="bg-white rounded col-md-4 m-auto mt-4 mb-4 p-4">
		<?= $this->Flash->render()?>
		<?= $this->Form->create($user) ?>
		<fieldset>
			<div class="form-group">
				<label for="username" class="form-label"><i class="fa-solid fa-user"></i> Usuario</label>
				<?= $this->Form->input('username', ['class' => 'form-control', 'required' => true]) ?>
			</div>
			<div class="form-group">
				<label for="username" class="form-label"><i class="fa-solid fa-lock"></i> Contraseña</label>
				<?= $this->Form->input('password', ['type' => 'password', 'class' => 'form-control', 'required' => true]) ?>
			</div>
			<div class="text-center mt-3">
				<?= $this->Form->button('Entrar', [
					'class' => 'if-btn'
				]);?>
			</div>
		</fieldset>
		<?= $this->Form->end() ?>
	</div>
</div>