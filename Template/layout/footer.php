<?php if ($this->bugzilla->isBugzillaTask($task['id'])): ?>
<div>
    <a href="<?php echo $this->url->href('Bugzilla', 'show', array('plugin' => 'Bugzilla', 'task_id' => $task['id'], 'project_id' => $task['project_id'])); ?>" title="<?php echo t('Show Bugzilla status'); ?>" class="bugzilla-status-button"><i class="fa fa-fw fa-bug" aria-hidden="true"></i></a>
    <span class="bugzilla-status"></span>
</div>
<?php endif; ?>
