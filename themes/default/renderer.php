<?php
/**********************************************************************
	Copyright (C) NotrinosERP.
	Released under the terms of the GNU General Public License, GPL, 
	as published by the Free Software Foundation, either version 3 
	of the License, or (at your option) any later version.
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
	See the License here <http://www.gnu.org/licenses/gpl-3.0.html>.
***********************************************************************/

class renderer {
	function menu_icon($menu) {
		global $SysPrefs;

		if ($SysPrefs->show_menu_category_icons) {
			if($menu == MENU_TRANSACTION)
				$ic = 'fas fa-exchange-alt';
			elseif($menu == MENU_SYSTEM)
				$ic = 'fas fa-database';
			elseif($menu == MENU_UPDATE)
				$ic = 'fas fa-sync-alt';
			elseif($menu == MENU_INQUIRY)
				$ic = 'fas fa-search';
			elseif($menu == MENU_ENTRY)
				$ic = 'fas fa-folder-plus';
			elseif($menu == MENU_REPORT)
				$ic = 'far fa-file-pdf';
			elseif($menu == MENU_MAINTENANCE)
				$ic = 'fas fa-edit';
			elseif($menu == MENU_SETTINGS)
				$ic = 'far fa-list-alt';
			else
				$ic = 'fas fa-caret-square-right';
		}
		else	
			$ic = 'fas fa-caret-square-right';
		return "<i class='".$ic."'></i>";
	}

	function wa_header() {
		page(_($help_context = 'Main Menu'), false, true);
	}

	function wa_footer() {
		end_page(false, true);
	}

	function menu_header($title, $no_menu, $is_index) {
		global $path_to_root, $SysPrefs, $db_connections;

		add_js_file('dynamic-menu.js');
		add_css_file($path_to_root.'/libraries/dynamic-menu.css');
		send_scripts();
		send_css();

		echo "<table class='callout_main' border='0' cellpadding='0' cellspacing='0'>\n";
		echo "<tr>\n";
		echo "<td colspan='2' rowspan='2'>\n";

		echo "<table class='main_page' border='0' cellpadding='0' cellspacing='0'>\n";
		echo "<tr>\n";
		echo "<td>\n";
		echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";
		echo "<tr>\n";
		echo "<td class='quick_menu'>\n"; // tabs

		$indicator = $path_to_root."/themes/".user_theme().'/images/ajax-loader.gif';
		if (!$no_menu) {

			$app_icons = array('orders'=>'fas fa-tags','AP'=>'fas fa-shopping-cart','stock'=>'fas fa-warehouse','manuf'=>'fas fa-industry','assets'=>'fas fa-building','proj'=>'fas fa-map-marked-alt','GL'=>'fas fa-book','FrontHrm'=>'fas fa-users','hrm'=>'fas fa-users','extendedhrm'=>'fas fa-users','school'=>'fas fa-graduation-cap', 'kanban' => 'fas fa-tasks', 'pos'=>'fas fa-shopping-basket', 'grm'=>'fas fa-commenting-o', 'trade_finance'=>'fas fa-money', 'weigh_bridge'=>'fas fa-balance-scale', 'additional_fields'=>'fas fa-plus-square', 'booking'=>'fas fa-check-square-o', 'hospital'=>'fas fa-hospital-o', 'Projects'=>'fas fa-check-square-o', 'system'=>'fas fa-cog');
			$applications = $_SESSION['App']->applications;
			$sel_app = $_SESSION['sel_app'];
			echo "<table cellpadding='0' cellspacing='0' width='100%'><tr><td>";
			echo "<div class='tabs collapsible-nav'><center class='collapsible-menu'>";
			foreach($applications as $app) {
				if ($_SESSION['wa_current_user']->check_application_access($app)) {
					$acc = access_string($app->name);
					$ap_title = str_replace(array('<u>','</u>'), '', $acc[0]);
					if(empty($app_icons[$app->id]))
						$app_icons[$app->id] = 'fas fa-cube';
					echo "<a class='".($sel_app == $app->id ? 'selected' : 'menu_tab')."' href='".$path_to_root."/index.php?application=".$app->id."' title='".$ap_title."' $acc[1]><i class='".$app_icons[$app->id]." nav-icon'></i><span class='nav-text'>".$acc[0]."</span></a>";
				}
			}
			echo "</center>";
			echo "<button hidden>"._('MORE').'&#9662;'."</button><div class='hidden-menu-links hidden'></div>";
			echo "</div>";
			echo "</td></tr></table>";
			// top status bar
			$rimg = "<i class='fas fa-tachometer-alt'></i>&nbsp;";
			$pimg = "<i class='fas fa-cogs'></i>&nbsp;";
			$limg = "<i class='fas fa-key'></i>&nbsp;";
			$himg = "<i class='fas fa-question-circle'></i>&nbsp;";
			$img = "<i class='fas fa-sign-out-alt'></i>&nbsp;";

			echo "<table class='logoutBar'>";
			echo "<tr><td class='headingtext3'>".$db_connections[user_company()]['name']." | ".$_SERVER['SERVER_NAME']." | ".$_SESSION['wa_current_user']->name."</td>";
			echo "<td class='logoutBarRight'><img id='ajaxmark' src='".$indicator."' align='center' style='visibility:hidden;' alt='ajaxmark'></td>";
			echo "<td class='logoutBarRight'><a href='".$path_to_root."/admin/dashboard.php?sel_app=$sel_app'>".$rimg.'<span>'._('Dashboard')."</span></a>\n";
			echo "<a class='shortcut' href='".$path_to_root."/admin/display_prefs.php?'>".$pimg.'<span>'._('Preferences')."</span></a>\n";
			echo " <a class='shortcut' href='".$path_to_root."/admin/change_current_user_password.php?selected_id=".$_SESSION['wa_current_user']->username."'>".$limg.'<span>'._('Change password')."</span></a>\n";

			if ($SysPrefs->help_base_url != null)
				echo "<a target = '_blank' onclick=".'"'."javascript:openWindow(this.href,this.target); return false;".'" '."href='".help_url()."'>".$himg.'<span>'._('Help')."</span></a>";
				
			echo "<a class='shortcut' href='".$path_to_root."/access/logout.php?'>".$img.'<span>'._('Logout')."</span></a>";
			echo "</td></tr><tr><td colspan=3>";
			echo "</td></tr></table>";
		}
		echo "</td></tr></table>";

		if ($no_menu)	// ajax indicator for installer and popups
			echo "<center><table class='tablestyle_noborder'><tr><td><img id='ajaxmark' src='".$indicator."' align='center' style='visibility:hidden;' alt='ajaxmark'></td></tr></table></center>";
		elseif ($title && !$is_index)
			echo "<center><table id='title'><tr><td width='100%' class='titletext'>".$title."</td><td align=right>".(user_hints() ? "<span id='hints'></span>" : '')."</td></tr></table></center>";
	}

