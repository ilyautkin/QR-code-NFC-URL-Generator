<?php

require_once __DIR__ . '/qrnfcgenerator.class.php';

/**
 * Class QRNFCGeneratorPlugins.
 */
class QRNFCGeneratorPlugins extends QRNFCGenerator
{
    public function onDocFormRender()
    {
        $this->modx->regClientStartupHTMLBlock('<script type="text/javascript">
            Ext.onReady(function() {
                QRNFCGenerator.config = ' . json_encode($this->config) .';
                
                Ext.applyIf(MODx.lang, ' . json_encode($this->modx->lexicon->loadCache($this->config['namespace'])).');
            });
        </script>');

        $this->modx->regClientStartupScript($this->config['assets_url'] . 'js/mgr/qrnfcgenerator.js');
    }

    public function onLoadWebDocument()
    {
        if (isset($_GET['qrnfc_res']) && !empty($_GET['qrnfc_res'])) {
            $resourceId = (int) $this->decrypt($_GET['qrnfc_res']);

            if ($modResource = $this->modx->getObject('modResource', $resourceId)) {
                $visit = $this->modx->newObject('QRNFCVisit');
                $visit->fromArray([
                    'resource' => $resourceId,
                    'type'     => $_GET['utm_medium']
                ]);

                $visit->save();
            }
        }
    }

    public function onPageNotFound()
    {
        $alias = $this->modx->context->getOption('request_param_alias', 'q');
        if (isset($_REQUEST[$alias])) {
            $parts = explode('/', $_REQUEST[$alias]);
            if (count($parts) > 1) {
                $prefix = array_shift($parts);
                $alias = implode('/', $parts);
                if ($prefix == 'go') {
                    if ($link = $this->modx->getObject('QRNFCLink', ['alias' => $alias])) {
                        $this->modx->sendRedirect($link->url, ['responseCode' => 'HTTP/1.1 301 Moved Permanently']);
                    }
                }
            }
        }
    }
}
