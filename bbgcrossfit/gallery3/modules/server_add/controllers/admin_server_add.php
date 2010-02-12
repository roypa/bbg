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
class Admin_Server_Add_Controller extends Admin_Controller {
  public function index() {
    $view = new Admin_View("admin.html");
    $view->content = new View("admin_server_add.html");
    $view->content->form = $this->_get_admin_form();
    $paths = unserialize(module::get_var("server_add", "authorized_paths", "a:0:{}"));
    $view->content->paths = array_keys($paths);

    print $view;
  }

  public function add_path() {
    access::verify_csrf();

    $form = $this->_get_admin_form();
    $paths = unserialize(module::get_var("server_add", "authorized_paths", "a:0:{}"));
    if ($form->validate()) {
      if (is_readable($form->add_path->path->value)) {
        $path = $form->add_path->path->value;
        $paths[$path] = 1;
        module::set_var("server_add", "authorized_paths", serialize($paths));
        message::success(t("Added path %path", array("path" => $path)));
        server_add::check_config($paths);
        url::redirect("admin/server_add");
      } else {
        $form->add_path->path->add_error("not_readable", 1);
      }
    }

    $view = new Admin_View("admin.html");
    $view->content = new View("admin_server_add.html");
    $view->content->form = $form;
    $view->content->paths = array_keys($paths);
    print $view;
  }

  public function remove_path() {
    access::verify_csrf();

    $path = $this->input->get("path");
    $paths = unserialize(module::get_var("server_add", "authorized_paths"));
    if (isset($paths[$path])) {
      unset($paths[$path]);
      message::success(t("Removed path %path", array("path" => $path)));
      module::set_var("server_add", "authorized_paths", serialize($paths));
      server_add::check_config($paths);
    }
    url::redirect("admin/server_add");
  }

  public function autocomplete() {
    $directories = array();
    $path_prefix = $this->input->get("q");
    foreach (glob("{$path_prefix}*") as $file) {
      if (is_dir($file)) {
        $directories[] = $file;
      }
    }

    print implode("\n", $directories);
  }

  private function _get_admin_form() {
    $form = new Forge("admin/server_add/add_path", "", "post",
                      array("id" => "gServerAddAdminForm"));
    $add_path = $form->group("add_path");
    $add_path->input("path")->label(t("Path"))->rules("required")
      ->error_messages("not_readable", t("This directory is not readable by the webserver"));
    $add_path->submit("add")->value(t("Add Path"));

    return $form;
  }
}