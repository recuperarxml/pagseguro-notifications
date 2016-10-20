<?php
	# https://ws.pagseguro.uol.com.br/v3/transactions/notifications/
	# transaction-code
	# ?email=pagseguro-account
	# &token=pagseguro-token
	
	// Credentials
	$email = 'PagSeguro account';
	$token = 'PagSeguro Token';

	date_default_timezone_set('America/Sao Paulo');
	$date = date('Y-m-d H:i');
	//////////////////////////////////////////////////

	$url = 'https://ws.pagseguro.uol.com.br/v3/transactions/notifications/' . $_POST['notificationCode'] . '?email=' . $email . '&token=' . $token;
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$transaction = curl_exec($curl);
	curl_close($curl);

	if (($transaction == 'Unauthorized') or ($transaction == '')) {
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

	