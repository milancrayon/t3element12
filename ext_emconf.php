<?php
$EM_CONF[$_EXTKEY] = array(
    'title' => 'T3element',
    'description' => 'Create Custom Element using T3element',
    'category' => 'be',
    'author' => 'T3Element',
    'author_email' => 'info@t3element.com',
    'author_company' => 'Crayonwebstudio private limited',
    'state' => 'stable',
    'version' => '1.0.8',
    'clearcacheonload' => true,
    'constraints' => array(
        'depends' => array(
            'typo3' => '12.0.0-12.4.99',
            'container' => '3.1.0'
        ),
        'conflicts' =>array(),
        'suggests' => array(),
    )
);