	function menu_footer($no_menu, $is_index) {
		global $version, $path_to_root, $Pagehelp, $Ajax, $SysPrefs;

		include_once($path_to_root . '/includes/date_functions.inc');

		echo "</td></tr></table>\n"; // 'main_page'
		if ($no_menu == false) { // bottom status line
			if ($is_index)
				echo "<table class='bottomBar'>\n";
			else
				echo "<table class='bottomBar2'>\n";
			echo "<tr>";
			if (isset($_SESSION['wa_current_user'])) {
				$phelp = implode('; ', $Pagehelp);
				echo "<td class='bottomBarCell'>" . Today() . " | " . Now() . "</td>\n";
				$Ajax->addUpdate(true, 'hotkeyshelp', $phelp);
				echo "<td id='hotkeyshelp'>".$phelp."</td>";
			}
			echo "</tr></table>\n";
		}
		echo "</td></tr> </table>\n"; // 'callout_main'
		if ($no_menu == false) {
			echo "<table align='center' id='footer'>\n";
			echo "<tr>\n";
			echo "<td align='center' class='footer'><a target='_blank' href='".$SysPrefs->power_url."' tabindex='-1'><font color='#555555'>".$SysPrefs->app_title
				.' '.$version.' - ' . _('Theme:') . ' ' . user_theme().show_users_online()."</font></a></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "<td align='center' class='footer'><a target='_blank' href='".$SysPrefs->power_url
				."' tabindex='-1'><font color='#0080c0'>".$SysPrefs->power_by."</font></a></td>\n";
			echo "</tr>\n";
			if ($SysPrefs->allow_demo_mode) {
				echo "<tr>\n";
				echo "</tr>\n";
			}
			echo "</table><br><br>\n";
		}
	}

	function display_applications(&$waapp) {
		global $path_to_root;

		$selected_app = $waapp->get_selected_application();
		if (!$_SESSION['wa_current_user']->check_application_access($selected_app))
			return;

		if (method_exists($selected_app, 'render_index')) {
			$selected_app->render_index();
			return;
		}

		echo "<table class='menu_table' width='100%' cellpadding='0' cellspacing='0'>";
		foreach ($selected_app->modules as $module) {
			if (!$_SESSION['wa_current_user']->check_module_access($module))
				continue;
			// image
			echo "<tr>";
			// values
			echo "<td valign='top' class='menu_group'>";
			echo "<table border=0 width='100%'>";
			echo "<tr><td class='menu_group'>";
			echo $module->name;
			echo "</td></tr><tr>";
			echo "<td class='menu_group_items'>";

			foreach ($module->lappfunctions as $appfunction) {
				$img = $this->menu_icon($appfunction->category);
				if ($appfunction->label == '')
					echo "&nbsp;<br>";
				elseif ($_SESSION['wa_current_user']->can_access_page($appfunction->access)) 
					echo menu_link($appfunction->link, $img.$appfunction->label)."<br>\n";
				elseif (!$_SESSION['wa_current_user']->hide_inaccessible_menu_items())
					echo '<span class="inactive">'.access_string($img.$appfunction->label, true)."</span><br>\n";
			}
			echo '</td>';
			if (sizeof($module->rappfunctions) > 0) {
				echo "<td width='50%' class='menu_group_items'>";
				foreach ($module->rappfunctions as $appfunction) {
					$img = $this->menu_icon($appfunction->category);
					if ($appfunction->label == '')
						echo "&nbsp;<br>";
					elseif ($_SESSION['wa_current_user']->can_access_page($appfunction->access)) 
						echo menu_link($appfunction->link, $img.$appfunction->label)."<br>\n";
					elseif (!$_SESSION['wa_current_user']->hide_inaccessible_menu_items())
						echo '<span class="inactive">'.access_string($img.$appfunction->label, true)."</span><br>\n";
				}
				echo "</td>";
			}

			echo "</tr></table></td></tr>";
		}
		echo "</table>";
	}
}
