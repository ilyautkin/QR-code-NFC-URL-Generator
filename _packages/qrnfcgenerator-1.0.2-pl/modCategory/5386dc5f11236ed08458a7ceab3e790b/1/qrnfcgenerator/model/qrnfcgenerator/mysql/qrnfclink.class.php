<?php
/**
 * @package qrnfcgenerator
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/qrnfclink.class.php');
class QRNFCLink_mysql extends QRNFCLink {}
?>