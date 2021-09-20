<?php 
include '../../common/view/header.html.php';
?>

<?php $browseLink = $this->createLink('brief', 'index', "");?>
<div id="mainMenu" class="clearfix">
    <div class="btn-toolbar pull-left">
        <?php if(!isonlybody()):?>
        <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-link'");?>
        <div class="divider"></div>
        <?php endif;?>
        <div class="page-title">
            <span class="label label-id"><?php echo $brief->id?></span>
            <span class="text" title='<?php echo $brief->name;?>' style='color: <?php echo $brief->color; ?>'>
                <?php if(!empty($brief->parent)) echo '<span class="label label-badge label-primary no-margin">' . $this->lang->brief->childrenAB . '</span>';?>
                <?php if(!empty($brief->team)) echo '<span class="label label-badge label-primary no-margin">' . $this->lang->brief->multipleAB . '</span>';?>
                <?php echo isset($brief->parentName) ? html::a(inlink('view', "briefID={$brief->parent}"), $brief->parentName) . ' / ' : '';?><?php echo $brief->name;?>
            </span>
            <?php if($brief->deleted):?>
            <span class='label label-danger'><?php echo $lang->brief->deleted;?></span>
            <?php endif;?>
            <?php if($brief->fromBug != 0):?>
            <small><?php echo html::icon($lang->icons['bug']) . " {$lang->brief->fromBug}$lang->colon$task->fromBug";?></small>
            <?php endif;?>
        </div>
    </div>
