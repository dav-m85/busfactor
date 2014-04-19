<?php

namespace DavM85\BusFactor\Entity;

/**
 * Translates a git log output into something edible
 *
 * @package DavM85\BusFactor\Entity
 */
class PathStatistic
{
    private $data = array(
        'risky_count' => 1,
        'heroes' => array(),
        'busfactor' => 32,

        'authors' => array()
    );

    /**
     * @param string[] $commits
     */
    public function addCommit(Commit $commit)
    {
        $this->data['authors'][$commit->author] = 1;
        $this->data['authors_count'] = count(array_keys($this->data['authors']));

    }

    /**
     * Used for aggregating more commits data.
     * Will change data answer.
     *
     * @param array $statistics
     */
    public function aggregate(array $statistics = array())
    {
        // MapReduce !
        /*
        $counts = array_map(function ($data) {return $data->getData()['count'];}, $statistics);
        $this->data['count'] = array_reduce($counts, function ($a, $b) {
            return $a >= $b ? $a : $b;
        });
        */
        foreach($statistics as $st){
            $this->data['authors'] = array_merge(
                $this->data['authors'],
                $st->getData()['authors']
            );
            $this->data['monthAuthors'] = array_merge(
                $this->data['monthAuthors'],
                $st->getData()['monthAuthors']
            );
            $this->data['hashes'] = array_merge(
                $this->data['hashes'],
                $st->getData()['hashes']
            );
        }

        $this->data['authors_count'] = count(array_keys($this->data['authors']));
        $this->data['hashes_count'] = count(array_keys($this->data['hashes']));
        $this->data['monthAuthors_count'] = count(array_keys($this->data['monthAuthors']));

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }
}
