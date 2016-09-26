<?php
namespace app\models;

use app\models\UploadForm;
use yii\base\Exception;
use yii\base\Model;

define("ENCRYPT", 0);
define("DECRYPT", 1);

class CryptoMethods extends Model
{
    public $currentAction; // encrypt or decrypt
    public $affineParams = [];
    public $currentMethod;
    public $alphabet = [];
    public $uploadModel;
    public $initialText = "";
    public $resultText = "";

    public function CryptoMethods($uploadModel)
    {
        //$this->uploadModel = $uploadModel;
        //$this->initialText = $uploadModel->initialText;
    }

    public function createAlphabet()
    {
        $curNum = 0;
        for($i = 65; $i <= 90; $i++) // chars
        {
            $this->alphabet[chr(i)] = $curNum++;
        }
        for($i = 48; $i <= 57; $i++) // digits
        {
            $this->alphabet[chr(i)] = $curNum++;
        }
    }

    public function encryptDecrypt()
    {
        $a = $this->affineParams['a'];
        $b = $this->affineParams['b'];
        $m = count($this->alphabet);
        $resNumChar = 0;
        $this->clearResult();

        if($this->checkSimpleDigits($a, $m))
        {
            for($i = 0; $i <= mb_strlen($this->initialText); $i++)
            {
                $curChar = $this->initialText[$i];
                if(($numChar = $this->getNumCharFromAlphabet($curChar)) == -1) {
                    continue; // skip this char
                }
                switch($this->currentAction) {
                    case ENCRYPT:
                        $resNumChar = ($a * $numChar + $b) % $m;
                        break;
                    case DECRYPT:
                        $resNumChar = ((intval(1 / $a)) * ($numChar - $b)) % $m;
                        break;
                }

                $resChar = array_keys($this->alphabet)[$resNumChar];
                $this->resultText .= $resChar;
            }
        }
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
        try
        {
            $numChar = $this->alphabet[$curChar];
        } catch (Exception $e) {
            return $numChar = -1;
        }
        return $numChar;
    }

    public function rules()
    {
        return [
//            [['currentAction'], 'numerical', 'integerOnly' => 'true'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'txtFile' => 'Choose file',
            'currentMethod' => 'Choose encrypt method'
        ];
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


}

