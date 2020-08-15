<?php
/**
 * Plugin Name: Bulk Update Blocks
 * Plugin Script: bulk-update-blocks.php
 * Plugin URI: https://bdwm.be
 * Description: Bulk Update Blocks. Insert/delete a block in a selection of posts or find and replace all occurences of a single block troughout your website.
 * Version: 0.1
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Author: Jules Colle
 * Author URI: https://bdwm.be
 * Text Domain: bulk-update-blocks
 */

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA.
 */
defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

include 'common.php';
include 'insert-blocks.php';
include 'delete-block.php';
include 'replace-block.php';
include 'contains-block.php';
include 'ajax.php';
include 'admin-interface.php';
