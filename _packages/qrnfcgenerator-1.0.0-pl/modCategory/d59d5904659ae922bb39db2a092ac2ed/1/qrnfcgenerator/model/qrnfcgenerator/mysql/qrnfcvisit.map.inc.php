<?php
/**
 * @package seopro
 */
$xpdo_meta_map['QRNFCVisit'] = [
    'package' => 'qrnfcgenerator',
    'version' => NULL,
    'table' => 'qrnfc_visit',
    'extends' => 'xPDOSimpleObject',
    'tableMeta' => [
        'engine' => 'InnoDB',
    ],
    'fields' => [
        'resource'  => 0,
        'type'      => '',
        'createdon' => 'CURRENT_TIMESTAMP',
    ],
    'fieldMeta' => [
        'resource' => [
            'dbtype'    => 'integer',
            'precision' => '10',
            'phptype'   => 'int',
            'null'      => false,
            'default'   => 0,
        ],
        'type' => [
            'dbtype'    => 'text',
            'precision' => '100',
            'phptype'   => 'string',
            'null'      => true,
            'default'   => '',
        ],
        'createdon' => [
            'dbtype'  => 'timestamp',
            'phptype' => 'timestamp',
            'null'    => false,
            'default' => 'CURRENT_TIMESTAMP',
        ],
    ]
];
