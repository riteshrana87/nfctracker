<?php //echo htmlspecialchars_decode($pp_forms[0]['form_data']);exit;             ?>
<!-- main content start-->
<div id="page-wrapper">
    <div class="main-page">
        <h1 class="page-title">
            Key Session <small>New Forest Care</small>

        </h1>
        <h1 class="page-title">
            <small>Name: </small><?= !empty($YP_details[0]['yp_fname']) ? $YP_details[0]['yp_fname'] : '' ?> <?= !empty($YP_details[0]['yp_lname']) ? $YP_details[0]['yp_lname'] : '' ?><small>&nbsp;&nbsp;&nbsp;&nbsp;<div class="visible-xs-block"></div></small><small>DOB:</small> <?= (!empty($YP_details[0]['date_of_birth']) && $YP_details[0]['date_of_birth'] != '0000-00-00') ? date('d-m-Y', strtotime($YP_details[0]['date_of_birth'])) : '' ?>
        </h1>
        <?php if(($this->session->flashdata('msg'))){ ?>
            <div class="col-sm-12 text-center" id="div_msg">
                <?php echo $this->session->flashdata('msg');?>
            </div>
            <?php } ?>
        <div class="row">
            <form action="<?= base_url('KeySession/insert') ?>" method="post" id="ksform" name="ksform" data-parsley-validate enctype="multipart/form-data">
                <?php
                if (!empty($ks_form_data)) {
                    foreach ($ks_form_data as $row) {

                        if ($row['type'] == 'textarea') {
                            ?>
                            <div class="col-sm-12">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2><?= !empty($row['label']) ? $row['label'] : '' ?> <?= !empty($row['required']) ? '<span class="astrick">*</span>' : '' ?></h2>

                                        <textarea 
                                            class="<?= !empty($row['className']) ? $row['className'] : '' ?>" 
                                            <?= !empty($row['required']) ? 'required=true' : '' ?>
                                            name="<?= !empty($row['name']) ? $row['name'] : '' ?>" 
                                            placeholder="<?= !empty($row['placeholder']) ? $row['placeholder'] : '' ?>"
                                            <?= !empty($row['maxlength']) ? 'maxlength="' . $row['maxlength'] . '"' : '' ?>
                                            <?= !empty($row['rows']) ? 'rows="' . $row['rows'] . '"' : '' ?>
                                            id="<?= !empty($row['name']) ? $row['name'] : '' ?>" ></textarea>

                                    </div>
                                </div>
                            </div>
                            <?php
                        } else if ($row['type'] == 'text' || $row['type'] == 'number' || $row['type'] == 'date') {
                            ?>
                            <div class="col-sm-12">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2><?= !empty($row['label']) ? $row['label'] : '' ?> <?= !empty($row['required']) ? '<span class="astrick">*</span>' : '' ?></h2>
                                         <div class="<?=(!empty($row['subtype']) && $row['subtype'] == 'time')?'input-group input-append bootstrap-timepicker':''?>">
                                        <input type="<?=(!empty($row['type']) && $row['type']=='number')?'number':((!empty($row['subtype']) && $row['subtype'] !='time')?$row['subtype']:'text')?>"  
                                               class="<?= !empty($row['className']) ? $row['className'] : '' ?>" 
                                               <?= !empty($row['required']) ? 'required=true' : '' ?>
                                               name="<?= !empty($row['name']) ? $row['name'] : '' ?>" id="<?= !empty($row['name']) ? $row['name'] : '' ?>" 
                                               <?= !empty($row['maxlength']) ? 'maxlength="' . $row['maxlength'] . '"' : '' ?>
                                               <?= !empty($row['min']) ? 'min="' . $row['min'] . '"' : '' ?>
                                               <?= !empty($row['max']) ? 'max="' . $row['max'] . '"' : '' ?>
                                               <?= !empty($row['step']) ? 'step="' . $row['step'] . '"' : '' ?>
                                               placeholder="<?= !empty($row['placeholder']) ? $row['placeholder'] : '' ?>"
                                               value="" <?= ($row['type'] == 'date') ? 'readonly' : '' ?> />
                                               <?php if(!empty($row['subtype']) && $row['subtype'] == 'time') {?>
                                                <span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span>
                                                    <script type="text/javascript">
                                                        $(function () {
                                                            $("<?=!empty($row['name'])?'#'.$row['name']:'#'?>").timepicker({
                                                                defaultTime: '',
                                                            });
                                                          
                                                        });
                                                    </script>
                                                    <?php } ?>
                                               <?php if ($row['type'] == 'date') { ?>

                                            </span>
                                            <script type="text/javascript">
                                                $(function () {
                                                    $("<?= !empty($row['name']) ? '#' . $row['name'] : '#' ?>").datepicker({
                                                        format: 'yyyy-mm-dd',
                                                    });
                                                });
                                            </script>
                                        <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php
                        } else if ($row['type'] == 'radio-group') {
                            ?>
                            <div class="col-sm-12">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2><?= !empty($row['label']) ? $row['label'] : '' ?> <?= !empty($row['required']) ? '<span class="astrick">*</span>' : '' ?></h2>

                                        <div class="radio-group">
                                            <?php
                                            if (count($row['values']) > 0) {
                                                foreach ($row['values'] as $radio) {
                                                    if (!empty($radio['label'])) {
                                                        ?>
                                                        <div class="<?= !empty($row['inline']) ? 'radio-inline' : 'radio' ?>">
                                                            <label ><input name="<?= !empty($row['name']) ? $row['name'] : '' ?>" <?= !empty($row['required']) ? 'required=true' : '' ?> 
                                                                           class="<?= !empty($row['className']) ? $row['className'] : '' ?>" 
                                                                           value="<?= !empty($radio['value']) ? $radio['value'] : '' ?>"  type="radio">
                                                                <?= !empty($radio['label']) ? $radio['label'] : '' ?></label>
                                                        </div>
                                                        <?php
                                                    }
                                                }
                                            } //radio loop  
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        } else if ($row['type'] == 'checkbox-group') {
                            ?>
                            <div class="col-sm-12">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2><?= !empty($row['label']) ? $row['label'] : '' ?> <?= !empty($row['required']) ? '<span class="astrick">*</span>' : '' ?></h2>

                                        <div class="checkbox-group">
                                            <?php
                                            if (count($row['values']) > 0) {

                                                foreach ($row['values'] as $checkbox) {
                                                    if (!empty($checkbox['label'])) {
                                                        ?>
                                                        <div class="<?= !empty($row['inline']) ? 'checkbox-inline' : 'checkbox' ?>">
                                                            <label class="<?= !empty($row['toggle']) ? 'kc-toggle' : '' ?>"><input 
                                                                    class="<?= !empty($row['className']) ? $row['className'] : '' ?> <?= !empty($row['toggle']) ? 'kc-toggle' : '' ?>"
                                                                    name="<?= !empty($row['name']) ? $row['name'] . '[]' : '' ?>" value="<?= !empty($checkbox['value']) ? $checkbox['value'] : '' ?>" 
                                                                    <?= !empty($row['required']) ? 'required=true' : '' ?>
                                                                    type="checkbox">
                                                                <?= !empty($checkbox['label']) ? $checkbox['label'] : '' ?></label>
                                                        </div>
                                                        <?php
                                                    }
                                                }
                                            } //radio loop 
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        } else if ($row['type'] == 'select') {
                            ?>
                            <div class="col-sm-12">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2><?= !empty($row['label']) ? $row['label'] : '' ?> <?= !empty($row['required']) ? '<span class="astrick">*</span>' : '' ?></h2>

                                        <select class="<?= !empty($row['className']) ? $row['className'] : '' ?>" name="<?= !empty($row['name']) ? $row['name'] : '' ?>" id="<?= !empty($row['name']) ? $row['name'] : '' ?>" <?= !empty($row['required']) ? 'required=true' : '' ?>>
                                            <option value="">Select</option>
                                            <?php
                                            if (count($row['values']) > 0) {
                                                foreach ($row['values'] as $select) {
                                                    if (!empty($select['label'])) {
                                                        ?>
                                                        <option value="<?= !empty($select['value']) ? $select['value'] : '' ?>"><?= !empty($select['label']) ? $select['label'] : '' ?></option>
                                                        <?php
                                                    }
                                                }
                                            } //select loop  
                                            ?>

                                        </select>

                                    </div>
                                </div>
                            </div>
                            <?php
                        } else if ($row['type'] == 'hidden' || $row['type'] == 'button') {
                            ?>
                            <?php if ($row['type'] == 'button') { ?>
                                <div class="col-sm-12">
                                    <div class="panel panel-default tile tile-profile">
                                        <div class="panel-body">
                                            <div class="fb-button form-group">

                                                <button name="<?= !empty($row['name']) ? $row['name'] : '' ?>" value="" type="<?= !empty($row['type']) ? $row['type'] : '' ?>" class="<?= !empty($row['className']) ? $row['className'] : '' ?>" name="<?= !empty($row['name']) ? $row['name'] : '' ?>" id="<?= !empty($row['name']) ? $row['name'] : '' ?>" style="<?= !empty($row['style']) ? $row['style'] : '' ?>"><?= !empty($row['label']) ? $row['label'] : '' ?></button>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if ($row['type'] == 'hidden') { ?>
                                <div class="col-sm-12">
                                    <input type="hidden" name="<?= !empty($row['name']) ? $row['name'] : '' ?>" id="<?= !empty($row['name']) ? $row['name'] : '' ?>" value="" />
                                </div>
                            <?php } ?>
                            <?php
                        } else if ($row['type'] == 'header') {
                            ?>
                            <div class="col-sm-12">
                                <div class="">
                                    <h1 class="page-title"><?= !empty($row['label']) ? $row['label'] : '' ?></h1>
                                </div>
                            </div>
                        <?php } else if ($row['type'] == 'file') {
                            ?>
                            <div class="col-sm-12">
                                <div class="panel panel-default tile tile-profile">
                                    <div class="panel-body">
                                        <h2 class="page-title"><?= !empty($row['label']) ? $row['label'] : '' ?></h2>
                                        <input type="file" name="<?= !empty($row['name']) ? $row['name'] . '[]' : '' ?>" id="<?= !empty($row['name']) ? $row['name'] : '' ?>"  class="<?= !empty($row['className']) ? $row['className'] : '' ?>" 
                                               <?= !empty($row['multiple']) ? 'multiple="true"' : '' ?> >
                                        <h2></h2>
                                        <input type="hidden" name="<?= !empty($row['name']) ? 'hidden_' . $row['name'] : '' ?>" id="<?= !empty($row['name']) ? 'hidden_' . $row['name'] : '' ?>" value="">
                                    </div>

                                </div>
                            </div>
                        <?php } ?>
                        <?php
                    } //foreach
                    ?>

                    <div class="col-sm-12">
                        <div class="">
                            <input type="hidden" name="yp_id" id="yp_id" value="<?php echo $ypid; ?>">

                            <button type="submit" class="btn btn-default" name="submit_ksform" id="submit_ksform" value="submit" style="default">Create</button>
                        </div>
                    </div>
                    <?php
                }
                ?>

            </form>        
        </div>

    </div>
</div>
