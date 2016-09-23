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
        $this->uploadModel = $uploadModel;
        $this->initialText = $uploadModel->initialText;
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
        $curChar = '';
        $resChar = '';
        if($this->currentAction == ENCRYPT)
        {
            for($i = 0; $i <= mb_strlen($this->initialText); $i++)
            {
                $curChar = $this->initialText[$i];
                if(($numChar = $this->getNumCharFromAlphabet($curChar)) == -1)
                {
                    continue; // skip this char
                }
                $resNumChar = ($a * $numChar + $b) % $m;
                $resChar = array_values(key($this->alphabet[$resNumChar]));
                $this->resultText .= $resChar;
            }
        }
    }

    public function getCharAlphabet($num)
    {
        for($i = 0; $i <= count($this->alphabet); $i++)
        {

        }
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
            [['txtFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'txt'],
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

