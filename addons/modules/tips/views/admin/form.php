<?php if ($this->method == 'create_item'): ?>
    <h3><?php echo lang('tip_create_title'); ?></h3>
<?php else: ?>
    <h3><?php echo sprintf(lang('tip_edit_title'), $post->tip_name); ?></h3>
<?php endif; ?>

<?php echo form_open(uri_string(), 'class="crud"'); ?>

<div>

    <div>
        <ol>
            <li>
                <label for="tip_name"><?php echo lang('tip_name_label'); ?></label>
                <?php echo form_input('tip_name', htmlspecialchars_decode($post->tip_name), 'maxlength="100"'); ?>
                <span class="required-icon tooltip"><?php echo lang('required_label'); ?></span>
            </li>
            <li>
                <label for="tip_status"><?php echo lang('cp_status_title'); ?></label>
                <?php echo form_dropdown('tip_status', array('1' => lang('cp_status_active'), '0' => lang('cp_status_inactive')), $post->tip_status) ?>
            </li>
            <li>
                <?php echo form_textarea(array('id' => 'tip_desc', 'name' => 'tip_desc', 'value' => stripslashes($post->tip_desc), 'rows' => 50, 'class' => 'wysiwyg-advanced')); ?>
            </li>
        </ol>
    </div>


</div>

<div class="buttons float-right padding-top">
    <?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'save_exit', 'cancel'))); ?>
</div>

<?php echo form_close(); ?>