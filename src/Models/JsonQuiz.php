<?php

namespace App\Models;

class JsonQuiz extends QuizAbstract implements QuizInterface
{
    public $quizName = null;

    /**
     * Return a Quiz object based on a filename.
     *
     * @param $filename
     * @static
     * @return QuizInterface | false
     */
    public static function getByFilename($filename)
    {
        if (strpos($filename, '.json') === false) {
            $filename = $filename . '.json';
        }

        if (!file_exists('../data/'.$filename)) {
            return false;
        }

        $contents = file_get_contents('../data/'.$filename);
        $contents = json_decode($contents);

        $obj = new static();
        $obj->name = str_replace('.json', '', basename($filename));
        foreach ($contents as $key => $val) {
            $obj->{$key} = $val;
        }

        return $obj;
    }


    /**
     * Return a Quiz object based on a slug.
     *
     * @param $slug
     * @static
     * @return QuizInterface | false
     */
    public static function getBySlug($slug)
    {
        $list = static::getList();

        foreach ($list as $quiz) {
            if ($quiz->getSlug() == $slug) {
                return $quiz;
            }
        }

        return false;
    }


    /**
     * Returns list of Quiz objects.
     *
     * Searches for files ending with .json in data directory.
     * Each file is appended as Quiz object to the array.
     *
     * @static
     * @return array[] QuizInterface
     */
    public static function getList()
    {
        $list = [];
        foreach (glob("../data/*.json") as $filename) {
            $quiz = static::getByFilename($filename);

            if ($quiz) {
                $list[] = $quiz;
            }
        }

        return $list;
    }


    /**
     * @return int
     */
    public function getMaxScore()
    {
        $max = 0;
        foreach ($this->questions as $question) {
            $answerMax = 0;
            foreach($question->answers as $answer) {
                if ($answer->score > $answerMax) {
                    $answerMax = $answer->score;
                }
            }
            $max += $answerMax;
        }

        return $max;
    }
}