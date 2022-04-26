<?php

/*
 * This file is part of the biblys/isbn package.
 *
 * (c) Clément Latzarus
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

namespace Biblys\Isbn;

class Ranges
{
    private static $prefixes, $groups;

    public static function getPrefixes()
    {
        if (!isset(self::$prefixes)) {
           self::$prefixes = include('prefixes-array.php');
        }
        return self::$prefixes;
    }

    public static function getGroups()
    {
        if (!isset(self::$groups)) {
           self::$groups = include('groups-array.php');
        }
        return self::$groups;
    }
}
