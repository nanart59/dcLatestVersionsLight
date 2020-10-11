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
 * define plugin' debug part:
	- just give some tests
	- see constant in install
 */
/* dcLatestVersionsLight/debug.php */

if (!defined('DC_CONTEXT_ADMIN')) {return;}
if(!defined('PLUG_DEBUG') || !PLUG_DEBUG) { return; }

		#user pref
			$core->auth->user_prefs->addWorkspace($workspace);
		#don't show on dashboard
			$on = $core->auth->user_prefs->$workspace->$pref_show;
		#versions types
			// $builds = 'stable,unstable,testing,sexy';
			$builds = $module_setting[0][2];
			$builds = explode(',', $builds);

#debug mode
		echo '<p class="as_h3">DEBUG mode</p>';
		#builds
			echo printR($builds, 'versions types');
		#compose
			$text = '<li><a href="%u" title="Download Dotclear version %v"> %r </a> : %v</li>';
			$li = array();
		#no versions ?
			$cache_versions = DC_TPL_CACHE.'/versions';
			$exists = file_exists($cache_versions);
			$cache_exists = $exists ?__('exists') :__('dont exists!');
			if($exists) {
				$empty = sizeof(scandir($cache_versions))<=2;
			} else {
				$error = sprintf(__('your cache versions dir %s dont exists ! Test %s.'), $cache_versions, __('force dotclear update'));
				dcPage::addErrorNotice($error);
				return;
			}
			$nb_files = isset($empty) ?sizeof(scandir($cache_versions))-2 :0;
			$cache_empty = $nb_files ?__('as files') :__('as no file!!');
			$files = $nb_files > 0 ?array_diff(scandir($cache_versions), array('..', '.')) :'';
		#infos cache
			echo '<p class="info">' .sprintf(__('Cache versions %s %s: (%s)'),$cache_versions, $cache_empty, $nb_files) .'</p>';
			if($files) { 
				echo printR($files, __('cache versions content')); 
			} else {
				$error = sprintf(__('There is no file in your cache versions dir %s. Test %s.'), $cache_versions, __('force dotclear update'));
				dcPage::addErrorNotice($error);
				return;
			}
		#witch versions type
		foreach($builds as $build) {
			$build = strtolower(trim($build));
				if (empty($build)) {
					continue;
				}
			$updater = new dcUpdate(
				DC_UPDATE_URL,
				'dotclear',
				$build,
				DC_TPL_CACHE.'/versions'
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
					$build,
					$updater->getVersion(),
					$updater->getFileURL()
				),

				$text
			);
		}//foreach

if($li) {
		# Display
		$items[] =
		'<div class="box small" id="udclatestversionsitems">'.
		'<h3>' .'<img src="' . dcPage::getPF("$module_id/icon-small.png") . '" alt="" /> ' .html::escapeHTML(__("Dotclear's latest versions")).'</h3>'.
		'<ul>'.implode('', $li).'</ul>'.
		'</div>';
}//if li
	if(isset($items)) { echo printR($items[0], __('what should be nearly displayed on dashboard')); }


	/**
	 * usage: @ignore ?
	 * Return print_r code
	 * @param	$value			<b>mixed</b> 	-- value to print_r
	 * @param	$title			<b>string</b> 	-- title data name to show, default empty
	 * 
	 * @return  $message		<b>string</b>	-- print_r or Dotclear msg notice
	 */
function printR($value, $title ='') {
		Global $core;
		#backtrace
			$call['file'] = basename(debug_backtrace()[0]['file']);
			$call['line'] = basename(debug_backtrace()[0]['line']);
		#id
			$id = $call['line'];
			$link = "<a href='#$id'>go to $id</a>" ;
		#message
			$message = '';
			$message .= "<b>$title</b>";
			$message .= ' -- ' .__('from') .': ' .$call['file'] .' | ' .$call['line'] .' ';
			$message = '<hr>' .'<p class="as_h4" id="' .$call['line'] .'">' .$message;
			$message .= print_r($value, true);
			$message .= '<hr>';

		return $message;
}//printR
