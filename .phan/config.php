<?php
return [
    'target_php_version' => '7.2',
    'directory_list' => [
        'src',
        'vendor'
    ],
    'exclude_file_regex' => '@^vendor/.*/(tests?|Tests?)/@',
    'exclude_analysis_directory_list' => [
        'vendor/'
    ],
 	'file_list' => [
 		'vendor/symfony/framework-bundle/Test/WebTestCase.php',
 		'vendor/symfony/framework-bundle/Test/KernelTestCase.php',
  	]
];