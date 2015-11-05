<?php
/**
 * FeaturedUser extension - adds <featureduser> parser hook
 * to display a featuredly chosen 'featured' user and some info regarding the
 * user, such as their avatar.
 *
 * Meant to be used with the SocialProfile extension, fails without.
 * Make sure to set either $wgUserStatsTrackWeekly or $wgUserStatsTrackMonthly
 * to true in your wiki's LocalSettings.php and before doing so, be sure that
 * you have created the three necessary tables in the database:
 * user_points_archive, user_points_monthly and user_points_weekly.
 * Then add <featureduser/> tag to whichever page you want to.
 *
 * @file
 * @ingroup Extensions
 * @author Aaron Wright <aaron.wright@gmail.com>
 * @author David Pean <david.pean@gmail.com>
 * @author Jack Phoenix <jack@countervandalism.net>
 * @link https://www.mediawiki.org/wiki/Extension:FeaturedUser Documentation
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

// What to display on the output of <featureduser> tag...
$wgFeaturedUser['avatar'] = true;
$wgFeaturedUser['points'] = true;
$wgFeaturedUser['about'] = true;

// Extension credits that will show up on Special:Version
$wgExtensionCredits['parserhook'][] = array(
	'name' => 'FeaturedUser',
	'version' => '1.3',
	'author' => array( 'Aaron Wright', 'David Pean', 'Jack Phoenix' ),
	'descriptionmsg' => 'featureduser-desc',
	'url' => 'https://www.mediawiki.org/wiki/Extension:FeaturedUser',
);

// Internationalization messages
$wgMessagesDirs['FeaturedUser'] = __DIR__ . '/i18n';

$wgAutoloadClasses['FeaturedUser'] = __DIR__ . '/FeaturedUser.class.php';

$wgHooks['ParserFirstCallInit'][] = 'wfFeaturedUser';

/**
 * Set up the <featureduser> tag
 * @param $parser Object: instance of Parser (not necessarily $wgParser)
 * @return Boolean: true
 */
function wfFeaturedUser( &$parser ) {
        $parser->setHook( 'featureduser', array( 'FeaturedUser', 'getUser' ) );
        return true;
}
