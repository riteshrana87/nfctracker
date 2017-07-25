<div class="nav-buttons">
    <ul class="nav nav-pills nav-justified">
        <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "pp") { ?>class="active"<?php } ?>>
            <?php
            $formdata = checkFormBuilderData(PP_FORM);
            if (!empty($formdata) && !empty($formdata[0]['pp_form_id'])) {
                ?>
                <a href="<?= base_url($this->type . '/AdminPlacementPlan/edit/') . '/' . $formdata[0]['pp_form_id'] ?>"><i class="fa fa-file-text"></i> PP</a>
            <?php } else { ?>
                <a href="<?= base_url($this->type . '/AdminPlacementPlan/add') ?>"><i class="fa fa-file-text"></i> PP</a>
            <?php } ?>
        </li>

        <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "ibp") { ?>class="active"<?php } ?>>
            <?php
            $formdata = checkFormBuilderData(IBP_FORM);
            if (!empty($formdata) && !empty($formdata[0]['ibp_form_id'])) {
                ?>
                <a href="<?= base_url($this->type . '/AdminIndividualBehaviourPlan/edit/') . '/' . $formdata[0]['ibp_form_id'] ?>"><i class="fa fa-file-text"></i> IBP</a>
            <?php } else { ?>
                <a href="<?= base_url($this->type . '/AdminIndividualBehaviourPlan/add') ?>"><i class="fa fa-file-text"></i> IBP</a>
            <?php } ?>
        </li>
        <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "ra") { ?>class="active"<?php } ?>>
            <?php
            $formdata = checkFormBuilderData(RA_FORM);
            if (!empty($formdata) && !empty($formdata[0]['ra_form_id'])) {
                ?>
                <a href="<?= base_url($this->type . '/AdminRiskAssessment/edit/') . '/' . $formdata[0]['ra_form_id'] ?>"><i class="fa fa-file-text"></i> RA</a>
            <?php } else { ?>
                <a href="<?= base_url($this->type . '/AdminRiskAssessment/add') ?>"><i class="fa fa-file-text"></i> RA</a>
            <?php } ?>
        </li>
        <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "do") { ?>class="active"<?php } ?>>
            <?php
            $formdata = checkFormBuilderData(DO_FORM);
            if (!empty($formdata) && !empty($formdata[0]['do_form_id'])) {
                ?>
                <a href="<?= base_url($this->type . '/AdminDailyObservations/edit/') . '/' . $formdata[0]['do_form_id'] ?>"><i class="fa fa-file-text"></i> DO</a>
            <?php } else { ?>
                <a href="<?= base_url($this->type . '/AdminDailyObservations/add') ?>"><i class="fa fa-file-text"></i> DO</a>
            <?php } ?>
        </li>
        <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "ks") { ?>class="active"<?php } ?>>
            <?php
            $formdata = checkFormBuilderData(KS_FORM);
            if (!empty($formdata) && !empty($formdata[0]['ks_form_id'])) {
                ?>
                <a href="<?= base_url($this->type . '/AdminKeySession/edit/') . '/' . $formdata[0]['ks_form_id'] ?>"><i class="fa fa-file-text"></i> KS</a>
            <?php } else { ?>
                <a href="<?= base_url($this->type . '/AdminKeySession/add') ?>"><i class="fa fa-file-text"></i> KS</a>
            <?php } ?>
        </li>
        <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "docs") { ?>class="active"<?php } ?>>
            <?php
            $formdata = checkFormBuilderData(DOCS_FORM);
            if (!empty($formdata) && !empty($formdata[0]['docs_form_id'])) {
                ?>
                <a href="<?= base_url($this->type . '/AdminDocuments/edit/') . '/' . $formdata[0]['docs_form_id'] ?>"><i class="fa fa-file-text"></i> DOCS</a>
            <?php } else { ?>
                <a href="<?= base_url($this->type . '/AdminDocuments/add') ?>"><i class="fa fa-file-text"></i> DOCS</a>
            <?php } ?>
        </li>
        <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "mac") { ?>class="active"<?php } ?>>
            <?php
            $formdata = checkFormBuilderData(MAC_FORM);
            if (!empty($formdata)) {
                ?>
                <a href="<?= base_url($this->type . '/AdminMedicalInformation/editAuthorisations/') . '/' . $formdata[0]['mac_form_id'] ?>"><i class="fa fa-medkit"></i>MODC</a>
            <?php } else { ?>
                <a href="<?= base_url($this->type . '/AdminMedicalInformation/addAuthorisations') ?>"><i class="fa fa-medkit"></i>MODC</a>
            <?php } ?>
        </li>
        <li <?php if (isset($button_header['menu_module']) && $button_header['menu_module'] == "coms") { ?>class="active"<?php } ?>>
            <?php
            $formdata = checkFormBuilderData(COMS_FORM);
            if (!empty($formdata)) {
                ?>
                <a href="<?= base_url($this->type . '/AdminCommunication/edit/') . '/' . $formdata[0]['coms_form_id'] ?>"><i class="fa fa-medkit"></i>COMS</a>
            <?php } else { ?>
                <a href="<?= base_url($this->type . '/AdminCommunication/add') ?>"><i class="fa fa-medkit"></i>COMS</a>
            <?php } ?>
        </li>
    </ul>
</div>