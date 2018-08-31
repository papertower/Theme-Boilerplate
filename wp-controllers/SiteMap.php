<?php

class SiteMap extends Page
{
    public static $controller_page_template = 'page-templates/sitemap';

    public function pages()
    {
        if ('select' === $this->meta->pages_type) {
            return $this->meta->pages('controllers');
        }

        $options      = $this->meta->pages_options('all');
        $show_private = (in_array('show-private', $options) && is_user_logged_in());
        $nest_pages   = in_array('nest-pages', $options);

        $args = [
            'post_status' => ($show_private) ? 'publish,private' : 'publish'
        ];

        if ($nest_pages) {
            $args['hierarchical'] = false;
            $args['parent']       = 0;
        }

        return self::get_controllers($args);
    }

    public function posts()
    {
        $options = $this->meta->posts_options('all');

        $show_private = (in_array('show-private', $options) && is_user_logged_in());
        $status       = ($show_private) ? ['publish', 'private'] : 'publish';

        switch ($this->meta->posts_type) {
            case 'select':
                return $this->meta->posts('controllers');

            case 'all':
                return PostController::get_controllers([
                    'post_type'   => 'post',
                    'numberposts' => -1,
                    'post_status' => $status,
                    'orderby'     => 'date'
                ]);

            case 'latest':
                return PostController::get_controllers([
                    'post_type'   => 'post',
                    'numberposts' => absint($this->meta->posts_latest),
                    'post_status' => $status,
                    'orderby'     => 'date'
                ]);

            case 'range':
                return PostController::get_controllers([
                    'post_type'   => 'post',
                    'numberposts' => -1,
                    'post_status' => $status,
                    'orderby'     => 'date',
                    'date_query'  => [
                        'after' => $this->meta->posts_after_date
                    ]
                ]);

        }
    }

    public function custom_post_types()
    {
        $posts = [];
        foreach ($this->meta->custom_post_types('all') as $type) {
            $object = get_post_type_object($type);

            $posts[$object->labels->menu_name] = PostController::get_controllers([
                'post_type'   => $type,
                'numberposts' => -1
            ]);
        }

        return $posts;
    }
}
