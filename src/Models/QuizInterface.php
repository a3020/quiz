<?php

namespace App\Models;

interface QuizInterface {
    public static function getBySlug($slug);
    public static function getList();
    public function getSlug();
    public function getName();
    public function getMaxScore();
}