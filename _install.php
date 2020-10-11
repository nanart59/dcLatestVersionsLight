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
/* dcLatestVersionsLight/_install.php */

if (!defined('DC_CONTEXT_ADMIN')) {
	return null;
}

#dc min version (tested)
	$dc_min = '2.9';
#php min version (tested)
#@ignore
	$php_min = '5.6.40';

/*------please, dont change bellow---------*/
#module id
	$module_id = 'dcLatestVersionsLight';
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
	# Check Dotclear version @ignore
		if (!method_exists('dcUtils', 'versionsCompare') 
		 || dcUtils::versionsCompare(DC_VERSION, $dc_min, '<', false)) {
			throw new Exception(sprintf(
				'%s requires Dotclear %s', $module_id, $dc_min
			));
		}
	# set ws & user pref if not defined
		if (!$core->auth->user_prefs->$workspace->prefExists($pref_show)) {
			$core->auth->user_prefs->$workspace->put(
				$pref_show,
				false,
				'boolean',
				'Show Dotclear latest versions on dashboard.'
			);
		}
	#get old user prefs
		$old_pref = $core->auth->user_prefs->dashboard->prefExists('dcLatestVersionsLightItems') 
			? $core->auth->user_prefs->dashboard->get('dcLatestVersionsLightItems')
			: null;
	#get last user prefs
		$new_pref = $core->auth->user_prefs->$workspace->get($pref_show);
	#set/keep pref -- if dcLatestVersionsLightItems or dclv_show_on_dashboard
		$user_pref = ($old_pref || $new_pref) ?true :false;
		$core->auth->user_prefs->dashboard->put($pref_show, $user_pref, 'boolean', 'Show Dotclear latest versions on dashboard.');
	#del obsolete user prefs
		# suppression de la préférence
			$core->auth->user_prefs->dashboard->drop('dcLatestVersionsLightItems');
			$core->auth->user_prefs->dashboard->drop($pref_show);

	#set module version
		$core->setVersion($module_id, $new_version);

	return true;

} catch (Exception $e) {
	$core->error->add($e->getMessage());

	return false;
}
