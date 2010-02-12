<?php defined("SYSPATH") or die("No direct script access.");
/**
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2009 Bharat Mediratta
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street - Fifth Floor, Boston, MA  02110-1301, USA.
 */
class task_Core {
  /**
   * Get all available tasks
   */
  static function get_definitions() {
    $tasks = array();
    foreach (module::active() as $module) {
      $class_name = "{$module->name}_task";
      if (method_exists($class_name, "available_tasks")) {
        foreach (call_user_func(array($class_name, "available_tasks")) as $task) {
          $tasks[$task->callback] = $task;
        }
      }
    }

    return $tasks;
  }

  static function create($task_def, $context) {
    $task = ORM::factory("task");
    $task->callback = $task_def->callback;
    $task->name = $task_def->name;
    $task->percent_complete = 0;
    $task->status = "";
    $task->state = "started";
    $task->owner_id = user::active()->id;
    $task->context = serialize($context);
    $task->save();

    return $task;
  }

  static function cancel($task_id) {
    $task = ORM::factory("task", $task_id);
    if (!$task->loaded) {
      throw new Exception("@todo MISSING_TASK");
    }
    $task->done = 1;
    $task->state = "cancelled";
    $task->log(t("Task %task_name cancelled (task id %task_id)",
                 array("task_name" => $task->name, "task_id" => $task->id)));
    $task->save();

    return $task;
  }

  static function remove($task_id) {
    $task = ORM::factory("task", $task_id);
    if ($task->loaded) {
      $task->delete();
    }
  }

  static function run($task_id) {
    $task = ORM::factory("task", $task_id);
    if (!$task->loaded) {
      throw new Exception("@todo MISSING_TASK");
    }

    try {
      $task->state = "running";
      call_user_func_array($task->callback, array(&$task));
      if ($task->done) {
        $task->log($task->status);
      }
      $task->save();
    } catch (Exception $e) {
      Kohana::log("error", $e->__toString());
      $task->log($e->__toString());
      $task->state = "error";
      $task->done = true;
      $task->status = substr($e->getMessage(), 0, 255);
      $task->save();
    }

    return $task;
  }
}