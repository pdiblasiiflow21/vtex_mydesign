<?= $this->Form->create($store, [
	'templates' => [
		'inputContainer' => '<div class="form-group {{type}}{{required}}">{{content}}</div>',
		'label' => '<label class="form-label" {{attrs}}>{{text}}</label>',
	]
]) ?>

<fieldset>
	<legend>Datos de Vtex</legend>
	<div class="row">
		<div class="col-md-6">
			<?php echo $this->Form->control('account_name', ['label' => 'Nombre cuenta', 'class' => 'form-control'])?>	
		</div>
		<div class="col-md-6">
			<?php echo $this->Form->control('environment', ['label' => 'Ambiente', 'class' => 'form-control'])?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<?php echo $this->Form->control('appkey', ['label' => 'App Key', 'class' => 'form-control'])?>
			
		</div>
		<div class="col-md-6">
			<?php echo $this->Form->control('apptoken', ['label' => 'App Token', 'class' => 'form-control'])?>
		</div>
	</div>
</fieldset>

<fieldset>
	<legend>Datos del muelle</legend>
	<div class="row">
		<div class="col-md-6">
			<?= $this->Form->control('state', [
				'label' => 'Provincia',
				'class' => 'form-select',
				'options' => [
					'Buenos Aires' => 'Buenos Aires',
					'Catamarca' => 'Catamarca',
					'Chaco' => 'Chaco',
					'Chubut' => 'Chubut',
					'Cordoba' => 'Cordoba',
					'Corrientes' => 'Corrientes',
					'Entre Rios' => 'Entre Rios',
					'Formosa' => 'Formosa',
					'Jujuy' => 'Jujuy',
					'La Pampa' => 'La Pampa',
					'La Rioja' => 'La Rioja',
					'Mendoza' => 'Mendoza',
					'Misiones' => 'Misiones',
					'Neuquen' => 'Neuquen',
					'Rio Negro' => 'Rio Negro',
					'Salta' => 'Salta',
					'San Juan' => 'San Juan',
					'San Luis' => 'San Luis',
					'Santa Cruz' => 'Santa Cruz',
					'Santa Fe' => 'Santa Fe',
					'Santiago Del Estero' => 'Santiago Del Estero',
					'Tierra Del Fuego' => 'Tierra Del Fuego',
					'Tucuman' => 'Tucuman',
				]
			])?>
		</div>
		<div class="col-md-6">
			<?= $this->Form->control('city', ['label' => 'Ciudad', 'class' => 'form-control'])?>
		</div>
	</div>

	<div class="row form">
		<div class="col-md-6">
			<?= $this->Form->control('street', ['label' => 'Calle', 'class' => 'form-control'])?>
		</div>
		<div class="col-md-6">
			<?= $this->Form->control('street_number', ['label' => 'Número', 'class' => 'form-control'])?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<?= $this->Form->control('postal_code', ['label' => 'Código postal', 'class' => 'form-control'])?>
		</div>
	</div>
</fieldset>
<fieldset>
	<legend>Datos de acceso api iFlow</legend>
	<?= $this->Form->control('user_api_iflow', ['label' => 'Usuario', 'class' => 'form-control'])?>
	<?= $this->Form->control('pass_api_iflow', ['label' => 'Contraseña', 'class' => 'form-control'])?>
</fieldset>

<div class="mt-4 text-center">
	<?= $this->Form->button('Guardar', ['class' => 'if-btn']) ?>
</div>
<?= $this->Form->end() ?>
