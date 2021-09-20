<?php 
echo $product->name;
echo "\r\n";
if($product->hasChildren == 0):
echo "$product->sku\t{$this->smdproduct->getMetaValueById($child->colourId)}";
else:
    foreach($product->children as $child):
        echo "$child->sku\t{$this->smdproduct->getMetaValueById($child->colourId)}\t$child->barcode\r\n";
    endforeach;
endif;
echo "\r\n";
echo $product->barcode;
echo "\r\n";
echo $category;
echo "\r\n";
echo strip_tags($product->description);
echo "\r\n\r\n";
echo "Normal FABs:\r\n";
echo strip_tags($product->normalFabs);
echo "\r\n\r\n";
echo "Shoutout FABs:\r\n";
echo strip_tags($product->shoutoutFabs);
echo "\r\n\r\n";
echo "Extended FABs:\r\n";
echo strip_tags($product->extendedFabs);
echo "\r\n\r\n";
echo "Specs:\r\n";
foreach($metaTypeFields as $k => $m): if($m == '') continue;
    echo "$m:\t" . html_entity_decode($this->smdproduct->getMetaValueById($this->smdproduct->getProductMetaFieldValueId($k, $product->id))) . "\r\n";                                
endforeach;
echo "\r\n\r\n";
echo 'Last Modified: ' . $product->modified;