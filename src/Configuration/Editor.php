<?php

namespace Theme\Configuration;

use Theme\Registerable;

/**
 * Provides configuration for the editor and content of the website. This includes editting the
 * Tiny MCE buttons and wrap iframes automatically for responsiveness.
 */
class Editor implements Registerable
{
    /**
     * Register all the hooks
     */
    public function register()
    {
        // Hook into the Tiny MCE Editor
        add_filter('mce_buttons', [$this, 'set_first_row_tiny_mce_buttons'], 10, 2);
        add_filter('mce_buttons_2', [$this, 'set_second_row_tiny_mce_buttons'], 10, 2);
        add_filter('tiny_mce_before_init', [$this, 'force_paste_as_text'], 10, 2);

        // Hook into the post content
        add_filter('the_content', [$this, 'wrap_iframes_in_content']);
    }

    /**
     * Set the default Tiny MCE buttons to a limited list users should be safe using. This only
     * affects the default content editor, not custom ones.
     *
     * @param array  $mce_buttons Default buttons
     * @param string $editor_id   HTML entity ID for editor
     *
     * @return array  Buttons to display
     */
    public function set_first_row_tiny_mce_buttons($mce_buttons, $editor_id)
    {
        if ('content' !== $editor_id) {
            return $mce_buttons;
        }

        return [
            'formatselect',
            'bold',
            'italic',
            'underline',
            'blockquote',
            'strikethrough',
            'bullist',
            'numlist',
            'undo',
            'redo',
            'link',
            'unlink',
            'removeformat',
            'fullscreen'
        ];
    }

    /**
     * Removes the second row of Tiny MCE buttons as the only ones we want will be in the first row.
     * Also only affects the main editor.
     *
     * @param array  $mce_buttons Default buttons
     * @param string $editor_id   HTML entity ID for editor
     *
     * @return array  Buttons to display
     */
    public function set_second_row_tiny_mce_buttons($mce_buttons, $editor_id)
    {
        return 'content' === $editor_id ? [] : $mce_buttons;
    }

    /**
     * When a user pastes text into the Tiny MCE it can be anyone's guess how the formatting actually
     * ends up. The safest way to handle this is to strip everything but text from pasted content,
     * then have the user format the text in the editor.
     *
     * @param  array $mce_init  Tiny MCE configuration
     * @param string $editor_id HTML entity ID for editor
     *
     * @return array  Modified Tiny MCE configuration
     */
    public function force_paste_as_text($mce_init, $editor_id)
    {
        $mce_init['paste_as_text'] = true;

        return $mce_init;
    }

    /**
     * For styling iframes to be responsive, wrap any iframes automatically within a div container.
     *
     * @param  string $content Post content
     *
     * @return string Modified post content
     */
    public function wrap_iframes_in_content($content)
    {
        if (false !== strpos($content, 'iframe')) {
            $content = preg_replace('~(<iframe.+</iframe>)~i', '<div class="iframe-container">$1</div>', $content);
        }

        return $content;
    }
}
