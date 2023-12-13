<!DOCTYPE html>
<html>
<head>
	<?= $this->Html->charset() ?>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>IFlow - Administrador Vtex</title>
	<?= $this->Html->meta('icon') ?>

	<link rel="preconnect" href="https://fonts.googleapis.com">
  	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100&amp;display=swap" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
	<link href="<?=$this->Url->build('/css/style.css', ['fullBase' => true])?>" rel="stylesheet">

	<?= $this->fetch('meta') ?>
	<?= $this->fetch('css') ?>
	<?= $this->fetch('script') ?>
</head>
<body>
	<header>
		<div class="container">
			<div class="if-logo">
				<img src="/img/logo.png" alt="iFlow">
			</div>
		</div>
	</header>
	<main class="main">
		<div class="container">
			<?= $this->fetch('content') ?>
		</div>
	</main>
	<footer>
		<div class="if-footer">
			<div class="container">
				Sitio desarrollado por <a href="https://www.mydesign.com.ar/" target="_blank" class="ms-1"><img src="/img/mydesign.png" alt="MyDesign"></a>
			</div>
		</div>
	</footer>

	<script src="https://kit.fontawesome.com/b546633b3b.js" crossorigin="anonymous"></script>
</body>
</html>
