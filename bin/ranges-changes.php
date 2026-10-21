<?php

/*
 * This file is part of the biblys/isbn package.
 *
 * (c) Clément Latzarus
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 *
 * Usage: php bin/ranges-changes.php <old-groups-array.php> <new-groups-array.php>
 *
 * Prints a markdown summary of the registration groups (countries/languages)
 * that were added or whose ranges changed between two versions of
 * groups-array.php. Used to write release notes.
 *
 */

if ($argc !== 3) {
    exit("Usage: php bin/ranges-changes.php <old-file> <new-file>\n");
}

$old = include($argv[1]);
$new = include($argv[2]);

$added = [];
$changed = [];
$removed = [];

foreach ($new as $prefix => $group) {
    $label = sprintf('%s (%s)', $group[0], $prefix);
    if (!isset($old[$prefix])) {
        $added[] = $label;
    } elseif ($old[$prefix] !== $group) {
        $changed[] = $label;
    }
}

foreach ($old as $prefix => $group) {
    if (!isset($new[$prefix])) {
        $removed[] = sprintf('%s (%s)', $group[0], $prefix);
    }
}

$sections = [
    'New registration groups' => $added,
    'Registration groups with updated ranges' => $changed,
    'Removed registration groups' => $removed,
];

foreach ($sections as $title => $items) {
    if (count($items) === 0) {
        continue;
    }
    echo "### $title\n\n";
    foreach ($items as $item) {
        echo "- $item\n";
    }
    echo "\n";
}
