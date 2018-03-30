<?php
class Event extends Post {
  const CACHE_GROUP = 'event_controller';

  public static $controller_post_type = 'tribe_events';

  public static function categories() {
    $categories = wp_cache_get(__FUNCTION__, self::CACHE_GROUP);
    if ( false !== $categories ) return $categories;

    $categories = Term::get_controllers(array(
      'taxonomy'  => 'tribe_events_cat',
      'orderby'   => 'name',
      'order'     => 'ASC'
    ));

    wp_cache_set(__FUNCTION__, $categories, self::CACHE_GROUP);
    return $categories;
  }

  public function has_organizer() {
    return tribe_has_organizer($this->id);
  }

  public function organizer() {
    return $this->has_organizer() ? self::get_controller(tribe_get_organizer_id($this->id)) : null;
  }

  public function cost($with_currency_symbol = false) {
    return tribe_get_cost($this->id, $with_currency_symbol);
  }

  public function is_past() {
    return tribe_is_past_event($this->id);
  }

  public function is_on_date($date = null) {
    return tribe_event_is_on_date($date);
  }

  public function is_multi_day() {
    return tribe_event_is_multiday($this->id);
  }

  public function is_all_day() {
    return tribe_event_is_all_day($this->id);
  }

  public function is_recurring() {
    return tribe_is_recurring_event($this->id);
  }

  public function recurrence_description() {
    return tribe_get_recurrence_text($this->id);
  }

  public function start_date($format) {
    return tribe_get_start_date($this->id, true, $format);
  }

  public function end_date($format) {
    return tribe_get_end_date($this->id, true, $format);
  }
}
