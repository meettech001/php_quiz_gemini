<?php
namespace App\Services;

use GuzzleHttp\Client;

class AiService {

    private string $apiKey;
    private string $model;
    private Client $http;

    public function __construct(string $apiKey, string $model) {
        $this->apiKey = $apiKey;
        $this->model = $model;
        $this->http = new Client();
    }

    public static function fetchWikipediaSummary(string $topic): string {
        try {
            $client = new Client();
            $res = $client->get('https://en.wikipedia.org/api/rest_v1/page/summary/'.urlencode($topic));
            $data = json_decode($res->getBody()->getContents(), true);
            return $data['extract'] ?? '';
        } catch (\Exception $e){
            return '';
        }
    }

    public function generateQuiz(string $topic, string $context=''): array {

        $prompt = "
Generate EXACT JSON with 5 MCQs:
{
  \"questions\": [
     {
       \"q\": \"question text\",
       \"options\":{\"A\":\"..\",\"B\":\"..\",\"C\":\"..\",\"D\":\"..\"},
       \"answer\":\"A\",
       \"explanation\":\"short explanation\"
     }
  ]
}
Topic: {$topic}
Context: {$context}

Return ONLY valid JSON.
";

        try {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/"
                 . $this->model . ":generateContent?key=" . $this->apiKey;

            $res = $this->http->post($url, [
                'headers' => ['Content-Type'=>'application/json'],
                'json' => [
                    "contents" => [
                        [ "parts" => [ ["text"=>$prompt] ] ]
                    ]
                ]
            ]);

            $raw = json_decode($res->getBody()->getContents(), true);

            
            $text = $raw["candidates"][0]["content"]["parts"][0]["text"];
            $text = preg_replace('/```json|```/', '', $text);
            return json_decode($text, true);

        } catch(\Exception $e){
            return ["error"=>$e->getMessage()];
        }
    }
}
