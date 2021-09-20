<?php

$config->brief = new stdClass();

$config->brief->defaultWorkDays         = '5.0';
$config->brief->defaultWorkhours        = '6.0';
$config->brief->maxBurnDay              = '31';
$config->brief->weekend                 = '2';

global $lang, $app;

$config->brief->create  = new stdClass();
$config->brief->edit    = new stdClass();

$config->brief->create->requiredFields  = 'name,type,deadline';

$config->brief->editor = new stdclass();
$config->brief->editor->create   = array('id' => 'desc,functions,lookAndFeel',    'tools' => 'simpleTools');
$config->brief->editor->edit     = array('id' => 'desc,functions,lookAndFeel',    'tools' => 'simpleTools');
$config->brief->editor->accept   = array('id' => 'comment', 'tools' => 'simpleTools');
$config->brief->editor->decline  = array('id' => 'comment', 'tools' => 'simpleTools');
$config->brief->editor->revert   = array('id' => 'comment', 'tools' => 'simpleTools');
$config->brief->editor->archive  = array('id' => 'comment', 'tools' => 'simpleTools');

define('TABLE_BRIEFS',          '`c_' . $config->db->prefix . 'brief`');

// IDS of relevant departments
define('PRODDEV_ID', 15);
define('TRAFFIC_ID', 14);

$config->brief->typefields['packagingNew']          = array('name', 'onBehalfOf', 'deadline', 'timeAllocation', 'dateOrdered', 'type', 'desc', 'products');
$config->brief->typefields['packagingUpdate']       = array('name', 'onBehalfOf', 'deadline', 'timeAllocation', 'dateOrdered', 'type', 'desc', 'products');
$config->brief->typefields['packagingRedesign']     = array('name', 'onBehalfOf', 'deadline', 'timeAllocation', 'dateOrdered', 'type', 'desc', 'products', 'links');
$config->brief->typefields['packagingConcept']      = array('name', 'onBehalfOf', 'deadline', 'timeAllocation', 'type', 'desc', 'links', 'lsm', 'range', 'packaging');
$config->brief->typefields['productDevelopment']    = array('name', 'onBehalfOf', 'deadline', 'timeAllocation', 'type', 'desc', 'brand', 'collected');
$config->brief->typefields['productPhotography']    = array('name', 'onBehalfOf', 'deadline', 'timeAllocation', 'type', 'desc', 'angles', 'resolution');
$config->brief->typefields['promotionalGraphics']   = array('name', 'onBehalfOf', 'deadline', 'timeAllocation', 'type', 'desc', 'dimensions', 'paperSize', 'measurements', 'orientation', 'finishing', 'printing', 'printQty');
$config->brief->typefields['corporate']             = array('name', 'onBehalfOf', 'deadline', 'timeAllocation', 'type', 'desc', 'printing', 'printQty');
$config->brief->typefields['createNewProduct']      = array('name', 'onBehalfOf', 'deadline', 'timeAllocation', 'type', 'desc', 'functions', 'lookAndFeel', 'products', 'links', 'brand', 'xcaCode', 'techSpec', 'funcSpec', 'size', 'additionalSpec', 'client', 'targetPrice', 'quantity', 'deliveryDate');
$config->brief->typefields['modifyProduct']         = array('name', 'onBehalfOf', 'deadline', 'timeAllocation', 'dateOrdered', 'type', 'desc', 'products', 'brand', 'techSpec', 'funcSpec', 'size', 'additionalSpec', 'client', 'targetPrice', 'quantity', 'deliveryDate');
$config->brief->typefields['newConcept']            = array('name', 'onBehalfOf', 'deadline', 'timeAllocation', 'dateOrdered', 'type', 'desc', 'techSpec', 'funcSpec', 'size', 'additionalSpec', 'client', 'targetPrice', 'quantity', 'deliveryDate');
$config->brief->typeFields['digitalDesign']         = array('name', 'onBehalfOf', 'deadline', 'timeAllocation', 'type', 'desc', 'dimensions', 'orientation');
$config->brief->typeFields['catalogue']		        = array('name', 'onBehalfOf', 'deadline', 'timeAllocation', 'type', 'desc');
$config->brief->typeFields['advert']		        = array('name', 'onBehalfOf', 'deadline', 'timeAllocation', 'type', 'desc', 'dimensions', 'orientation');
$config->brief->typeFields['copySeo']               = array('name', 'onBehalfOf', 'deadline', 'timeAllocation', 'type', 'desc');
$config->brief->typeFields['copyScript']            = array('name', 'onBehalfOf', 'deadline', 'timeAllocation', 'type', 'desc');
$config->brief->typeFields['copyContent']           = array('name', 'onBehalfOf', 'deadline', 'timeAllocation', 'type', 'desc');
$config->brief->typeFields['copyBlog']              = array('name', 'onBehalfOf', 'deadline', 'timeAllocation', 'type', 'desc');
$config->brief->typeFields['copyAdvertorial']       = array('name', 'onBehalfOf', 'deadline', 'timeAllocation', 'type', 'desc');

// Allowed HTML tags
$config->brief->cleanFields                         = array('desc', 'functions', 'techSpec', 'funcSpec', 'additionalSpec', 'lookAndFeel');  
$config->brief->myAllowedTags                       = "<img><a><b><u><br><i><ul><ol><li><strong><p><em>";