<?php

/**
 * Custom Fonts
 */


Kirki::add_field( 'themegoods_customize', array(
    'type' => 'title',
    'settings'  => 'tg_custom_fonts_title',
    'label'    => esc_html__('Uploaded Fonts Settings', 'musico' ),
    'section'  => 'general_fonts',
	'priority' => 5,
) );

Kirki::add_field( 'themegoods_customize', array(
    'type' => 'repeater',
    'label' => esc_html__( 'Uploaded Fonts', 'musico' ) ,
    'description' => esc_html__( 'Here you can add your custom fonts', 'musico' ) ,
    'settings' => 'tg_custom_fonts',
    'priority' => 6,
    'transport' => 'auto',
    'section' => 'general_fonts',
    'row_label' => array(
        'type' => 'text',
        'value' => esc_html__( 'Upload Font', 'musico' ) ,
    ),
    'fields' => array(
        'font_name' => array(
            'type' => 'text',
            'label' => esc_html__( 'Name', 'musico' ) ,
        ) ,
        'font_url' => array(
            'type' => 'upload',
            'label' => esc_html__( 'Font File (*.woff)', 'musico' ) ,
        ) ,
        'font_weight' => array(
            'type' => 'select',
            'label' => esc_html__( 'Font Weight', 'musico' ) ,
            'defalut' => 'bold',
            'choices' => array(
                100 => 100 ,
                200 => 200 ,
                300 => 300 ,
                400 => 400 ,
                500 => 500 ,
                500 => 500 ,
                600 => 600 ,
                700 => 700 ,
                800 => 800 ,
                900 => 900 ,
            )
        ) ,
        'font_style' => array(
            'type' => 'select',
            'label' => esc_html__( 'Font Style', 'musico' ) ,
            'defalut' => 'normal',
            'choices' => array(
                'normal' => esc_html__( 'Normal', 'musico' ) ,
                'italic' => esc_html__( 'Italic', 'musico' ) ,
            )
        ) ,
    ) 
) );