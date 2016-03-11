<?php

namespace App\Models;

use URLify;

abstract class QuizAbstract {
    public $name;

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Generates a slug based on the Quiz's name.
     *
     * @return string
     */
    public function getSlug()
    {
        return URLify::filter($this->name);
    }
}