<!DOCTYPE HTML>
<html>
	<head>
		<title><?=$this->headerTitle?></title>
		<meta name="description" content="<?=$this->headerDescription?>" />
		<meta name="keywords" content="<?=$this->headerKeywords?>" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="robots" content="index,follow,all" />
		<meta name="rating" content="General" />
		<meta name="revisit-after" content="1 days" />
		<link rel="icon" href="/static/images/favicon.ico" type="image/x-icon" />
		<link rel="shortcut icon" href="/static/images/favicon.ico" type="image/x-icon" />
		<link rel="image_src" type="image/jpeg" href="/static/images/shared.png" />
	<?php foreach($this->getHeaderCss() as $css): ?>
		<link rel="stylesheet" type="text/css" media="screen, projection" href="<?=$css?>" />
	<?php endforeach; ?>

	<?php foreach($this->getHeaderJavascripts() as $js): ?>
		<script src="<?=$js;?>" type="text/javascript"></script>
	<?php endforeach; ?>

		<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
	</head>

	<body>
		<header>
		</header>
		<div id=container>
			<?=$this->content?>
		</div>
		<footer>
		</footer>
		<?php $this->includeBlock('debug'); ?>
	</body>
</html>