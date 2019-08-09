<?php

/**
 * QRNFCGenerator
 *
 * Copyright 2019 by Sterc <modx@sterc.nl>
 */
class QRNFCGeneratorQRGenerateProcessor extends modProcessor
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
        $url = $this->modx->qrnfcgenerator->generateUrl($this->getProperty('resource'), 'qr');

        if (!$url || !$modResource = $this->modx->getObject('modResource', (int) $this->getProperty('resource'))) {
            return $this->failure();
        }


        $qrCode = $this->modx->qrnfcgenerator->generateQRCode($url);


        /* Download the QR code. */
        header('Content-Description: File Transfer');
        header('Content-Type: ' . $qrCode->getContentType());
        header('Content-Disposition: attachment; filename="' . $this->formatFilename($modResource) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($qrCode->getSize()));

        echo $qrCode->writeString();
        exit;
    }

    /**
     * Returns a formatted filename based on the resource pagetitle.
     *
     * @param modResource $resource
     * @return string
     */
    protected function formatFilename(modResource $resource)
    {
        $name = $resource->get('pagetitle');
        $name = strtolower($name);
        $name = str_replace(' ', '_', $name);

        return $name . '.png';
    }
}

return 'QRNFCGeneratorQRGenerateProcessor';
