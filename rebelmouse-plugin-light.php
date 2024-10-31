<?php
/*
 * Plugin Name: RebelMouse plugin light
 * Plugin URI: http://www.rebelmouse.com
 * Description: Add your RebelMouse to your blog using shortcode.
 * Version: 1.5
 * Author: Francisco Lavin
 * Author URI: http://www.rebelmouse.com/flavin
 *License: GPLv2 or later
 */

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/


function rebelmouse_lite_clean_sitename( $sitename ) {
    if ( ! empty( $sitename ) )
        return preg_replace( '/^http(s)?:\/\/(www.)?rebelmouse.com\//', '', $sitename );
    else
        '';
}


function rebelmouse_lite_render( $args ) {
    $r = wp_parse_args( $args, array( 'site_name' => 'rebelmouse'
                                    , 'skip' => ''
                                    , 'show_rebelnav' => False
                                    , 'height' => '0'
                                    , 'embed_type' => ''
                                    , 'limit' => ''
                                    , 'more_button' => False
                                    ) );

    $r['site_name'] = rebelmouse_lite_clean_sitename( $r['site_name'] );

    if ( empty( $r['height'] ) ) {
        $is_flexible   = true;
        $r['height']   = '1500';
        $r['flexible'] = '1';
    } else {
        $is_flexible   = false;
        $r['flexible'] = '0';
    }

    $url = add_query_arg( array(
        'site'     => rawurlencode( $r['site_name'] ),
        'height'   => urlencode( $r['height'] ),
        'flexible' => urlencode( $r['flexible'] ),
        'skip'     => urlencode( $r['skip'] ),
    ), 'https://www.rebelmouse.com/static/js-build/embed/embed.js' );

    if ( ! empty( $r['show_rebelnav'] ) )
        $url = add_query_arg( 'show_rebelnav', '1', $url );

    if ( ! empty( $r['embed_type'] ) )
        $url = add_query_arg( 'embed_type', urlencode( $r['embed_type'] ), $url );

    if ( ! empty( $r['limit'] ) )
        $url = add_query_arg( 'post_limit', urlencode( $r['limit'] ), $url );

    if ( ! empty( $r['more_button'] ) ) {
        $url = add_query_arg( 'more_button', $r['more_button']? '1': '0', $url );
        // more button should be allways related to dont_load_more_posts
        $url = add_query_arg( 'dont_load_new_posts', '1', $url );
    }

    $output  = '<script type="text/javascript" id="rebelmouse-embed-script" src="' . esc_url( $url ) . '"></script>';

    return $output;
}


/**
 * Shortcode to diplay rebelmouse in your site.
 * 
 * The list of arguments is below:
 *     'site_name' (string) - You rebelmouse site name
 *                    Default: rebelmouse
 *     'skip' (String) - Element to hide in the stream: about-site
 *     'height' (int) - height of the iframe
 *                    Default: initial auto adjustable to 20 posts.
 * 
 * Usage: 
 * [rebelmouse sitename="rebelmouse"]
 * [rebelmouse sitename="rebelmouse" h="1500"]
 * [rebelmouse sitename="rebelmouse" h="1500" type="sidebar"]
 */
function rebelmouse_lite_shortcode( $atts ) {

    $skip          = ( ! empty( $atts['skip'] ) ) ? $atts['skip'] : '';
    $limit         = ( ! empty( $atts['limit'] ) ) ? $atts['limit'] : '';
    $height        = ( ! empty( $atts['h'] ) ) ? $atts['h'] : $atts['height'];
    $site_name     = ( ! empty( $atts['sitename'] ) ) ? $atts['sitename'] : $atts['site_name'];
    $embed_type    = ( ! empty( $atts['type'] ) ) ? $atts['type'] : $atts['embed_type'];
    $show_rebelnav = ( ! empty( $atts['show_rebelnav'] ) ) ? $atts['show_rebelnav'] : false;
    $more_button   = ( ! empty( $atts['more_button'] ) ) ? $atts['more_button'] : false;

    return rebelmouse_lite_render( array(
        'site_name'     => $site_name,
        'skip'          => $skip,
        'limit'         => $limit,
        'more_button'   => $more_button,
        'height'        => $height,
        'embed_type'    => $embed_type,
        'show_rebelnav' => $show_rebelnav
    ) );
}
add_shortcode( 'rebelmouse', 'rebelmouse_lite_shortcode' );

?>
