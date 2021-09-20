<?php
include '../../common/view/header.html.php';
include '../../common/view/kindeditor.html.php';
?>
<?php $browseLink = $this->createLink('smdproduct', 'view', "productID=$product->id"); ?>
<div id="mainMenu" class="clearfix">
    <div class="btn-toolbar pull-left">
        <?php if (!isonlybody()) : ?>
            <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-link'"); ?>
            <div class="divider"></div>
        <?php endif; ?>
        <div class="page-title">
            <span class="label label-id"><?php echo $product->id ?></span>
            <span class="text" title='<?php echo $product->name; ?>'>
                <?php echo $lang->smdproduct->edit; ?>
            </span>
            <?php if ($product->active == 0) : ?>
                <span class='label label-danger'><?php echo $lang->smdproduct->deleted; ?></span>
            <?php endif; ?>
        </div>
    </div>
</div>
<div id="mainContent" class="main-row">
    <div class="main-col col-8">
        <div class="cell">
            <form class='load-indicator main-form form-ajax' method='post' target='hiddenwin' id='dataform'>
                <?php echo html::hidden('hasChildren', $product->hasChildren); ?>
                <div class="detail">
                    <div class="detail-title"><?php echo $lang->smdproduct->dataproduct; ?>
                        
                        <?php if (common::hasPriv('smdproduct', 'deleteproduct')) : ?>
                            <a href="<?php echo $this->createLink('smdproduct', 'deleteproduct', "productID=$product->id"); ?>" id="delete-product" data-id="<?php echo $product->id ?>" class="btn btn-sm btn-danger pull-right">Delete Product</a>  
                        <?php endif; ?>
                        <?php if(common::hasPriv('smdproduct', 'enablechildren') && $product->hasChildren == 0): ?>
                            <a href="<?php echo $this->createLink('smdproduct', 'enablechildren', "productID=$product->id"); ?>" id="enable-children-product" data-id="<?php echo $product->id ?>" class="btn btn-sm btn-primary pull-right">Enable Children</a>  
                        <?php endif; ?>
                    </div>
                    <div class="detail-content">
                        <table class="table-form">
                            <tr>
                                <th><?php echo $lang->smdproduct->nameproduct ?></th>
                                <td><?php echo html::input('name', $product->name, "class='form-control' "); ?></td>
                            </tr>
                            <tr>
                                <th><?php echo $lang->smdproduct->metatypecat ?></th>
                                <td><?php echo html::select('metaTypeId', $metaTypes, $product->metaTypeId, "class='form-control chosen'"); ?></td>
                            </tr>
                            <tr>
                                <?php if (isset($product->children)) :
                                    $product->colourId = array();
                                    foreach ($product->children as $child) :
                                        $product->colourId[] = $child->colourId;
                                    endforeach;
                                endif;
                                ?>
                                <th class="text-left"><?php echo $lang->smdproduct->coloursproduct ?></th>
                                <td><?php echo html::select('meta-5' . COLOURID . '[]', $colours, $product->colourId, "class='form-control chosen' multiple"); ?><a href="javascript:addNewValue(<?php echo COLOURID ?>);">Add New Value</a></td>
                            </tr>
                            <?php if ($product->hasChildren == 0) : ?>
                                <tr>
                                    <th><?php echo $lang->smdproduct->skuproduct ?></th>
                                    <td><?php echo html::input('sku', $product->sku, "class='form-control' "); ?></td>
                                </tr>
                                <?php if($product->hasChildren == 1): ?>
                                <tr>
                                    <td></td>
                                    <td><?php common::printIcon('smdproduct', 'addchild', "productID=$product->id", '', 'button', '', '', 'iframe', true); ?></td>
                                </tr>
                            <?php endif; ?>
                                <tr>
                                    <th><?php echo $lang->smdproduct->barcodeproduct ?></th>
                                    <td><?php echo html::input('barcode', $product->barcode, "class='form-control' "); ?></td>
                                </tr>
                            <?php else : ?>
                                <tr>
                                    <th><?php echo $lang->smdproduct->skuchildproduct ?></th>
                                    <td>
                                        <table>
                                            <tr>
                                                <th>Product Code</th>
                                                <th>Colour</th>
                                                <th>Barcode</th>
                                                <th></th>
                                            </tr>

                                            <?php foreach ($product->children as $child) : ?>
                                                <tr>
                                                    <td><?php echo html::input('child[' . $child->id . '][sku]', $child->sku, "class='form-control' placeholder='" . $product->sku . "'"); ?></td>
                                                    <td><?php echo html::input('child[' . $child->id . '][colour]', $this->smdproduct->getMetaValueById($child->colourId), "class='form-control' readonly"); ?></td>
                                                    <td><?php echo html::input('child[' . $child->id . '][barcode]', $child->barcode, "class='form-control' placeholder='" . $product->barcode . "'"); ?></td>
                                                    <?php
                                                    if (common::hasPriv('smdproduct', 'deletechild')) :
                                                        $deleteChildLink = $this->createLink('smdproduct', 'deletechild', "productID=$child->id"); ?>
                                                        <td>
                                                            <?php echo html::a($deleteChildLink, '<i class="icon icon-times icon-sm"></i> ' . $lang->smdproduct->deletechild, '', "class='delete-child btn btn-link text-danger text-red'"); ?>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php if (common::hasPriv('smdproduct', 'addchild')) : ?>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="4"><?php common::printIcon('smdproduct', 'addchild', "productID=$product->id", '', 'button', '', '', 'iframe', true); ?></td>
                                                    </tr>
                                                </tfoot>
                                            <?php endif; ?>
                                        </table>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th><?php echo $lang->smdproduct->brandproduct ?></th>
                                <td><?php echo html::select('brandId', $brands, $product->brandId, "class='form-control chosen'"); ?></td>
                            </tr>
                            <tr>
                                <th><?php echo $lang->smdproduct->descproduct ?></th>
                                <td><?php echo html::textarea('description', $product->description, "rows='6' class='form-control'"); ?></td>
                            </tr>
                            <tr>
                                <th><?php echo $lang->smdproduct->normalfabs ?></th>
                                <td><?php echo html::textarea('normalFabs', $product->normalFabs, "rows='6' class='form-control'"); ?></td>
                            </tr>
                            <tr>
                                <th><?php echo $lang->smdproduct->shoutout ?></th>
                                <td><?php echo html::textarea('shoutoutFabs', $product->shoutoutFabs, "rows='6' class='form-control'"); ?></td>
                            </tr>
                            <tr>
                                <th><?php echo $lang->smdproduct->extended ?></th>
                                <td><?php echo html::textarea('extendedFabs', $product->extendedFabs, "rows='6' class='form-control'"); ?></td>
                            </tr>
                            <tr>
                                <th><?php echo $lang->smdproduct->boxcontents ?></th>
                                <td><?php echo html::textarea('boxContents', $product->boxContents, "rows='6' class='form-control'"); ?><p class='help-block'><?php echo $lang->smdproduct->ttBoxContents ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="detail">
                    <div class="detail-title"><?php echo $lang->smdproduct->specsproduct ?></div>
                    <div class="detail-content">
                        <table class="table-form">
                            <tr>
                                <th><?php echo $lang->smdproduct->rrp ?></th>
                                <td><?php echo html::input('default-' . RRPID, $this->smdproduct->getProductMetaFieldValue($this->smdproduct->getProductMetaFieldValueId(RRPID, $product->id)), "class='form-control' placeholder='in ZAR'"); ?></td>
                            </tr>
                            <tr>
                                <th>Product Dimensions</th>
                                <td>
                                    <?php
                                    $pL = $this->smdproduct->getProductMetaFieldValue($this->smdproduct->getProductMetaFieldValueId(PRODUCTLENGTHID, $product->id));
                                    $pW = $this->smdproduct->getProductMetaFieldValue($this->smdproduct->getProductMetaFieldValueId(PRODUCTWIDTHID, $product->id));
                                    $pH = $this->smdproduct->getProductMetaFieldValue($this->smdproduct->getProductMetaFieldValueId(PRODUCTHEIGHTID, $product->id));

                                    ?>
                                    <table>
                                        <tr>
                                            <td><?php echo html::input("default-" . PRODUCTLENGTHID, $pL, "class='form-control inline' size='5' placeholder='L' "); ?></td>
                                            <td><?php echo html::input("default-" . PRODUCTWIDTHID, $pW, "class='form-control inline' size='5' placeholder='W' "); ?></td>
                                            <td><?php echo html::input("default-" . PRODUCTHEIGHTID, $pH, "class='form-control inline' size='5' placeholder='H' "); ?></td>
                                            <td>mm</td>
                                        </tr>
                                    </table>
                                    <p class="help-block">L x W x H when out of packaging</p>
                                </td>
                            </tr>
                            <tr>
                                <?php $pWeight = $this->smdproduct->getProductMetaFieldValue($this->smdproduct->getProductMetaFieldValueId(PRODUCTWEIGHTID, $product->id)); ?>
                                <th>Product Weight</th>
                                <td>
                                    <table>
                                        <tr>
                                            <td><?php echo html::input('default-' . PRODUCTWEIGHTID, $pWeight, "class='form-control form-inline' size='5' "); ?></td>
                                            <td>kg</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <?php $packaging = $this->smdproduct->getProductMetaFieldValue($this->smdproduct->getProductMetaFieldValueId(PACKAGINGTYPEID, $product->id)); ?>
                                <th>Packaging</th>
                                <td><?php
                                    echo html::select(
                                        "default-" . PACKAGINGTYPEID,
                                        $this->smdproduct->formatForSelect($this->smdproduct->getMetaFieldValues(PACKAGINGTYPEID)),
                                        $this->smdproduct->getProductMetaFieldValueId(PACKAGINGTYPEID, $product->id),
                                        "class='form-control chosen'"
                                    );
                                    ?> <a href="javascript:addNewValue(<?php echo PACKAGINGTYPEID ?>);">Add New Value</a></td>
                            </tr>
                            <tr>
                                <th>Packaging Dimensions</th>
                                <td>
                                    <?php $pL = $this->smdproduct->getProductMetaFieldValue($this->smdproduct->getProductMetaFieldValueId(PACKAGINGLENGTHID, $product->id));
                                    $pW = $this->smdproduct->getProductMetaFieldValue($this->smdproduct->getProductMetaFieldValueId(PACKAGINGWIDTHID, $product->id));
                                    $pH = $this->smdproduct->getProductMetaFieldValue($this->smdproduct->getProductMetaFieldValueId(PACKAGINGHEIGHTID, $product->id));
                                    ?>
                                    <table>
                                        <tr>
                                            <td><?php echo html::input('default-' . PACKAGINGLENGTHID, $pL, "class='form-control inline' size='5' placeholder='L' "); ?></td>
                                            <td><?php echo html::input('default-' . PACKAGINGWIDTHID, $pW, "class='form-control inline' size='5' placeholder='W' "); ?></td>
                                            <td><?php echo html::input('default-' . PACKAGINGHEIGHTID, $pH, "class='form-control inline' size='5' placeholder='H' "); ?></td>
                                            <td>mm</td>
                                        </tr>
                                    </table>
                                    <p class="help-block">L x W x H of individual product packaging</p>
                                </td>
                            </tr>
                            <tr>
                                <?php $packWeight = $this->smdproduct->getProductMetaFieldValue($this->smdproduct->getProductMetaFieldValueId(PACKAGEWEIGHTID, $product->id)); ?>
                                <th>Product Gross Weight (Product in Packaging)</th>
                                <td>
                                    <table>
                                        <tr>
                                            <td><?php echo html::input('default-' . PACKAGEWEIGHTID, $packWeight, "class='form-control form-inline' size='5' "); ?></td>
                                            <td>kg</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <?php $units = $this->smdproduct->getProductMetaFieldValue($this->smdproduct->getProductMetaFieldValueId(UNITSPERCARTONID, $product->id)); ?>
                                <th>Units per Carton</th>
                                <td>
                                    <table>
                                        <tr>
                                            <td><?php echo html::input('default-' . UNITSPERCARTONID, $units, "class='form-control form-inline' size='5' "); ?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <th>Carton Dimensions</th>
                                <td>
                                    <?php $cL = $this->smdproduct->getProductMetaFieldValue($this->smdproduct->getProductMetaFieldValueId(CARTONLENGTHID, $product->id));
                                    $cW = $this->smdproduct->getProductMetaFieldValue($this->smdproduct->getProductMetaFieldValueId(CARTONWIDTHID, $product->id));
                                    $cH = $this->smdproduct->getProductMetaFieldValue($this->smdproduct->getProductMetaFieldValueId(CARTONHEIGHTID, $product->id));

                                    ?>
                                    <table>
                                        <tr>
                                            <td><?php echo html::input('default-' . CARTONLENGTHID, $cL, "class='form-control form-inline' size='5' placeholder='L' "); ?></td>
                                            <td><?php echo html::input('default-' . CARTONWIDTHID, $cW, "class='form-control' size='5' placeholder='W' "); ?></td>
                                            <td><?php echo html::input('default-' . CARTONHEIGHTID, $cH, "class='form-control inline' size='5' placeholder='H' "); ?></td>
                                            <td>mm</td>
                                        </tr>
                                    </table>
                                    <p class="help-block">L x W x H of packing carton</p>
                                </td>
                            </tr>
                            <tr>
                                <?php $cGrossWeight = $this->smdproduct->getProductMetaFieldValue($this->smdproduct->getProductMetaFieldValueId(CARTONGROSSWEIGHTID, $product->id)); ?>
                                <th>Carton Gross Weight</th>
                                <td>
                                    <table>
                                        <tr>
                                            <td><?php echo html::input('default-' . CARTONGROSSWEIGHTID, $cGrossWeight, "class='form-control form-inline' size='5' "); ?></td>
                                            <td>kg</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <?php $cNetWeight = $this->smdproduct->getProductMetaFieldValue($this->smdproduct->getProductMetaFieldValueId(CARTONNETWEIGHTID, $product->id)); ?>
                                <th>Carton Net Weight</th>
                                <td>
                                    <table>
                                        <tr>
                                            <td><?php echo html::input('default-' . CARTONNETWEIGHTID, $cNetWeight, "class='form-control form-inline' size='5' "); ?></td>
                                            <td>kg</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="detail">
                    <div class="detail-title"><?php echo $lang->smdproduct->techspecs ?></div>
                    <div class="detail-content">
                        <table class="table-form">
                            <?php foreach ($metaTypeFields as $k => $m) : if ($m == '') continue; ?>
                                <tr>
                                    <th><?php echo $m ?></th>
                                    <td style="width: 300px">
                                        <?php
                                        echo html::select(
                                            "meta-$k",
                                            $this->smdproduct->formatForSelect($metaFieldValues[$k]),
                                            $this->smdproduct->getProductMetaFieldValueId($k, $product->id),
                                            "class='form-control chosen'"
                                        ); ?>
                                        <p><a href="javascript:addNewValue(<?php echo $k ?>);">Add New Value</a> </p>
                                    </td>
                                    <td class="data-tooltip">
                                        <span class="help-block"><?php echo strip_tags($metaFieldDescs[$k]); ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                    <div class='text-center'>
                        <?php echo html::submitButton('', '', 'btn btn-wide btn-primary'); ?>
                        <?php echo html::a($browseLink, $lang->goback, '', 'class="btn btn-wide"'); ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="side-col col-4">
        <div class="cell">
            <details class="detail" open>
                <summary class="detail-title"><?php echo $lang->smdproduct->imagesproduct ?></summary>
                <div class="detail-content">

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
                    </table>
                </div>
            </details>
        </div>
    </div>
</div>

<?php include '../../common/view/footer.html.php'; ?>