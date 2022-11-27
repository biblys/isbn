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
    private $prefixes, $groups;

    public function __construct()
    {
        $this->prefixes = include('prefixes-array.php');
        $this->groups = include('groups-array.php');
    }

    public function getPrefixes()
    {
        return $this->prefixes;
    }

    public function getGroups()
    {
        return $this->groups;
    }
}
