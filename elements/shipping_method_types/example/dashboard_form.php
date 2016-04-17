<?php
defined('C5_EXECUTE') or die("Access Denied.");
extract($vars); ?>
<div class="row">
    <div class="col-xs-12 col-sm-6">
        <?= $form->label('minimumAmount',t("Shipping cost")); ?>
        <div class="input-group">
            <div class="input-group-addon"><?=Config::get('community_store.symbol')?></div>
            <?= $form->text('rate',$smtm->getRate()); ?>
        </div>
    </div>
</div>