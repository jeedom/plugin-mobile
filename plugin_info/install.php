<?php
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */
require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

function mobile_install()
{
	//config::save('displayMobilePanel',1, 'mobile');
  
	/* Create folder for notifications */  
	$pathNotifications = dirname(__FILE__) . '/../core/data/notifications/';
	if(!is_dir($pathNotifications)){
		mkdir($pathNotifications, 0775, true);
	}

	$mobiles = eqLogic::byType('mobile');
	foreach($mobiles as $mobile){
		/* Delete mobile with bad logicalId */
		if ($mobile->getLogicalId() == null || $mobile->getLogicalId() == "") {
			$mobile->remove();
			continue;
		}     
		/* Set menu by defaut if no exist */
		$customMenu  = $mobile->getConfiguration('menuCustomArray');
		if(empty($customMenu)){
			$menuCustomArray = mobile::getMenuDefaultV2();
			$mobile->setConfiguration('nbIcones', count($menuCustomArray));
			$mobile->setConfiguration('defaultIdMobile', $mobile->getId());
			$mobile->setConfiguration('menuCustomArray', $menuCustomArray);
			$mobile->save();
		}
	}

	/* Delete old "menuCustom_" and "NoCut" save into config of plugin */
	foreach(config::searchKey('menuCustom_', 'mobile') as $configMenuCustom) {
		config::remove($configMenuCustom['key'], 'mobile');
	}
	foreach(config::searchKey('NoCut', 'mobile') as $iconNoCut) {
		config::remove($iconNoCut['key'], 'mobile');
	}
  
	/* Delete old infos save into config of plugin */
	config::remove('previousMenus', 'mobile');
	config::remove('pluginPanelOutMobile', 'mobile');
  	config::remove('checkdefaultID', 'mobile');
  
	/* Delete old files of plugin */
	$oldFiles = [dirname(__FILE__) . '/../desktop/css/panel.css', 
		dirname(__FILE__) . '/../desktop/php/panelMenuCustom.php',
		dirname(__FILE__) . '/../desktop/js/panelMenuCustom.js',
		dirname(__FILE__) . '/../desktop/modal/health.php',
		dirname(__FILE__) . '/../desktop/modal/modal.previousMenus.php',
		dirname(__FILE__) . '/../desktop/modal/plugin.php',
		dirname(__FILE__) . '/../desktop/modal/piece.php',
		dirname(__FILE__) . '/../desktop/modal/scenario.php',
		dirname(__FILE__) . '/../desktop/modal/info_app.mobile.php',
		dirname(__FILE__) . '/../desktop/modal/plugin.mobile.php',
		dirname(__FILE__) . '/../desktop/modal/object.mobile.php',
		dirname(__FILE__) . '/../desktop/modal/scenario.mobile.php',
		dirname(__FILE__) . '/../desktop/modal/update.mobile.php',
		dirname(__FILE__) . '/../desktop/modal/firstPage.php',
		dirname(__FILE__) . '/../desktop/modal/secPage.php',
		dirname(__FILE__) . '/../desktop/modal/thirdPage.php',
		dirname(__FILE__) . '/../desktop/modal/fourPage.php',
		dirname(__FILE__) . '/../desktop/modal/fivePage.php',
		dirname(__FILE__) . '/../desktop/modal/fiveModal.php',
		dirname(__FILE__) . '/../desktop/modal/sixPage.php',
		dirname(__FILE__) . '/../desktop/modal/wizard.php'];
	foreach ($oldFiles as $oldFile) {
		if (file_exists($oldFile)) {
			shell_exec('rm ' . $oldFile);
		} 		
	}

	/* Generate ApiKey if no exist */
	jeedom::getApiKey('mobile');
  
	
}



function mobile_update()
{
	//	config::save('displayMobilePanel',1, 'mobile');
  
	/* Create folder for notifications */
	$pathNotifications = dirname(__FILE__) . '/../core/data/notifications/';
	if(!is_dir($pathNotifications)){
		mkdir($pathNotifications, 0775, true);
	}
	
	$mobiles = eqLogic::byType('mobile');
	foreach($mobiles as $mobile){
		/* Delete mobile with bad logicalId */
		if ($mobile->getLogicalId() == null || $mobile->getLogicalId() == "") {
			$mobile->remove();
			continue;
		}     
		/* Set menu by defaut if no exist */
		$customMenu  = $mobile->getConfiguration('menuCustomArray');
		if(empty($customMenu)){
			$menuCustomArray = mobile::getMenuDefaultV2();
			$mobile->setConfiguration('nbIcones', count($menuCustomArray));
			$mobile->setConfiguration('defaultIdMobile', $mobile->getId());
			$mobile->setConfiguration('menuCustomArray', $menuCustomArray);
			$mobile->save();
		}
	}
  
	/* Delete old "menuCustom_" and "NoCut" save into config of plugin */
	foreach(config::searchKey('menuCustom_', 'mobile') as $configMenuCustom) {
		config::remove($configMenuCustom['key'], 'mobile');
	}
	foreach(config::searchKey('NoCut', 'mobile') as $iconNoCut) {
		config::remove($iconNoCut['key'], 'mobile');
	}
  
	/* Delete old infos save into config of plugin */
	config::remove('previousMenus', 'mobile');
	config::remove('pluginPanelOutMobile', 'mobile');
  	config::remove('checkdefaultID', 'mobile');
  
	/* Delete old files of plugin */
	$oldFiles = [dirname(__FILE__) . '/../desktop/css/panel.css', 
		dirname(__FILE__) . '/../desktop/php/panelMenuCustom.php',
		dirname(__FILE__) . '/../desktop/js/panelMenuCustom.js',
		dirname(__FILE__) . '/../desktop/modal/health.php',
		dirname(__FILE__) . '/../desktop/modal/modal.previousMenus.php',
		dirname(__FILE__) . '/../desktop/modal/plugin.php',
		dirname(__FILE__) . '/../desktop/modal/piece.php',
		dirname(__FILE__) . '/../desktop/modal/scenario.php',
		dirname(__FILE__) . '/../desktop/modal/info_app.mobile.php',
		dirname(__FILE__) . '/../desktop/modal/plugin.mobile.php',
		dirname(__FILE__) . '/../desktop/modal/object.mobile.php',
		dirname(__FILE__) . '/../desktop/modal/scenario.mobile.php',
		dirname(__FILE__) . '/../desktop/modal/update.mobile.php',
		dirname(__FILE__) . '/../desktop/modal/firstPage.php',
		dirname(__FILE__) . '/../desktop/modal/secPage.php',
		dirname(__FILE__) . '/../desktop/modal/thirdPage.php',
		dirname(__FILE__) . '/../desktop/modal/fourPage.php',
		dirname(__FILE__) . '/../desktop/modal/fivePage.php',
		dirname(__FILE__) . '/../desktop/modal/fiveModal.php',
		dirname(__FILE__) . '/../desktop/modal/sixPage.php',
		dirname(__FILE__) . '/../desktop/modal/wizard.php'];
	foreach ($oldFiles as $oldFile) {
		if (file_exists($oldFile)) {
			shell_exec('rm ' . $oldFile);
		} 		
	}
  
	/* Generate ApiKey if no exist */
	jeedom::getApiKey('mobile');
  
	/* Make template in json format */
	/* V1 ?
	mobile::makeTemplateJson();
	*/
}
