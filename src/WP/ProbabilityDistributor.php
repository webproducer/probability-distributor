<?php
namespace WP;

class ProbabilityDistributor {

    private static $rmax = 0;
    
    private $_sumW = 0;
    private $_items = [];
    private $_weightsVector = [];

    
    /**
     * @param array $itemsWithWeights - [[$item, $weight], ...]
     */
    public function __construct($itemsWithWeights) {
        $sorted = $itemsWithWeights;
        usort($sorted, function($a, $b) {
            return ($a[1] === $b[1]) ? 0 : (($a[1] > $b[1]) ? -1 : 1);
        });
        $current = 0;
        $this->_weightsVector = array_map(function($item) use (&$current) {
            $current += $item[1];
            return $current;
        }, $sorted);
        $this->_items = array_map(function($item) use (&$current) {
            return $item[0];
        }, $sorted);
        $this->_sumW = $current;
        if (!self::$rmax) {
            self::$rmax = getrandmax();
        }
    }

    /**
     * @return mixed - Some random item from given list
     */
    public function next() {
        $point = self::randF(0, $this->_sumW);
        return $this->_items[self::locatePoint($point, $this->_weightsVector)];
    }

    /**
     * @return \Generator
     */
    public function generate($count = -1) {
        $generated = 0;
        while ($generated < $count) {
            $generated++;
            yield $this->next();
        }
    }

    private static function locatePoint($point, $vector) {
        $l = 0;
        $r = count($vector);
        while (($r - $l) > 1) {
            $m = self::round(($l + $r) / 2);
            if ($point < $vector[$m]) {
                $r = $m;
            } else {
                $l = $m;
            }
        }
        return (($l === 0) && ($point < $vector[$l])) ? 0 : $l+1;
    }

    private static function round($num) {
        return intval(round($num));
    }

    private static function randF($min, $max) {
        return $min + rand(0, self::$rmax) / self::$rmax * abs($max - $min);
    }

}