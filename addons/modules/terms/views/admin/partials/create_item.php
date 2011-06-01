<div id="terms_form_box">

    <h3><?php echo lang('terms.new_item'); ?></h3>

    <?php echo form_open_multipart($this->uri->uri_string(), 'class="crud"'); ?>
    <ol>
        <li class="<?php echo alternator('', 'even'); ?>">
            <label for="term_name"><?php echo lang('terms.term_name'); ?></label>
            <?php echo form_input('term_name', set_value('term_name'), 'class="width-15"'); ?>
            <span class="required-icon tooltip">Required</span>
        </li>

        <li>
            <?php echo form_textarea(array('id' => 'term_desc', 'name' => 'term_desc', 'value' => stripslashes($post->term_desc), 'rows' => 50, 'class' => 'wysiwyg-advanced')); ?>
        </li>
    </ol>

    <?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'custom_button'))); ?>

    <?php echo form_close(); ?>

</div>
