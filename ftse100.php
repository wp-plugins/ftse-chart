<?php
/*
  Plugin Name: FTSE 100 Chart
  Plugin URI:
  Description: Adds a Widget so you can embed a FTSE 100 chart anywhere on your Wordpress site.
  Version: 1.3
  Author: KiwiCoder
  Author URI: http://coder.kiwi
  License: GPLv2
 */

class ftse_widget extends WP_Widget {

    function ftse_widget() {
        $widget_ops = array('classname' => 'ftse_widget', 'description' => __('FTSE 100 Widget', 'ftse-plugin'));
        parent::__construct('ftse_widget_css', __('FTSE Widget', 'ftse-plugin'), $widget_ops);
    }

    function form($instance) {
        // admin form
        $defaults = array('title' => __('FTSE 100 from Yahoo! Finance', 'ftse-plugin'));
        $instance = wp_parse_args((array) $instance, $defaults);
        $title = strip_tags($instance['title']);
        ?>
        <p><?php _e('Title', 'ftse-plugin') ?>: <input class="widefat" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

        <?php
    }

    function update($new_instance, $old_instance) {
        // save widget options
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }

    function widget($args, $instance) {
        // display the widget
        extract($args);
        echo $before_widget;
        $title = apply_filters('widget_title', $instance['title']);
        if (!empty($title)) {
            echo $before_title . $title . $after_title;
        };
        $json = file_get_contents('http://m8y.co/api/ftse');
        $obj = json_decode($json);

        echo '<img class="ftse-100-chart" src="' . $obj->url . '"/>';

        echo $after_widget;
    }

}

function ftse_register_widget() {
    register_widget('ftse_widget');
}

add_shortcode('ftse_chart', 'ftse_chart_shortcode');

function ftse_chart_shortcode($atts) {
    $json = file_get_contents('http://m8y.co/api/ftse');
    $obj = json_decode($json);

    $output = '<img class="ftse-100-chart" src="' . $obj->url . '"/>';
    return $output;
}

add_action('widgets_init', 'ftse_register_widget');
?>
