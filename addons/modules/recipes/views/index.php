<div class="terms-container">
    <?php if ($items): ?>
    		
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <th>{pyro:helper:lang line="terms.term_name"}</th>
                    <th>{pyro:helper:lang line="terms.term_desc"}</th>
                </tr>
                {pyro:items}
                <tr class="even">
                    <td width="100">{pyro:term_name}</td>
                    <td>{pyro:term_desc}</td>
                </tr>
                {/pyro:items}
            </table>
    		

    <?php else: ?>
        <p>No listings have been added.</p>
    <?php endif; ?>

    <?php $this->load->view('admin/partials/pagination'); ?>

</div>