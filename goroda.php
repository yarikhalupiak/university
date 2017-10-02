<?php 
/** 
 * Created by PhpStorm. 
 * User: Taras 
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

    /** 
     * Устанавливает начальные расстояния 
     * @param $dist Массив расстояний 
     */ 
    public function setDistances($dist) 
    { 
        // :TODO: проверить формат $dist 
        $this->distances = $dist; 
    } 

    /** 
     * 
     * 
     */ 
    protected function correct_distances() 
    { 
        // исправляем '-' на MAX число, чтобы не мешали искать самые короткие пути 
        $sum = 0; 
        // вычисляем сумму всех 
        foreach($this->distances as $row) 
            $sum += array_sum($row); 
        // вместо '-' подставляем найденную сумму  
        for($i = 0; $i < count($this->distances); $i++) { 
            for($j = 0; $j < count($this->distances[$i]); $j++) { 
                if ($this->distances[$i][$j] === "-") { 
                    $this->distances[$i][$j] = $sum; 
                } 
            } 
        } 
    } 


    protected function fix_optimal_path_length($from, $to) 
    { 
        // пытаемся улучшить оценку расстояния : 
        // перезаписываем в $dist[$from][$to] длину пути через 3-й (потенциально - любой) узел, 
        //  если такой (окольный) путь окажется короче 
        for($temp = 0; $temp < count($this->distances); $temp++) { 
            $temp_distance = $this->distances[$from][$temp] + $this->distances[$temp][$to]; 
            if ($temp_distance < $this->distances[$from][$to]) { 
                $this->distances[$from][$to] = $temp_distance; 
            } 
        } 
    } 


    protected function optimize_distances() 
    { 
        // для каждой ячейки $dist (т.е. для каждого пути из $from в $to) 
        // пытаемся найти лучший путь, проходящий через 1 промежуточный узел 
        for($from = 0; $from < count($this->distances); $from++) { 
            for($to = 0; $to < count($this->distances[$from]); $to++) { 
                $this->fix_optimal_path_length($from, $to); 
            } 
        } 
    } 


    protected function optimize_all() 
    { 
        // просто запускаем постепенное "улучшение" оценкок пути несколько раз 
        // для нахождения точно оптимального расстояния 
        //  (достаточно - log2(N) раз, где N - количество узлов, 
        //   мы повторим N-1 раз) 
        for($i = 0; $i < count($this->distances) - 1; $i++) { 
            $this->optimize_distances(); 
            //        посмотрим на промежуточные результатаы оптимизации (после каждой итерации): 
            //        print_array2($this->distances); 
            //        echo "\n"; 
        } 
    } 


    public function print_array2() 
    { 
        // "красивая" распечатка оптимальных дистанций 
        //  на текущий момент (!) 
        $max = $this->distances[0][0]; 
        // сначала - подготовка к распечатке 
        for($from = 0; $from < count($this->distances); $from++) { 
            for($to = 0; $to < count($this->distances[$from]); $to++) { 
                if ($this->distances[$from][$to] > $max) { 
                    $max = $this->distances[$from][$to]; 
                } 
            } 
        } 
        $max_length = strlen($max); 
        // а теперь - собственно вывод "на экран" (в ответ) - echo 
        for($from = 0; $from < count($this->distances); $from++) { 
            for($to = 0; $to < count($this->distances[$from]); $to++) { 
                echo str_pad($this->distances[$from][$to], $max_length+2, " ", STR_PAD_LEFT), ' '; 
            } 
            echo "\n"; 
        } 
    } 


    public function findOptimalDistances() 
    { 
        // 1) исправляем '-' на MAX число, чтобы не мешали искать самые короткие пути 
        $this->correct_distances(); 

        // 2) "оптимизируем": находим самые короткие пути 
        //    между всеми парами узлов (городов) 
        //     (итерационно, по разработанному алгоритму) 
        $this->optimize_all(); 
    } 

    // print_r($distances)