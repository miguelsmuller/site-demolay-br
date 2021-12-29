<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Em Manutenção</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
</head>
<body <?php body_class(); ?>>
	<?php
	global $ClassMaintenance;
	$retorno = $ClassMaintenance->getRetorno();
	$retorno = $retorno['date'].' ás '.$retorno['time'];
	echo "O site está em manutenção. A previsão de retorno é para <strong> $retorno; </strong>";
	?>
</body>
</html>