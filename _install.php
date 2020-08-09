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
/**
 * @brief dcLatestVersionsLight, a plugin for Dotclear 2
 * 
 * define plugin' install:
	- Dotclear version min 2.16 -- tested on
	- Php version min 7.4.1 	-- tested on
	- module_id					-- module title, settings & user pref names
	- module_setting			-- setting name and content
	- module_setting			-- setting name and content
	- user prefs
		. workspace 			-- workspace name
		. pref_show				-- user pref name
	- versions					-- updated version ?
	- plugin versions compare -- update ?
 */
/* dcLatestVersionsLight/_install.php */

if (!defined('DC_CONTEXT_ADMIN')) {
	return null;
}

#dc min version (tested)
	$dc_min = '2.16';
#php min version (tested)
#@ignore
	$php_min = '7.4.1';

/*------please, dont change bellow---------*/
#module id
	$module_id = 'dcLatestVersionsLight';
#module_setting
	$module_setting = [
		[	'versions_type',
			"List of Dotclear's builds",
			'stable,unstable,testing,sexy', //sexy ignore?
			'string'
		]
	];
#user prefs
	$workspace		= 'dcLatestVersionsLight';
	$pref_show		= 'dclv_show_on_dashboard';
#versions
	$new_version = $core->plugins->moduleInfo($module_id, 'version');
	$old_version = $core->getVersion($module_id);

# -- Nothing to change below --
if (version_compare($old_version, $new_version, '>=')) {
//*/
	return null;
//*/
}

try {
	# Check Dotclear version
		if (!method_exists('dcUtils', 'versionsCompare') 
		 || dcUtils::versionsCompare(DC_VERSION, $dc_min, '<', false)) {
			throw new Exception(sprintf(
				'%s requires Dotclear %s', $module_id, $dc_min
			));
		}

	# Set module settings
		$core->blog->settings->addNamespace($module_id);
		foreach($module_setting as $v) {
			$core->blog->settings->{$module_id}->put(
				$v[0], $v[2], $v[3], $v[1], true, true
			);
		}

	# Set module user prefs
		$core->auth->user_prefs->addWorkspace($module_id);
		// Default prefs
			$core->auth->user_prefs->$module_id->put($pref_prefix .$pref_show, false, 'boolean', 'Show Dotclear latest versions on dashboard.', false, true);

	#set module version
		$core->setVersion($module_id, $new_version);

	return true;

} catch (Exception $e) {
	$core->error->add($e->getMessage());

	return false;
}
