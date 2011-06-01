<?php echo form_open('admin/terms/delete_item'); ?>

<?php if (!empty($items)): ?>
    <h3><?php echo lang('terms.item_list'); ?></h3>

    <table border="0" class="table-list">
        <thead>
            <tr>
                <th><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')); ?></th>
                <th><?php echo lang('terms.term_name'); ?></th>
                <th><?php echo lang('terms.term_desc'); ?></th>
                <th><?php echo lang('terms.manage'); ?></th>
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
                    <td><?php echo form_checkbox('action_to[]', $item->term_id); ?></td>
                    <td><?php echo $item->term_name; ?></td>
                    <td><?php echo $item->term_desc; ?><td>
                        <?php
                        echo
                        anchor('admin/terms/edit_item/' . $item->term_id, lang('terms.edit')) . ' | ' .
                        anchor('admin/terms/delete_item/' . $item->term_id, lang('terms.delete'), array('class' => 'confirm'));
                        ?>
                    </td>
                </tr>
    <?php endforeach; ?>
        </tbody>
    </table>
    <?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete'))); ?>

<?php else: ?>
    <div class="blank-slate">
        <img src="<?php echo site_url('addons/modules/terms/img/album.png') ?>" />

        <h2><?php echo lang('terms.no_items'); ?></h2>
    </div>
<?php endif; ?>

<?php echo form_close(); ?>