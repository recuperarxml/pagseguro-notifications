<?php
	# https://ws.pagseguro.uol.com.br/v3/transactions/notifications/
	# transaction-code
	# ?email=pagseguro-account
	# &token=pagseguro-token
	
	// Credentials
	$email = 'PagSeguro account';
	$token = 'PagSeguro Token';
	$bdServidor = 'Database address';
	$bdUsuario  = 'Database user';
	$bdSenha    = 'Database password';
	$bdBanco    = 'Database name';

	date_default_timezone_set('America/Sao Paulo');
	$date = date('Y-m-d H:i');
	//////////////////////////////////////////////////

	$url = 'https://ws.pagseguro.uol.com.br/v3/transactions/notifications/' . $_POST['notificationCode'] . '?email=' . $email . '&token=' . $token;
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$transaction = curl_exec($curl);
	curl_close($curl);

	if ($transaction == 'Unauthorized') {
		$name = 'log1-pagseguro-receive-' . $date . '.xml';
		$file = fopen($name, 'a');
		fwrite($file, $transaction);
		fclose($file);
		Exit();
	}
	
	// transforma o XML em objeto
	$transaction = simplexml_load_string($transaction);

	$comprador_email       = $transaction -> sender -> email;
	$status_transaction    = $transaction -> status;
	$tipo_transacao        = $transaction -> type;
	$data_transacao        = $transaction -> date;
	$codigo_transacao      = $transaction -> code;
	$tipo_pagamento        = $transaction -> paymentMethod -> type;
	$valor_bruto           = $transaction -> grossAmount;
	$tarifa_intermediacao  = $transaction -> creditorFees -> intermediationRateAmount;
	$taxa_intermediacao    = $transaction -> creditorFees -> intermediationFeeAmount;
	$valor_liquido         = $transaction -> netAmount;
	$data_credito_liberado = $transaction -> escrowEndDate;
	$comprador_nome        = $transaction -> sender -> name;
	$comprador_telefone    = $transaction -> sender -> phone -> number;
	$comprador_ddd         = $transaction -> sender -> phone -> areaCode;
	$comprador_endereco    = $transaction -> shipping -> address -> street;
	$comprador_numero      = $transaction -> shipping -> address -> number;
	$comprador_bairro      = $transaction -> shipping -> address -> district;
	$comprador_cep         = $transaction -> shipping -> address -> postalCode;
	$comprador_cidade      = $transaction -> shipping -> address -> city;
	$comprador_uf          = $transaction -> shipping -> address -> state;
	$comprador_pais        = $transaction -> shipping -> address -> country;
	
	// Conexão com banco de dados
	$conexao = mysqli_connect($bdServidor, $bdUsuario, $bdSenha, $bdBanco);
	if (mysqli_connect_errno($conexao)) {
		$name = 'log2-conexao-bd-' . $date . '.txt';
		$file = fopen($name, 'a');
		fwrite($file, 'Erro de conexao');
		fclose($file);

		die();
	}
	
	$sqlInsert = "
	insert into transacao (
		`comprador_email`,
		`status`,
		`tipo_transacao`,
		`data_transacao`,
		`codigo_transacao`, 
		`tipo_pagamento`,
		`valor_bruto`,
		`tarifa_intermediacao`,
		`taxa_intermediacao`, 
		`valor_liquido`,
		`data_credito_liberado`,
		`comprador_nome`,
		`comprador_telefone`, 
		`comprador_ddd`,
		`comprador_endereco`,
		`comprador_numero`,
		`comprador_bairro`, 
		`comprador_cep`,
		`comprador_cidade`,
		`comprador_uf`,
		`comprador_pais`)
	values (
		'$comprador_email', 
		'$status_transaction',
		'$tipo_transacao',
		'$data_transacao',
		'$codigo_transacao',
		'$tipo_pagamento',
		'$valor_bruto',
		'$tarifa_intermediacao',
		'$taxa_intermediacao',
		'$valor_liquido',         
		'$data_credito_liberado',
		'$comprador_nome',
		'$comprador_telefone',
		'$comprador_ddd',
		'$comprador_endereco',
		'$comprador_numero',
		'$comprador_bairro',
		'$comprador_cep',
		'$comprador_cidade',
		'$comprador_uf',
		'$comprador_pais'
	)";

	try {  
		mysqli_query($conexao, $sqlInsert);
	}  
	catch(exception $e) {
		$name = 'log3-insert-error-' . $date . '.txt';
     	$file = fopen($name, 'a');
		fwrite($file, 'Erro de insert: ' .$e );
		fclose($file);
	}
?>