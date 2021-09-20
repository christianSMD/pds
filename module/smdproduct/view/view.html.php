<?php 
include '../../common/view/header.html.php';
?>
<?php $browseLink = $this->createLink('smdproduct', 'index', "");?>
<div id="mainMenu" class="clearfix">
    <div class="btn-toolbar pull-left">
        <?php if(!isonlybody()):?>
        <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-link'");?>
        <div class="divider"></div>
        <?php endif;?>
        <div class="page-title">
            <span class="label label-id"><?php echo $product->id?></span>
            <span class="text" title='<?php echo $product->name;?>'>
                <?php echo $product->name;?>
            </span>
            <?php if($product->active == 0):?>
            <span class='label label-danger'><?php echo $lang->smdproduct->deleted;?></span>
            <?php endif;?>
        </div>
    </div>
</div>
<div id="mainContent" class="main-row">
    <div class="main-col col-8">
        <div class="cell">
            <div class="detail">
                <div class="detail-title"><?php echo $lang->smdproduct->dataproduct; ?></div>
                <div class="detail-content">
                    <table class="table-form product-table">
                        <tr>
                            <th><?php echo $lang->smdproduct->metatypecat ?></th>
                            <td><?php echo $product->metaType ?></td>
                        </tr>
                        <?php if($product->metaTypeChildId > 0): ?>
                        <tr>
                            <th><?php echo $lang->smdproduct->metatypecatchild ?></th>
                            <td><?php echo $product->metaTypeChild ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($product->hasChildren == 0): // Product has no children, a single SKU and Barcode etc. ?>
                        <tr>
                            <th><?php echo $lang->smdproduct->skuproduct ?></th>
                            <td class="sku"><?php echo $product->sku ?></td>
                        </tr>
                        <tr>
                            <th><?php echo $lang->smdproduct->barcodeproduct ?></th>
                            <td><?php echo $product->barcode ?></td>
                        </tr>
                        <tr>
                            <th><?php echo $lang->smdproduct->coloursproduct ?></th>
                            <td><?php echo $this->smdproduct->getMetaValueById($product->colourId); ?></td>
                        </tr>
                        <?php else: ?>
                        <tr>
                            <th><?php echo $lang->smdproduct->skuchildproduct; ?></th>
                            <td>
                                <table>
                                    <?php foreach($product->children as $child): ?>
                                    <tr>
                                        <td class="sku"><?php echo $child->sku ?></td>
                                        <td><?php echo $this->smdproduct->getMetaValueById($child->colourId); ?></td>
                                        <td><?php echo $child->barcode ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </table>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <th><?php echo $lang->smdproduct->brandproduct ?></th>
                            <td><?php echo $product->brand ?></td>
                        </tr>
                        <tr class="fab">
                            <th><?php echo $lang->smdproduct->descproduct ?></th>
                            <td><?php echo !empty($product->description) ? nl2br(html_entity_decode($product->description)) : "<div class='text-center text-muted'>" . $lang->noData . '</div>'; ?></td>
                        </tr>
                        <tr class="fab">
                            <th><?php echo $lang->smdproduct->normalfabs ?></th>
                            <td><?php echo !empty($product->normalFabs) ? nl2br(html_entity_decode($product->normalFabs)) : "<div class='text-center text-muted'>" . $lang->noData . '</div>'; ?></td>
                        </tr>
                        <tr class="fab">
                            <th><?php echo $lang->smdproduct->shoutout ?></th>
                            <td><?php echo !empty($product->shoutoutFabs) ? nl2br(html_entity_decode($product->shoutoutFabs)) : "<div class='text-center text-muted'>" . $lang->noData . '</div>'; ?></td>
                        </tr>
                        <tr class="fab">
                            <th><?php echo $lang->smdproduct->extended ?></th>
                            <td><?php echo !empty($product->extendedFabs) ? nl2br(html_entity_decode($product->extendedFabs)) : "<div class='text-center text-muted'>" . $lang->noData . '</div>'; ?></td>
                        </tr>
                        <tr class="fab">
                            <th><?php echo $lang->smdproduct->boxcontents ?></th>
                            <td><?php echo !empty($product->boxContents) ? nl2br(html_entity_decode($product->boxContents)) : "<div class='text-center text-muted'>" . $lang->noData . '</div>'; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="detail">
                    <div class="detail-title"><?php echo $lang->smdproduct->specsproduct ?></div>
                    <div class="detail-content">
                        <table class="table-form">
                            <tr>
                                <th><?php echo $lang->smdproduct->rrp ?>:</th>
                                <td>
                                    <?php $rrp = $this->smdproduct->getProductMetaFieldValue($this->smdproduct->getProductMetaFieldValueId(RRPID, $product->id)); ?>
                                    <?php if($rrp): ?>
                                    R <?php echo $rrp; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                             <?php // Product Dimensions
                            $pL = $this->smdproduct->getProductMetaFieldValue($this->smdproduct->getProductMetaFieldValueId(PRODUCTLENGTHID, $product->id));
                            $pW = $this->smdproduct->getProductMetaFieldValue($this->smdproduct->getProductMetaFieldValueId(PRODUCTWIDTHID, $product->id));
                            $pH = $this->smdproduct->getProductMetaFieldValue($this->smdproduct->getProductMetaFieldValueId(PRODUCTHEIGHTID, $product->id));
                            ?>
                            <tr>
                                <th>Product Dimensions:</th>
                                <td>
                                    <?php if($pL): ?>
                                    <table>
                                        <tr>
                                            <td><?php echo $pL; ?></td><td>x</td>
                                            <td><?php echo $pW; ?></td><td>x</td>
                                            <td><?php echo $pH; ?></td>
                                            <td>mm</td>
                                        </tr>
                                    </table>
                                        <?php $convert = $this->smdproduct->needsConvert(PRODUCTLENGTHID);
                                        if($convert): ?>
                                        <table>
                                            <tr>
                                                <td><?php echo $this->smdproduct->convertMetaValue($convert, PRODUCTLENGTHID, $product->id, 2, false); ?></td><td>x</td>
                                                <td><?php echo $this->smdproduct->convertMetaValue($convert, PRODUCTWIDTHID, $product->id, 2, false); ?></td><td>x</td>
                                                <td><?php echo $this->smdproduct->convertMetaValue($convert, PRODUCTHEIGHTID, $product->id, 2, true); ?></td>
                                                <td></td>
                                            </tr>
                                        </table>

                                        <?php    
                                    endif; endif; ?>
                                </td>
                            </tr>
                            <?php // Product Weight
                            $pWeight = $this->smdproduct->getProductMetaFieldValue($this->smdproduct->getProductMetaFieldValueId(PRODUCTWEIGHTID, $product->id)); ?>
                            <tr>
                                <th>Product Weight:</th>
                                <td>
                                    <?php if($pWeight): ?>
                                    <table><tr><td><?php echo $pWeight;?></td><td>kg</td></tr></table>
                                    <?php endif; ?>
                                </td>
                                <?php 
                                $convert = $this->smdproduct->needsConvert(PRODUCTWEIGHTID);
                                
                                if($convert): ?>
                                <td>
                                    <?php echo $this->smdproduct->convertMetaValue($convert, PRODUCTWEIGHTID, $product->id); ?>
                                </td>  
                                <?php endif; ?>
                            </tr>
                            <?php // Get packaging Type
                            $packaging = $this->smdproduct->getMetaValueById($this->smdproduct->getProductMetaFieldValueId(PACKAGINGTYPEID, $product->id)); ?>
                            <tr>
                                <th>Packaging:</th>
                                <td><?php if($packaging): 
                                        echo $packaging; 
                                          endif; ?>
                                </td>
                            </tr>
                            <?php // Get Packaging Dimensions
                            $pL = $this->smdproduct->getProductMetaFieldValue($this->smdproduct->getProductMetaFieldValueId(PACKAGINGLENGTHID, $product->id));
                            $pW = $this->smdproduct->getProductMetaFieldValue($this->smdproduct->getProductMetaFieldValueId(PACKAGINGWIDTHID, $product->id));
                            $pH = $this->smdproduct->getProductMetaFieldValue($this->smdproduct->getProductMetaFieldValueId(PACKAGINGHEIGHTID, $product->id));
                            ?>
                            <tr>
                                <th>Packaging Dimensions:</th>
                                <td>       
                                    <?php if($pL): ?>
                                    <table>
                                        <tr>
                                            <td><?php echo $pL ?></td><td>x</td>
                                            <td><?php echo $pW ?></td><td>x</td>
                                            <td><?php echo $pH ?></td>
                                            <td>mm</td>
                                        </tr>
                                    </table>
                                    <?php $convert = $this->smdproduct->needsConvert(PACKAGINGLENGTHID);
                                        if($convert): ?>
                                        <table>
                                            <tr>
                                                <td><?php echo $this->smdproduct->convertMetaValue($convert, PACKAGINGLENGTHID, $product->id, 2, false); ?></td><td>x</td>
                                                <td><?php echo $this->smdproduct->convertMetaValue($convert, PACKAGINGWIDTHID, $product->id, 2, false); ?></td><td>x</td>
                                                <td><?php echo $this->smdproduct->convertMetaValue($convert, PACKAGINGHEIGHTID, $product->id, 2, true); ?></td>
                                                <td></td>
                                            </tr>
                                        </table>
                                        <?php endif;
                                        endif; ?>
                                </td>
                            </tr>
                            <?php // Product Weight
                            $packWeight = $this->smdproduct->getProductMetaFieldValue($this->smdproduct->getProductMetaFieldValueId(PACKAGEWEIGHTID, $product->id)); ?>
                            <tr>
                                <th>Product Gross Weight (Product in Packaging):</th>
                                <td>
                                    <?php if($packWeight): ?>
                                    <table><tr><td><?php echo $packWeight;?></td><td>kg</td></tr></table>
                                    <?php endif; ?>
                                </td>
                                <?php 
                                $convert = $this->smdproduct->needsConvert(PACKAGEWEIGHTID);
                                
                                if($convert): ?>
                                <td>
                                    <?php echo $this->smdproduct->convertMetaValue($convert, PACKAGEWEIGHTID, $product->id); ?>
                                </td>  
                                <?php endif; ?>
                            </tr>
                            <?php // Get value of Units per carton
                            $units = $this->smdproduct->getProductMetaFieldValue($this->smdproduct->getProductMetaFieldValueId(UNITSPERCARTONID, $product->id));
                            ?>
                            <tr>
                                <th>Units per Carton:</th>
                                <td>
                                    <?php if($units): ?>
                                    <?php echo $units;?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            
                            <?php // Get carton dimensions
                            $cL = $this->smdproduct->getProductMetaFieldValue($this->smdproduct->getProductMetaFieldValueId(CARTONLENGTHID, $product->id));
                            $cW = $this->smdproduct->getProductMetaFieldValue($this->smdproduct->getProductMetaFieldValueId(CARTONWIDTHID, $product->id));
                            $cH = $this->smdproduct->getProductMetaFieldValue($this->smdproduct->getProductMetaFieldValueId(CARTONHEIGHTID, $product->id));                            
                            
                            ?>
                            <tr>
                                <th>Carton Dimensions:</th>
                                <td>
                                    <?php if($cL): ?>
                                    <table>
                                        <tr>
                                            <td><?php echo $cL ?></td><td>x</td>
                                            <td><?php echo $cW ?></td><td>x</td>
                                            <td><?php echo $cH ?></td>
                                            <td>mm</td>
                                        </tr>
                                    </table>
                                     <?php $convert = $this->smdproduct->needsConvert(CARTONLENGTHID);
                                        if($convert): ?>
                                        <table>
                                            <tr>
                                                <td><?php echo $this->smdproduct->convertMetaValue($convert, CARTONLENGTHID, $product->id, 2, false); ?></td><td>x</td>
                                                <td><?php echo $this->smdproduct->convertMetaValue($convert, CARTONWIDTHID, $product->id, 2, false); ?></td><td>x</td>
                                                <td><?php echo $this->smdproduct->convertMetaValue($convert, CARTONHEIGHTID, $product->id, 2, true); ?></td>
                                                <td></td>
                                            </tr>
                                        </table>
                                        <?php endif;
                                        
                                    endif; ?>
                                </td>
                            </tr>
                            
                            <?php // Get Carton weight
                            $cWeight = $this->smdproduct->getProductMetaFieldValue($this->smdproduct->getProductMetaFieldValueId(CARTONGROSSWEIGHTID, $product->id)); ?>
                            <tr>
                                <th>Carton Gross Weight:</th>
                                <td>
                                    <?php if($cWeight): ?>
                                    <table>
                                        <tr>
                                            <td><?php echo $cWeight;?></td><td>kg</td>
                                        </tr>
                                    </table> 
                                    <?php endif; ?>
                                </td>
                                <?php 
                                $convert = $this->smdproduct->needsConvert(CARTONGROSSWEIGHTID);
                                if($convert): ?>
                                <td>
                                    <?php echo $this->smdproduct->convertMetaValue($convert, CARTONGROSSWEIGHTID, $product->id); ?>
                                </td>  
                                <?php endif; ?>
                            </tr>
                            <?php // Get Carton weight
                            $cWeight = $this->smdproduct->getProductMetaFieldValue($this->smdproduct->getProductMetaFieldValueId(CARTONNETWEIGHTID, $product->id)); ?>
                            <tr>
                                <th>Carton Net Weight:</th>
                                <td>
                                    <?php if($cWeight): ?>
                                    <table>
                                        <tr>
                                            <td><?php echo $cWeight;?></td><td>kg</td>
                                        </tr>
                                    </table> 
                                    <?php endif; ?>
                                </td>
                                <?php 
                                $convert = $this->smdproduct->needsConvert(CARTONNETWEIGHTID);
                                if($convert): ?>
                                <td>
                                    <?php echo $this->smdproduct->convertMetaValue($convert, CARTONNETWEIGHTID, $product->id); ?>
                                </td>  
                                <?php endif; ?>
                            </tr>
                        </table>
                    </div>
                </div>
            <div class="detail">
                <div class="detail-title"><?php echo $lang->smdproduct->techspecs ?></div>
                <div class="detail-content">
                    <table class="table-form">
                        <?php 
                        foreach($metaTypeFields as $k => $m): if($m == '') continue; ?>
                            <tr>
                                <th class="w-200px text-left"><?php echo $m ?>:</th>
                                <td><?php echo $this->smdproduct->getMetaValueById($this->smdproduct->getProductMetaFieldValueId($k, $product->id)) ?></td>
                                <?php 
                                $convert = $this->smdproduct->needsConvert($k);
                                
                                if($convert): ?>
                                <td>
                                    <?php echo $this->smdproduct->convertMetaValue($convert, $k, $product->id); ?>
                                </td>  
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; ?>
                    </table>
                </div>
            </div>
            <?php include '../../common/view/action.html.php';?>
            <div class='actions'> <?php if($product->active == 1) echo $actionLinks;?></div>
        </div>
    </div>
    <div class="side-col col-4">
        <div class="cell">
            <details class="detail" open>
                <summary class="detail-title"><?php echo $lang->smdproduct->imagesproduct ?></summary>
                <div class="detail-content">
                    <input type="hidden" id="imageContent" value='<?php echo $images ?>' />
                    <div id="imageContainer">
                        
                    </div>
                </div>
            </details>
        </div>
        <div class="cell">
            <details class="detail" open>
                <summary class="detail-title"><?php echo $lang->smdproduct->infoproduct ?></summary>
                <div class="detail-content">
                    <table class="table table-data">
                        <tr>
                            <th>Creator</th>
                            <td><?php echo $this->loadModel('user')->getById($product->creatorId, 'id')->realname; ?></td>
                        </tr>
                        <tr>
                            <th>Created</th>
                            <td><?php echo $product->created ?></td>
                        </tr>
                        <tr>
                            <th>Last Modified By</th>
                            <td><?php echo $this->loadModel('user')->getById($product->modifierId, 'id')->realname; ?></td>
                        </tr>
                        <tr>
                            <th>Last Modified</th>
                            <td><?php echo $product->modified ?></td>
                        </tr>
                        <tr>
                            <td colspan="2">                                
                                <?php 
                                if(common::hasPriv('smdproduct', 'sendtoteam')):
                                    common::printLink('smdproduct', 'sendtoteam', "productID=$product->id", "" . $lang->smdproduct->send, '', "class='btn btn-primary iframe'", true, true);
                                endif; 
                                if($product->sent == 1): ?>
                                Sent to Team
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </details>
        </div>
    </div>
</div>
<div id="mainActions">
    <?php common::printPreAndNext($preAndNext); ?>
    <div class="btn-toolbar">
        <?php
        common::printBack($browseLink);
        $params = array('productID' => $product->id);
        common::printIcon('smdproduct', 'edit', $params, $product);
        
        common::printIcon('smdproduct', 'download', $params, $product, 'button', 'arrow-down', '', '', false, '');
        // printIcon($module, $method, $vars = '', $object = '', $type = 'button', $icon = '', $target = '', $extraClass = '', $onlyBody = false, $misc = '', $title = '')
        ?>
    </div>
</div>

<?php include '../../common/view/footer.html.php';?>