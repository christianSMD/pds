<?php 
include '../../common/view/header.html.php';
include '../../common/view/datepicker.html.php';
include '../../common/view/kindeditor.html.php';

js::import($jsRoot . 'misc/date.js');
js::set('weekend', $config->brief->weekend);
js::set('holders', $lang->brief->placeholder);
?>
<div id="mainContent" class="main-content">
    <div class="center-block">
        <div class="main-header">
            <h2><?php echo $lang->brief->edit ?></h2>
            <div class="pull-right btn-toolbar">
                <!-- button type='button' class='btn btn-link' id='cpmBtn'><?php echo html::icon($lang->icons['copy'], 'muted') . ' ' . $lang->brief->copy;?></button> -->
            </div>
        </div>
        <form class='form-indicator main-form form-ajax' method='post' target='hiddenwin' id='dataform'>
            <input type="hidden" name="briefID" value="<?php echo $brief->id ?>" />
            <table class='table table-form'> 
                <tr>
                    <th class='w-110px'><?php echo $lang->brief->name;?></th>
                    <td colspan="3" class='w-p25-f'><?php echo html::input('name', $brief->name, "class='form-control' autocomplete='off' ");?></td><td></td>
                </tr>
                <tr>
                    <th><?php echo $lang->brief->onBehalf;?></th>
                    <td colspan="3"><?php echo html::select('onBehalfOf', $poUsers, $this->loadModel('user')->getById($brief->onBehalfOf, 'id')->account, "class='form-control chosen'");?></td>
                </tr>
                <tr>
                    <th><?php echo $lang->brief->deadline ?></th>
                    <td>
                        <div class='input-group'>
                            <?php echo html::input('deadline', $brief->deadline, "class='form-control w-100px form-date' ");?>                        
                        </div>
                    </td>
                    
                    <th><?php echo $lang->brief->time ?></th>
                    <td>
                        <div class="input-group">
                            <?php echo html::select('timeAllocation', $lang->brief->timeAllocation, $brief->timeAllocation, "class='form-control' "); ?>
                        </div>
                    </td>
                    
                </tr>
                
                <tr>
                    <th><?php echo $lang->brief->type;?></th>
                    <td colspan="3">
                      <?php echo html::select('type', $lang->brief->typeList, $brief->type, "class='form-control' onchange='showTypeFields()' ");?>
                    </td>
                </tr>
            </table>
            <table class="table table-form sub-form">
                <tr class="packagingNew">
                    <td class='w-110px'></td>
                    <td><p class="help-block"><?php echo $lang->brief->formNew ?></p></td>
                </tr>
                <tr class="packagingUpdate">
                    <td class='w-110px'></td>
                    <td><p class="help-block"><?php echo $lang->brief->formUpdate ?></p></td>
                </tr>
                <tr class="corporate">
                    <td class='w-110px'></td>
                    <td><p class="help-block"><?php echo $lang->brief->formCorporate ?></p></td>
                </tr>
                <tr class="productPhotography">
                    <td class='w-110px'></td>
                    <td><p class="help-block"><?php echo $lang->brief->formPhotography ?></p></td>
                </tr>
                <tr class="packagingConcept">
                    <td class='w-110px'></td>
                    <td><p class="help-block"><?php echo $lang->brief->formConcept ?></p></td>
                </tr>
                <tr class="packagingRedesign">
                    <td class='w-110px'></td>
                    <td><p class="help-block"><?php echo $lang->brief->formRedesign ?></p></td>
                </tr>
                <tr class="promotionalGraphics">
                    <td class='w-110px'></td>
                    <td><p class="help-block"><?php echo $lang->brief->formPromotional ?></p></td>
                </tr>
                <tr class="createNewProduct">
                    <th></th>
                    <td><p class="help-block"><?php echo $lang->brief->formCreateNewProduct ?></p></td>
                </tr>
                <tr class="modifyProduct">
                    <th></th>
                    <td><p class="help-block"><?php echo $lang->brief->formModifyProduct ?></p></td>
                </tr>
                <tr class="newConcept">
                    <th></th>
                    <td><p class="help-block"><?php echo $lang->brief->formNewConcept ?></p></td>
                </tr>
                <tr class="packagingNew packagingUpdate packagingRedesign packagingConcept productDevelopment productPhotography promotionalGraphics corporate createNewProduct modifyProduct newConcept">
                    <th class='w-110px'><?php echo $lang->brief->desc;?></th>
                    <td>
                        <?php echo html::textarea('desc', $brief->desc, "rows='6' class='form-control'");?>
                        <p class="help-block"><?php echo $lang->brief->ttDesc ?></p>
                    </td>
                </tr>
                <tr class="createNewProduct">
                    <th class=''><?php echo $lang->brief->functions;?></th>
                    <td>
                        <?php echo html::textarea('functions', $brief->functions, "rows='6' class='form-control'");?>
                        <p class="help-block"><?php echo $lang->brief->ttFunctions ?></p>
                    </td>
                </tr>
                <tr class="createNewProduct">
                    <th class=''><?php echo $lang->brief->lookAndFeel;?></th>
                    <td>
                        <?php echo html::textarea('lookAndFeel', $brief->lookAndFeel, "rows='6' class='form-control'");?>
                        <p class="help-block"><?php echo $lang->brief->ttLookAndFeel ?></p>
                    </td>
                </tr>
                <tr class="packagingUpdate packagingRedesign productPhotography">
                    <th><?php echo $lang->brief->products;?></th>
                    <td>
                        <div class='w-p45-f'><?php echo html::select('products[]', $poProducts, $brief->products, "class='form-control chosen' multiple");?></div>
                        <p class="help-block"><?php echo $lang->brief->ttProducts ?></p>
                    </td>
                </tr>
                <tr class="packagingNew packagingUpdate packagingRedesign packagingConcept productDevelopment productPhotography promotionalGraphics corporate createNewProduct modifyProduct newConcept">
                    <th class=''><?php echo $lang->brief->upload;?></th>
                    <td>
                        <?php echo $this->fetch('file', 'printFiles', array('files' => $brief->files, 'fieldset' => 'false'));?>
                        <?php echo $this->fetch('file', 'buildForm') ?>
                        <p class="help-block"><?php echo $lang->brief->ttUpload ?></p>
                    </td>
                </tr>
                <tr class="packagingRedesign packagingConcept createNewProduct">
                    <th><?php echo $lang->brief->links;?></th>
                    <td><div class="w-p45-f"><?php echo html::textarea('links', $brief->links, "rows='3' class='form-control'");?></div></td>
                </tr>
                <tr class="packagingConcept">
                    <th><?php echo $lang->brief->lsm ?></th>
                    <td><?php echo html::checkbox('lsm', $lang->brief->lsmList , $brief->lsm);?></td>
                </tr>
                <tr class="packagingConcept">
                    <th><?php echo $lang->brief->range ?></th>
                    <td>
                        <?php echo html::input('range', $brief->range, "class='form-control'");?>
                        <p class="help-block"><?php echo $lang->brief->ttRange ?></p>
                    </td>
                </tr>
                <tr class="packagingConcept">
                    <th><?php echo $lang->brief->packaging ?></th>
                    <td><?php echo html::checkbox('packaging', $lang->brief->packagingList , $brief->packaging);?></td>
                </tr>
                <tr class="productDevelopment createNewProduct modifyProduct">
                    <th><?php echo $lang->brief->brand ?></th>
                    <td><div class="w-p45-f"><?php echo html::select('brand', $poBrands, $brief->brand, "class='form-control chosen'");?></div></td>
                </tr>
                <tr class="createNewProduct">
                    <th><?php echo $lang->brief->xcaCode ?></th>
                    <td><div class='input-group w-p25-f'><?php echo html::input('xcaCode', $brief->xcaCode, "class='form-control'");?></div></td>
                </tr>
                <tr class="productDevelopment">
                    <th><?php echo $lang->brief->collection ?></th>
                    <td>
                        <div class='input-group w-p25-f'>
                            <?php echo html::input('collected', $brief->collected, "class='form-control w-100px form-date'");?>                        
                        </div>
                    </td>
                </tr>
                <tr class="productPhotography">
                    <th><?php echo $lang->brief->photos ?></th>
                    <td><?php echo html::checkbox('angles', $lang->brief->photoList , $brief->angles);?></td>
                </tr>
                <tr class="productPhotography">
                    <th><?php echo $lang->brief->resolution ?></th>
                    <td><?php echo html::checkbox('resolution', $lang->brief->resolutionList, $brief->resolution);?></td>
                </tr>
                <tr class="promotionalGraphics">
                    <th><?php echo $lang->brief->dimensions ?></th>
                    <td><div class='input-group w-p25-f'><?php echo html::input('dimensions', $brief->dimensions, "class='form-control'");?></div></td>
                </tr>
                <tr class="promotionalGraphics">
                    <th><?php echo $lang->brief->paperSize ?></th>
                    <td><div class='input-group w-p25-f'><?php echo html::input('paperSize', $brief->paperSize, "class='form-control'");?></div></td>
                </tr>
                <tr class="promotionalGraphics">
                    <th><?php echo $lang->brief->measurements ?></th>
                    <td><div class='input-group w-p25-f'><?php echo html::input('measurements', $brief->measurements, "class='form-control'");?></div></td>
                </tr>
                <tr class="promotionalGraphics">
                    <th><?php echo $lang->brief->orientation ?></th>
                    <td><?php echo html::checkbox('orientation', $lang->brief->orientationList, $brief->orientation);?></td>
                </tr>
                <tr class="promotionalGraphics">
                    <th><?php echo $lang->brief->finishingDetails ?></th>
                    <td>
                        <div class='input-group'><?php echo html::input('finishingDetails', $brief->finishingDetails, "class='form-control'");?></div>
                        <p class="help-block"><?php echo $lang->brief->ttFinishing ?></p>
                    </td>
                </tr>
                <tr class="promotionalGraphics corporate">
                    <th><?php echo $lang->brief->requiresPrinting ?></th>
                    <td><?php echo html::radio('printing', $lang->brief->printingList , $brief->printing, "onchange='requiresPrinting()'");?></td>
                </tr>
                <tr class="printQty">
                    <th><?php echo $lang->brief->printQty ?></th>
                    <td><div class='input-group w-p15-f'><?php echo html::input('printQty', $brief->printQty, "class='form-control'");?></div></td>
                </tr>
                <tr class="createNewProduct modifyProduct newConcept">
                    <th></th>
                    <td><b>Specifications and Required Features</td>
                </tr>
                <tr class="createNewProduct modifyProduct newConcept">
                    <th><?php echo $lang->brief->techSpec ?></th>
                    <td><div class='input-group w-p35-f'><?php echo html::input('techSpec', $brief->techSpec, "class='form-control'");?><p class="help-block"><?php echo $lang->brief->ttTechSpecs ?></p></div></td>
                </tr>
                <tr class="createNewProduct modifyProduct newConcept">
                    <th><?php echo $lang->brief->funcSpec ?></th>
                    <td><div class='input-group w-p35-f'><?php echo html::input('funcSpec',$brief->funcSpec, "class='form-control'");?></div></td>
                </tr>
                <tr class="createNewProduct modifyProduct newConcept">
                    <th><?php echo $lang->brief->size ?></th>
                    <td><div class='input-group w-p35-f'><?php echo html::input('size', $brief->size, "class='form-control'");?></div></td>
                </tr>
                <tr class="createNewProduct modifyProduct newConcept">
                    <th><?php echo $lang->brief->additionalSpec ?></th>
                    <td><div class='input-group w-p35-f'><?php echo html::input('additionalSpec',$brief->additionalSpec, "class='form-control'");?><p class="help-block"><?php echo $lang->brief->ttAdditionalSPec ?></p></div></td>
                </tr>
                <tr class="createNewProduct modifyProduct newConcept">
                    <th></th>
                    <td><b>Customer, Pricing and Dates</td>
                </tr>
                <tr class="createNewProduct modifyProduct newConcept">
                    <th><?php echo $lang->brief->client ?></th>
                    <td><div class='input-group w-p35-f'><?php echo html::input('client', $brief->client, "class='form-control'");?><p class="help-block"><?php echo $lang->brief->ttClient ?></p></div></td>
                </tr>
                <tr class="createNewProduct modifyProduct newConcept">
                    <th><?php echo $lang->brief->targetPrice ?></th>
                    <td><div class='input-group w-p35-f'><?php echo html::input('targetPrice', $brief->targetPrice, "class='form-control'");?><p class="help-block"><?php echo $lang->brief->ttTargetPrice ?></p></div></td>
                </tr>
                <tr class="createNewProduct modifyProduct newConcept">
                    <th><?php echo $lang->brief->quantity ?></th>
                    <td><div class='input-group w-p35-f'><?php echo html::input('quantity', $brief->quantity, "class='form-control'");?></div></td>
                </tr>
                <tr class="createNewProduct modifyProduct newConcept">
                    <th><?php echo $lang->brief->deliveryDate ?></th>
                    <td><div class='input-group w-p35-f'><?php echo html::input('deliveryDate', $brief->deliveryDate, "class='form-control w-100px form-date' ");?> </div></td>
                </tr>
                <tr class="packagingNew packagingUpdate packagingRedesign createNewProduct modifyProduct">
                    <th><?php echo $lang->brief->dateOrdered ?></th>
                    <td><div class='input-group w-p35-f'><?php echo html::input('dateOrdered', $brief->dateOrdered, "class='form-control w-100px form-date' ");?></div>
                            <p class="help-block"><?php echo $lang->brief->ttDateOrdered ?></p></td>
                </tr>
            </table>
            <div class='text-center'>
                <?php echo html::submitButton('', '', 'btn btn-wide btn-primary');?>
                <?php echo html::backButton('', '', 'btn btn-wide');?>
            </div>
        </form>
    </div>
</div>

<?php include '../../common/view/footer.html.php';?>