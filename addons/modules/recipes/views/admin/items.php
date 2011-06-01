<?php echo form_open('admin/recipes/delete_item'); ?>

<?php if (!empty($items)): ?>
    <h3><?php echo lang('recipes.item_list'); ?></h3>

    <table border="0" class="table-list">
        <thead>
            <tr>
                <th><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')); ?></th>
                <th><?php echo lang('recipes.recipe_name'); ?></th>
                <th><?php echo lang('recipes.recipe_desc'); ?></th>
                <th><?php echo lang('recipes.manage'); ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="5">
                    <div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
                </td>
            </tr>
        </tfoot>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?php echo form_checkbox('action_to[]', $item->recipe_id); ?></td>
                    <td><?php echo $item->recipe_name; ?></td>
                    <td><?php echo $item->recipe_desc; ?><td>
                        <?php
                        echo
                        anchor('admin/recipes/edit_item/' . $item->recipe_id, lang('recipes.edit')) . ' | ' .
                        anchor('admin/recipes/delete_item/' . $item->recipe_id, lang('recipes.delete'), array('class' => 'confirm'));
                        ?>
                    </td>
                </tr>
    <?php endforeach; ?>
        </tbody>
    </table>
    <?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete'))); ?>

<?php else: ?>
    <div class="blank-slate">
        <img src="<?php echo site_url('addons/modules/recipes/img/album.png') ?>" />

        <h2><?php echo lang('recipes.no_items'); ?></h2>
    </div>
<?php endif; ?>

<?php echo form_close(); ?>