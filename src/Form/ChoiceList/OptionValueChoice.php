<?php

namespace App\Form\ChoiceList;

class OptionValueChoice
{
    private $idAsString;
    private $nameAsString;

    public function __construct(string $idAsString, string $nameAsString)
    {
        $this->idAsString = $idAsString;
        $this->nameAsString = $nameAsString;
    }

    public function getIdAsString()
    {
        return $this->idAsString;
    }

    public function getNameAsString()
    {
        return $this->nameAsString;
    }
}
