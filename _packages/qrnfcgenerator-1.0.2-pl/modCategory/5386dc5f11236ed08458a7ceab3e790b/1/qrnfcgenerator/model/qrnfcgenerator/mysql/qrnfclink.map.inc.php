<?php
/**
 * @package qrnfcgenerator
 */
$xpdo_meta_map['QRNFCLink']= array (
  'package' => 'qrnfcgenerator',
  'version' => NULL,
  'table' => 'qrnfc_link',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'alias' => '',
    'url' => '',
    'createdon' => 'CURRENT_TIMESTAMP',
  ),
  'fieldMeta' => 
  array (
    'alias' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'url' => 
    array (
      'dbtype' => 'text',
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
