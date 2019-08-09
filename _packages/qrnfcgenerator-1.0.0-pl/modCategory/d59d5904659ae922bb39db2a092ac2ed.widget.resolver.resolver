<?php
/**
 * QRNFCGenerator widget resolver
 *
 * @package QRNFCGenerator
 * @subpackage build
 */

/** @var $widgets */
$widgets = [
    [
        'name'        => 'QR NFC Visits',
        'description' => 'Displays visit statistics for QR/NFC campaigns.',
        'type'        => 'file',
        'content'     => '[[++core_path]]components/qrnfcgenerator/elements/widgets/visits.widget.php',
        'namespace'   => 'qrnfcgenerator',
        'lexicon'     => 'qrnfcgenerator:default',
        'size'        => 'half'
    ]
];

$success = false;
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        foreach ($widgets as $widget) {
            $widgetObject = $object->xpdo->getObject('modDashboardWidget', ['name' => $widget['name']]);
            if (!$widgetObject) {
                $widgetObject = $object->xpdo->newObject('modDashboardWidget');
                $widgetObject->fromArray($widget);
                $widgetObject->save();
                $object->xpdo->log(modX::LOG_LEVEL_INFO, 'Installed widget: ' . $widget['name']);
            }
        }

        $success = true;
        break;
    case xPDOTransport::ACTION_UNINSTALL:

        $success = true;
        break;
}

return $success;