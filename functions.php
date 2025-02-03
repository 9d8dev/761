<?php

/**
 * Security and Access Control
 * --------------------------
 * Functions for managing access and security settings
 */

class HeadlessWPSecurity {
    public function __construct() {
        // Core WordPress features
        add_theme_support('post-thumbnails');

        // Initialize all security measures
        $this->init_robots_blocking();
        $this->init_access_control();
        $this->init_header_cleanup();
    }

    /**
     * Initialize robots and search engine blocking
     */
    private function init_robots_blocking() {
        // Robots.txt blocking
        add_action('init', [$this, 'handle_robots_txt']);

        // Search engine blocking headers
        add_action('init', [$this, 'add_security_headers']);

        // REST API headers
        add_filter('rest_pre_serve_request', [$this, 'add_rest_api_headers']);
    }

    /**
     * Initialize access control features
     */
    private function init_access_control() {
        // Homepage redirect
        add_action('template_redirect', [$this, 'redirect_homepage']);

        // Disable XML-RPC
        add_filter('xmlrpc_enabled', '__return_false');
    }

    /**
     * Initialize header cleanup
     */
    private function init_header_cleanup() {
        // Remove various WordPress headers
        add_action('init', function() {
            remove_action('wp_head', 'rest_output_link_wp_head', 10);
            remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
            remove_action('wp_head', 'feed_links', 2);
            remove_action('wp_head', 'feed_links_extra', 3);
            remove_action('wp_head', 'wp_generator');
        });
    }

    /**
     * Handle robots.txt requests
     */
    public function handle_robots_txt() {
        if (is_robots()) {
            header("Content-Type: text/plain; charset=utf-8");
            echo "User-agent: *\n";
            echo "Disallow: /\n";
            exit();
        }
    }

    /**
     * Add security headers to all responses
     */
    public function add_security_headers() {
        header('X-Robots-Tag: noindex, nofollow', true);
    }

    /**
     * Add headers to REST API responses
     */
    public function add_rest_api_headers($response) {
        $response->header('X-Robots-Tag', 'noindex, nofollow');
        return $response;
    }

    /**
     * Redirect homepage to admin
     */
    public function redirect_homepage() {
        if (is_home() || is_front_page()) {
            wp_redirect(admin_url());
            exit;
        }
    }
}

// Initialize the security features
new HeadlessWPSecurity();