</div>
<div id="mainContent" class="main-row">
    <div class="main-col col-8">
        <div class="cell">
            <div class="detail">
                <div class="detail-title"><?php echo $lang->brief->desc;?></div>
                <div class="detail-content article-content">
                  <?php echo !empty($brief->desc) ? html_entity_decode($brief->desc) : "<div class='text-center text-muted'>" . $lang->noData . '</div>';?>
                </div>
            </div>
            <div class="detail">
                <table class="table-form <?php echo $brief->type ?>">
                    <tr class="content createNewProduct">
                        <th><?php echo $lang->brief->functions ?></th>
                        <td><?php echo !empty($brief->functions) ? nl2br(html_entity_decode($brief->functions)) : "<div class='text-center text-muted'>" . $lang->noData . '</div>';?></td>
                    </tr>
                    <tr class="content createNewProduct">
                        <th><?php echo $lang->brief->lookAndFeel ?></th>
                        <td><?php echo !empty($brief->lookAndFeel) ? nl2br(html_entity_decode($brief->lookAndFeel)) : "<div class='text-center text-muted'>" . $lang->noData . '</div>';?></td>
                    </tr>
                    <tr class="content packagingNew packagingUpdate packagingRedesign productPhotography modifyProduct">
                        <th class=''><?php echo $lang->brief->products;?></th>
                        <td>
                            <?php if(!is_null($brief->products)) { echo $this->brief->get_product_links($brief->products); } ?>
                        </td>
                    </tr>
                    <tr class="content packagingRedesign packagingConcept createNewProduct">
                        <th class=''><?php echo $lang->brief->links;?></th>
                        <td><?php echo nl2br($brief->links) ?></td>
                    </tr>
                    <tr class="content packagingConcept">
                        <th class=''><?php echo $lang->brief->lsm ?></th>
                        <td><?php
                            $lsm = explode(',', $brief->lsm);
                            foreach($lsm as $k => $l) {
                                $lsm[$k] = $lang->brief->lsmList[$l];
                            }
                            echo join(', ', $lsm);
                            ?> 
                        </td>
                    </tr>
                    <tr class="content packagingConcept">
                        <th class=''><?php echo $lang->brief->range ?></th>
                        <td>
                            <?php echo $brief->range ?>
                        </td>
                    </tr>
                    <tr class="content packagingConcept">
                        <th class=''><?php echo $lang->brief->packaging ?></th>
                        <td><?php
                                $packaging = explode(',', $brief->packaging);
                                foreach($packaging as $k => $p) {
                                    $packaging[$k] = $lang->brief->packagingList[$p];
                                }
                                echo join(', ', $packaging);
                            ?>
                        </td>
                    </tr>
                    <tr class="content productDevelopment modifyProduct createNewProduct">
                        <th class=''><?php echo $lang->brief->brand ?></th>
                        <td><div class="w-p45-f"><?php      
                                                
                        if(is_numeric($brief->brand)):
                            $brand = $this->loadModel('smdproduct')->getBrandById($brief->brand);  
                            echo $brand->name;
                            else:
                            echo $brief->brand;
                        endif; ?></div></td>
                    </tr>
                    <tr class="content productDevelopment createNewProduct">
                        <th class=''><?php echo $lang->brief->xcaCode ?></th>
                        <td><div class="w-p45-f"><?php echo $brief->xcaCode ?></div></td>
                    </tr>
                    <tr class="content productDevelopment">
                        <th class=''><?php echo $lang->brief->collection ?></th>
                        <td>
                            <?php echo $brief->collected ?>
                        </td>
                    </tr>
                    <tr class="content productPhotography">
                        <th class=''><?php echo $lang->brief->photos ?></th>
                        <td>
                            <?php
                                $angles = explode(',', $brief->angles);
                                foreach($angles as $k => $a) {
                                    $angles[$k] = $lang->brief->photoList[$a];
                                }
                                echo join(', ', $angles);
                            ?>
                        </td>
                    </tr>
                    <tr class="content productPhotography">
                        <th class=''><?php echo $lang->brief->resolution ?></th>
                        <td>
                            <?php
                                $resolutions = explode(',', $brief->resolution);
                                foreach($resolutions as $k => $r) {
                                    $resolution[$k] = $lang->brief->resolutionList[$r];
                                }
                                echo join(', ', $resolution);
                            ?>
                        </td>
                    </tr>
                    <tr class="content promotionalGraphics">
                        <th class=''><?php echo $lang->brief->dimensions ?></th>
                        <td><?php echo $brief->measurements ?></td>
                    </tr>
                    <tr class="content promotionalGraphics">
                        <th class=''><?php echo $lang->brief->paperSize ?></th>
                        <td><?php echo $brief->paperSize ?></td>
                    </tr>
                    <tr class="content promotionalGraphics">
                        <th class=''><?php echo $lang->brief->measurements ?></th>
                        <td><?php echo $brief->measurements ?></td>
                    </tr>
                    <tr class="content promotionalGraphics">
                        <th class=''><?php echo $lang->brief->orientation ?></th>
                        <td><?php
                                $orientations = explode(',', $brief->orientation);
                                foreach($orientations as $k => $o) {
                                    $orientations[$k] = $lang->brief->orientationList[$o];
                                }
                                echo join(', ', $orientations);
                            ?>                    
                        </td>
                    </tr>
                    <tr class="content promotionalGraphics">
                        <th class=''><?php echo $lang->brief->finishingDetails ?></th>
                        <td>
                            <?php echo $brief->finishingDetails ?>
                        </td>
                    </tr>
                    <tr class="content promotionalGraphics corporate">
                        <th class=''><?php echo $lang->brief->requiresPrinting ?></th>
                        <td><?php
                                $requiresPrinting = explode(',', $brief->printing);
                                foreach($requiresPrinting as $k => $p) {
                                    $requiresPrinting[$k] = $lang->brief->printingList[$p];
                                }
                                echo join(', ', $requiresPrinting);
                            ?>   </td>
                    </tr>
                    <?php if($brief->printing == 1) { ?>
                        <tr class="printQty">
                            <th class=''><?php echo $lang->brief->printQty ?></th>
                            <td><?php echo $brief->printQty ?></td>
                        </tr>
                    <?php } ?>
                        
                    <tr class="content createNewProduct modifyProduct newConcept">
                        <th></th>
                        <td><b>Specifications and Required Features</td>
                    </tr>
                    <tr class="content createNewProduct modifyProduct newConcept">
                    <th><?php echo $lang->brief->techSpec ?></th>
                        <td><?php echo $brief->techSpec  ?></td>
                    </tr>
                    <tr class="content createNewProduct modifyProduct newConcept">
                        <th><?php echo $lang->brief->funcSpec ?></th>
                        <td><?php echo $brief->funcSpec ?></td>
                    </tr>
                    <tr class="content createNewProduct modifyProduct newConcept">
                        <th><?php echo $lang->brief->size ?></th>
                        <td><?php echo $brief->size ?></td>
                    </tr>
                    <tr class="content createNewProduct modifyProduct newConcept">
                        <th><?php echo $lang->brief->additionalSpec ?></th>
                        <td><?php echo $brief->additionalSpec ?></td>
                    </tr>
                    <tr class="content createNewProduct modifyProduct newConcept">
                        <th></th>
                        <td><b>Customer, Pricing and Dates</td>
                    </tr>
                    <tr class="content createNewProduct modifyProduct newConcept">
                        <th><?php echo $lang->brief->client ?></th>
                        <td><?php echo$brief->client ?></td>
                    </tr>
                    <tr class="content createNewProduct modifyProduct newConcept">
                        <th><?php echo $lang->brief->targetPrice ?></th>
                        <td><?php echo $brief->targetPrice ?></td>
                    </tr>
                    <tr class="content createNewProduct modifyProduct newConcept">
                        <th><?php echo $lang->brief->quantity ?></th>
                        <td><?php echo $brief->quantity ?></td>
                    </tr>
                    <tr class="content createNewProduct modifyProduct newConcept">
                        <th><?php echo $lang->brief->deliveryDate ?></th>
                        <td><?php echo $brief->deliveryDate ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->brief->uploaded ?></th>
                        <td><?php echo $this->fetch('file', 'printFiles', array('files' => $brief->files, 'fieldset' => 'false'));?></td>
                    </tr>
                </table>
            </div>
            <?php include '../../common/view/action.html.php';?>
            <div class='actions'>
                <?php if(!$brief->deleted) echo $actionLinks;?>
            </div>
        </div>
    </div>
    <div class="side-col col-4">
    <div class="cell">
        <details class="detail" open>
            <summary class="detail-title"><?php echo $lang->brief->basicInfo;?></summary>
            <div class="detail-content">
                <table class="table table-data">
                    <tr>
                        <th class='w-110px text-right strong'><?php echo $lang->brief->name;?></th>
                        <td><?php echo $brief->name;?></td>
                    </tr>
                    <tr>
                        <th><?php echo $lang->brief->type;?></th>
                        <td><b><?php echo $lang->brief->typeList[$brief->type];?></b></td>
                    </tr>
                    <?php if(common::hasPriv('brief', 'archive')): ?>
                    <tr>
                        <th class="strong">Archive Brief</th>
                        <td class="strong">
                        <?php
                        if($brief->status != 'new' || $brief->revert): 
                            echo common::printIcon('brief', 'archive', "briefID=$brief->id", $brief, '', '', '', 'iframe', true);
                        else:
                            echo 'Archived';
                        endif;
                        ?>
                        </td>
                    </tr>
                    <?php endif;?>
                    <tr>
                        <th><?php echo $lang->brief->creator;?></th>
                        <td><?php echo $this->loadModel('user')->getById($brief->creatorId, 'id')->realname;?></td>
                    </tr>
                    <?php if($brief->onBehalfOf > 0) { ?>
                    <tr>
                        <th><?php echo $lang->brief->onBehalf;?></th>
                        <td><?php echo $this->loadModel('user')->getById($brief->onBehalfOf, 'id')->realname;?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <th><?php echo $lang->brief->dateCreated;?></th>
                        <td><?php echo $brief->created;?></td>
                    </tr>
                    <tr>
                        <th class='strong'><?php echo $lang->brief->deadline;?></th>
                        <td class='strong'><?php echo $brief->deadline;?></td>
                    </tr>
                    <tr>
                        <th class='strong'><?php echo $lang->brief->time;?></th>
                        <td class='strong'><?php echo $lang->brief->timeAllocation[$brief->timeAllocation];?></td>
                    </tr>
                    <tr>
                        <th class='strong'><?php echo $lang->brief->dateOrdered;?></th>
                        <td class='strong'><?php echo $brief->dateOrdered;?></td>
                    </tr>
                    <tr>
                        <th class='strong'><?php echo $lang->brief->status ?></th>
                        <td class='strong <?php echo $brief->status ?>'><?php echo $lang->brief->statusList[$brief->status] ?></td>
                    </tr>
                    <?php if($brief->status == 'accepted'): ?>
                    <tr>
                        <th class="strong"></th>
                        <td class="strong"><a href="/project-view-<?php echo $brief->projectID ?>" class="btn btn-primary">View Job &#8250;</a>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </details>
    </div>
</div>
</div>
<div id="mainActions">
    <?php common::printPreAndNext($preAndNext); ?>
    <div class="btn-toolbar">
        <?php common::printBack($browseLink);
        if(!isonlybody()) echo "<div class='divider'></div>";
        
        $user_groups = $this->app->user->groups;
        
        $is_prod_brief = in_array($brief->type, array('newConcept', 'modifyProduct', 'createNewProduct'));
        $is_prod_dev   = in_array((string)PRODDEV_ID, $user_groups);
        // Determine whether to display the Accept button on the brief        
        if($is_prod_brief === false && $is_prod_dev === true) {
            $prod_class = 'hidden';
        } else {
            $prof_class = '';
        }
        // Override if the user is a superadmin or traffic
        if(in_array((string)TRAFFIC_ID, $user_groups, true) !== false || in_array("1", array($user_groups), true) !== false) {
            echo '<!-- is admin -->';
            $prod_class = '';
        }
                
        if($brief->status == 'new' || $brief->status == 'reverted'):
                if(common::hasPriv('brief', 'accept')):
                    
                        common::printIcon('brief', 'accept', "briefID=$brief->id", $brief, 'button', '', '', "iframe {$prod_class}", true);
                
                endif;
                if(common::hasPriv('brief', 'revert')):
                    
                    common::printIcon('brief', 'revert', "briefID=$brief->id", $brief, 'button', '', '', 'iframe', true);
                
                endif;
                if(common::hasPriv('brief', 'decline')):
                    
                    common::printIcon('brief', 'decline', "briefID=$brief->id", $brief, 'button', '', '', 'iframe', true);
                
                endif;
        endif;
        
        if(!isonlybody()) echo "<div class='divider'></div>";
        
        
        $params = array('briefID' => $brief->id);
        
        if($brief->status == 'new' || $brief->status == 'reverted'):
                if(common::hasPriv('brief', 'edit')):
                    common::printIcon('brief', 'edit', $params, $brief);
                endif;
        else:
            if(common::hasPriv('brief', 'done')):                
                common::printIcon('brief', 'done',     "briefID=$brief->id", $brief, 'button', '', '', 'iframe', true);
            endif;
        endif;
        ?>
    </div>
</div>

<?php include '../../common/view/footer.html.php';?>