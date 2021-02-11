<?php

namespace App\Libraries\Jaro;

class JaroDistance
{
    const PREFIX_WEIGHT = 0.1;

    /**
     * Calculate
     *
     * @param string $s
     * @param string $t
     * @return int
     */
    public static function jaro($s, $t)
    {
        $s_len = strlen($s);
        $t_len = strlen($t);
        
        if ($s_len === 0 && $t_len === 0) {
            return 1;
        }
        
        $match_distance = (int) (max($s_len, $t_len) / 2) - 1;

        $s_matches = [];
        $t_matches = [];
        
        $matches = 0;
        $transpositions = 0;
        
        for ($i = 0; $i < $s_len; $i++) {
            $start = max(0, $i-$match_distance);
            $end = min($i+$match_distance+1, $t_len);

            for ($j = $start; $j < $end; $j++) {
                if (isset($t_matches[$j]) && $t_matches[$j]) {
                    continue;
                }

                if ($t_len - 1 < $j || $s{$i} != $t{$j}) {
                    continue;
                }
                $s_matches[$i] = true;
                $t_matches[$j] = true;
                $matches++;
                break;
            }
        }

        if ($matches === 0) {
            return 0;
        }

        $k = 0;
        for ($i = 0; $i < $s_len; $i++) {
            if (!isset($s_matches[$i]) || !$s_matches[$i]) {
                continue;
            }
            while (!isset($t_matches[$k]) || !$t_matches[$k]) {
                $k++;
            }
            if ($s{$i} != $t{$k}) {
                $transpositions++;
            }
            $k++;
        }

        return (((double) $matches / $s_len) +
                ((double) $matches / $t_len) +
                (((double) $matches - $transpositions/2.0) / $matches)) / 3.0;
    }

    /**
     * Winkler prefix scale
     * @param string $s
     * @param string $t
     * @return float|int
     */
    public static function jaroWinkler($s, $t)
    {
        $jaro = self::jaro($s, $t);

        $s_len = strlen($s);
        $t_len = strlen($t);

        if ($s_len === 0 || $t_len === 0) {
            return $jaro;
        }

        $loopLimitSize = min($s_len, $t_len);

        $prefixMatched = 0;

        // Get prefix matched
        for ($i = 0; $i < $loopLimitSize; $i++)
        {
            if ($s{$i} === $t{$i}) {
                $prefixMatched++;
            } else {
                break;
            }
        }

        return (double) $jaro + (self::PREFIX_WEIGHT * ($prefixMatched * (1 - $jaro)));
    }
}
