<?php

class Organizer extends Post
{
    const CACHE_GROUP = 'organizer_controller';

    public static $controller_post_type = 'tribe_organizer';

    public function __construct($post)
    {
        parent::__construct($post);

        $this->email   = tribe_get_organizer_email($this->id);
        $this->phone   = tribe_get_organizer_phone($this->id);
        $this->website = tribe_get_organizer_website_url($this->id);
    }
}
