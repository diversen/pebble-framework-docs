#!/usr/bin/env php
<?php

$files = [
    'src-docs/000-Setup.md',
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
        $link = "[$src_file]($src_file)";
        return $link . "\n\n" . $src_as_md;
    }
}

function get_toc($md)
{
    $toc_str = '';
    preg_match_all('/^(#+)(.+?)\n/uim', $md, $matches);
    if (empty($matches)) {
        return $toc_str;
    }

    $toc =  $matches[2];
    if (empty($toc)) return $toc_str;

    foreach ($toc as $header) {
        $header = trim($header);
        $hash = mb_strtolower($header);
        $hash = str_replace([' '], ['-'], $hash);
        $toc_str .= "* [$header](#$hash)\n";
    }

    return $toc_str;
}


function get_title(string $file)
{
    // Chapter title
    $part_pats = pathinfo($file);
    $title = $part_pats['filename'];

    // Remove '00-' '01-' part of filename
    $title_ary = explode('-', $title);
    $title = $title_ary[1];

    return $title;
}

function generate_toc_readme(array $files): string
{
    $md = "# Pebble Framework Documentation\n\n";

    foreach ($files as $key => $file) {
        $file = get_title($file);
        $md .= $key . '. ' . "[$file](#$file)\n";
    }

    return $md;
}

function generate_output(string $file)
{

    $title = get_title($file);

    $md = "## " . $title . "\n\n";

    // Chapter contents
    $content = file_get_contents($file);

    // Table of contents 
    $toc = get_toc($content);

    $md .= $toc . "\n\n";
    $md .= $content;

    // Insert src files <!-- include: some/src/file.php -->
    $md = preg_replace_callback('/<!--(.*)-->/Uis', 'preg_callback_insert_src', $md);

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


function generate_single_readme(array $files): void
{
    $readme = '';
    // $readme .= generate_toc_readme($files);

    $md_output = generate_docs($files);
    foreach ($md_output as $file => $md) {
        $readme .= "\n\n" . $md;
    }

    file_put_contents('README.md', trim($readme));
}


generate_single_readme($files);


// function generate_docs_folder(array $files): void
// {
//     $md_toc = generate_toc($files);
//     file_put_contents('docs/README.md', $md_toc);

//     $md_output = generate_docs($files);
//     foreach ($md_output as $file => $md) {
//         $md_basename = basename($file);
//         file_put_contents("docs/$md_basename", $md);
//     }
// }


// function generate_toc(array $files): string
// {
//     $md = "# Pebble Framework Documentation\n\n";

//     foreach ($files as $key => $file) {
//         $path_parts = pathinfo($file);
//         $file = $path_parts['filename'];
//         $md .= $key . '. ' . "[$file]($file.md)\n";
//     }

//     return $md;
// }

// generate_docs_folder($files);
