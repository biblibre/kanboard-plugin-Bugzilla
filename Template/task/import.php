<div class="page-header">
    <h2><?= $this->text->e($project['name']); ?> &gt; <?= t('Bugzilla Bug'); ?></h2>
</div>

<?= $this->form->label(t('Bug ID'), 'id'); ?>
<?= $this->form->text('id', $values, array(), array('required', 'autofocus')); ?>
