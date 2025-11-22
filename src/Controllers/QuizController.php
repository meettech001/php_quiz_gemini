<?php
namespace App\Controllers;

use App\Services\AiService;
use PDO;

class QuizController {

    public static function generate($req, $res) {
        $body = $req->getParsedBody();
        $topic = trim($body['topic']);
        $use = $body['use_retrieval'] ?? true;

        $ai = new AiService($_ENV['GEMINI_API_KEY'], $_ENV['GEMINI_MODEL']);

        $context = $use ? AiService::fetchWikipediaSummary($topic) : '';

        $quiz = $ai->generateQuiz($topic, $context);

        $res->getBody()->write(json_encode($quiz));
        return $res->withHeader('Content-Type', 'application/json');
    }

    public static function submit($req, $res) {
        $body = $req->getParsedBody();
        $quiz = $body['quiz'];
        $answers = $body['answers'];

        $correct = 0;
        $details = [];

        foreach ($quiz['questions'] as $i => $q){
            $ua = $answers[(string)$i] ?? null;
            $ca = $q['answer'];
            if ($ua === $ca) $correct++;

            $details[] = [
                "index"=>$i,
                "q"=>$q['q'],
                "user_answer"=>$ua,
                "correct_answer"=>$ca,
                "explanation"=>$q['explanation'],
                "is_correct"=>$ua===$ca
            ];
        }

        self::saveResult($body['topic'], $quiz, $answers, $correct);

        $response = [
            "score"=>$correct,
            "total"=>count($quiz['questions']),
            "details"=>$details
        ];

        $res->getBody()->write(json_encode($response));
        return $res->withHeader('Content-Type','application/json');
    }

    public static function history($req, $res) {
        $pdo = new PDO("sqlite:/var/www/html/database/quiz.db");
        $rows = $pdo->query("SELECT * FROM quiz_results ORDER BY created_at DESC")
                    ->fetchAll(PDO::FETCH_ASSOC);

        $res->getBody()->write(json_encode($rows));
        return $res->withHeader('Content-Type','application/json');
    }

    private static function saveResult($topic, $quiz, $answers, $score) {
        $pdo = new PDO("sqlite:/var/www/html/database/quiz.db");
        $pdo->exec("CREATE TABLE IF NOT EXISTS quiz_results (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            topic TEXT,
            quiz TEXT,
            answers TEXT,
            score INTEGER,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        $stmt = $pdo->prepare("INSERT INTO quiz_results (topic, quiz, answers, score) VALUES (?,?,?,?)");
        $stmt->execute([
            $topic,
            json_encode($quiz),
            json_encode($answers),
            $score
        ]);
    }
}
