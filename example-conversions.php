<?php

function bub_convert_block($src_block_str, $target_template) {

    $src_block = parse_blocks($src_block_str)[0];

    $files = [];

    foreach ($src_block['innerBlocks'] as $i => $file_block) {
        $files[] = [
            'id'=> $file_block['attrs']['id'],
            'i'=> $i
        ];
    }

    $m = new Mustache_Engine(array('entity_flags' => ENT_QUOTES));
    return $m->render($target_template, [
        'files' => $files,
        'file_count' => count($files),
    ]);

}



$src_template = [
    'acea/group-content', 
    [ "type"=>"_pdf_" ],
    [
        'core/file',
        [
            "id"=>"{{id}}"
        ]
    ]
];

ob_start();
?>
<!-- wp:acea/group-content {"type":"_pdf_","allowedBlocks":["core/file"],"max":900} -->
    <div class="_pdf_ block-container">
        <!-- wp:file {"id":920,"href":"http://localhost/files/Screenshot-from-2020-05-23-14-53-28-1.png"} -->
            <div class="wp-block-file"><a href="http://localhost/files/Screenshot-from-2020-05-23-14-53-28-1.png">Screenshot-from-2020-05-23-14-53-28-1</a><a href="http://localhost/files/Screenshot-from-2020-05-23-14-53-28-1.png" class="wp-block-file__button" download>Download</a></div>
        <!-- /wp:file -->

        <!-- wp:file {"id":915,"href":"http://localhost/files/Screenshot-from-2020-05-23-14-53-28.png"} -->
            <div class="wp-block-file"><a href="http://localhost/files/Screenshot-from-2020-05-23-14-53-28.png">Screenshot-from-2020-05-23-14-53-28</a><a href="http://localhost/files/Screenshot-from-2020-05-23-14-53-28.png" class="wp-block-file__button" download>Download</a></div>
        <!-- /wp:file -->
    </div>
<!-- /wp:acea/group-content -->
<?php
$src_block = trim(ob_get_clean());
ob_start();
?>
<!-- wp:acea/group-content {"type":"cntnt","allowedBlocks":[],"max":900} -->
    <div class="cntnt block-container">
        <!-- wp:acf/files {
            "id":"block_5f3e47cb52c75",
            "name":"acf/files",
            "data":{
                {{#files}}
                "files_{{i}}_file":{{id}},
                "_files_{{i}}_file":"field_5f3e4591bd4e6",
                {{/files}}
                "files":{{file_count}},
                "_files":"field_5f3e4578bd4e5"
            },
            "mode":"edit"
        }/-->
    </div>
<!-- /wp:acea/group-content -->
<?php
$target_template = trim(ob_get_clean());

echo '<pre>'.htmlentities(bub_convert_block($src_block, $target_template)).'</pre>';

wp_die();