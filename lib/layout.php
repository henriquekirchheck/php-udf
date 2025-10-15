<?php

if (!array_key_exists('title', get_defined_vars()))
    exit('No $title set in page. Make sure it is set before the require_once.');

if (!array_key_exists('layout', get_defined_vars()))
    $layout = realpath(__DIR__.'/../src/layout.php');

header('Vary: HX-Request, HX-Boosted, X-Requested-With');
if (isset($_SERVER['HTTP_HX_REQUEST']) && 'true' === $_SERVER['HTTP_HX_REQUEST'])
    $layout = null;

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 'XMLHttpRequest' === $_SERVER['HTTP_X_REQUESTED_WITH'])
    $layout = null;

$boosted = (isset($_SERVER['HTTP_HX_BOOSTED']) && 'true' === $_SERVER['HTTP_HX_BOOSTED']);

ob_start();

function layout_shutdown(?string $layout, bool $boosted, string $title)
{
    global $error_triggered;

    $content = ob_get_contents();
    ob_end_clean();

    if (null === $layout) {
        if ($boosted)
            echo "<head><title>{$title}</title></head>";

        echo $content;

        return;
    }

    if ($error_triggered) {
        echo $content;

        return;
    }

    foreach (headers_list() as $header)
        if (preg_match('/^Location/', $header))
            return;

    require $layout;
}
register_shutdown_function('layout_shutdown', $layout, $boosted, $title);
