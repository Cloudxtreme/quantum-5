<script type="text/javascript" src="/includes/js/quantumEngineDebugPanel.js"></script>
<link rel="stylesheet" type="text/css" media="screen, projection" href="/render/quantumEngineDebugPanel.css" />

<div class="debug">
	<div class="debug-text">QE</div>
	<div class="debug-info">
		<b>Page run time:</b> <?=substr(View::get('scriptTime'), 0, 6)?>sec,
		<b>Memory used:</b>
		<?php
			function convert($size)
			{
				$unit=array('b','kb','mb','gb','tb','pb');
				return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
			}

			echo convert(memory_get_usage(true)); // 123 kb
		?>
	</div>
	<div class="debug-info"><b>SESSION:</b><br /><pre><?php ($_SESSION); ?></pre></div>
	<div class="debug-info"><b>GET:</b><br /><pre><?php var_dump($_GET); ?></pre></div>
	<div class="debug-info"><b>POST:</b><br /><pre><?php var_dump($_POST); ?></pre></div>
	<div class="debug-info"><b>COOKIE:</b> <br /><pre><?php var_dump($_COOKIE); ?></pre></div>
	<div class="debug-info"><b>MYSQL:</b><br /><pre><?php print_r(View::get('mysqlLog')); ?></pre></div>
	<div class="debug-info"><b>GLOBALS:</b><br /><pre><?php var_dump($GLOBALS); ?></pre></div>
</div>