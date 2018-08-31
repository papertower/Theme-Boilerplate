<?php

namespace Theme;

/**
 * This is the connecting point of everything that ought to be bootstrapped when the theme is
 * loaded. Most of this will remain untouched, but the get_* methods will be adjusted on a per theme
 * basis to reflect custom post types, taxonomies, and such.
 */
final class Theme implements Registerable
{
    private $post_types = [];
    private $taxonomies = [];

    /**
     * Registers the various theme components and services
     */
    public function register()
    {
        // Register Services
        $this->register_post_types();
        $this->register_taxonomies();

        // Load Configurations
        $this->register_service($this->get_configurations());

        // Load Assets
        $this->register_service($this->get_asset_services());

        // Load template loader
        $this->register_service([TemplateLoader::class]);

        // Disable Theme Updates
        add_filter('http_request_args', [$this, 'disable_theme_updates'], 5, 2);

        // Add Theme Support
        add_action('after_setup_theme', [$this, 'add_theme_support']);
    }

    /**
     * Returns the services for registering the post types
     * @return array<string> Array of fully qualified class names.
     */
    private function get_custom_post_type_services()
    {
        return [
            PostType\Example::class,
        ];
    }

    /**
     * Returns the services for extending existing post types
     * @return array<string> Array of fully qualified class names.
     */
    private function get_extended_post_type_services()
    {
        return [
            PostType\Post::class,
            PostType\Page::class
        ];
    }

    /**
     * Returns the services for registering the taxonomies
     * @return array<string> Array of fully qualified class names.
     */
    private function get_taxonomy_services()
    {
        return [
            Taxonomy\Example::class
        ];
    }

    /**
     * Returns the services for registering the configurations
     * @return array<string> Array of fully qualified class names
     */
    private function get_configurations()
    {
        return [
            Configuration\Comments::class,
            Configuration\Editor::class,
            Configuration\Media::class,
            Configuration\Misc::class,
            Configuration\Menu::class,
            Configuration\Plugins::class,
        ];
    }

    /**
     * Returns the services for registering the assets
     * @return array<string> Array of fully qualified class names
     */
    private function get_asset_services()
    {
        return [
            Assets\FrontEnd::class,
            Assets\AdminSide::class
        ];
    }

    /**
     * Notifies WordPress what the theme officially supports
     */
    public function add_theme_support()
    {
        add_theme_support('post-thumbnails');

        add_theme_support('automatic-feed-links');

        add_theme_support('menus');
    }

    /**
     * Register the supplied services and store the service for later access
     *
     * @param  array<string>  $services Array of services to register
     * @param  string $property Theme property to store services in
     */
    private function register_service($services, $property = '')
    {
        // Instanttiate and register is service
        foreach ($services as $service) {
            if (class_exists($service)) {
                $service = new $service();

                if ($service instanceof Registerable) {
                    $service->register();

                    if ($property && isset($this->property)) {
                        $this->$property[$service->get_slug()] = $service;
                    }
                }
            }
        }
    }

    /**
     * Register the post type services
     */
    private function register_post_types()
    {
        // Gather all the post type services
        $post_types = array_merge(
            $this->get_custom_post_type_services(),
            $this->get_extended_post_type_services()
        );

        $this->register_service($post_types, 'post_types');
    }

    /**
     * Register the taxonomy services
     */
    private function register_taxonomies()
    {
        $this->register_service($this->get_taxonomy_services(), 'taxonomies');
    }

    /**
     * Disables updates to the theme from the WordPress themes repository. This protects against
     * accidentally updating a custom theme with free theme elsewhere, causing death and mayhem.
     *
     * @param  array  $request The request arguments
     * @param  string $url     URL for the request
     *
     * @return array           Modified request arguments
     */
    public function disable_theme_updates($request, $url)
    {
        if (false === strpos($url, '//api.wordpress.org/themes/update-check')) {
            return $request;
        }

        if (empty($request['body']['themes'])) {
            return $request;
        }

        $themes = (array)json_decode($request['body']['themes']);
        unset($themes[get_option('template')]);
        unset($themes[get_option('stylesheet')]);
        $request['body']['themes'] = serialize($themes);

        return $request;
    }
}
