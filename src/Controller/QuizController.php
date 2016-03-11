<?php

namespace App\Controller;

use App\Models\JsonQuiz;
use App\Models\QuizInterface;

class QuizController extends AbstractController {
    /**
     * Shows a page with available quizzes.
     *
     * @param $request
     * @param $response
     * @return string Parsed Twig template
     */
    public function getList($request, $response)
    {
        $quizzes = [];
        foreach (JsonQuiz::getList() as $quiz) {
            $quizzes[$quiz->getSlug()] = $quiz->getName();
        }

        return $this->view->render($response, 'list.html', [
            'quizzes' => $quizzes
        ]);
    }


    /**
     * Shows the quiz page.
     *
     * @param $request
     * @param $response
     * @param $args
     * @return string Parsed Twig template
     */
    public function getQuiz($request, $response, $args)
    {
        $quiz = JsonQuiz::getBySlug($args['slug']);

        if (!$quiz) {
            return $response->withStatus(301)->withHeader('Location', '/');
        }

        // Quiz has been filled out before, show score.
        if (isset($_SESSION[$quiz->getSlug()])) {
            return $this->showScore($response, $quiz);
        }

        // Show the quiz form
        return $this->view->render($response, 'quiz.html', [
            'quiz' => $quiz
        ]);
    }


    /**
     * Handles the submitted quiz.
     *
     * @param $request
     * @param $response
     * @param $args
     * @return string Parsed Twig template
     */
    public function postQuiz($request, $response, $args)
    {
        $quiz = JsonQuiz::getBySlug($args['slug']);

        if (!$quiz) {
            return $response->withStatus(301)->withHeader('Location', '/');
        }

        if (!isset($_SESSION[$quiz->getSlug()])) {
            $iScore = 0;

            foreach ($request->getParams() as $q => $answer) {
                $answer  = reset($answer);
                $qNumber = filter_var($q, FILTER_SANITIZE_NUMBER_INT) - 1;

                if (isset($quiz->questions[$qNumber])) {
                    $quizAnswers = $quiz->questions[$qNumber]->answers;

                    foreach ($quizAnswers as $quizAnswer) {

                        // If posted answer exists, add up the score.
                        if ($quizAnswer->name == $answer) {
                            $iScore += $quizAnswer->score;
                        }
                    }
                }
            }

            // Save the score in a session.
            $_SESSION[$quiz->getSlug()] = $iScore;
        }

        return $this->showScore($response, $quiz);
    }


    /**
     * Shows result page with score.
     *
     * @param $response
     * @param QuizInterface $quiz
     * @return string Parsed Twig template
     */
    public function showScore($response, $quiz)
    {
        $max_score  = $quiz->getMaxScore($quiz);
        $score      = $_SESSION[$quiz->getSlug()];
        $percentage = round($score / $max_score * 100);

        return $this->view->render($response, 'score.html', [
            'quiz' => $quiz,
            'score' => $score,
            'max_score' => $max_score,
            'percentage' => $percentage
        ]);
    }
}