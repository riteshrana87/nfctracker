<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>

<div class="table-responsive">
    <table class="table table-bordered table-striped dataTable" id="example1" customer="grid" aria-describedby="example1_info">
        <thead>
            <tr>
                <?php foreach ($information['headerData'] as $passHeader) { ?>
                    <th> <?= $passHeader; ?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody> 
                <?php if (!empty($information['informationData'])) { ?>
                    <tr colspan='<?php echo count($information['headerData']); ?>'>
                        <div class="text-right m-b-10">
                        <input name="exportFile" id="exportFile" value="Export File" style="" class="btn btn-primary" type="button">
                        </div>
                    </tr>
                    <?php foreach ($information['informationData'] as $rowData) { ?>
                        <tr>
                            <?php foreach ($rowData as $row) { ?>
                                <td><?= (strlen($row) > 100) ? substr($row, 0, 100) . '...' : $row; ?></td>
                            <?php } ?> 
                        </tr>
                    <?php } ?> 
                <?php } else { ?>
                    <tr class="text-center">
                        <td colspan="<?php echo count($information['headerData']); ?>">No Records Found</td>
                    </tr>
                <?php } ?>        
            </tbody>
    </table>
</div>   
<div id="common_tb">
    <?php
    if (isset($pagination)) {
        echo $pagination;
    }
    ?>
</div>

