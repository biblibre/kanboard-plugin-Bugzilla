<?php $taskShowUrl = $this->app->bugzillaClient->getShowUrl($external_task->getUri()); ?>

<h2><?= t('Bugzilla Bug') . ' ' . sprintf('<a href="%s">#%s</a>', $taskShowUrl, $external_task->getBugId()); ?></h2>

<table>
    <tr>
        <th class="column-25"><?= t('Status'); ?></th>
        <td class="column-25"><?= $external_task->getBug()['status']; ?></td>

        <th class="column-25"><?= t('Creation Date'); ?></th>
        <td class="column-25"><?= $this->dt->date($external_task->getBug()['creation_time']); ?></td>
    </tr>
    <tr>
        <th><?= t('Reporter'); ?></th>
        <td><?= $this->text->e($external_task->getBug()['creator_detail']['real_name']); ?></td>

        <th><?= t('Modification Date'); ?></th>
        <td><?= $this->dt->date($external_task->getBug()['last_change_time']); ?></td>
    </tr>
    <tr>
        <th><?= t('Assignee'); ?></th>
        <td>
            <?= $this->text->e($external_task->getBug()['assigned_to_detail']['real_name']); ?>
        </td>

        <th><?= t('Severity'); ?></th>
        <td><?= $this->text->e($external_task->getBug()['severity']); ?></td>
    </tr>
</table>
