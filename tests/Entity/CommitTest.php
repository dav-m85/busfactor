<?php

use \DavM85\BusFactor\Entity\Commit;

class CommitTest extends \PHPUnit_Framework_TestCase
{
    public function testStaticParse()
    {
        $raw = <<<DATA
# ffe74f18630c4250531d44db8380bb550aec29ef
  David Moreau
  dav.m85@gmail.com
  1397926004
  adding tests suits
path1
path2
DATA;

        $commits = Commit::parse(explode(PHP_EOL, $raw));
        $this->assertTrue(is_array($commits));
        $this->assertCount(1, $commits);

        $commit = array_shift($commits);
        $this->assertEquals('ffe74f18630c4250531d44db8380bb550aec29ef', $commit->hash);
        $this->assertEquals('David Moreau', $commit->author);
        $this->assertEquals('dav.m85@gmail.com', $commit->email);
        $this->assertEquals('1397926004', $commit->timestamp);
        $this->assertEquals(array('path1', 'path2'), $commit->paths);
    }

}