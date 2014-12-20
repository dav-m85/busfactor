<?php

namespace DavM85\BusFactor\Entity;


class Commit
{
    public $hash;
    public $author;
    public $email;
    public $timestamp;
    public $subject;
    public $paths = array();

    /*
     * hash, author name, author email, author timestamp, subject
     */
    public static $format = '# %H%n  %an%n  %ae%n  %at%n  %s';

    public static function parse(array $data)
    {
        $commits = array();
        $commit = null;

        while( ($line = array_shift($data)) !== null){
            // new commit
            if(0 === stripos($line, '#')){
                $commit = new self();
                if(! is_null($commit)){
                    array_push($commits, $commit);
                }

                $commit->hash = self::clean($line);
                $commit->author = self::clean(array_shift($data));
                $commit->email = self::clean(array_shift($data));
                $commit->timestamp = self::clean(array_shift($data));
                $commit->subject = self::clean(array_shift($data));
                continue;
            }
            if($commit){
                array_push($commit->paths, trim($line));
            }
        }

        return $commits;
    }

    public static function clean($string)
    {
        return trim(substr($string, 2));
    }
}
