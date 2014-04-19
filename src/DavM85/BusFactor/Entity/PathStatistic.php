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
        'risky_count' => 0,
        'heroes' => array(),
        'busfactor' => 0,

        'authors' => array()
    );

    /**
     * @param string[] $commits
     */
    public function addCommit(Commit $commit)
    {
        $this->data['authors'][$commit->author] = 1;
        $this->data['authors_count'] = count(array_keys($this->data['authors']));
        if($this->data['authors_count'] > 1){
            $this->data['risky_count'] = 0;
            $this->data['busfactor'] = 0;
            $this->data['heroes'] = array();
        }
        else{
            $this->data['risky_count'] = 1;
            $this->data['busfactor'] = 100;
            $this->data['heroes'] = $this->data['authors'];
        }
    }

    /**
     * Used for aggregating more commits data.
     * Will change data answer.
     *
     * @param array $statistics
     */
    public function aggregate(array $statistics = array())
    {
        foreach($statistics as $st){
            $this->data['heroes'] = array_merge(
                $this->data['heroes'],
                $st->getData()['heroes']
            );

            $this->data['risky_count'] += $st->getData()['risky_count'];
        }

        $totalFile = count($statistics);
        $this->data['busfactor'] = $this->data['risky_count'] / $totalFile * 100;

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }
}
