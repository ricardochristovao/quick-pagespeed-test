<?php
/*
Plugin Name: Quick PageSpeed Test
Plugin URI: https://github.com/ricardochristovao/quick-pagespeed-test
Description: Adiciona botão de teste rápido do Google PageSpeed na listagem de páginas
Version: 1.0
Author: Ricardo Christovão da Silva
Author URI: https://github.com/ricardochristovao
License: GPL v2 or later
*/

if (!defined('ABSPATH')) {
    exit;
}

class QuickPageSpeedTest {
    public function __construct() {
        add_action('admin_init', array($this, 'init'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_styles'));
    }

    public function init() {
        add_filter('post_row_actions', array($this, 'add_pagespeed_link'), 10, 2);
        add_filter('page_row_actions', array($this, 'add_pagespeed_link'), 10, 2);
    }

    public function enqueue_styles() {
        wp_enqueue_style(
            'quick-pagespeed-test-admin',
            plugin_dir_url(__FILE__) . 'assets/css/admin.css',
            array(),
            '1.0.0'
        );
    }

    public function add_pagespeed_link($actions, $post) {
        if ($post->post_status === 'publish') {
            $permalink = get_permalink($post->ID);
            $pagespeed_url = 'https://pagespeed.web.dev/report?url=' . urlencode($permalink);
            
            $actions['pagespeed_test'] = sprintf(
                '<a href="%s" target="_blank" class="pagespeed-test">%s</a>',
                esc_url($pagespeed_url),
                __('Testar PageSpeed', 'quick-pagespeed-test')
            );
        }
        return $actions;
    }
}

new QuickPageSpeedTest();