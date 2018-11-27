<?php

class Numbers_Widget extends WP_Widget
{
  public function __construct()
  {
    load_plugin_textdomain('onlinesim');

    parent::__construct('onlinesim-numbers-widget', __('Виджет бесплатных номеров Onlinesim', 'onlinesim'), [
      'description' => __('Виджет, который выводит на страницу бесплатные номера с сайта onlinesim и позволяет пользователям работать с ними', 'onlinesim')
    ]);
  }

  public function widget($args, $instance)
  {
    $title = apply_filters('widget_title', $instance['title']);
    echo $args['before_widget'];
    if (!empty($title)) echo $args['before_title'] . $title . $args['after_title'];
    echo __('Тест', 'onlinesim');
    echo $args['after_widget'];
  }
}