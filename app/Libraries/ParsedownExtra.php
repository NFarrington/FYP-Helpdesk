<?php

namespace App\Libraries;

class ParsedownExtra extends \ParsedownExtra
{
    protected function blockTable($Line, array $Block = null)
    {
        $block = parent::blockTable($Line, $Block);

        $block['element']['attributes'] = [];
        $block['element']['attributes']['class'] = 'table';

        return $block;
    }
}
