<?php
/**
 * @package qrnfcgenerator
 */
$xpdo_meta_map['QRNFCVisit']= array (
  'package' => 'qrnfcgenerator',
  'version' => NULL,
  'table' => 'qrnfc_visit',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'resource' => 0,
    'type' => '',
    'createdon' => 'CURRENT_TIMESTAMP',
  ),
  'fieldMeta' => 
  array (
    'resource' => 
    array (
      'dbtype' => 'integer',
      'precision' => '10',
      'phptype' => 'int',
      'null' => false,
      'default' => 0,
    ),
    'type' => 
    array (
      'dbtype' => 'text',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'createdon' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 'CURRENT_TIMESTAMP',
    ),
  ),
);
