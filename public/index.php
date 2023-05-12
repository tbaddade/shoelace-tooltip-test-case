<?php
include_once '../src/lib/WebpackEncoreResolver/AssetPathResolver.php';
include_once '../src/lib/WebpackEncoreResolver/functions.php';

use function Project\WebpackEncoreResolver\encore_entry_link_tags;
use function Project\WebpackEncoreResolver\encore_entry_script_tags;
?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Shoelace tooltip test case</title>
        <?= encore_entry_link_tags('app', __DIR__.'/build') ?>

        <?php
        // [Error] Unhandled Promise Rejection: Error: Invalid anchor element: no anchor could be found using the anchor slot or the anchor attribute.
        echo encore_entry_script_tags('app', __DIR__.'/build');
        ?>
    </head>
    <body style="text-align: center;">

        <h1>Shoelace tooltip test case</h1>
        <sl-tooltip content="This is a tooltip">
            <sl-button>Hover Me</sl-button>
        </sl-tooltip>

        <?php
        // No Error
        // echo encore_entry_script_tags('app', __DIR__.'/build');
        ?>
    </body>
</html>



