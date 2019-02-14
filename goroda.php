<?php

/**
 * Created by PhpStorm.
 * User: yarik
 * Date: 10.11.14
 * Time: 19:30
 */
class GPS
{
    protected $distances;

    function __constructor($dist)
    {
        $this->setDistances($dist);
    }

    public function setDistances($dist)
    {
        $this->distances = $dist;
    }

    protected function correct_distances()
    {
        $sum = 0;

        foreach ($this->distances as $row)
            $sum += array_sum($row);

        for ($i = 0; $i < count($this->distances); $i++) {
            for ($j = 0; $j < count($this->distances[$i]); $j++) {
                if ($this->distances[$i][$j] === "-") {
                    $this->distances[$i][$j] = $sum;
                }
            }
        }
    }

    protected function fix_optimal_path_length($from, $to)
    {
        for ($temp = 0; $temp < count($this->distances); $temp++) {
            $temp_distance = $this->distances[$from][$temp] + $this->distances[$temp][$to];
            if ($temp_distance < $this->distances[$from][$to]) {
                $this->distances[$from][$to] = $temp_distance;
            }
        }
    }

    protected function optimize_distances()
    {
        for ($from = 0; $from < count($this->distances); $from++) {
            for ($to = 0; $to < count($this->distances[$from]); $to++) {
                $this->fix_optimal_path_length($from, $to);
            }
        }
    }

    protected function optimize_all()
    {
        for ($i = 0; $i < count($this->distances) - 1; $i++) {
            $this->optimize_distances();
        }
    }

    public function print_array2()
    {
        $max = $this->distances[0][0];

        for ($from = 0; $from < count($this->distances); $from++) {
            for ($to = 0; $to < count($this->distances[$from]); $to++) {
                if ($this->distances[$from][$to] > $max) {
                    $max = $this->distances[$from][$to];
                }
            }
        }

        $max_length = strlen($max);

        for ($from = 0; $from < count($this->distances); $from++) {
            for ($to = 0; $to < count($this->distances[$from]); $to++) {
                echo str_pad($this->distances[$from][$to], $max_length + 2, " ", STR_PAD_LEFT), ' ';
            }
            echo "\n";
        }
    }

    public function findOptimalDistances()
    {
        $this->correct_distances();
        $this->optimize_all();
    }
}
