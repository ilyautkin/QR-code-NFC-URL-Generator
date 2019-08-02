<?php

/**
 * QRNFCGenerator
 *
 * Copyright 2019 by Sterc <modx@sterc.nl>
 */
require_once dirname(__DIR__, 5) . '/config.core.php';

require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';

$modx->getService(
    'qrnfcgenerator',
    'QRNFCGenerator',
    $modx->getOption(
        'qrnfcgenerator.core_path',
        null,
        $modx->getOption('core_path') . 'components/qrnfcgenerator/'
    ) . 'model/qrnfcgenerator/'
);

if ($modx->qrnfcgenerator instanceof QRNFCGenerator) {
    $modx->request->handleRequest([
        'processors_path'   => $modx->qrnfcgenerator->config['processors_path'],
        'location'          => ''
    ]);
}
