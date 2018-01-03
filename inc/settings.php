<?php
namespace Whippet;
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class Settings {

function __construct() {
    add_action('admin_init', array( $this, 'whippet_settings' ) );
}

/**
 * Register settings + options
 * @return [type] [description]
 */
public  function whippet_settings() {
    if(get_option('whippet_options') == false) {
        add_option('whippet_options', apply_filters( 'whippet_default_options', $this->whippet_default_options()));
    }

    /**
     * Requests Primary Section
     * @var [type]
     */
    add_settings_section('whippet_options', 'Requests', array($this, 'whippet_options_callback'), 'whippet_options');

    /**
     * Disable Emojis
     * @var [type]
     */
    add_settings_field(
        'disable_emojis', 
        $this->whippet_title('Disable Emojis', 'disable_emojis') . $this->whippet_tooltip('Removes WordPress Emojis JavaScript file (wp-emoji-release.min.js).'), 
        array($this, 'whippet_print_input'), 
        'whippet_options', 
        'whippet_options', 
        array(
            'id' => 'disable_emojis'
        )
    );
    
    /**
     * Remove Query Strings
     * @var [type]
     */
    add_settings_field(
        'remove_query_strings', 
        $this->whippet_title('Remove Query Strings', 'remove_query_strings') . $this->whippet_tooltip('Remove query strings from static resources (CSS, JS).'), 
        array($this, 'whippet_print_input' ), 
        'whippet_options', 
        'whippet_options', 
        array(
            'id' => 'remove_query_strings'
        )
    );

    /**
     * Disable Embeds
     * @var [type]
     */
    add_settings_field(
        'disable_embeds', 
        $this->whippet_title('Disable Embeds', 'disable_embeds') . $this->whippet_tooltip('Removes WordPress Embed JavaScript file (wp-embed.min.js).'), 
        array($this, 'whippet_print_input' ), 
        'whippet_options', 
        'whippet_options', 
        array(
            'id' => 'disable_embeds'
        )
    );

    /**
     * Disable whippet Maps
     * @var [type]
     */
    add_settings_field(
        'disable_google_maps', 
        $this->whippet_title('Disable Google Maps', 'disable_google_maps') . $this->whippet_tooltip('Removes any instances of Google Maps being loaded across your entire site.'), 
        array($this, 'whippet_print_input' ), 
        'whippet_options', 
        'whippet_options', 
        array(
            'id' => 'disable_google_maps'
        )
    );

    /**
     * Remove jQuery Migrate
     * @var [type]
     */
    add_settings_field(
        'remove_jquery_migrate', 
        $this->whippet_title('Remove jQuery Migrate', 'remove_jquery_migrate') . $this->whippet_tooltip('Removes jQuery Migrate JavaScript file (jquery-migrate.min.js).'), 
        array($this, 'whippet_print_input'), 
        'whippet_options', 
        'whippet_options', 
        array(
            'id' => 'remove_jquery_migrate'
        )
    );

    /**
     * Tags Primary Section
     * @var [type]
     */
    add_settings_section('whippet_tags', 'Tags', array($this, 'whippet_tags_callback'), 'whippet_options');

    /**
     * Remove RSD Link
     * @var [type]
     */
    add_settings_field(
        'remove_rsd_link', 
        $this->whippet_title('Remove RSD Link', 'remove_rsd_link') . $this->whippet_tooltip('Remove RSD (Real Simple Discovery) link tag.'), 
        array($this, 'whippet_print_input'), 
        'whippet_options', 
        'whippet_tags', 
        array(
            'id' => 'remove_rsd_link'
        )
    );

    /**
     * Remove Shortlink
     * @var [type]
     */
    add_settings_field(
        'remove_shortlink', 
        $this->whippet_title('Remove Shortlink', 'remove_shortlink') . $this->whippet_tooltip('Remove Shortlink link tag.'), 
        array($this, 'whippet_print_input' ),
        'whippet_options',
        'whippet_tags',
        array(
            'id' => 'remove_shortlink'
        )
    );

    /**
     * Remove REST API Links
     * @var [type]
     */
    add_settings_field(
        'remove_rest_api_links', 
        $this->whippet_title('Remove REST API Links', 'remove_rest_api_links') . $this->whippet_tooltip('Removes REST API link tag from the front end and the REST API header link from page requests.'), 
        array($this, 'whippet_print_input' ), 
        'whippet_options', 
        'whippet_tags', 
        array(
            'id' => 'remove_rest_api_links'
        )
    );

    /**
     * Remove wlmanifest Link
     * @var [type]
     */
    add_settings_field(
        'remove_wlwmanifest_link', 
        $this->whippet_title('Remove wlwmanifest Link', 'remove_wlwmanifest_link') . $this->whippet_tooltip('Remove wlwmanifest (Windows Live Writer) link tag.'), 
        array($this, 'whippet_print_input' ), 
        'whippet_options', 
        'whippet_tags',
        array(
            'id' => 'remove_wlwmanifest_link'
        )
    );

    /**
     * Remove Feed Links
     * @var [type]
     */
    add_settings_field(
        'remove_feed_links', 
        $this->whippet_title('Remove RSS Feed Links', 'remove_feed_links') . $this->whippet_tooltip('Disable WordPress generated RSS feed link tags.'), 
        array($this, 'whippet_print_input' ), 
        'whippet_options', 
        'whippet_tags', 
        array(
            'id' => 'remove_feed_links'
        )
    );

    /**
     * Admin Primary Section
     * @var [type]
     */
    add_settings_section('whippet_admin', 'Admin', array($this, 'whippet_admin_callback'), 'whippet_options');

    /**
     * Limit Post Revisions
     * @var [type]
     */
    add_settings_field(
        'limit_post_revisions', 
        '<label for=\'limit_post_revisions\'>' . __('Limit Post Revisions', 'whippet') . '</label>' . $this->whippet_tooltip('Limits the maximum amount of revisions that are allowed for posts and pages.'), 
        array($this, 'whippet_print_input'), 
        'whippet_options', 
        'whippet_admin', 
        array(
            'id' => 'limit_post_revisions',
            'input' => 'select',
            'options' => array(
                '' => 'Default',
                'false' => 'Disable Post Revisions',
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '10' => '10',
                '15' => '15',
                '20' => '20',
                '25' => '25',
                '30' => '30'
            )
        )
    );

    /**
     * Autosave Interval
     * @var [type]
     */
    add_settings_field(
        'autosave_interval', 
        '<label for=\'autosave_interval\'>' . __('Autosave Interval', 'whippet') . '</label>' . $this->whippet_tooltip('Controls how often WordPress will auto save posts and pages while editing.'), 
        array($this, 'whippet_print_input'), 
        'whippet_options', 
        'whippet_admin', 
        array(
            'id' => 'autosave_interval',
            'input' => 'select',
            'options' => array(
                '' => '1 Minute (Default)',
                '120' => '2 Minutes',
                '180' => '3 Minutes',
                '240' => '4 Minutes',
                '300' => '5 Minutes'
            )
        )
    );

    /**
     * Disable Heartbeat
     * @var [type]
     */
    add_settings_field(
        'disable_heartbeat', 
        '<label for=\'disable_heartbeat\'>' . __('Disable Heartbeat', 'whippet') . '</label>' . $this->whippet_tooltip('Disable WordPress Heartbeat everywhere or in certain areas (used for auto saving and revision tracking).'), 
        array($this, 'whippet_print_input'), 
        'whippet_options', 
        'whippet_admin', 
        array(
            'id' => 'disable_heartbeat',
            'input' => 'select',
            'options' => array(
                '' => 'Default',
                'disable_everywhere' => 'Disable Everywhere',
                'allow_posts' => 'Only Allow When Editing Posts/Pages'
            )
        )
    );

    /**
     * Heartbeat Frequency
     * @var [type]
     */
    add_settings_field(
        'heartbeat_frequency', 
        '<label for=\'heartbeat_frequency\'>' . __('Heartbeat Frequency', 'whippet') . '</label>' . $this->whippet_tooltip('Controls how often the WordPress Heartbeat API is allowed to run.'), 
        array($this, 'whippet_print_input'), 
        'whippet_options', 
        'whippet_admin', 
        array(
            'id' => 'heartbeat_frequency',
            'input' => 'select',
            'options' => array(
                '' => '15 Seconds (Default)',
                '30' => '30 Seconds',
                '45' => '45 Seconds',
                '60' => '60 Seconds'
            )
        )
    );

    /**
     * Misc Primary Section
     * @var [type]
     */
    add_settings_section('whippet_misc', 'Misc', array($this, 'whippet_misc_callback' ), 'whippet_options');

    /**
     * Disable Self Pingbacks
     * @var [type]
     */
    add_settings_field(
        'disable_self_pingbacks', 
        $this->whippet_title('Disable Self Pingbacks', 'disable_self_pingbacks') . $this->whippet_tooltip('Disable Self Pingbacks (generated when linking to an article on your own blog).'), 
        array($this, 'whippet_print_input'), 
        'whippet_options', 
        'whippet_misc', 
        array(
            'id' => 'disable_self_pingbacks'
        )
    );

    /**
     * Disable RSS Feeds
     * @var [type]
     */
    add_settings_field(
        'disable_rss_feeds', 
        $this->whippet_title('Disable RSS Feeds', 'disable_rss_feeds') . $this->whippet_tooltip('Disable WordPress generated RSS feeds.'), 
        array($this, 'whippet_print_input'), 
        'whippet_options', 
        'whippet_misc', 
        array(
            'id' => 'disable_rss_feeds'
        )
    );

    /**
     * Disable XML-RPC
     * @var [type]
     */
    add_settings_field(
        'disable_xmlrpc', 
        $this->whippet_title('Disable XML-RPC', 'disable_xmlrpc') . $this->whippet_tooltip('Disables WordPress XML-RPC functionality.'), 
        array($this, 'whippet_print_input'), 
        'whippet_options', 
        'whippet_misc', 
        array(
            'id' => 'disable_xmlrpc'
        )
    );

    /**
     * Hide WP Version
     * @var [type]
     */
    add_settings_field(
        'hide_wp_version', 
        $this->whippet_title('Hide WP Version', 'hide_wp_version') . $this->whippet_tooltip('Removes WordPress version meta tag.'), 
        array($this, 'whippet_print_input'), 
        'whippet_options', 
        'whippet_misc', 
        array(
            'id' => 'hide_wp_version'
        )
    );

    /**
     * WooCommerce Options Section
     * @var [type]
     */
    add_settings_section('whippet_woocommerce', 'WooCommerce', array($this, 'whippet_woocommerce_callback'), 'whippet_options');

    /**
     * Disable WooCommerce Scripts
     * @var [type]
     */
    add_settings_field(
        'disable_woocommerce_scripts', 
        $this->whippet_title('Disable Scripts', 'disable_woocommerce_scripts') . $this->whippet_tooltip('Disables WooCommerce scripts and styles except on product, cart, and checkout pages.'), 
        array($this, 'whippet_print_input'), 
        'whippet_options', 
        'whippet_woocommerce', 
        array(
            'id' => 'disable_woocommerce_scripts'
        )
    );

    /**
     * Disable WooCommerce Cart Fragmentation
     * @var [type]
     */
    add_settings_field(
        'disable_woocommerce_cart_fragmentation', 
        $this->whippet_title('Disable Cart Fragmentation', 'disable_woocommerce_cart_fragmentation') . $this->whippet_tooltip('Completely disables WooCommerce cart fragmentation script.'), 
        array($this, 'whippet_print_input'), 
        'whippet_options', 
        'whippet_woocommerce', 
        array(
            'id' => 'disable_woocommerce_cart_fragmentation'
        )
    );

    /**
     * Disable WooCommerce Status Meta Box
     * @var [type]
     */
    add_settings_field(
        'disable_woocommerce_status', 
        $this->whippet_title('Disable Status Meta Box', 'disable_woocommerce_status') . $this->whippet_tooltip('Disables WooCommerce status meta box from the WP Admin Dashboard.'), 
        array($this, 'whippet_print_input'), 
        'whippet_options', 
        'whippet_woocommerce', 
        array(
            'id' => 'disable_woocommerce_status'
        )
    );

    /**
     * Disable WooCommerce Widgets
     * @var [type]
     */
    add_settings_field(
        'disable_woocommerce_widgets', 
        $this->whippet_title('Disable Widgets', 'disable_woocommerce_widgets') . $this->whippet_tooltip('Disables all WooCommerce widgets.'), 
        array($this, 'whippet_print_input'), 
        'whippet_options', 
        'whippet_woocommerce', 
        array(
            'id' => 'disable_woocommerce_widgets'
        )
    );

    register_setting('whippet_options', 'whippet_options', 'whippet_sanitize_extras');

}

/**
 * Options default values
 * @return [type] [description]
 */
public  function whippet_default_options() {
    $defaults = array(
        'disable_emojis' => "0",
        'remove_query_strings' => "0",
        'disable_embeds' => "0",
        'disable_google_maps' => "0",
        'remove_jquery_migrate' => "0",
        'remove_rsd_link' => "0",
        'remove_shortlink' => "0",
        'remove_rest_api_links' => "0",
        'remove_wlwmanifest_link' => "0",
        'remove_feed_links' => "0",
        'disable_xmlrpc' => "0",
        'hide_wp_version' => "0",
        'disable_rss_feeds' => "0",
        'disable_self_pingbacks' => "0",
        'disable_heartbeat' => "",
        'heartbeat_frequency' => "",
        'limit_post_revisions' => "",
        'autosave_interval' => "",
        'remove_comments' => "0",
        'disable_woocommerce_scripts' => "0",
        'disable_woocommerce_cart_fragmentation' => "0",
        'disable_woocommerce_status' => "0",
        'disable_woocommerce_widgets' => "0"
    );
    return apply_filters( 'whippet_default_options', $defaults );

}

/**
 * Options group callback
 * @return [type] [description]
 */
public  function whippet_options_callback() {
    echo '<p class="whippet-subheading">' . __( 'Select which performance options you would like to enable.', 'whippet' ) . '</p>';
}

/**
 * Tags group callback
 * @return [type] [description]
 */
function whippet_tags_callback() {
    echo '<p class="whippet-subheading">' . __( 'Select which performance options you would like to enable.', 'whippet' ) . '</p>';
}

/**
 * Admin group callback
 * @return [type] [description]
 */
function whippet_admin_callback() {
    echo '<p class="whippet-subheading">' . __( 'Select which performance options you would like to enable.', 'whippet' ) . '</p>';
}

/**
 * Misc group callback
 * @return [type] [description]
 */
function whippet_misc_callback() {
    echo '<p class="whippet-subheading">' . __( 'Select which performance options you would like to enable.', 'whippet' ) . '</p>';
}

/**
 * Woocommerce options group callback
 * @return [type] [description]
 */
function whippet_woocommerce_callback() {
    echo '<p class="whippet-subheading">' . __( 'Disable specific elements of WooCommerce.', 'whippet' ) . '</p>';
}

/**
 * Print form inputs
 * @param  [type] $args [description]
 * @return [type]       [description]
 */
public  function whippet_print_input($args) {
    if(!empty($args['option'])) {
        $option = $args['option'];
        $options = get_option($args['option']);
    }
    else {
        $option = 'whippet_options';
        $options = get_option('whippet_options');
    }

    echo "<div style='display: table; width: 100%;'>";
        echo "<div class='whippet-input-wrapper'>";

            //Text
            if(!empty($args['input']) && ($args['input'] == 'text' || $args['input'] == 'color')) {
                echo "<input type='text' id='" . $args['id'] . "' name='" . $option . "[" . $args['id'] . "]' value='" . ($options[$args['id']] ? $options[$args['id']] : '') . "' />";
            }

            //Select
            elseif(!empty($args['input']) && $args['input'] == 'select') {
                echo "<select id='" . $args['id'] . "' name='" . $option . "[" . $args['id'] . "]'>";
                    foreach($args['options'] as $value => $title) {
                        echo "<option value='" . $value . "' "; 
                        if(!empty($options[$args['id']]) && $options[$args['id']] == $value) {
                            echo "selected";
                        } 
                        echo ">" . $title . "</option>";
                    }
                echo "</select>";
            }

            //Checkbox + Toggle
            else {
                    echo "<input type='checkbox' id='" . $args['id'] . "' name='" . $option . "[" . $args['id'] . "]' value='1' style='display: block; margin: 0px;' ";
                    if(!empty($options[$args['id']]) && $options[$args['id']] == "1") {
                        echo "checked";
                    }
                    echo ">";
            }

        echo "</div>";

        if(!empty($args['tooltip'])) {
                echo "<p style='font-size: 12px; font-style: italic;'>" . $args['tooltip'] . "</p>";
        }
    echo "</div>";
}

/**
 * Print checkbox toggle option
 * @param  [type] $args [description]
 * @return [type]       [description]
 */
public  function whippet_print_toggle($args) {
    if(!empty($args['section'])) {
        $section = $args['section'];
        $options = get_option($args['section']);
    }
    else {
        $section = 'whippet_options';
        $options = get_option('whippet_options');
    }
        echo "<input type='checkbox' id='" . $args['id'] . "' name='" . $section . "[" . $args['id'] . "]' value='1' style='display: block; margin: 0px;' ";
        if(!empty($options[$args['id']]) && $options[$args['id']] == "1") {
            echo "checked";
        }
        echo ">";
}

/**
 * Print select option
 * @param  [type] $args [description]
 * @return [type]       [description]
 */
public  function whippet_print_select($args) {
    $options = get_option('whippet_options');
    echo "<select id='" . $args['id'] . "' name='whippet_options[" . $args['id'] . "]'>";
        foreach($args['options'] as $value => $title) {
            echo "<option value='" . $value . "' "; 
            if($options[$args['id']] == $value) {
                echo "selected";
            } 
            echo ">" . $title . "</option>";
        }
    echo "</select>";
}

//sanitize extras
public static function whippet_sanitize_extras($values) {
    if(!empty($values['dns_prefetch'])) {
        $text = trim($values['dns_prefetch']);
        $text_array = explode("\n", $text);
        $text_array = array_filter($text_array, 'trim');
        $values['dns_prefetch'] = $text_array;
    }
    return $values;
}

/**
 * Print tooltip
 * @param  [type] $link [description]
 * @return [type]       [description]
 */
public  function whippet_tooltip($link) {
    $var = "<a ";
        if(!empty($link)) {
            $var.= "title='$link' ";
        }
        $var.= "class='whippet-tooltip'>?";
    $var.= "</a>";
    return $var;
}

/**
 * Print title
 * @param  [type]  $title    [description]
 * @param  [type]  $id       [description]
 * @param  boolean $checkbox [description]
 * @return [type]            [description]
 */
public  function whippet_title($title, $id, $checkbox = false) {
    if(!empty($title)) {
        $var = $title;
        return $var;
    }
}

}

new Settings;