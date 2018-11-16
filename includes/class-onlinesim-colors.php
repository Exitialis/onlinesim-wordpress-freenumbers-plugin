<?php

class OnlinesimColors
{
  public function registerCustomize(WP_Customize_Manager $wp_customize)
  {
    $wp_customize->add_setting('muted_color', array(
      'default' => '#999999',
      'transport' => 'refresh',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'muted_color', array(
      'section' => 'colors',
      'label' => esc_html__('Numbers plugin muted color', 'onlinesim'),
    )));

    $wp_customize->add_setting('primary_color', array(
      'default' => '#2196f3',
      'transport' => 'refresh',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_color', array(
      'section' => 'colors',
      'label' => esc_html__('Numbers plugin primary color', 'onlinesim'),
    )));

    $wp_customize->add_setting('border_color', array(
      'default' => '#d2d8e1',
      'transport' => 'refresh',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'border_color', array(
      'section' => 'colors',
      'label' => esc_html__('Numbers plugin border color', 'onlinesim'),
    )));

    $wp_customize->add_setting('hover_color', array(
      'default' => '#f1f4f8',
      'transport' => 'refresh',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'hover_color', array(
      'section' => 'colors',
      'label' => esc_html__('Numbers plugin hover color', 'onlinesim'),
    )));

  }
}