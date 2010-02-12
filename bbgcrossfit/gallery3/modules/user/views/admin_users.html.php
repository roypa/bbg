<?php defined("SYSPATH") or die("No direct script access.") ?>
<script type="text/javascript">
  var add_user_to_group_url = "<?= url::site("admin/users/add_user_to_group/__USERID__/__GROUPID__?csrf=$csrf") ?>";
  $(document).ready(function(){
    $("#gUserAdminList .core-info").draggable({
      helper: "clone"
    });
    $("#gGroupAdmin .gGroup").droppable({
      accept: ".core-info",
      hoverClass: "gSelected",
      drop: function(ev, ui) {
        var user_id = $(ui.draggable).attr("id").replace("user-", "");
        var group_id = $(this).attr("id").replace("group-", "");
        $.get(add_user_to_group_url.replace("__USERID__", user_id).replace("__GROUPID__", group_id),
              {},
              function() {
                reload_group(group_id);
              });
      }
    });
    $("#group-1").droppable("destroy");
    $("#group-2").droppable("destroy");
  });

  var reload_group = function(group_id) {
    var reload_group_url = "<?= url::site("admin/users/group/__GROUPID__") ?>";
    $.get(reload_group_url.replace("__GROUPID__", group_id),
          {},
          function(data) {
            $("#group-" + group_id).html(data);
            $("#group-" + group_id + " .gDialogLink").gallery_dialog();
          });
  }

  var remove_user = function(user_id, group_id) {
    var remove_user_url = "<?= url::site("admin/users/remove_user_from_group/__USERID__/__GROUPID__?csrf=$csrf") ?>";
    $.get(remove_user_url.replace("__USERID__", user_id).replace("__GROUPID__", group_id),
          {},
          function() {
            reload_group(group_id);
          });
  }
</script>
<div class="gBlock">
  <a href="<?= url::site("admin/users/add_user_form") ?>"
      class="gDialogLink gButtonLink right ui-icon-left ui-state-default ui-corner-all"
      title="<?= t("Create a new user")->for_html_attr() ?>">
    <span class="ui-icon ui-icon-circle-plus"></span>
    <?= t("Add a new user") ?>
  </a>

  <h2>
    <?= t("User Admin") ?>
  </h2>

  <div class="gBlockContent">
    <table id="gUserAdminList">
      <tr>
        <th><?= t("Username") ?></th>
        <th><?= t("Full name") ?></th>
        <th><?= t("Email") ?></th>
        <th><?= t("Last login") ?></th>
        <th><?= t("Actions") ?></th>
      </tr>

      <? foreach ($users as $i => $user): ?>
      <tr id="gUser-<?= $user->id ?>" class="<?= text::alternate("gOddRow", "gEvenRow") ?> user <?= $user->admin ? "admin" : "" ?>">
        <td id="user-<?= $user->id ?>" class="core-info gDraggable">
          <img src="<?= $user->avatar_url(20, $theme->url("images/avatar.jpg", true)) ?>"
               title="<?= t("Drag user onto group below to add as a new member")->for_html_attr() ?>"
               alt="<?= html::clean_attribute($user->name) ?>"
               width="20"
               height="20" />
          <?= html::clean($user->name) ?>
        </td>
        <td>
          <?= html::clean($user->full_name) ?>
        </td>
        <td>
          <?= html::clean($user->email) ?>
        </td>
        <td>
          <?= ($user->last_login == 0) ? "" : gallery::date($user->last_login) ?>
        </td>
        <td class="gActions">
          <a href="<?= url::site("admin/users/edit_user_form/$user->id") ?>"
              open_text="<?= t("close") ?>"
              class="gPanelLink gButtonLink ui-state-default ui-corner-all ui-icon-left">
            <span class="ui-icon ui-icon-pencil"></span><span class="gButtonText"><?= t("edit") ?></span></a>
          <? if (user::active()->id != $user->id && !$user->guest): ?>
          <a href="<?= url::site("admin/users/delete_user_form/$user->id") ?>"
              class="gDialogLink gButtonLink ui-state-default ui-corner-all ui-icon-left">
            <span class="ui-icon ui-icon-trash"></span><?= t("delete") ?></a>
          <? else: ?>
          <span title="<?= t("This user cannot be deleted")->for_html_attr() ?>"
              class="gButtonLink ui-state-disabled ui-corner-all ui-icon-left">
            <span class="ui-icon ui-icon-trash"></span><?= t("delete") ?></span>
          <? endif ?>
        </td>
      </tr>
      <? endforeach ?>
    </table>
  </div>
</div>

<div id="gGroupAdmin" class="gBlock">
  <a href="<?= url::site("admin/users/add_group_form") ?>"
      class="gDialogLink gButtonLink right ui-icon-left ui-state-default ui-corner-all"
      title="<?= t("Create a new group")->for_html_attr() ?>">
    <span class="ui-icon ui-icon-circle-plus"></span>
    <?= t("Add a new group") ?>
  </a>

  <h2>
    <?= t("Group Admin") ?>
  </h2>

  <div class="gBlockContent">
    <ul>
      <? foreach ($groups as $i => $group): ?>
      <li id="group-<?= $group->id ?>" class="gGroup <?= ($group->special ? "gDefaultGroup" : "") ?>" />
        <? $v = new View("admin_users_group.html"); $v->group = $group; ?>
        <?= $v ?>
      </li>
      <? endforeach ?>
    </ul>
  </div>
</div>
