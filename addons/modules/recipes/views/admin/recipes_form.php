<?php if ($this->method == 'create_item'): ?>
    <h3><?php echo lang('recipe_create_title'); ?></h3>
<?php else: ?>
    <h3><?php echo sprintf(lang('recipe_edit_title'), $post->recipe_name); ?></h3>
<?php endif; ?>

<?php echo form_open(uri_string(), 'class="crud"'); ?>

<div>

    <div>
        <ol>
            <li>
                <label for="recipe_name"><?php echo lang('recipe_name_label'); ?></label>
                <?php echo form_input('recipe_name', htmlspecialchars_decode($post->recipe_name), 'maxlength="100"'); ?>
                <span class="required-icon tooltip"><?php echo lang('required_label'); ?></span>
            </li>
            
            <li>
                <label for="recipe_desc"><?php echo lang('recipes.desc'); ?></label>
                <?php echo form_textarea(array('id' => 'recipe_desc', 'name' => 'recipe_desc', 'value' => stripslashes($post->recipe_desc), 'rows' => 50, 'class' => 'wysiwyg-simple')); ?>
            </li>
            
            <li>
                <label for="recipe_style_cat"><?php echo lang('recipes.style_cat'); ?></label>
                
                <?php 
                $options = array('' => 'Select One');
                foreach($style_cats as $style_cat) {
                    $options[$style_cat->style_cat_id] = $style_cat->style_cat_name;
                }
                echo form_dropdown('recipe_style_cat', $options, set_value('recipe_style_cat'));
                ?>
                <span class="required-icon tooltip"><?php echo lang('required_label'); ?></span>
            </li>
            
            
            <li>
                <label for="recipe_style"><?php echo lang('recipes.style'); ?></label>
                <?php 
                $options = array('' => 'Select One');
                foreach($styles as $style) {
                    $options[$style->style_id] = $style->style_name;
                }
                echo form_dropdown('recipe_style', $options, set_value('recipe_style'));
                ?>
                <span class="required-icon tooltip"><?php echo lang('required_label'); ?></span>
            </li>
            
            
            <li>
                <label for="recipe_type">Recipe Type</label>
                <?php 
                $options = array(
                    'e' => 'Extract',
                    'p' => 'Partial mash',
                    'a' => 'All Grain'
                    );
                echo form_dropdown('recipe_type', $options, set_value('recipe_type'));?>
            </li>
            
            <li>
                <label for="recipe_status">Units Type</label>
                <div id="unit_types">
                <input type="radio" value="M" id="unit_type_M" name="unit_type"  /><label for="unit_type_M">Metric System</label>
                <input type="radio" value="US" id="unit_type_US" name="unit_type"  /><label for="unit_type_US">US System</label>
                </div>
            </li>
            
            <li>
                <label for="recipe_status"><?php echo lang('cp_status_title'); ?></label>
                <?php echo form_dropdown('recipe_status', array('1' => lang('cp_status_active'), '0' => lang('cp_status_inactive')), isset($post->recipe_status)?$post->recipe_status:'1') ?>
            </li>

        </ol>
    </div>


</div>

<div class="buttons float-right padding-top">
    <?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'save_exit', 'cancel'))); ?>
</div>

<?php echo form_close(); ?>