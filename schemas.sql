-- --------------------------------------------------------
-- Versão do servidor:           5.5.46-log - Source distribution
-- OS do Servidor:               Linux
-- HeidiSQL Versão:              8.3.0.4694
-- --------------------------------------------------------

CREATE DATABASE IF NOT EXISTS `database` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `database`;

CREATE TABLE IF NOT EXISTS `transacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comprador_email` varchar(500) DEFAULT NULL COMMENT 'email do comprador',
  `data_transacao` varchar(500) DEFAULT NULL COMMENT 'Data da criação da transação no PagSeguro',
  `datahora_servidor` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'servidor rx do banco de dados',
  `codigo_transacao` varchar(500) DEFAULT NULL COMMENT 'Código identificador da transação no PagSeguro',
  `status` int(11) DEFAULT NULL COMMENT '1-Aguardando pagamento; 2-Em análise; 3-Paga; 4-Disponível; 5-Em disputa; 6-Devolvida; 7-Cancelada; 8-Debitado; 9-Retenção temporária;',
  `tipo_transacao` int(11) DEFAULT NULL COMMENT '1-Pagamento, 11-Assinatura',
  `tipo_pagamento` int(11) DEFAULT NULL COMMENT '1-Cartão de crédito; 2-Boleto; 3-Débito online; 4-Saldo PagSeguro; 5-Oi Paggo; 7-Depósito em conta;',
  `valor_bruto` float DEFAULT NULL COMMENT 'Valor bruto da transação',
  `tarifa_intermediacao` float DEFAULT NULL COMMENT '<intermediationRateAmount> Informa o valor da tarifa de intermediação dessa transação.',
  `taxa_intermediacao` float DEFAULT NULL COMMENT '<intermediationFeeAmount> Informa o valor da taxa de intermediação dessa transação.',
  `comprador_nome` varchar(500) DEFAULT NULL,
  `comprador_telefone` varchar(500) DEFAULT NULL,
  `comprador_ddd` varchar(500) DEFAULT NULL,
  `comprador_endereco` varchar(500) DEFAULT NULL,
  `comprador_numero` varchar(500) DEFAULT NULL,
  `comprador_bairro` varchar(500) DEFAULT NULL,
  `comprador_cep` varchar(500) DEFAULT NULL,
  `comprador_cidade` varchar(500) DEFAULT NULL,
  `comprador_uf` varchar(500) DEFAULT NULL,
  `comprador_pais` varchar(500) DEFAULT NULL,
  `valor_liquido` float DEFAULT NULL COMMENT 'Valor liquido da transação',
  `data_credito_liberado` datetime DEFAULT NULL COMMENT 'data que o valor estará disponivel para saque pelo vendedor',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
