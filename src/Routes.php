<?php
namespace App;

use Slim\App;
use App\Controllers\QuizController;

class Routes {
    private App $app;

    public function __construct(App $app){
        $this->app = $app;
    }

    public function register(): void {
        $this->app->post('/generate', [QuizController::class, 'generate']);
        $this->app->post('/submit', [QuizController::class, 'submit']);
        $this->app->get('/history', [QuizController::class, 'history']);
    }
}
