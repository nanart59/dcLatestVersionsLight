<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of dcLatestVersionsLight, a plugin for Dotclear 2.
# 
# Copyright (c) 2020 Nan'Art and contributors
# a light corrected and updated version of dcLatestVersions 2015-03-11, Jean-Christian Denis and contributors
# 
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# -- END LICENSE BLOCK ------------------------------------
/* dcLatestVersionsLight/_admin.php */

if (!defined('DC_RC_PATH')) { return; }
// Public and Admin mode

if (!defined('DC_CONTEXT_ADMIN')) { return; }
// Admin mode only

// dead but useful code, in order to have translations
__('dcLatestVersionsLight').__('Show the latest available versions of Dotclear in dashboard');

#plugin title
$module_id = 'dcLatestVersionsLight';

# Sidebar menu
$_menu['Plugins']->addItem(
	__($module_id),
	$core->adminurl->get('admin.plugin.' .$module_id),
	dcPage::getPF($module_id .'/icon.png'),
	preg_match('/' . preg_quote($core->adminurl->get('admin.plugin.' .$module_id)) . '(&.*)?$/', $_SERVER['REQUEST_URI']),
	$core->auth->check('admin', $core->blog->id)
);

/* behaviors */
$core->addBehavior('adminDashboardFavorites',array('dcLatestVersionsLightAdmin','adminDashboardFavorites'));
$core->addBehavior('adminDashboardOptionsForm',array('dcLatestVersionsLightAdmin','adminDashboardOptionsForm'));
$core->addBehavior('adminAfterDashboardOptionsUpdate',array('dcLatestVersionsLightAdmin','adminAfterDashboardOptionsUpdate'));
$core->addBehavior('adminDashboardItems',array('dcLatestVersionsLightAdmin','adminDashboardItems'));

class dcLatestVersionsLightAdmin
{
	public static	$module_id		= 'dcLatestVersionsLight';
	public static 	$workspace		= 'dcLatestVersionsLight';
	public static	$pref_show		= 'dclv_show_on_dashboard';
	public static	$version_types	= 'stable,unstable,testing,sexy'; //sexy ignore?
	public static	$versions_cache	= '/versions';

    /**
     * Favorites.
     *
     * @param    $core    <b>dcCore</b>    dcCore instance
     * @param    $favs    <b>arrayObject</b>    Array of favs
     */
	public static function adminDashboardFavorites($core, $favs)
	{
		$class = __CLASS__;
		$plugin_name = self::$module_id;
		
		$favs->register('dcLatestVersionsLight', [
			'title'		=> __('Dotclear latests versions light'),
			'url'		  => $core->adminurl->get('admin.plugin.' .$plugin_name),
			'small-icon'   => dcPage::getPF($plugin_name .'/icon.png'),
			'large-icon'   => dcPage::getPF($plugin_name .'/icon-big.png'),
			'permissions'  => 'admin',
			// 'dashboard_cb' => [$class, 'adminDashboardItems']
		]);
	}//adminDashboardFavorites

	/**
	 * User preferences form.
	 *
	 * @param	$core	<b>object</b>	dcCore instance or record
	 */
	public static function adminDashboardOptionsForm($core)
	{
		$show		= 	self::$pref_show;
		$workspace	=	self::$workspace;
		#is preference
			if (!$core->auth->user_prefs->$workspace->prefExists($show)) {
				$core->auth->user_prefs->$workspace->put(
					$show,
					false,
					'boolean'
				);
			}
		#user pref
			$show_value = $core->auth->user_prefs->$workspace->get($show);

		#some plugin infos
			#plugin name
				$p_name = self::$module_id;
			#plugin version
				$p_version = $core->plugins->moduleInfo($p_name, 'version');
			#plugin message
				$p_message = sprintf(__('%s - %s'), __($p_name), $p_version);

		echo '<div class="fieldset" id="' .$workspace .'">';
		echo '<h4>' . $p_message .'</h4>';
		echo '<p>';
			echo '<label for="' .$show .'" class="classic">';
				echo form::checkbox($show, 1, $show_value);
					echo __('Show Dotclear\'s latest versions on dashboard.');
			echo '</label>';
		echo '</p>';
		echo '</div>';
	}//adminDashboardOptionsForm

	/**
	 * User preferences form get updated form
	 *
	 * @param	$user_id	<b>string</b>	user id
	 */
	public static function adminAfterDashboardOptionsUpdate($user_id)
	{
		Global $core;

		$show		= 	self::$pref_show;
		$workspace	=	self::$workspace;
		# Get and store user's prefs for plugin options 
        try {
            // Hosting monitor options
            $core->auth->user_prefs->$workspace->put($show, !empty($_POST[$show]), 'boolean');
        } catch (Exception $e) {
            $core->error->add($e->getMessage());
        }
	}//adminAfterDashboardOptionsUpdate

	/**
	 * set show on dashboard
	 *
     * @param    $core    <b>dcCore</b>    		 dcCore instance
     * @param    $items   <b>arrayObject</b>    Dashboard items
	 */
	public static function adminDashboardItems($core, $items)
	{
		$show		= 	self::$pref_show;
		$workspace	=	self::$workspace;
		#don't show on dashboard
			if (!$core->auth->user_prefs->$workspace->get($show)) { return; }

		#get version types
			$builds = self::$version_types;
			$builds = explode(',', $builds);
		
		#get path cache/verions
			$path = self::$versions_cache;
		#module id
			$module_id = self::$module_id;

		#compose
			$title		= "%r";
			$version	= sprintf(__('Dotclear %s'), "%v");
			$text		= '<li><a href="%u" title="' .$title .'">%r</a> : ' .$version .'</li>';
			$li = array();

		foreach($builds as $build) {
			$build = strtolower(trim($build));
			if (empty($build)) {
				continue;
			}

			$updater = new dcUpdate(
				DC_UPDATE_URL,
				'dotclear',
				$build,
				DC_TPL_CACHE .$path
			);

			#no update for $build
			if (!$updater->check($build)) {
				continue;
			}

			$li[] = str_replace(
				array(
					'%r',
					'%v',
					'%u'
				),
				array(
					__('Download version ') .$build,
					$updater->getVersion(),
					$updater->getFileURL()
				),
				$text
			);
		}

		if (empty($li)) { return null; }


		//some plugin infos
		#plugin name
			$p_name = self::$module_id;
		#plugin version
			$p_version = $core->plugins->moduleInfo($p_name, 'version');

        #msg
            $ok = (version_compare(PHP_VERSION, '7.0.0') >= 0) ?__('able') :__('unable');
            $type = (version_compare(PHP_VERSION, '7.0.0') >= 0) ?__('success') :__('warning');
            $msg = sprintf(__('Your PHP version %s is %s to support the future Dotclear version 2.19.'), phpversion(), $ok);

		# Display
        $items[] = new ArrayObject([
		'<div class="box small" id="udclatestversionsitems">'.
		'<h3>' .'<img src="' . dcPage::getPF("$module_id/icon-small.png") . '" alt="" /> ' .html::escapeHTML(__("Dotclear latests versions - light")) .'</h3>'.
		'<ul>'.implode('', $li).'</ul>'.
        '<p class="' .$type .'">' .$msg .'</p>'.
		'</div>'
 ]);
	}//adminDashboardItems

}//dcLatestVersionsLightAdmin
