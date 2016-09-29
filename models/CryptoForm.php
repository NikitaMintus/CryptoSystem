<?php
namespace app\models;

use yii\base\Exception;
use yii\base\Model;
use yii\web\UploadedFile;

define("ENCRYPT", 0);
define("DECRYPT", 1);

class CryptoForm extends Model
{

    public $currentAction; // encrypt or decrypt
    public $affineParams = ['a'=> 5, 'b'=> 7];
    public $currentMethod;
    public $alphabet = [];
    public $initialText = "";
    public $resultText = "";

    /**
     * @var UploadedFile
     */
    public $txtFile;
    /**
     * @var UploadedFile
     */
    public $resFile;

    function __construct() {

        parent::__construct();
    }



    public function rules()
    {
        return [
            [['txtFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'txt'],
            [['currentAction', 'currentMethod', 'affineParams'], 'required'],
            [['currentMethod'], 'checkSimpleDigit'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'txtFile' => 'Choose file',
            'currentMethod' => 'Choose encrypt method',
            'affineParams' => "Parameters must be integer",
        ];
    }

    public function checkSimpleDigit($attribute, $params) {
        $a = $attribute[0];
        $b = array_values($attribute)[1];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->txtFile->saveAs('uploads/' . $this->txtFile->baseName . '.' . $this->txtFile->extension);
            return true;
        } else {
            return false;
        }
    }

    public function readFile()
    {
        $fileName = 'uploads/' . $this->txtFile->baseName . '.' . $this->txtFile->extension;
        $fp = fopen($fileName, "r");

        $arr = [];

        while ( $line = fgets($fp, 1000) )
        {
            $nl = mb_strtoupper($line,'UTF-8');
            $arr[] = $nl;
        }

        print_r($arr);

    }

    public function readFileToStr()
    {
        $fileName = 'uploads/' . $this->txtFile->baseName . '.' . $this->txtFile->extension;
        $lines = file_get_contents($fileName);
        return $lines;

    }

    public function createAlphabet()
    {
        $curNum = 1;
        for($i = 65; $i <= 90; $i++) // chars
        {
            $this->alphabet[chr($i)] = $curNum++;
        }
        for($i = 48; $i <= 57; $i++) // digits
        {
            $this->alphabet[chr($i)] = $curNum++;
        }
    }

    public function encryptDecrypt()
    {
        $this->createAlphabet();
        $a = $this->affineParams['a'];
        $b = $this->affineParams['b'];
        $m = count($this->alphabet);
        $resNumChar = 0;
        $this->clearResult();

        if($this->checkSimpleDigits($a, $m))
        {
            for($i = 0; $i <= mb_strlen($this->initialText); $i++)
            {
                $curChar = mb_strtoupper($this->initialText[$i]);
                if(($numChar = $this->getNumCharFromAlphabet($curChar)) == -1) {
                    continue; // skip this char
                }
                switch($this->currentAction) {
                    case ENCRYPT:
                        $resNumChar = ($a * $numChar + $b) % $m;
                        break;
                    case DECRYPT:
                        $inverseA = $this->inverse($a, $m);
                        $resNumChar = $this->leadToValuesOfAlphabet(($inverseA * ($numChar - $b)) % $m, $m);
                        break;
                }

                $resChar = array_keys($this->alphabet)[$resNumChar - 1];
                $this->resultText .= $resChar;
            }
            $bnbn = 0;
            $this->writeInFile($this->resultText);
        }
    }

    public function leadToValuesOfAlphabet($curNumChar, $m) {
        while($curNumChar < 0) {
            $curNumChar += $m;
        }
        return $curNumChar;
    }

    public function clearResult()
    {
        $this->resultText = "";
    }

    public function checkSimpleDigits($a, $m)
    {
        while ($a != 0 && $m != 0)
        {
            if($a > $m) {
                $a = $a % $m;
            } else {
                $m = $m % $a;
            }
        }
        return $a + $m == 1 ? true : false;
    }

    public function getNumCharFromAlphabet($curChar)
    {
        return (($numChar = $this->alphabet[$curChar]) == null) ? -1 : $numChar;
    }

//    public function extended_euclid($a, $b, &$x, &$y, &$d)
//    {
////        $q = 0, $r = 0, $x1 = 0, $x2 = 0, $y1 = 0, $y2 = 0;
//
//        if ($b == 0) {
//            $d = $a;
//            $x = 1;
//            $y = 0;
//            return;
//        }
//
//        $x2 = 1;
//        $x1 = 0;
//        $y2 = 0;
//        $y1 = 1;
//
//        while ($b > 0) {
//            $q = $a / $b; //celoe
//            $r = $a - $q * $b; //ostatok ot delenia
//
//            $x = $x2 - $q * $x1;
//            $y = $y2 - $q * $y1;
//
//            $a = $b;
//            $b = $r;
//
//            $x2 = $x1;
//            $x1 = $x;
//            $y2 = $y1;
//            $y1 = $y;
//        }
//
//        $d = $a;
//
//        $x = $x2;
//        $y = $x1;
//
//    }
//    public function inverse($a, $n) {
//        $d = 0;
//        $x = 0;
//        $y = 0;
//        $this->extended_euclid($a, $n, $x, $y, $d);
//
//        if ($d == 1) {
//            if ($x < 0) {
//                return $x + $y;
//            }
//            return $x;
//        }
//        return 0;
//    }
    public function extended_euclid($a, $b, &$x, &$y, &$d)
    {
        $q = 0; $r = 0; $x1 = 0; $x2 = 0; $y1 = 0; $y2 = 0;

        if ($b == 0)
        {
            $d = a;
            $x = 1;
            $y = 0;
            return;
        }

        $x2 = 1;
        $x1 = 0;
        $y2 = 0;
        $y1 = 1;

        while ($b > 0)
        {
            $q = intval($a / $b);//celoe
            $r = $a - $q * $b;//ostatok ot delenia

            $x = $x2 - $q * $x1;
            $y = $y2 - $q * $y1;

            $a = $b;
            $b = $r;

            $x2 = $x1;
            $x1 = $x;
            $y2 = $y1;
            $y1 = $y;
        }

        $d = $a;
        //x = x2;
        //y = y2;
        $x = $x2;
        $y = $x1;
    }

    public function inverse($a, $n)
    {
        $d = 0; $x = 0; $y = 0;

        $this->extended_euclid($a, $n, $x, $y, $d);

        if ($d == 1)
        {
            if ($x < 0)
            {
                return $x + $y;
            }
            return $x;
        }
        return 0;
    }

    public function writeInFile($text) {
        $file = fopen("uploads/resText.txt", "w");
        fwrite($file, $text);
        fclose($file);
    }

    public static function clear() {

    }

    public static function download($filename){
        if(!empty($filename)){
            // Specify file path.
            $path = 'uploads/'; // '/uplods/'
            $download_file =  $path.$filename;
            // Check file is exists on given path.
            if(file_exists($download_file))
            {
                // Getting file extension.
                $extension = explode('.',$filename);
                $extension = $extension[count($extension)-1];
                // For Gecko browsers
                header('Content-Transfer-Encoding: binary');
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($path)) . ' GMT');
                // Supports for download resume
                header('Accept-Ranges: bytes');
                // Calculate File size
                header('Content-Length: ' . filesize($download_file));
                header('Content-Encoding: none');
                // Change the mime type if the file is not PDF
                header('Content-Type: application/'.$extension);
                // Make the browser display the Save As dialog
                header('Content-Disposition: attachment; filename=' . $filename);
                readfile($download_file);
//                exit;
            }
            else
            {
                echo 'File does not exists on given path';
            }

        }
    }


}

