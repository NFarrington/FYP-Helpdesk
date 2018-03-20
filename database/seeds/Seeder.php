<?php

abstract class Seeder extends \Illuminate\Database\Seeder
{
    public function __construct()
    {
        Event::fake();
    }
}
