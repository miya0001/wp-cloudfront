<?php
/*
Plugin Name: WP Cloud Front
Author: Digitalcube Co,.Ltd (Takayuki Miyauchi)
Description: Deliver static files from Cloud Front CDN.
Version: 0.1.0
Author URI: http://digitalcube.jp/
Domain Path: /languages
Text Domain: megumi-cdn
*/

require_once(dirname(__FILE__).'/includes/admin.class.php');

new MegumiCDN();

class MegumiCDN {

function __construct()
{
    $hooks = array(
        "stylesheet_directory_uri",
        "template_directory_uri",
        "plugins_url",
    );
    foreach ($hooks as $hook) {
        add_filter(
            $hook,
            array(&$this, "filter")
        );
    }
    add_filter('the_content', array(&$this, 'the_content'));
}

public function the_content($html)
{
    $up = wp_upload_dir();
    $upload_url = $up['baseurl'];
    $filterd_url = $this->filter($upload_url);
    return str_replace($upload_url, $filterd_url, $html);
}

public function filter($uri)
{
    return str_replace(
        untrailingslashit(home_url()),
        untrailingslashit(esc_url($this->get_url())),
        $uri
    );
}

private function get_url()
{
    if (defined("CLOUD_FRONT_URL")) {
        return CLOUD_FRONT_URL;
    } else {
        return home_url();
    }
}

} // MegumiCDN



// EOF
