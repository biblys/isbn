<?php

/*
 * This file is part of the biblys/isbn package.
 *
 * (c) Clément Bourgoin
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

namespace Biblys\Isbn;

class Ranges
{
    private static $prefixes = include('prefixes-array.php');
    private static $groups = include('groups-array.php');

    public function getPrefixes()
    {
        return self:$prefixes;
    }

    public function getGroups()
    {
        return self:$groups;
    }
}
