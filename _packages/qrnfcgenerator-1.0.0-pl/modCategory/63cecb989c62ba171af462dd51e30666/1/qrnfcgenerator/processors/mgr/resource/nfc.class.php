<?php

/**
 * QRNFCGenerator
 *
 * Copyright 2019 by Sterc <modx@sterc.nl>
 */

class QRNFCGeneratorNFCUrlGenerateProcessor extends modProcessor
{
    /**
     * @access public.
     * @var array.
     */
    public $languageTopics = ['qrnfcgenerator:default'];

    /**
     * @access public.
     * @return Mixed.
     */
    public function initialize()
    {
        $this->modx->getService(
            'qrnfcgenerator',
            'QRNFCGenerator',
            $this->modx->getOption(
                'qrnfcgenerator.core_path',
                null,
                $this->modx->getOption('core_path') . 'components/qrnfcgenerator/'
            ) . 'model/qrnfcgenerator/'
        );

        return parent::initialize();
    }

    /**
     * @access public.
     * @return Mixed.
     */
    public function process()
    {
        $url = $this->modx->qrnfcgenerator->generateUrl($this->getProperty('resource'), 'nfc');

        if (!$url) {
            return $this->failure();
        }

        return $this->success('', [
            'url' => $url
        ]);
    }
}

return 'QRNFCGeneratorNFCUrlGenerateProcessor';
