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
	/* function launched when activating the plugin or when installing the plugin */
	log::add('mobile', 'debug', ':fg-warning:Launch function mobile_install() :/fg:');
  
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
  
	/* Delete the old files of plugin */
	$oldFiles = ['/../desktop/css/panel.css', '/../desktop/php/panelMenuCustom.php',
		'/../desktop/php/modalConfigPlugin.php', '/../desktop/js/panelMenuCustom.js',
		'/../desktop/modal/health.php', '/../desktop/modal/modal.previousMenus.php',
		'/../desktop/modal/plugin.php', '/../desktop/modal/piece.php',
		'/../desktop/modal/scenario.php', '/../desktop/modal/info_app.mobile.php',
		'/../desktop/modal/plugin.mobile.php', '/../desktop/modal/object.mobile.php',
		'/../desktop/modal/scenario.mobile.php', '/../desktop/modal/update.mobile.php',
		'/../desktop/modal/firstPage.php', '/../desktop/modal/secPage.php',
		'/../desktop/modal/thirdPage.php', '/../desktop/modal/fourPage.php',
		'/../desktop/modal/fivePage.php', '/../desktop/modal/fiveModal.php',
		'/../desktop/modal/sixPage.php', '/../desktop/modal/wizard.php'];
	foreach ($oldFiles as $oldFile) {
		if (file_exists(dirname(__FILE__) . $oldFile)) {
			shell_exec('rm ' . dirname(__FILE__) . $oldFile);
		} 		
	}

	/* Delete the old images in "core" folder */
	$oldCoreImgs = ['Button_Dashboard_icon@3x.png',
		'Button_Design_icon@3x.png', 'Button_Synthese_icon@3x.png',
		'Button_URL_icon@3x.png', 'IMG_0738.PNG',
		'android.png', 'ios.png', 'v22methods.jpeg',
		'v2ActualBoxFlouted.jpeg', 'v2AddZone.jpeg',
		'v2ConnectBox.jpeg', 'v2FullMenu.jpeg',
		'v2MenuBoxs.PNG', 'v2MenuBoxs.jpeg',
		'v2ModalMenuCustom.png', 'v2ModalQrCode.png',
		'v2ModifyBigRadius.jpeg', 'v2ModifyLittleRadius.jpeg',
		'v2QRCodeConnect.PNG', 'v2ZoneInactive.jpeg',
		'v2connectMarket.jpeg', 'v2firstConnect.jpeg',
		'v2floutedBoxs.png', 'v2greenBtnAdd.PNG'];  
	foreach ($oldCoreImgs as $oldCoreImg) {
		if (file_exists(dirname(__FILE__) . '/../core/img/' . $oldCoreImg)) {
			shell_exec('rm ' . dirname(__FILE__) . '/../core/img/' . $oldCoreImg);
		} 	
	}

	/* Generate ApiKey if no exist */
	jeedom::getApiKey('mobile');
}

function mobile_update()
{
	/* function launched when updating plugin */
	log::add('mobile', 'debug', ':fg-warning:Launch function mobile_update() :/fg: ──────────');
  
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
	$oldFiles = ['/../desktop/css/panel.css', '/../desktop/php/panelMenuCustom.php',
		'/../desktop/php/modalConfigPlugin.php', '/../desktop/js/panelMenuCustom.js',
		'/../desktop/modal/health.php', '/../desktop/modal/modal.previousMenus.php',
		'/../desktop/modal/plugin.php', '/../desktop/modal/piece.php',
		'/../desktop/modal/scenario.php', '/../desktop/modal/info_app.mobile.php',
		'/../desktop/modal/plugin.mobile.php', '/../desktop/modal/object.mobile.php',
		'/../desktop/modal/scenario.mobile.php', '/../desktop/modal/update.mobile.php',
		'/../desktop/modal/firstPage.php', '/../desktop/modal/secPage.php',
		'/../desktop/modal/thirdPage.php', '/../desktop/modal/fourPage.php',
		'/../desktop/modal/fivePage.php', '/../desktop/modal/fiveModal.php',
		'/../desktop/modal/sixPage.php', '/../desktop/modal/wizard.php'];
	foreach ($oldFiles as $oldFile) {
		if (file_exists(dirname(__FILE__) . $oldFile)) {
			shell_exec('rm ' . dirname(__FILE__) . $oldFile);
		} 		
	}

	/* Delete the old images in "core" folder */
	$oldCoreImgs = ['Button_Dashboard_icon@3x.png',
		'Button_Design_icon@3x.png', 'Button_Synthese_icon@3x.png',
		'Button_URL_icon@3x.png', 'IMG_0738.PNG',
		'android.png', 'ios.png', 'v22methods.jpeg',
		'v2ActualBoxFlouted.jpeg', 'v2AddZone.jpeg',
		'v2ConnectBox.jpeg', 'v2FullMenu.jpeg',
		'v2MenuBoxs.PNG', 'v2MenuBoxs.jpeg',
		'v2ModalMenuCustom.png', 'v2ModalQrCode.png',
		'v2ModifyBigRadius.jpeg', 'v2ModifyLittleRadius.jpeg',
		'v2QRCodeConnect.PNG', 'v2ZoneInactive.jpeg',
		'v2connectMarket.jpeg', 'v2firstConnect.jpeg',
		'v2floutedBoxs.png', 'v2greenBtnAdd.PNG'];
  
	foreach ($oldCoreImgs as $oldCoreImg) {
		if (file_exists(dirname(__FILE__) . '/../core/img/' . $oldCoreImg)) {
			shell_exec('rm ' . dirname(__FILE__) . '/../core/img/' . $oldCoreImg);
		} 	
	}
  
	/* Generate ApiKey if no exist */
	jeedom::getApiKey('mobile');
}
