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

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPUnit\Framework\TestCase;

class rangesChangesTest extends TestCase
{
    private function runScript(array $old, array $new)
    {
        $oldFile = $this->writeGroups($old);
        $newFile = $this->writeGroups($new);

        $command = sprintf(
            '%s %s %s %s',
            escapeshellarg(PHP_BINARY),
            escapeshellarg(__DIR__ . '/../bin/ranges-changes.php'),
            escapeshellarg($oldFile),
            escapeshellarg($newFile)
        );
        $output = (string) shell_exec($command);

        unlink($oldFile);
        unlink($newFile);

        return $output;
    }

    private function writeGroups(array $groups)
    {
        $file = tempnam(sys_get_temp_dir(), 'groups');
        file_put_contents($file, '<?php return ' . var_export($groups, true) . ';');

        return $file;
    }

    public function testNoChanges()
    {
        $groups = ['978-2' => ['French language', [['0000000', '1999999', 2]]]];

        $this->assertSame('', $this->runScript($groups, $groups));
    }

    public function testNewGroup()
    {
        $old = ['978-2' => ['French language', [['0000000', '1999999', 2]]]];
        $new = $old + ['978-99' => ['Atlantis', [['0000000', '9999999', 3]]]];

        $this->assertSame(
            "### New registration groups\n\n- Atlantis (978-99)\n\n",
            $this->runScript($old, $new)
        );
    }

    public function testUpdatedGroup()
    {
        $old = [
            '978-2' => ['French language', [['0000000', '1999999', 2]]],
            '978-3' => ['German language', [['0000000', '1999999', 2]]],
        ];
        $new = $old;
        $new['978-2'][1][0] = ['0000000', '2999999', 2];

        $this->assertSame(
            "### Registration groups with updated ranges\n\n- French language (978-2)\n\n",
            $this->runScript($old, $new)
        );
    }

    public function testRemovedGroup()
    {
        $old = [
            '978-2' => ['French language', [['0000000', '1999999', 2]]],
            '978-3' => ['German language', [['0000000', '1999999', 2]]],
        ];
        $new = ['978-2' => $old['978-2']];

        $this->assertSame(
            "### Removed registration groups\n\n- German language (978-3)\n\n",
            $this->runScript($old, $new)
        );
    }
}
