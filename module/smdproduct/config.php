<?php

$config->smdproduct = new stdClass();

$config->smdproduct->editor->editmetafield  = array('id' => 'desc', 'tools' => 'simpleTools');
$config->smdproduct->editor->editmetatype   = array('id' => 'desc', 'tools' => 'simpleTools');
$config->smdproduct->editor->edit           = array('id' => 'description,normalFabs,shoutoutFabs,extendedFabs,boxContents', 'tools' => 'simpleTools');

define('TABLE_PRODUCTS',            '`c_' . $config->db->prefix . 'product`');
define('TABLE_META_FIELDS',         '`c_' . $config->db->prefix . 'meta_field`');
define('TABLE_META_TYPE_FIELDS',    '`c_' . $config->db->prefix . 'meta_type_field`');
define('TABLE_META_TYPES',          '`c_' . $config->db->prefix . 'meta_type`');
define('TABLE_META_FIELD_VALUES',   '`c_' . $config->db->prefix . 'meta_field_value`');
define('TABLE_PRODUCTS_META',       '`c_' . $config->db->prefix . 'product_meta`');
define('TABLE_BRANDS',              '`c_' . $config->db->prefix . 'brand`');

// Default Field IDs
define('PRODUCTLENGTHID',   10);
define('PRODUCTWIDTHID',    12);
define('PRODUCTHEIGHTID',   13);
define('PRODUCTWEIGHTID',   14);
define('PACKAGINGTYPEID',   15);
define('PACKAGINGLENGTHID', 16);
define('PACKAGINGWIDTHID',  17);
define('PACKAGINGHEIGHTID', 18);
define('UNITSPERCARTONID',  19);
define('CARTONLENGTHID',    20);
define('CARTONWIDTHID',     21);
define('CARTONHEIGHTID',    22);
define('CARTONGROSSWEIGHTID', 23);
define('CARTONNETWEIGHTID', 24);
define('COLOURID', 25);
define('PACKAGEWEIGHTID',   57);
define('RRPID', 210);

define('NEWPRODUCTGROUPID', 19);

// Allowed HTML tags
$config->smdproduct->cleanFields                         = array('description', 'normalFabs', 'shoutoutFabs', 'extendedFabs');  
$config->smdproduct->myAllowedTags                       = "<img><a><b><u><i><ul><ol><li>";