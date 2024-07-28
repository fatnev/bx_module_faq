<section class="faq">
<?if(!empty($arResult["ITEMS"])): ?>
    <?foreach($arResult["ITEMS"] as $faq): ?>
        <details>
            <summary><?= htmlspecialchars($faq["NAME"]) ?></summary>
            <p><?= htmlspecialchars($faq["PREVIEW_TEXT"]) ?></p>
        </details>
<?endforeach;?>
<?endif;?>
</section>