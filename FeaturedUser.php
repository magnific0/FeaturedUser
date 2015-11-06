<?php
/**
 * FeaturedUser extension - adds <featureduser> parser hook
 * to display a chosen user and some info regarding the
 * user, such as their avatar, bio etc. 
 *
 * Meant to be used with the SocialProfile extension, fails without.
 * Add <featureduser user="User Name"/> tag to display an user on 
 * any page.
 *
 * Heavily inspired by RandomFeaturedUser extension:
 * https://www.mediawiki.org/wiki/Extension:RandomFeaturedUser 
 * Aaron Wright <aaron.wright@gmail.com>, David Pean <david.pean@gmail.com>  
 * and Jack Phoenix <jack@countervandalism.net>.
 *
 * @file
 * @ingroup Extensions
 * @author Jacco Geul <jacco@geul.net>
 * @link https://www.mediawiki.org/wiki/Extension:FeaturedUser Documentation
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

// What to display on the output of <featureduser> tag...
$wgFeaturedUser['avatar'] = true;
$wgFeaturedUser['points'] = true;
$wgFeaturedUser['titles'] = true;
$wgFeaturedUser['inline'] = true;

$wgFeaturedUserBits = array();
$wgFeaturedUserBits['real_name']    = 'user-personal-info-real-name';
$wgFeaturedUserBits['birthday']     = 'user-personal-info-birthday';
$wgFeaturedUserBits['occupation']   = 'user-personal-info-occupation';
$wgFeaturedUserBits['websites']     = 'user-personal-info-websites';
$wgFeaturedUserBits['places_lived'] = 'user-personal-info-places_lived';
$wgFeaturedUserBits['schools']      = 'user-personal-info-schools';
$wgFeaturedUserBits['about']        = 'user-personal-info-about';
$wgFeaturedUserBits['location']     = 'user-personal-info-location';
$wgFeaturedUserBits['hometown']     = 'user-personal-info-hometown';

$wgFeaturedUserBits['custom_1']     = 'custom-info-field1';
$wgFeaturedUserBits['custom_2']     = 'custom-info-field2';
$wgFeaturedUserBits['custom_3']     = 'custom-info-field3';
$wgFeaturedUserBits['custom_4']     = 'custom-info-field4';

$wgFeaturedUserBits['movies']       = 'other-info-movies';
$wgFeaturedUserBits['tv']           = 'other-info-tv';
$wgFeaturedUserBits['music']        = 'other-info-music';
$wgFeaturedUserBits['books']        = 'other-info-books';
$wgFeaturedUserBits['video-games']  = 'other-info-video-games';
$wgFeaturedUserBits['magazines']    = 'other-info-magazines';
$wgFeaturedUserBits['snacks']       = 'other-info-snacks';
$wgFeaturedUserBits['drinks']       = 'other-info-drinks';

// Extension credits that will show up on Special:Version
$wgExtensionCredits['parserhook'][] = array(
	'name' => 'FeaturedUser',
	'version' => '1.0',
	'author' => array( 'Jacco Geul' ),
	'descriptionmsg' => 'featureduser-desc',
	'url' => 'https://www.mediawiki.org/wiki/Extension:FeaturedUser',
);

// Internationalization messages
$wgMessagesDirs['FeaturedUser'] = __DIR__ . '/i18n';

$wgAutoloadClasses['FeaturedUser'] = __DIR__ . '/FeaturedUser.class.php';

$wgHooks['ParserFirstCallInit'][] = 'FeaturedUser::onParserFirstCallInit';
