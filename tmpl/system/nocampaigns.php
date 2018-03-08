<?php
/**
 * @package      Crowdfunding
 * @subpackage   Modules
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2017 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
/**
 * @var string $userName
 * @var string $linkCreateCampaign
 */
?>
<div class="cf-mod-userprojects<?php echo $moduleclassSfx; ?>">
    <div><?php echo JText::sprintf('MOD_CROWDFUNDINGUSERPLEDGES_HAS_NO_CAMPAIGN', $userName); ?></div>
    <div><?php echo JText::sprintf('MOD_CROWDFUNDINGUSERPLEDGES_CREATE_CAMPAIGN', $linkCreateCampaign); ?></div>
</div>