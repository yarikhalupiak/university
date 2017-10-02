<?php

class player
{
        public $my = array();
       
        public $player2 = array();
      
        public $free = array();
     
        private $c = null;
       
        private $iq = null;
      
        private $matrix;
        
        public function __construct($c, $iq, &$matrix)
        {
                $this -> c = $c;
                $this -> iq = $iq;
                $this -> matrix = &$matrix;
        }
        
        public function run($x = null, $y = null)
        {
              
                $this -> estimate();
                if (is_null($x) || is_null($y))
                {
                     $go = $this -> bot();
                   
                        if (!isset($go[1]))
                        {
                                return 0;
                        }
                        $x = $go[1];
                        $y = $go[0];
                }
                
                if (isset($this -> free[$y][$x]))
                {
                        // Вліво
                        if (isset($this -> player2[$y][$x-1]))
                        {
                                for ($xc = $x - 2; $xc >= 0; $xc--)
                                {
                                       
                                        if (isset($this -> my[$y][$xc]) && isset($this -> player2[$y][$xc+1]))
                                        {
                                                $end = $xc;
                                                break;
                                        }
                                      
                                        elseif (isset($this -> free[$y][$xc+1]))
                                        {
                                                break;
                                        }
                                }
                                
                                if (isset($end))
                                {
                                       
                                        for ($xc = $x; $xc >= $end; $xc--)
                                        {
                                                $this -> matrix[$y][$xc] = $this -> c;
                                        }
                                        unset($end);
                                }
                                
                        }
                        // Вправо
                        if (isset($this -> player2[$y][$x+1]))
                        {
                                for ($xc = $x + 2; $xc < 8; $xc++)
                                {
                                        if (isset($this -> my[$y][$xc]) && isset($this -> player2[$y][$xc-1]))
                                        {
                                                $end = $xc;
                                                break;
                                        }
                                        elseif (isset($this -> free[$y][$xc-1]))
                                        {
                                                break;
                                        }
                                }
                                if (isset($end))
                                {
                                        for ($xc = $x; $xc <= $end; $xc++)
                                        {
                                                $this -> matrix[$y][$xc] = $this -> c;
                                        }
                                        unset($end);
                                }
                        }
                        // Вверх
                        if (isset($this -> player2[$y-1][$x]))
                        {
                                for ($yc = $y - 2; $yc >= 0; $yc--)
                                {
                                        if (isset($this -> my[$yc][$x]) && isset($this -> player2[$yc+1][$x]))
                                        {
                                                $end = $yc;
                                                break;
                                        }
                                        elseif (isset($this -> free[$yc+1][$x]))
                                        {
                                                break;
                                        }
                                }
                                if (isset($end))
                                {
                                        for ($yc = $y; $yc >= $end; $yc--)
                                        {
                                                $this -> matrix[$yc][$x] = $this -> c;
                                        }
                                        unset($end);
                                }
                        }
                        // Вниз
                        if (isset($this -> player2[$y+1][$x]))
                        {
                                for ($yc = $y + 2; $yc < 8; $yc++)
                                {
                                        if (isset($this -> my[$yc][$x]) && isset($this -> player2[$yc-1][$x]))
                                        {
                                                $end = $yc;
                                                break;
                                        }
                                        elseif (isset($this -> free[$yc-1][$x]))
                                        {
                                                break;
                                        }
                                }
                                if (isset($end))
                                {
                                        for ($yc = $y; $yc <= $end; $yc++)
                                        {
                                                $this -> matrix[$yc][$x] = $this -> c;
                                        }
                                        unset($end);
                                }
                        }
                        // Диагональ ПН
                        if (isset($this -> player2[$y+1][$x+1]))
                        {
                                for ($yc = $y + 2, $xc = $x + 2; $yc < 8 && $xc < 8; $yc++, $xc++)
                                {
                                        if (isset($this -> my[$yc][$xc]) && isset($this -> player2[$yc-1][$xc-1]))
                                        {
                                                $end = true;
                                                $end_y = $yc;
                                                $end_x = $xc;
                                                break;
                                        }
                                        elseif (isset($this -> free[$yc-1][$xc-1]))
                                        {
                                                break;
                                        }
                                }
                                if (isset($end))
                                {
                                        for ($yc = $y, $xc = $x; $yc <= $end_y && $xc <= $end_x; $yc++, $xc++)
                                        {
                                                $this -> matrix[$yc][$xc] = $this -> c;
                                        }
                                        unset($end);
                                }
                        }
                        // Диагональ ПВ
                        if (isset($this -> player2[$y-1][$x+1]))
                        {
                                for ($yc = $y - 2, $xc = $x + 2; $yc >= 0 && $xc < 8; $yc--, $xc++)
                                {
                                        if (isset($this -> my[$yc][$xc]) && isset($this -> player2[$yc+1][$xc-1]))
                                        {
                                                $end = true;
                                                $end_y = $yc;
                                                $end_x = $xc;
                                                break;
                                        }
                                        elseif (isset($this -> free[$yc+1][$xc-1]))
                                        {
                                                break;
                                        }
                                }
                                if (isset($end))
                                {
                                        for ($yc = $y, $xc = $x; $yc >= $end_y && $xc <= $end_x; $yc--, $xc++)
                                        {
                                                $this -> matrix[$yc][$xc] = $this -> c;
                                        }
                                        unset($end);
                                }
                        }
                        // Диагональ ЛН
                        if (isset($this -> player2[$y+1][$x-1]))
                        {
                                for ($yc = $y + 2, $xc = $x - 2; $yc < 8 && $xc >= 0; $yc++, $xc--)
                                {
                                        if (isset($this -> my[$yc][$xc]) && isset($this -> player2[$yc-1][$xc+1]))
                                        {
                                                $end = true;
                                                $end_y = $yc;
                                                $end_x = $xc;
                                                break;
                                        }
                                        elseif (isset($this -> free[$yc-1][$xc+1]))
                                        {
                                                break;
                                        }
                                }
                                if (isset($end))
                                {
                                        for ($yc = $y, $xc = $x; $yc <= $end_y && $xc >= $end_x; $yc++, $xc--)
                                        {
                                                $this -> matrix[$yc][$xc] = $this -> c;
                                        }
                                        unset($end);
                                }
                        }
                        // Диагональ ЛВ
                        if (isset($this -> player2[$y-1][$x-1]))
                        {
                                for ($yc = $y - 2, $xc = $x - 2; $yc >= 0 && $xc >= 0; $yc--, $xc--)
                                {
                                        if (isset($this -> my[$yc][$xc]) && isset($this -> player2[$yc+1][$xc+1]))
                                        {
                                                $end = true;
                                                $end_y = $yc;
                                                $end_x = $xc;
                                                break;
                                        }
                                        elseif (isset($this -> free[$yc+1][$xc+1]))
                                        {
                                                break;
                                        }
                                }
                                if (isset($end))
                                {
                                        for ($yc = $y, $xc = $x; $yc >= $end_y && $xc >= $end_x; $yc--, $xc--)
                                        {
                                                $this -> matrix[$yc][$xc] = $this -> c;
                                        }
                                        unset($end);
                                }
                        }
                }
        }
        
       
        private function bot()
        {
                // Варианты ходов
                $ican = array();
                foreach ($this -> free as $y => $row)
                {
                        foreach ($row as $x => $t)
                        {
                                $curr = "$y:$x";
                                $ican[$curr] = 0;
                                
                                if (isset($this -> player2[$y][$x-1]))
                                {
                                        for ($xc = $x - 2; $xc >= 0; $xc--)
                                        {
                                                if (isset($this -> my[$y][$xc]) && isset($this -> player2[$y][$xc+1]))
                                                {
                                                        $end = $xc;
                                                        break;
                                                }
                                                elseif (isset($this -> free[$y][$xc+1]))
                                                {
                                                        break;
                                                }
                                        }
                                        if (isset($end))
                                        {
                                                $ican[$curr] += $x - $end - 1;
                                                unset($end);
                                        }
                                }
                                // Вправо
                                if (isset($this -> player2[$y][$x+1]))
                                {
                                        for ($xc = $x + 2; $xc < 8; $xc++)
                                        {
                                                if (isset($this -> my[$y][$xc]) && isset($this -> player2[$y][$xc-1]))
                                                {
                                                        $end = $xc;
                                                        break;
                                                }
                                                elseif (isset($this -> free[$y][$xc-1]))
                                                {
                                                        break;
                                                }
                                        }
                                        if (isset($end))
                                        {
                                                $ican[$curr] += $end - $x - 1;
                                                unset($end);
                                        }
                                }
                                // Вверх
                                if (isset($this -> player2[$y-1][$x]))
                                {
                                        for ($yc = $y - 2; $yc >= 0; $yc--)
                                        {
                                                if (isset($this -> my[$yc][$x]) && isset($this -> player2[$yc+1][$x]))
                                                {
                                                        $end = $yc;
                                                        break;
                                                }
                                                elseif (isset($this -> free[$yc+1][$x]))
                                                {
                                                        break;
                                                }
                                        }
                                        if (isset($end))
                                        {
                                                $ican[$curr] += $y - $end - 1;
                                                unset($end);
                                        }
                                }
                                // Вниз
                                if (isset($this -> player2[$y+1][$x]))
                                {
                                        for ($yc = $y + 2; $yc < 8; $yc++)
                                        {
                                                if (isset($this -> my[$yc][$x]) && isset($this -> player2[$yc-1][$x]))
                                                {
                                                        $end = $yc;
                                                        break;
                                                }
                                                elseif (isset($this -> free[$yc-1][$x]))
                                                {
                                                        break;
                                                }
                                        }
                                        if (isset($end))
                                        {
                                                $ican[$curr] += $end - $y - 1;
                                                unset($end);
                                        }
                                }
                                // ЛВ
                                if (isset($this -> player2[$y-1][$x-1]))
                                {
                                        for ($yc = $y - 2, $xc = $x - 2; $yc >= 0 && $xc >= 0; $yc--, $xc--)
                                        {
                                                if (isset($this -> my[$yc][$xc]) && isset($this -> player2[$yc+1][$xc+1]))
                                                {
                                                        $end = $yc;
                                                        break;
                                                }
                                                elseif (isset($this -> free[$yc+1][$xc+1]))
                                                {
                                                        break;
                                                }
                                        }
                                        if (isset($end))
                                        {
                                                $ican[$curr] += $y - $end - 1;
                                                unset($end);
                                        }
                                }
                                // ЛН
                                if (isset($this -> player2[$y+1][$x-1]))
                                {
                                        for ($yc = $y + 2, $xc = $x - 2; $yc < 8 && $xc >= 0; $yc++, $xc--)
                                        {
                                                if (isset($this -> my[$yc][$xc]) && isset($this -> player2[$yc-1][$xc+1]))
                                                {
                                                        $end = $yc;
                                                        break;
                                                }
                                                elseif (isset($this -> free[$yc-1][$xc+1]))
                                                {
                                                        break;
                                                }
                                        }
                                        if (isset($end))
                                        {
                                                $ican[$curr] += $end - $y - 1;
                                                unset($end);
                                        }
                                }
                                // ПВ
                                if (isset($this -> player2[$y-1][$x+1]))
                                {
                                        for ($yc = $y - 2, $xc = $x + 2; $yc >= 0 && $xc <= 8; $yc--, $xc++)
                                        {
                                                if (isset($this -> my[$yc][$xc]) && isset($this -> player2[$yc+1][$xc-1]))
                                                {
                                                        $end = $yc;
                                                        break;
                                                }
                                                elseif (isset($this -> free[$yc+1][$xc-1]))
                                                {
                                                        break;
                                                }
                                        }
                                        if (isset($end))
                                        {
                                                $ican[$curr] += $y - $end - 1;
                                                unset($end);
                                        }
                                }
                                // ПН
                                if (isset($this -> player2[$y+1][$x+1]))
                                {
                                        for ($yc = $y + 2, $xc = $x + 2; $yc <= 8 && $xc <= 8; $yc++, $xc++)
                                        {
                                                if (isset($this -> my[$yc][$xc]) && isset($this -> player2[$yc-1][$xc-1]))
                                                {
                                                        $end = $yc;
                                                        break;
                                                }
                                                elseif (isset($this -> free[$yc-1][$xc-1]))
                                                {
                                                        break;
                                                }
                                        }
                                        if (isset($end))
                                        {
                                                $ican[$curr] += $end - $y - 1;
                                                unset($end);
                                        }
                                }
                                
                                if ($ican[$curr] == 0)
                                {
                                        unset($ican[$curr]);
                                }
                        }
                }
               
                if ($this -> iq == 0)
                {
                        $move = array_rand($ican);
                }
                else
                {
                  
                        asort($ican);
                        end($ican);
                        $best = key($ican);
                     
                        if ($this -> iq === 1)
                        {
                                $move = $best;
                        }
                        // Иначе выбираем случайных из лучший
                        else
                        {
                                $more = array();
                                foreach ($ican as $k => $v)
                                {
                                        if ($v === $ican[$best])
                                        {
                                                $more[] = $k;
                                        }
                                }
                                if (!empty($more))
                                {
                                        $more = array_values($more);
                                        shuffle($more);
                                        $best = array_shift($more);
                                }
                                $move = $best;
                        }
                }
                return explode(':', $move);
        }
        
    
        public function estimate()
        {
                $this -> my = array();
                $this -> player2 = array();
                $this -> free = array();
                foreach ($this -> matrix as $y => $row)
                {
                        foreach ($row as $x => $cell)
                        {
                                if ($cell === $this -> c)
                                {
                                        $this -> my[$y][$x] = 1;
                                }
                                elseif ($cell !== 0)
                                {
                                        $this -> player2[$y][$x] = 1;
                                }
                                else
                                {
                                        $this -> free[$y][$x] = 1;
                                }
                        }
                }
        }
}
 

function change(&$matrix)
{
        foreach ($matrix as $row)
        {
                foreach ($row as $cell)
                {
                        if ($cell === 0)
                        {
                                $cell = '.';
                        }
                        echo "$cell ";
                }
                echo "\n";
        }
        echo "\r\n";
}

$matrix[0] = [0,0,0,0,0,0,0,0];
$matrix[1] = [0,0,0,0,0,0,0,0];
$matrix[2] = [0,0,0,0,0,0,0,0];
$matrix[3] = [0,0,0,'X','O',0,0,0];
$matrix[4] = [0,0,0,'O','X',0,0,0];
$matrix[5] = [0,0,0,0,0,0,0,0];
$matrix[6] = [0,0,0,0,0,0,0,0];
$matrix[7] = [0,0,0,0,0,0,0,0];
 
$player1 = new player('X', intval($_GET['iq1']), $matrix);
$player2 = new player('O', intval($_GET['iq2']), $matrix);
 
echo '<pre>';
for ($i=1; $i<=(8*8)-4; $i++)
{
        is_int($i/2)?$player2->run():$player1->run();
        change($matrix);
}


?>