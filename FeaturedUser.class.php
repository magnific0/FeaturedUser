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
		global $wgMemc, $wgFeaturedUser;

		$parser->disableCache();

		$period = ( isset( $args['period'] ) ) ? $args['period'] : '';
		if ( $period != 'weekly' && $period != 'monthly' ) {
			return '';
		}

		$user_list = array();
		$count = 20;
		$realCount = 10;

		// Try cache
		$key = wfMemcKey( 'user_stats', 'top', 'points', 'weekly', $realCount );
		$data = $wgMemc->get( $key );

		if ( $data != '' ) {
			wfDebug( "Got top $period users by points ({$count}) from cache\n" );
			$user_list = $data;
		} else {
			wfDebug( "Got top $period users by points ({$count}) from DB\n" );

			$dbr = wfGetDB( DB_SLAVE );
			$res = $dbr->select(
				'user_points_' . $period,
				array( 'up_user_id', 'up_user_name', 'up_points' ),
				array( 'up_user_id <> 0' ),
				__METHOD__,
				array(
					'ORDER BY' => 'up_points DESC',
					'LIMIT' => $count
				)
			);
			$loop = 0;
			foreach ( $res as $row ) {
				// Prevent blocked users from appearing
				$user = User::newFromId( $row->up_user_id );
				if ( !$user->isBlocked() ) {
					$user_list[] = array(
						'user_id' => $row->up_user_id,
						'user_name' => $row->up_user_name,
						'points' => $row->up_points
					);
					$loop++;
				}
				if ( $loop >= 10 ) {
					break;
				}
			}

			if ( count( $user_list ) > 0 ) {
				$wgMemc->set( $key, $user_list, 60 * 60 );
			}
		}

		// Make sure we have some data
		if ( !is_array( $user_list ) || count( $user_list ) == 0 ) {
			return '';
		}

		$featured_user = $user_list[array_rand( $user_list, 1 )];

		// Make sure we have a user
		if ( !$featured_user['user_id'] ) {
			return '';
		}

		$output = '<div class="featured-user">';

		if ( $wgFeaturedUser['points'] == true ) {
			$stats = new UserStats( $featured_user['user_id'], $featured_user['user_name'] );
			$stats_data = $stats->getUserStats();
			$points = $stats_data['points'];
		}

		if ( $wgFeaturedUser['avatar'] == true ) {
			$user_title = Title::makeTitle( NS_USER, $featured_user['user_name'] );
			$avatar = new wAvatar( $featured_user['user_id'], 'ml' );
			$avatarImage = $avatar->getAvatarURL();

			$output .= "<a href=\"" . htmlspecialchars( $user_title->getFullURL() ) . "\">{$avatarImage}</a>\n";
		}

		$output .= "<div class=\"featured-user-title\">
					<a href=\"" . htmlspecialchars( $user_title->getFullURL() ) . "\">" .
					wordwrap( $featured_user['user_name'], 12, "<br />\n", true ) .
					"</a><br /> " .
				wfMessage( "featured-user-points-{$period}", $points )->text() .
			"</div>\n\n";

		if ( $wgFeaturedUser['about'] == true ) {
			$p = new Parser();
			$profile = new UserProfile( $featured_user['user_name'] );
			$profile_data = $profile->getProfile();
			$about = ( isset( $profile_data['about'] ) ) ? $profile_data['about'] : '';
			// Remove templates
			$about = preg_replace( '@{{.*?}}@si', '', $about );
			if ( !empty( $about ) ) {
				global $wgTitle, $wgOut;
				$output .= '<div class="featured-user-about-title">' .
					wfMessage( 'featured-user-about-me' )->text() . '</div>' .
					$p->parse( $about, $wgTitle, $wgOut->parserOptions(), false )->getText();
			}
		}

		$output .= '</div><div class="visualClear"></div>';

		return $output;
	}

}