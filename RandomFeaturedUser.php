<?php
/**
 * RandomFeaturedUser extension - adds <randomfeatureduser> parser hook
 * to display a randomly chosen 'featured' user and some info regarding the
 * user, such as their avatar.
 *
 * Meant to be used with the SocialProfile extension, fails without.
 * Make sure to set either $wgUserStatsTrackWeekly or $wgUserStatsTrackMonthly
 * to true in your wiki's LocalSettings.php and before doing so, be sure that
 * you have created the three necessary tables in the database:
 * user_points_archive, user_points_monthly and user_points_weekly.
 * Then add <randomfeatureduser/> tag to whichever page you want to.
 *
 * @file
 * @ingroup Extensions
 * @author Aaron Wright <aaron.wright@gmail.com>
 * @author David Pean <david.pean@gmail.com>
 * @author Jack Phoenix <jack@countervandalism.net>
 * @link https://www.mediawiki.org/wiki/Extension:RandomFeaturedUser Documentation
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

// What to display on the output of <randomfeatureduser> tag...
$wgRandomFeaturedUser['avatar'] = true;
$wgRandomFeaturedUser['points'] = true;
$wgRandomFeaturedUser['about'] = true;

// Extension credits that will show up on Special:Version
$wgExtensionCredits['parserhook'][] = array(
	'name' => 'RandomFeaturedUser',
	'version' => '1.3',
	'author' => array( 'Aaron Wright', 'David Pean', 'Jack Phoenix' ),
	'descriptionmsg' => 'randomfeatureduser-desc',
	'url' => 'https://www.mediawiki.org/wiki/Extension:RandomFeaturedUser',
);

// Internationalization messages
$wgMessagesDirs['RandomFeaturedUser'] = __DIR__ . '/i18n';

$wgAutoloadClasses['RandomFeaturedUser'] = __DIR__ . '/RandomFeaturedUser.class.php';

$wgHooks['ParserFirstCallInit'][] = 'wfRandomFeaturedUser';

/**
 * Set up the <randomfeatureduser> tag
 * @param $parser Object: instance of Parser (not necessarily $wgParser)
 * @return Boolean: true
 */
function wfRandomFeaturedUser( &$parser ) {
        $parser->setHook( 'randomfeatureduser', array( 'RandomFeaturedUser', 'getRandomUser' ) );
        return true;
}
