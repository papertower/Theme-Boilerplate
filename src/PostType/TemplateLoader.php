<?php

namespace Theme\PostType;

/**
 * A magical trait for hooking a post type into the custom templating system. Custom templates are
 * loaded based on their position in the view structure, rather than using the "Template Post Type"
 * parameter. It also makes it possible as WordPress only supports templates one-level deep.
 *
 * To load a template for a post type place the file within the /views/Post Type/{$post_type}/
 * directory with the normal "Template Name" parameter. Works normally from there!
 */
trait TemplateLoader
{
    /**
     * Hooks into the appropriate WordPress hook to load the post type templates.
     *
     * @param  string $post_type Post type to load this for
     */
    protected function load($post_type)
    {
        add_filter("theme_{$post_type}_templates", [$this, 'load_templates'], 10, 4);
    }

    /**
     * Goes through two-levels of files within the post type view directory, checks for the
     * "Template Name" parameter, and adds it to the templates if applicable.
     *
     * @param  array    $post_templates Default templates
     * @param  WP_Theme $wp_theme       WP_Theme instance
     * @param  WP_Post  $post           Current WP_Post
     * @param  string   $post_type      Current post type
     *
     * @return array                    Templates with the included view templates
     */
    public function load_templates($post_templates, $wp_theme, $post, $post_type)
    {
        $directories = [
            TEMPLATEPATH . "/views/PostType/$post_type",
            STYLESHEETPATH . "/views/PostType/$post_type"
        ];

        // Check the post directory, too, so the post archive file can be a page template.
        if ('page' === $post_type) {
            $directories[] = STYLESHEETPATH . "/views/PostType/post";
        }

        foreach ($directories as $directory) {
            if (is_dir($directory)) {
                $files = (array)self::scandir($directory, ['php', 'twig'], 2);

                $headers = [
                    'template_name'      => 'Template Name',
                    'template_post_type' => 'Template Post Type'
                ];

                foreach ($files as $file) {
                    $data = get_file_data($file, $headers);

                    if (empty($data['template_post_type'])) {
                        $data['template_post_type'] = 'page';
                    }

                    if ( ! empty($data['template_name']) && $post_type === $data['template_post_type']) {
                        $path                  = str_replace(TEMPLATEPATH . '/', '', $file);
                        $path                  = str_replace(STYLESHEETPATH . '/', '', $path);
                        $post_templates[$path] = $data['template_name'];
                    }
                }
            }
        }

        return $post_templates;
    }

    /**
     * Scans the provided directory (and child directories up to a set depth) and returns the files
     * found. This is taken directly from WP_Theme::scandir method:
     *
     * @link https://developer.wordpress.org/reference/classes/wp_theme/scandir/
     *
     * @return array
     */
    private static function scandir($path, $extensions = null, $depth = 0, $relative_path = '')
    {
        if ( ! is_dir($path)) {
            return false;
        }

        if ($extensions) {
            $extensions  = (array)$extensions;
            $_extensions = implode('|', $extensions);
        }

        $relative_path = trailingslashit($relative_path);
        if ('/' == $relative_path) {
            $relative_path = '';
        }

        $results    = scandir($path);
        $files      = [];
        $exclusions = ['CVS', 'node_modules'];

        foreach ($results as $result) {
            if ('.' == $result[0] || in_array($result, $exclusions, true)) {
                continue;
            }
            if (is_dir($path . '/' . $result)) {
                if ( ! $depth) {
                    continue;
                }
                $found = self::scandir($path . '/' . $result, $extensions, $depth - 1, $relative_path . $result);
                $files = array_merge_recursive($files, $found);
            } elseif ( ! $extensions || preg_match('~\.(' . $_extensions . ')$~', $result)) {
                $files[$relative_path . $result] = $path . '/' . $result;
            }
        }

        return $files;
    }
}
