<?php

require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;

/**
 * Class QRNFCGenerator.
 */
class QRNFCGenerator
{
    const ENCRYPT_METHOD = 'AES-256-CBC';

    /* Holds the encryption key. */
    protected $encryptKey;

    /* Holds the encrypt IV. */
    protected $encryptIV;

    /**
     * The modX object.
     *
     * @since    1.0.0
     * @access   public
     * @var      modX      The modX object.
     */
    public $modx;

    /**
     * The namespace for this package.
     *
     * @since    1.0.0
     * @access   public
     * @var      string         The package namespace.
     */
    public $namespace = 'qrnfcgenerator';

    /**
     * Holds all configs values.
     *
     * @since    1.0.0
     * @access   public
     * @var      array          Config value holder.
     */
    public $config = [];

    /**
     * Initialize the class.
     *
     * @since    1.0.0
     * @param    modX    $modx    The modX object.
     * @param    array   $config  Array with config values.
     */
    public function __construct(modX $modx, array $config = [])
    {
        $this->modx      =& $modx;
        $this->namespace = $this->modx->getOption('namespace', $config, 'qrnfcgenerator');

        $corePath = $this->modx->getOption(
            'qrnfcgenerator.core_path',
            $config,
            $this->modx->getOption('core_path')   . 'components/qrnfcgenerator/'
        );

        $assetsUrl = $this->modx->getOption(
            'qrnfcgenerator.assets_url',
            $config,
            $this->modx->getOption('assets_url')  . 'components/qrnfcgenerator/'
        );

        $assetsPath = $this->modx->getOption(
            'qrnfcgenerator.assets_path',
            $config,
            $this->modx->getOption('assets_path') . 'components/qrnfcgenerator/'
        );

        $this->config = array_merge([
            'namespace'       => $this->namespace,
            'core_path'       => $corePath,
            'model_path'      => $corePath . 'model/',
            'chunks_path'     => $corePath . 'elements/chunks/',
            'snippets_path'   => $corePath . 'elements/snippets/',
            'templates_path'  => $corePath . 'templates/',
            'processors_path' => $corePath . 'processors/',
            'assets_path'     => $assetsPath,
            'assets_url'      => $assetsUrl,
            'lexicons'        => ['qrnfcgenerator:default'],
            'js_url'          => $assetsUrl . 'js/',
            'css_url'         => $assetsUrl . 'css/',
            'connector_url'   => $assetsUrl . 'connector.php'
        ], $config);

        $this->modx->lexicon->load('qrnfcgenerator:default');

        $this->modx->addPackage('qrnfcgenerator', $this->config['model_path']);

    }

    /**
     * Generate the QR Code/NFC url based on the provided resource id and source type.
     *
     * @param int $resourceId
     * @param string $source
     * @return bool|string
     */
    public function generateUrl($resourceId = 0, $source = 'qr')
    {
        if (!$resourceId === 0) {
            return false;
        }

        if ($this->modx->getOption('qrnfcgenerator.use_utm')) {
            $url = $this->modx->makeUrl($resourceId, '', [
                'utm_source'   => $source,
                'utm_medium'   => $source,
                'utm_campaign' => $this->modx->getOption('qrnfcgenerator.utm_campaign', null, 'website'),
                'qrnfc_res'    => $this->encrypt($resourceId)
            ],
            'full',
            [
                'xhtml_urls' => false
            ]);
        } else {
            $url = $this->modx->makeUrl($resourceId, '', [], 'full', ['xhtml_urls' => false]);
        }
        
        if ($this->modx->getOption('qrnfcgenerator.use_shorter')) {
            if (!$link = $this->modx->getObject('QRNFCLink', ['url' => $url])) {
                if ($resource = $this->modx->getObject('modResource', $resourceId)) {
                    $alias = $resource->get('alias');
                } else {
                    $alias = uniqid();
                }
                if ($link = $this->modx->getObject('QRNFCLink', ['alias' => $alias])) {
                    $count = $this->modx->getCount('QRNFCLink', ['alias:LIKE' => "{$alias}-%"]) + 2;
                    $alias = "{$alias}-{$count}";
                }
                $link = $this->modx->newObject('QRNFCLink');
                $link->fromArray([
                    'alias' => $alias,
                    'url'   => $url
                ]);
                $link->save();
            }
            if ($link) {
                return rtrim($this->modx->getOption('site_url'), '/') . '/go/' . $link->alias;
            }
        }
        return $url;
    }

    /**
     * Generate QrCode object.
     *
     * @param $url
     * @return QrCode
     */
    public function generateQRCode($url)
    {
        $qrCode = new QrCode($url);
        $qrCode->setSize(300);

        /* Set advanced options. */
        $qrCode->setWriterByName('png');
        $qrCode->setMargin(10);
        $qrCode->setEncoding('UTF-8');
        $qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevel(ErrorCorrectionLevel::HIGH));
        $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
        $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
        $qrCode->setLogoSize(150, 200);
        $qrCode->setRoundBlockSize(true);
        $qrCode->setValidateResult(false);
        $qrCode->setWriterOptions(['exclude_xml_declaration' => true]);

        return $qrCode;
    }

    /**
     * Return encryption key.
     *
     * @return mixed
     */
    protected function getEncryptionKey()
    {
        if (!$this->encryptKey) {
            $this->setEncryptionKey();
        }

        return $this->encryptKey;
    }

    /**
     * Set the encryption key.
     */
    protected function setEncryptionKey()
    {
        $this->encryptKey = $this->modx->getOption($this->namespace . '.encryption.encrypt_key');
    }

    /**
     * Return encryption IV.
     *
     * @return mixed
     */
    protected function getEncryptionIV()
    {
        if (!$this->encryptIV) {
            $this->setEncryptionIV();
        }

        return $this->encryptIV;
    }

    /**
     * Set the encryption IV.
     */
    protected function setEncryptionIV()
    {
        $this->encryptIV = $this->modx->getOption($this->namespace . '.encryption.encrypt_iv');
    }

    /**
     * Encrypt string.
     *
     * @param $string
     * @return string
     */
    public function encrypt($string)
    {
        $key       = hash('sha256', $this->getEncryptionKey());
        $encryptIv = substr(hash('sha256', $this->getEncryptionIV()), 0, 16);
        $output    = base64_encode(openssl_encrypt($string, self::ENCRYPT_METHOD, $key, 0, $encryptIv));

        return $output;
    }

    /**
     * Decrypt string.
     *
     * @param $string
     * @return string
     */
    public function decrypt($string)
    {
        $key       = hash('sha256', $this->getEncryptionKey());
        $encryptIv = substr(hash('sha256', $this->getEncryptionIV()), 0, 16);
        $output    = openssl_decrypt(base64_decode($string), self::ENCRYPT_METHOD, $key, 0, $encryptIv);

        return $output;
    }
}