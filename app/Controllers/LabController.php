<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Tool;
use App\Models\Quiz;

class LabController extends Controller
{
    protected Tool $toolModel;
    protected Quiz $quizModel;

    public function __construct()
    {
        parent::__construct();
        $this->toolModel = new Tool();
        $this->quizModel = new Quiz();
    }

    public function index(Request $request): void
    {
        $tools = $this->toolModel->where('is_active', 1);
        $quizzes = $this->quizModel->where('is_active', 1);
        $this->render('lab.index', ['tools' => $tools, 'quizzes' => $quizzes], 'main');
    }

    public function tool(Request $request, array $params): void
    {
        $slug = $params['slug'] ?? '';
        $tool = $this->toolModel->findBy('slug', $slug);

        if (!$tool || !$tool['is_active']) {
            \App\Core\ErrorHandler::renderErrorPage(404, "ابزار یافت نشد", "ابزار مورد نظر فعال نمی‌باشد.");
            exit;
        }

        $this->render('lab.tool', ['tool' => $tool], 'main');
    }

    public function quiz(Request $request, array $params): void
    {
        $slug = $params['slug'] ?? '';
        $quiz = $this->quizModel->findBy('slug', $slug);

        if (!$quiz || !$quiz['is_active']) {
            \App\Core\ErrorHandler::renderErrorPage(404, "آزمون یافت نشد", "آزمون مورد نظر فعال نمی‌باشد.");
            exit;
        }

        $this->render('lab.quiz', ['quiz' => $quiz], 'main');
    }
}
