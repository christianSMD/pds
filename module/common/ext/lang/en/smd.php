<?php
$lang->welcome        = "%s PDS";

// Place "Brief" shortcut in the main menu
$lang->menuOrder[6] = 'brief';
$lang->menu->brief    = 'Brief|brief|index';

$lang->brief->menu->team =  array('link' => 'Team|brief|team|briefID=%s');

// Unset items from dashboard view
unset(
    $lang->menuOrder[10],           // Remove default `Product` from main menu   
    $lang->my->menu->bug,           // Remove Bug from submenu on the dashboard
    $lang->my->menu->testtask,      // Remove Testtask from submenu on the dashboard
    $lang->my->menu->story,         // Remove Story from submenu on the dashboard
    $lang->my->menu->manageContacts // Remove Contact from submenu on dashboard
); 

// Default "Project" link to "All" overview
$lang->menu->project = 'Job|project|all|all';

// SMD Product Menu
$lang->menuOrder[16] = 'smdproduct';
$lang->menu->smdproduct = 'Product|smdproduct|index';
$lang->smdproduct->menu->products   = 'Manage Products|smdproduct|index';
$lang->smdproduct->menu->metaFields = 'Manage Meta Fields|smdproduct|metaFields';
$lang->smdproduct->menu->metaTypes  = 'Manage Meta Types|smdproduct|metaTypes';
$lang->smdproduct->menu->brand      = 'Manage Brands|smdproduct|brands';


$lang->menuOrder[25] = 'traffic';
$lang->menu->traffic    = 'Traffic|traffic|index';
    

// Remove & Edit menus and sub-items
$lang->menu->product            = null; // Remove standard Product
$lang->menu->qa                 = null; // Remove QA/Bugs
$lang->project->menu->qa        = null; // Remove Bug
$lang->project->menu->kanban    = null; // Remove Kanban
$lang->project->menu->story     = null; // Remove Story
$lang->project->menu->product   = null; // Remove Product
$lang->project->menu->action    = array('link' => 'Dynamic|project|dynamic|projectID=%s');

// Remove menu items from Dashboard
// $lang->my->bug = null;
//$lang->my->menuOrder[20] = null;

// Pager info
$lang->pager = new stdclass();
$lang->pager->noRecord     = "No Records";
$lang->pager->digest       = " <strong>%s</strong> in total. %s <strong>%s/%s</strong> &nbsp; ";
$lang->pager->recPerPage   = " <strong>%s</strong> per page";
$lang->pager->first        = "<i class='icon-step-backward' title='Home'></i>";
$lang->pager->pre          = "<i class='icon-play icon-flip-horizontal' title='Previous Page'></i>";
$lang->pager->next         = "<i class='icon-play' title='Next Page'></i>";
$lang->pager->last         = "<i class='icon-step-forward' title='Last Page'></i>";
$lang->pager->locate       = "Go!";
$lang->pager->previousPage = "Prev";
$lang->pager->nextPage     = "Next";
$lang->pager->summery      = "<strong>%s-%s</strong> of <strong>%s</strong>.";
