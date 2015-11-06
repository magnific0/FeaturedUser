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

class FeaturedUser {

	/**
	 * Set up the <featureduser> tag
	 *
	 * @param Parser $parser
	 * @return bool
	 */
	public static function onParserFirstCallInit( &$parser ) {
		$parser->setHook( 'featureduser', array( __CLASS__, 'getUser' ) );
		return true;
	}

	public static function getUser( $input, $args, $parser ) {
		global $wgMemc, $wgFeaturedUser, $wgFeaturedUserBits;

		$parser->disableCache();

		if ( !isset( $args['user'] ) ){
			return '';
		}
		$user = $args['user'];

		$bits = array();
		// If bits are present
		if ( isset( $args['bits'] ) ){
			// Put into array
                        $bits = explode(',',$args['bits']);
                }

		// Get profile for user
		$profile = new UserProfile( $user );
		$profile_data = $profile->getProfile();
		$profile_data['user_name'] = $user;

		$output = '<div class="featured-user"><table><tr>';

		if ( $wgFeaturedUser['points'] == true ) {
			$stats = new UserStats( $profile_data['user_id'], $profile_data['user_name'] );
			$stats_data = $stats->getUserStats();
			$points = $stats_data['points'];
			$points = '<br />' . $points;
		} else {
			$points = '';
		}

		if ( $wgFeaturedUser['avatar'] == true ) {
			$user_title = Title::makeTitle( NS_USER, $profile_data['user_name'] );
			$avatar = new wAvatar( $profile_data['user_id'], 'l' );
			$avatarImage = $avatar->getAvatarURL();

			$output .= "<td valign=\"top\"><a href=\"" . htmlspecialchars( $user_title->getFullURL() ) . "\">{$avatarImage}</a></td>\n";
		}

		$output .= "<td valign=\"top\"><div class=\"featured-user-title\">
					<a href=\"" . htmlspecialchars( $user_title->getFullURL() ) . "\">" .
					"<h3>" . $profile_data['user_name'] . "</h3></a>" . $points . "</div>\n\n";

		$p = new Parser();
		$all_keys = array_keys($bits);
		$last_key = end($all_keys);
		foreach ($bits as $key => $bit) {
			// If bit is invalid, skip immidiately
			if ( !array_key_exists( $bit, $wgFeaturedUserBits )){
				continue;
			}

			$label = wfMessage($wgFeaturedUserBits[$bit])->text();

			$text  = ( isset( $profile_data[$bit] ) ) ? $profile_data[$bit] : '';

			// Remove templates
			$text  = preg_replace( '@{{.*?}}@si', '', $text );
			if ( empty( $text )){
				continue;
			}
			global $wgTitle, $wgOut;
			if ( $wgFeaturedUser['titles'] == true ) {
				if ( $wgFeaturedUser['inline'] == true ){
					$output .= '<b>' . $label . ' </b>';
				} else {
					$output .= '<div class="featured-user-title">' . $label . '</div>';
				}
			}
			$output .= $p->parse( $text, $wgTitle, $wgOut->parserOptions(), false )->getText();
			if ($key != $last_key){
				$output .= "<br />\n";
			}
		}

		$output .= '</td></tr></table></div><div class="visualClear"></div>';

		return $output;
	}

}
