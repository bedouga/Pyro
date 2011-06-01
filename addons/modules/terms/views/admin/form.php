<?php if ($this->method == 'create_item'): ?>
    <h3><?php echo lang('term_create_title'); ?></h3>
<?php else: ?>
    <h3><?php echo sprintf(lang('term_edit_title'), $post->term_name); ?></h3>
<?php endif; ?>

<?php echo form_open(uri_string(), 'class="crud"'); ?>

<div>

    <div>
        <ol>
            <li>
                <label for="term_name"><?php echo lang('term_name_label'); ?></label>
                <?php echo form_input('term_name', htmlspecialchars_decode($post->term_name), 'maxlength="100"'); ?>
                <span class="required-icon tooltip"><?php echo lang('required_label'); ?></span>
            </li>
            <li>
                <label for="term_status"><?php echo lang('cp_status_title'); ?></label>
                <?php echo form_dropdown('term_status', array('1' => lang('cp_status_active'), '0' => lang('cp_status_inactive')), $post->term_status) ?>
            </li>
            <li>
                <?php echo form_textarea(array('id' => 'term_desc', 'name' => 'term_desc', 'value' => stripslashes($post->term_desc), 'rows' => 50, 'class' => 'wysiwyg-advanced')); ?>
            </li>
        </ol>
    </div>


</div>

<div class="buttons float-right padding-top">
    <?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'save_exit', 'cancel'))); ?>
</div>

<?php echo form_close(); ?>