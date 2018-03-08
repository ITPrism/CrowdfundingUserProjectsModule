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
$doc = JFactory::getDocument();
$doc->addStyleSheet('modules/mod_crowdfundinguserprojects/css/style.css');

/**
 * @var Joomla\Registry\Registry $params
 * @var Joomla\Registry\Registry $componentParams
 * @var Prism\Money\Money $money
 * @var array $projects
 * @var Prism\Integration\Profile\ProfileInterface $socialProfile
 * @var string $imagesDirectory
 * @var int $titleLength
 * @var int $descriptionLength
 * @var bool $displayCreator
 * @var bool $displayDescription
 * @var bool $displayInfo
 * @var bool $displayReadon
 * @var bool $displaySeeProjects
 * @var string $startingDate
 * @var string $endingDate
 * @var string $dateFormat
 */
?>
<div class="cf-mod-userprojects<?php echo $moduleclassSfx; ?>">
    <div class="row">
        <div class="col-md-12">
        <?php foreach ($projects as $project) {
        $title       = JHtmlString::truncate($project->title, $titleLength);
        $description = JHtmlString::truncate($project->short_desc, $descriptionLength);

        // Route project link
        $projectLink = JRoute::_(CrowdfundingHelperRoute::getDetailsRoute($project->slug, $project->catslug));

        $image = CrowdfundingHelper::getImage($params->get('image_type', 'square'), $project, $componentParams, $imagesDirectory);

        $projectClass = 'cf-usrprjv-project-100';
        if (count($image) > 0) {
            $projectClass = 'cf-usrprjv-project-80';
        }

        // Prepare social profile.
        $profileName = '';
        if ($displayCreator) {
            $socialProfileLink = (!$socialProfile) ? null : $socialProfile->getLink();
            $profileName       = JHtml::_('crowdfunding.socialProfileLink', $socialProfileLink, $project->user_name);
        }

        // Prepare information about project funding state.
        if ($displayInfo) {
            if (!empty($project->funding_days)) {
                $fundingStartDate = new Crowdfunding\Date($project->funding_start);
                $fundingEndDate = $fundingStartDate->calculateEndDate($project->funding_days);
                $project->funding_end = $fundingEndDate->format(Prism\Constants::DATE_FORMAT_SQL_DATE);
            }
            $startingDate = JHtml::_('crowdfunding.date', $project->funding_start, $dateFormat);
            $endingDate   = JHtml::_('crowdfunding.date', $project->funding_end, $dateFormat);
        }?>
        <div>
            <?php if (count($image) > 0) { ?>
                <div class="cf-usrprjv-image">
                    <?php if ($params->get('image_link', 0)) { ?>
                    <a href="<?php echo $projectLink; ?>">
                        <?php } ?>
                        <img src="<?php echo $image['image']; ?>"
                             alt="<?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>"
                             width="<?php echo $image['width']; ?>" height="<?php echo $image['height']; ?>"/>
                        <?php if ($params->get('image_link', 0)) { ?>
                    </a>
                <?php } ?>
                </div>
            <?php } ?>
            <div class="<?php echo $projectClass; ?>">
                <h5>
                    <a href="<?php echo $projectLink; ?>">
                        <?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                </h5>
                <?php if ($displayCreator) { ?>
                    <span class="font-xxsmall">
                    <?php echo JText::sprintf('MOD_CROWDFUNDINGUSERPROJECTS_BY_S', $profileName); ?>
                    </span>
                <?php } ?>

                <?php if ($displayDescription) { ?>
                    <p class="font-small"><?php echo htmlspecialchars($description, ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php if ($displayReadon) { ?>
                        <a href="<?php echo $projectLink; ?>" rel="nofollow"><?php echo JText::_('MOD_CROWDFUNDINGUSERPROJECTS_READON'); ?></a>
                    <?php } ?>
                <?php } ?>

                <?php if ($displayInfo) { ?>
                    <p class="font-xxsmall"><?php echo JText::sprintf('MOD_CROWDFUNDINGUSERPROJECTS_STARTED_ON', $startingDate, $endingDate); ?></p>
                    <p class="font-xxsmall"><?php echo JText::sprintf('MOD_CROWDFUNDINGUSERPROJECTS_GOAL', $money->setAmount($project->goal)->formatCurrency(), $money->setAmount($project->funded)->formatCurrency()); ?></p>
                <?php } ?>
            </div>
            <div class="clearfix"></div>
        </div>
        <?php } ?>
        </div>
    </div>
    <?php if ($displaySeeProjects) { ?>
    <div class="row">
        <div class="col-md-12 center">
            <a href="<?php echo JRoute::_(CrowdfundingHelperRoute::getDiscoverRoute()); ?>" rel="nofollow" class="btn btn-primary btn-lg"><?php echo JText::_('MOD_CROWDFUNDINGUSERPROJECTS_SEE_PROJECTS'); ?></a>
        </div>
    </div>
    <?php } ?>
</div>