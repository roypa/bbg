<?php defined("SYSPATH") or die("No direct script access.") ?>
<script>
  var TAG_RENAME_URL = <?= html::js_string(url::site("admin/tags/rename/__ID__")) ?>;
  $("document").ready(function() {
    // using JS for adding link titles to avoid running t() for each tag
    $("#gTagAdmin .tag-name").attr("title", <?= t("Click to edit this tag")->for_js() ?>);
    $("#gTagAdmin .delete-link").attr("title", $(".delete-link:first span").html());

    // In-place editing for tag admin
    $(".gEditable").bind("click", editInPlace);
  });
  // make some values available within tag.js
  var csrf_token = "<?= $csrf ?>";
  var save_i18n = <?= html::js_string(t("save")->for_html_attr()) ?>;
  var cancel_i18n = <?= html::js_string(t("cancel")->for_html_attr()) ?>;
</script>
<div class="gBlock">
  <h2>
    <?= t("Tag Admin") ?>
  </h2>

  <? $tags_per_column = $tags->count()/5 ?>
  <? $column_tag_count = 0 ?>

  <table id="gTagAdmin" class="gBlockContent">
    <caption class="understate">
      <?= t2("There is one tag", "There are %count tags", $tags->count()) ?>
    </caption>
    <tr>
      <td>
        <? foreach ($tags as $i => $tag): ?>
          <? $current_letter = strtoupper(mb_substr($tag->name, 0, 1)) ?>

          <? if ($i == 0): /* first letter */ ?>
            <strong><?= html::clean($current_letter) ?></strong>
            <ul>
          <? elseif ($last_letter != $current_letter): /* new letter */ ?>
            <? if ($column_tag_count > $tags_per_column): /* new column */ ?>
               </td>
              <td>
              <? $column_tag_count = 0 ?>
            <? endif ?>

            </ul>
            <strong><?= html::clean($current_letter) ?></strong>
            <ul>
          <? endif ?>

          <li>
            <span id="gTag-<?= $tag->id ?>" class="gEditable tag-name"><?= html::clean($tag->name) ?></span>
            <span class="understate">(<?= $tag->count ?>)</span>
            <a href="<?= url::site("admin/tags/form_delete/$tag->id") ?>"
               class="gDialogLink delete-link gButtonLink">
                <span class="ui-icon ui-icon-trash"><?= t("Delete this tag") ?></span></a>
          </li>

          <? $column_tag_count++ ?>
          <? $last_letter = $current_letter ?>
        <? endforeach /* $tags */ ?>
        </ul>
      </td>
    </tr>
  </table>
</div>
