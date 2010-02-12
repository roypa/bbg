<?php defined("SYSPATH") or die("No direct script access.") ?>
<style>
  #gExifData {font-size: .85em;}
  .gOdd {background: #BDD2FF;}
  .gEven {background: #DFEFFC;}
</style>
<h1 style="display: none;"><?= t("Photo Detail") ?></h1>
<div id="gExifData">
  <table class="gMetadata" >
    <tbody>
      <? for ($i = 0; $i < count($details); $i++): ?>
      <tr>
         <td class="gEven">
         <?= $details[$i]["caption"] ?>
         </td>
         <td class="gOdd">
         <?= html::clean($details[$i]["value"]) ?>
         </td>
         <? if (!empty($details[++$i])): ?>
           <td class="gEven">
           <?= $details[$i]["caption"] ?>
           </td>
           <td class="gOdd">
           <?= html::clean($details[$i]["value"]) ?>
           </td>
         <? else: ?>
           <td class="gEven"></td><td class="gOdd"></td>
         <? endif ?>
       </tr>
       <? endfor ?>
    </tbody>
  </table>
</div>
