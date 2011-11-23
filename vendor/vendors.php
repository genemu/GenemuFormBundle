#!/usr/bin/env php

<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Olivier Chauvel <olivier@generation-multiple.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

set_time_limit(0);

$vendorDir = __DIR__;
$version = isset($_SERVER['SYMFONY_VERSION']) ? $_SERVER['SYMFONY_VERSION'] : 'origin/master';

$deps = array(
    array('symfony', 'git://github.com/symfony/symfony.git', $version),
    array('doctrine', 'git://github.com/doctrine/doctrine2.git', 'origin/master'),
    array('doctrine-common', 'git://github.com/doctrine/common.git', 'origin/master'),
    array('doctrine-dbal', 'git://github.com/doctrine/dbal.git', 'origin/master'),
    array('doctrine-mongodb', 'git://github.com/doctrine/mongodb.git', 'origin/master'),
    array('doctrine-mongodb-odm', 'git://github.com/doctrine/mongodb-odm.git', 'origin/master'),
);

foreach ($deps as $dep) {
    list($name, $url, $rev) = $dep;

    echo "> Installing/Updating $name\n";

    $installDir = $vendorDir.'/'.$name;
    if (!is_dir($installDir)) {
        system(sprintf('git clone -q %s %s', escapeshellarg($url), escapeshellarg($installDir)));
    }

    system(sprintf('cd %s && git fetch -q origin && git reset --hard %s', escapeshellarg($installDir), escapeshellarg($rev)));
}
