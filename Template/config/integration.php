<h3><i class="fa fa-bug fa-fw"></i><?= t('Bugzilla Plugin'); ?></h3>
<div class="panel">
    <?= $this->form->label(t('Bugzilla URL'), 'bugzilla_url'); ?>
    <?= $this->form->text('bugzilla_url', $values); ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-blue"><?= t('Save'); ?></button>
    </div>
</div>
