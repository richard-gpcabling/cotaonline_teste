<?php

namespace App\Helpers;



class UtilHelper
{
    /**
     * Extract columns from object
     *
     * @param object     $object
     * @param array      $columns
     * @param null|mixed $defaultValue
     * @return stdClass
     */
    public static function extractColumnsFromObject($object, $columns = [], $defaultValue = null)
    {
        $data = new \stdClass();
        foreach ($columns as $column) {
            if (isset($object->$column)) {
                $data->$column = $object->$column;
            } else {
                $data->$column = $defaultValue;
            }
        }
        return $data;
    }

    /**
     * Extract columns from object
     *
     * @param object     $object
     * @param array      $columns
     * @return stdClass
     */
    public static function assignEntity($object, $columns = [])
    {
        $object->assign($columns);
        return $object;
    }

    /**
     * Encode to UTF-8
     *
     * @param string $string
     * @return false|string|string[]|null
     */
    public static function encodeToUtf8($string)
    {
        return mb_convert_encoding($string, "UTF-8", mb_detect_encoding($string, "UTF-8, ISO-8859-1, ISO-8859-15", true));
    }

    /**
     * Encode to ISO
     *
     * @param $string
     * @return false|string|string[]|null
     */
    public static function encodeToIso($string)
    {
        return mb_convert_encoding($string, "ISO-8859-1", mb_detect_encoding($string, "UTF-8, ISO-8859-1, ISO-8859-15", true));
    }

    /**
     * Normalize text
     *
     * @param $str
     * @return string
     */
    public static function mbConvertCaseTitle($str)
    {
        return mb_convert_case($str, MB_CASE_TITLE);
    }

    /**
     * Get messages
     *
     * @param \Phalcon\Validation\Message\Group $messages
     * @return array
     */
    public static function getValidatorMessages(\Phalcon\Validation\Message\Group $messages)
    {
        $result = [];
        foreach ($messages as $message) {
            $result[] = $message->getMessage();
        }
        return $result;
    }

    /**
     * Make trim
     *
     * @param string $str
     * @param int    $length
     * @param string $limiter
     * @param string $suffix
     * @return string
     */
    public static function makeTrim($str, $length, $limiter = ' ', $suffix = '')
    {
        $strLen = strlen($str);

        if ($strLen <= $length) {
            return $str;
        }

        $strSubstr = substr($str, 0, $length);

        $strrpos = strrpos($strSubstr, $limiter);
        if (!$strrpos) {
            return $strSubstr . $suffix;
        }
        return substr($strSubstr, 0, $strrpos) . $suffix;
    }

    /**
     * Substr first occurrence
     *
     * @param $str
     * @param $occurrence
     * @param bool $removeOccurrence
     * @return bool|string
     */
    public static function substrFirstOcurrence($str, $occurrence, $includeOccurrence = false)
    {
        $pos = strpos($str, $occurrence);
        if (!$pos) {
            return $str;
        } elseif ($includeOccurrence === true) {
            $pos++;
        }
        return substr($str, 0, $pos);
    }

    /**
     * Array union
     *
     * @param array $array1
     * @param array $array2
     * @return array
     */
    public static function array_union($array1, $array2)
    {
        return array_merge(
            array_intersect($array1, $array2),
            array_diff($array1, $array2),
            array_diff($array2, $array1)
        );
    }


    public static function maskNumber($value,$mask){
        $maskared = '';
        $k = 0;
        
        for($i = 0; $i<=strlen($mask)-1; $i++)
        {
            if($mask[$i] == '#')
            {
                if(isset($value[$k]))
                $maskared .= $value[$k++];
            }
            else
            {
                if(isset($mask[$i]))
                $maskared .= $mask[$i];
            }
        }
        
        return $maskared;
    }

    public static function requestURI(){
        return  str_replace('/','**',$_SERVER['REQUEST_URI']);
    }

    public static function converteUnidadeVenda($unidade_venda){
        switch ($unidade_venda) {
            case 'PC':
                return 'Peça';
                break;
            
            case 'MT':
                return 'Metro';
                break;

            default:
                return 'Caixa';
                break;
        }
    }

    public static function showPercentage($p,$t){
        $rtn = 0;

        if($p > 0 OR $p != 'Não possui'):
            $t = str_replace('.','',$t);
            $t = str_replace(',','.',$t);
            $t = floatval($t);
            $p = str_replace('.','',$p);
            $p = str_replace(',','.',$p);
            $p = floatval($p);

            //dd($p);

            //$p = number_format($p,2);
            //$t = number_format($t,2);

            $rtn = ($p * $t)/100;

            $rtn = number_format($rtn,'2',',','.');
        endif;

        return $rtn;
    }
    /*
    UploadFileName: altera o nome dos arquivos enviados para o padrão linux.
    */
    public static function UploadFileName($s) {
        //Return Value
        $rtn = [];
      
        //Lower Case
        $s = strtolower($s); //small caps

        //Swaps
        $s = str_replace(" ","_",$s);
        $s = str_replace("ã","a",$s);
        $s = str_replace("Ã","a",$s);
        $s = str_replace("Õ","o",$s);
        $s = str_replace("ç","c",$s);
        $s = str_replace("Ç","c",$s);

        //Split String
        $s = str_split($s);

        //Valid Characters
        $vc = ["a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","0","1","2","3","4","5","6","7","8","9","_","."];

        //Verifica se os characteres da string pertencem ao array de characteres válidos
        foreach ($s as $e):
            if (in_array($e,$vc)):
                $rtn[] = $e;
            endif;
        endforeach;

        $rtn = implode($rtn);

        return $rtn;
   }
}
