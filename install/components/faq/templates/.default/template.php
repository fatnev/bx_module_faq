<div class="faq-list">
    <?if(!empty($arResult["ITEMS"])):?>
        <?foreach($arResult["ITEMS"] as $faq):?>
            <div class="faq-item">
                <h3><?= htmlspecialchars($faq["NAME"]) ?></h3>
                <div class="faq-answer"><?= htmlspecialchars($faq["PREVIEW_TEXT"]) ?></div>
            </div>
        <?endforeach;?>
    <?endif;?>
</div>