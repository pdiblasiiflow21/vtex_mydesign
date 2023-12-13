<?php
$controller = $this->request->getParam('controller');
$action = $this->request->getParam('action');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?= $this->Html->charset() ?>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Iflow</title>
	<?= $this->Html->meta('icon', $this->Url->build('/img/favicon.ico')) ?>
	<link rel="preconnect" href="https://fonts.googleapis.com">
  	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100&amp;display=swap" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
	<link href="<?=$this->Url->build('/css/style.css', ['fullBase' => true])?>" rel="stylesheet">
	<script>
		var baseUrl = '<?= $this->Url->build('/', ['fullBase' => true]) ?>';
		var csrfToken = '<?=$this->request->getAttribute('csrfToken')?>';
	</script>
</head>
<body>
	<header>
		<div class="if-header">
			<img src="/img/logo.png" alt="iFlow" class="logo">
		</div>
	</header>
	<div class="row container-fluid">
		<aside class="col-md-2 aside">
			<ul class="menu">
				<li><a href="<?=$this->Url->build(['controller' => 'Stores', 'action' => 'index'])?>" <?php if ($controller == 'Stores'):?>class="active"<?php endif?>>Tiendas</a></li>
				<li><a href="<?=$this->Url->build(['controller' => 'Users', 'action' => 'logout', 'prefix' => false])?>">Salir</a></li>
			</ul>
		</aside>
		<section class="col-md-10 pt-2 pb-2">
			<?= $this->Flash->render() ?>
			<?= $this->fetch('content') ?>
		</section>
	</div>
	<footer>
		<div class="if-footer">
			<div class="container-fluid">
				Sitio desarrollado por <a href="https://www.mydesign.com.ar/" target="_blank" class="ms-1"><img src="/img/mydesign.png" alt="MyDesign"></a>
			</div>
		</div>
	</footer>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.2/js/bootstrap.min.js" integrity="sha512-5BqtYqlWfJemW5+v+TZUs22uigI8tXeVah5S/1Z6qBLVO7gakAOtkOzUtgq6dsIo5c0NJdmGPs0H9I+2OHUHVQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://kit.fontawesome.com/f896f8de0d.js" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/localization/messages_es_AR.min.js"></script>
	<script src="/js/main.js"></script>
	<?= $this->fetch('script') ?>
</body>
</html>