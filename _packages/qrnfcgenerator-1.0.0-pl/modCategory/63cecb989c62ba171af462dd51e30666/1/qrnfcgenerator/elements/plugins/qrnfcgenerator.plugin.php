<?php
$generator = $modx->getService(
    'qrnfcgeneratorplugins',
    'QRNFCGeneratorPlugins',
    $modx->getOption(
        'qrnfcgenerator.core_path', null, $modx->getOption('core_path') . 'components/qrnfcgenerator/'
    ) . 'model/qrnfcgenerator/',
    []
);

if (!($generator instanceof QRNFCGenerator)) {
    $modx->log(xPDO::LOG_LEVEL_ERROR, '[plugin.QRNFCGenerator] getService failed for class QRNFCGeneratorPlugins.');

    return '';
}

$method = lcfirst($modx->event->name);
if (method_exists($generator, $method)) {
    $generator->$method($scriptProperties);
}