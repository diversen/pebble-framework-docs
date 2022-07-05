#!/usr/bin/env php
<?php

/**
 * This script prepares for generating mkdocs
 * 
 */

require "vendor/autoload.php";

$files = [
    'src-docs/000-index.md',
    'src-docs/100-Router.md',
    'src-docs/110-AppExec.md',
    'src-docs/180-Special.md',
    'src-docs/200-Template.md',
    'src-docs/300-Logging.md',
    'src-docs/400-Config.md',
    'src-docs/500-DB.md',
    'src-docs/600-Migration.md',
    'src-docs/700-Auth.md',
    'src-docs/800-ACL.md',
    'src-docs/900-ACLRole.md',
    'src-docs/910-Flash.md',
    'src-docs/920-SMTP.md',
    'src-docs/922-CLI.md',
    'src-docs/930-Misc.md',
];

/**
 * function that takes a matched comment and substitutes it with a 
 * markdown source code block
 */
function preg_callback_insert_src($match)
{
    $github_base_url = "https://github.com/diversen/pebble-framework-docs/blob/main"; 
    $include = trim($match[1]);
    $content = explode(':', $include);
    if (trim($content[0] === 'include')) {
        $src_file = trim($content[1]);
        if (!file_exists($src_file)) {
            throw new Exception(
                $src_file . " not found when trying to include src file into markdown"
            );
        }

        $ext = pathinfo($src_file, PATHINFO_EXTENSION);

        $file = file_get_contents($src_file);
        $src_as_md = <<<EOF
~~~$ext
$file
~~~
EOF;    
        $link = "(<a href='$github_base_url/$src_file' target='_blank'>$src_file</a>)";
        $link = "($src_file) -&gt;";
        // $link = '<a href="http://example.com/" target="_blank">example</a>';
        return $link . "\n\n" . $src_as_md;
    }
}


function get_title(string $file)
{
    // Chapter title
    $part_pats = pathinfo($file);
    $title = $part_pats['filename'];

    $title_ary = explode('-', $title);
    $title = $title_ary[1];

    if ($title === 'index') $title = 'Home';

    return $title;
}

function generate_output(string $file)
{

    $title = get_title($file);

    // $md = "## " . "$title" . "\n\n";

    // Chapter contents
    $content = file_get_contents($file);

    // $md .= $toc . "\n\n";
    $md = $content;

    // Insert src files <!-- include: some/src/file.php -->
    $md = preg_replace_callback('/<!--(.*)-->/Uis', 'preg_callback_insert_src', $md) . "\n\n";

    $md.= "<hr /><a href='https://github.com/diversen/pebble-framework-docs/blob/main/$file'>Edit this page on GitHub</a>";

    return $md;
}

function generate_docs(array $files): array
{
    $md_ouput = [];
    foreach ($files as $file) {
        if (!file_exists($file)) exit($file . " not found when trying to include src file into markdown");
        $md_ouput[$file] = generate_output($file);
    }

    return $md_ouput;
}

function generate_mkdocs(array $files): void
{

    $md_output = generate_docs($files);
    foreach ($md_output as $file => $md) {
        $md_basename = basename($file);
        [, $title] = explode('-', $md_basename);
        file_put_contents("docs/$title", $md);
    }
}


generate_mkdocs($files);
