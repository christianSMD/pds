<?php

$config->project->defaultWorkhours = '6.0';

if(!isset($config->project->editor)) {
    $config->project->editor = new stdclass();
}

$config->project->editor->briefdesign  = array('id' => 'comment', 'tools' => 'simpleTools');
$config->project->editor->done         = array('id' => 'comment', 'tools' => 'simpleTools');
$config->project->editor->desc         = array('id' => 'desc', 'tools' => 'simpleTools');