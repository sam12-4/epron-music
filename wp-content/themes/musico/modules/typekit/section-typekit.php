<?php

/**
 * Typekit
 */

$priority = 0;

Kirki::add_field( 'themegoods_customize', array(
    'type' => 'title',
    'settings'  => 'tg_typekit_title',
    'label'    => esc_html__('Typekit Settings', 'musico' ),
    'section'  => 'general_fonts',
	'priority' => 0,
) );


Kirki::add_field( 'themegoods_customize', array(
    'type' => 'switch',
    'settings' => 'tg_enable_typekit',
    'label' => esc_html__( 'Enable Typekit', 'musico' ) ,
    'section' => 'general_fonts',
    'default' => 0,
    'priority' => $priority,
    'transport' => 'auto',
    'choices' => array(
        'on'  => esc_html__( 'Enable', 'musico' ),
        'off' => esc_html__( 'Disable', 'musico' )
    )
) );

Kirki::add_field( 'themegoods_customize', array(
    'type' => 'text',
    'settings' => 'tg_typekit_id',
    'label' => esc_html__( 'Typekit ID', 'musico' ) ,
    'section' => 'general_fonts',
    'default' => '',
    'priority' => $priority,
    'transport' => 'auto',
    'required' => array(
        array(
            'setting' => 'tg_enable_typekit',
            'operator' => '==',
            'value' => '1',
        )
    ) ,
) );

Kirki::add_field( 'themegoods_customize', array(
    'type' => 'repeater',
    'label' => esc_html__( 'Typekit Fonts', 'musico' ) ,
    'description' => esc_html__( 'Here you can add typekit fonts', 'musico' ) ,
    'settings' => 'tg_typekit_fonts',
    'priority' => $priority,
    'transport' => 'auto',
    'section' => 'general_fonts',
    'row_label' => array(
        'type' => 'text',
        'value' => esc_html__( 'Typekit Font', 'musico' ) ,
    ),
    'default' => array(
        array(
            'font_name' => 'Europa',
            'font_css_name' => 'europa-web',
            'font_variants' => array( 'regular', 'italic', '700', '700italic' ),
            'font_fallback' => 'sans-serif',
            'font_subsets' => 'latin'
        )
    ),
    'fields' => array(
        'font_name' => array(
            'type' => 'text',
            'label' => esc_html__( 'Name', 'musico' ) ,
        ) ,
        'font_css_name' => array(
            'type' => 'text',
            'label' => esc_html__( 'CSS Name', 'musico' ) ,
        ) ,
        'font_variants' => array(
            'type' => 'select',
            'label' => esc_html__( 'Variants', 'musico' ) ,
            'multiple' => 18,
            'choices' => array(
                '100' => esc_html__( '100', 'musico' ) ,
                '100italic' => esc_html__( '100italic', 'musico' ) ,
                '200' => esc_html__( '200', 'musico' ) ,
                '200italic' => esc_html__( '200italic', 'musico' ) ,
                '300' => esc_html__( '300', 'musico' ) ,
                '300italic' => esc_html__( '300italic', 'musico' ) ,
                'regular' => esc_html__( 'regular', 'musico' ) ,
                'italic' => esc_html__( 'italic', 'musico' ) ,
                '500' => esc_html__( '500', 'musico' ) ,
                '500italic' => esc_html__( '500italic', 'musico' ) ,
                '600' => esc_html__( '600', 'musico' ) ,
                '600italic' => esc_html__( '600italic', 'musico' ) ,
                '700' => esc_html__( '700', 'musico' ) ,
                '700italic' => esc_html__( '700italic', 'musico' ) ,
                '800' => esc_html__( '800', 'musico' ) ,
                '800italic' => esc_html__( '800italic', 'musico' ) ,
                '900' => esc_html__( '900', 'musico' ) ,
                '900italic' => esc_html__( '900italic', 'musico' ) ,
            )
        ),
        'font_fallback' => array(
            'type' => 'select',
            'label' => esc_html__( 'Fallback', 'musico' ) ,
            'choices' => array(
                'sans-serif' => esc_html__( 'Helvetica, Arial, sans-serif', 'musico' ) ,
                'serif' => esc_html__( 'Georgia, serif', 'musico' ) ,
                'display' => esc_html__( '"Comic Sans MS", cursive, sans-serif', 'musico' ) ,
                'handwriting' => esc_html__( '"Comic Sans MS", cursive, sans-serif', 'musico' ) ,
                'monospace' => esc_html__( '"Lucida Console", Monaco, monospace', 'musico' ) ,
            )
        ) ,
        'font_subsets' => array(
            'type' => 'select',
            'label' => esc_html__( 'Subsets', 'musico' ) ,
            'multiple' => 7,
            'choices' => array(
                'cyrillic' => esc_html__( 'Cyrillic', 'musico' ) ,
                'cyrillic-ext' => esc_html__( 'Cyrillic Extended', 'musico' ) ,
                'devanagari' => esc_html__( 'Devanagari', 'musico' ) ,
                'greek' => esc_html__( 'Greek', 'musico' ) ,
                'greek-ext' => esc_html__( 'Greek Extended', 'musico' ) ,
                'khmer' => esc_html__( 'Khmer', 'musico' ) ,
                'latin' => esc_html__( 'Latin', 'musico' ) ,
            )
        ) ,
    ) ,
    'active_callback' => array(
        array(
            'setting' => 'tg_enable_typekit',
            'operator' => '==',
            'value' => '1'
        )
    )
) );