<?php

class Example extends Post
{
    public function posts($count)
    {
        return self::get_controllers([
            'post_type'    => 'post',
            'numberposts'  => $count,
            'post_belongs' => $this->id
        ]);
    }

    public function related($count)
    {
        $term_ids = wp_get_post_terms($this->id, 'example_category', [
            'fields' => 'ids'
        ]);

        return self::get_controllers([
            'post_type'    => 'example',
            'numberposts'  => $count,
            'post__not_in' => [$this->id],
            'tax_query'    => [
                [
                    'taxonomy' => 'example_category',
                    'terms'    => $term_ids
                ]
            ]
        ]);
    }
}
