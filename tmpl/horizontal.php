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
 * @var int $limitResults
 * @var int $fundedPercent
 * @var int $daysLeft
 * @var int $raised
 */

$doc = JFactory::getDocument();
$doc->addStyleSheet('modules/mod_crowdfundinguserprojects/css/style.css');

// Calculate span.
$projectsNumber     = count($projects);
$limitResults       = ($projectsNumber < $limitResults) ? $projectsNumber : $limitResults;
$itemSpan           = ceil(12 / $limitResults);
if ($itemSpan <= 0) {
    $itemSpan = 1;
}
?>
<div class="cf-mod-userprojects<?php echo $moduleclassSfx; ?>">
    <div class="row">
        <?php foreach ($projects as $project) {
            $title       = JHtmlString::truncate($project->title, $titleLength);
            $description = JHtmlString::truncate($project->short_desc, $descriptionLength);

            $image = CrowdfundingHelper::getImage($params->get('image_type', 'square'), $project, $componentParams, $imagesDirectory);

            // Route project link
            $projectLink = JRoute::_(CrowdfundingHelperRoute::getDetailsRoute($project->slug, $project->catslug));

            // Prepare social profile.
            $profileName = '';
            if ($displayCreator) {
                $socialProfileLink = (!$socialProfile) ? null : $socialProfile->getLink();
                $profileName   = JHtml::_('crowdfunding.socialProfileLink', $socialProfileLink, $project->user_name);
            }

            // Prepare information about project funding state.
            if ($displayInfo) {
                $raised        = $money->setAmount($project->funded)->formatCurrency();

                $today = new Crowdfunding\Date();
                $daysLeft = $today->calculateDaysLeft($project->funding_days, $project->funding_start, $project->funding_end);

                $fundedPercent = Prism\Utilities\MathHelper::calculatePercentage($project->funded, $project->goal, 0);
            }
            ?>
            <div class="col-md-<?php echo $itemSpan; ?>">

                <div class="thumbnail cf-usrprjh-project">
                    <?php if (count($image) > 0) { ?>

                    <?php if ($params->get('image_link', 0)) { ?>
                    <a href="<?php echo $projectLink; ?>">
                        <?php } ?>
                        <img src="<?php echo $image['image']; ?>"
                             alt="<?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>"
                             width="<?php echo $image['width']; ?>" height="<?php echo $image['height']; ?>">
                        <?php if ($params->get('image_link', 0)) { ?>
                    </a>
                    <?php } ?>

                    <?php } ?>

                    <div class="caption">
                        <h3>
                            <a href="<?php echo $projectLink; ?>">
                                <?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </h3>

                        <?php if ($displayCreator) { ?>
                        <span class="font-xxsmall">
                            <?php echo JText::sprintf('MOD_CROWDFUNDINGUSERPROJECTS_BY_S', $profileName); ?>
                        </span>
                        <?php } ?>

                        <?php if ($displayDescription) { ?>
                        <p><?php echo htmlspecialchars($description, ENT_QUOTES, 'UTF-8'); ?>
                            <?php if ($displayReadon) { ?>
                                <a href="<?php echo $projectLink; ?>" rel="nofollow"><?php echo JText::_('MOD_CROWDFUNDINGUSERPROJECTS_READON'); ?></a>
                            <?php } ?>
                        </p>
                        <?php } ?>
                    </div>

                    <?php if ($displayInfo) { ?>
                    <div class="cf-usrprjh-caption-info absolute-bottom">
                        <?php echo JHtml::_('crowdfunding.progressbar', $fundedPercent, $daysLeft, $project->funding_type); ?>
                        <div class="row">
                            <div class="col-sm-4 hidden-xs">
                                <div class="bolder"><?php echo $fundedPercent; ?>%</div>
                                <div class="text-uppercase"><?php echo JText::_('MOD_CROWDFUNDINGUSERPROJECTS_FUNDED'); ?></div>
                            </div>
                            <div class="col-sm-4 col-xs-6">
                                <div class="bolder"><?php echo $raised; ?></div>
                                <div class="text-uppercase"><?php echo JText::_('MOD_CROWDFUNDINGUSERPROJECTS_RAISED'); ?></div>
                            </div>
                            <div class="col-sm-4 col-xs-6">
                                <div class="bolder"><?php echo $daysLeft; ?></div>
                                <div class="text-uppercase"><?php echo JText::_('MOD_CROWDFUNDINGUSERPROJECTS_DAYS_LEFT'); ?></div>
                            </div>
                        </div>
                    <?php } ?>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        <?php } ?>
    </div>
    <?php if ($displaySeeProjects) { ?>
    <div class="row">
        <div class="col-md-12 center">
            <a href="<?php echo JRoute::_(CrowdfundingHelperRoute::getDiscoverRoute()); ?>" rel="nofollow" class="btn btn-primary btn-lg"><?php echo JText::_('MOD_CROWDFUNDINGUSERPROJECTS_SEE_PROJECTS'); ?></a>
        </div>
    </div>
    <?php } ?>
</div>