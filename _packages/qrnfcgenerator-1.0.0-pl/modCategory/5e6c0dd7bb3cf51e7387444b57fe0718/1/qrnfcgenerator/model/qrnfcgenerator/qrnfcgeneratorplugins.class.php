<?php

require_once __DIR__ . '/qrnfcgenerator.class.php';

/**
 * Class QRNFCGeneratorPlugins.
 */
class QRNFCGeneratorPlugins extends QRNFCGenerator
{
    /**
     * @param array $properties
     */
    public function onDocFormRender(array $properties = [])
    {
        $this->modx->regClientStartupHTMLBlock('<script type="text/javascript">
            Ext.onReady(function() {
                QRNFCGenerator.config = ' . json_encode($this->config) .';
                
                Ext.applyIf(MODx.lang, ' . json_encode($this->modx->lexicon->loadCache($this->config['namespace'])).');
            });
        </script>');

        $this->modx->regClientStartupScript($this->config['assets_url'] . 'js/mgr/qrnfcgenerator.js');
    }

    public function onLoadWebDocument(array $properties = [])
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

            var_dump($this->decrypt($_GET['qrnfc_res']));
            exit;
        }

    }
}
