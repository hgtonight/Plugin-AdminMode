<?php if (!defined('APPLICATION')) exit();
/* Copyright 2014-2017 Zachary Doll
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
* 
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
$PluginInfo['AdminMode'] = array(
  'Name' => 'Admin Mode',
  'Description' => 'Puts the site into administrator mode. Any user without the `Garden.Settings.Manage` permission will be unable to use the site.',
  'Version' => '1.1.0',
  'RequiredApplications' => array('Vanilla' => '2.3.1'),
  'RequiredTheme' => FALSE,
  'RequiredPlugins' => FALSE,
  'MobileFriendly' => TRUE,
  'HasLocale' => TRUE,
  'RegisterPermissions' => FALSE,
  'Author' => 'Zachary Doll',
  'AuthorEmail' => 'hgtonight@daklutz.com',
  'AuthorUrl' => 'http://www.daklutz.com',
  'License' => 'GPLv2'
);

class AdminMode extends Gdn_Plugin {

  /**
   * Never block the entry/signin page when in admin mode
   * @param type $Sender
   */
  public function Gdn_Dispatcher_BeforeBlockDetect_Handler($Sender) {
    $BlockExceptions =& $Sender->EventArguments['BlockExceptions'];
    unset($BlockExceptions['/^entry(\/.*)?$/']);
    $BlockExceptions['/^entry\/signin$/'] = Gdn_Dispatcher::BLOCK_NEVER;
  }

  /**
   * Show an inform message with an appropriate quick link.
   * @param Gdn_Controller $Sender
   */
  public function Base_Render_Before($Sender) {
    $Session = Gdn::Session();
    if($Session->CheckPermission('Garden.Settings.Manage')) {
      $Sender->InformMessage(Anchor(T('Plugins.AdminMode.DisableLink'), '/settings/plugins/all/AdminMode/'.$Session->TransientKey()), 'HasSprite');
    }
    else {
      $Sender->InformMessage(Anchor(T('Plugins.AdminMode.EntryLink'), 'entry/signin', 'Popup'), 'HasSprite');
    }
  }

  /**
   * Save the update config item when the plugin is enabled
   */
  public function Setup() {
    SaveToConfig('Garden.UpdateMode', TRUE);
  }

  /**
   * Remove the update config item when the plugin is disabled
   */
  public function OnDisable() {
    SaveToConfig('Garden.UpdateMode', FALSE, array('RemoveEmpty' => TRUE));
  }
}
