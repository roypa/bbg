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
class slideshow_event_Core {
  static function module_change($changes) {
    if (!module::is_active("rss") || in_array("rss", $changes->deactivate)) {
      site_status::warning(
        t("The Slideshow module requires the RSS module.  " .
          "<a href=\"%url\">Activate the RSS module now</a>",
          array("url" => html::mark_clean(url::site("admin/modules")))),
        "slideshow_needs_rss");
    } else {
      site_status::clear("slideshow_needs_rss");
    }
  }

  static function album_menu($menu, $theme) {
    $descendants_count = ORM::factory("item", $theme->item()->id)
      ->descendants_count(array("type" => "photo"));
    if ($descendants_count > 1) {
      $menu->append(Menu::factory("link")
                    ->id("slideshow")
                    ->label(t("View slideshow"))
                    ->url("javascript:PicLensLite.start(" .
                          "{maxScale:0,feedUrl:'" . self::_feed_url($theme) . "'})")
                    ->css_id("gSlideshowLink"));
    }
  }

  static function photo_menu($menu, $theme) {
    $menu->append(Menu::factory("link")
                  ->id("slideshow")
                  ->label(t("View slideshow"))
                  ->url("javascript:PicLensLite.start(" .
                        "{maxScale:0,feedUrl:'" . self::_feed_url($theme) . "'})")
                  ->css_id("gSlideshowLink"));
  }

  static function tag_menu($menu, $theme) {
    $menu->append(Menu::factory("link")
                  ->id("slideshow")
                  ->label(t("View slideshow"))
                  ->url("javascript:PicLensLite.start(" .
                        "{maxScale:0,feedUrl:'" . self::_feed_url($theme) . "'})")
                  ->css_id("gSlideshowLink"));
  }

  private static function _feed_url($theme) {
    if ($item = $theme->item()) {
      if (!$item->is_album()) {
        $item = $item->parent();
      }
      return rss::url("gallery/album/{$item->id}?page_size=100");
    } else {
      return rss::url("tag/tag/{$theme->tag()->id}?page_size=100");
    }
  }
}
