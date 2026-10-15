<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\RBAC;
use App\Core\Session;
use App\Core\Sanitizer;
use App\Core\Logger;
use App\Models\Quiz;

class QuizController extends Controller
{
    protected Quiz $quizModel;

    public function __construct()
    {
        parent::__construct();
        RBAC::checkPermission('manage_quizzes');
        $this->quizModel = new Quiz();
    }

    public function index(Request $request): void
    {
        $quizzes = $this->quizModel->all();
        $this->render('admin.quizzes.index', ['quizzes' => $quizzes], 'admin');
    }

    public function create(Request $request): void
    {
        $this->render('admin.quizzes.edit', ['quiz' => null], 'admin');
    }

    public function edit(Request $request, array $params): void
    {
        $id = (int)$params['id'];
        $quiz = $this->quizModel->find($id);
        $this->render('admin.quizzes.edit', ['quiz' => $quiz], 'admin');
    }

    public function save(Request $request, array $params = []): void
    {
        $id = (int)($params['id'] ?? 0);
        $title = $request->post('title');
        $slug = $request->post('slug') ?: Sanitizer::sanitizeSlug($title);

        $data = [
            'title' => $title,
            'slug' => $slug,
            'description' => $request->post('description'),
            'questions_json' => $request->post('questions_json'),
            'is_active' => (int)$request->post('is_active', 1),
        ];

        if ($id > 0) {
            $this->quizModel->update($id, $data);
            Logger::info("آزمون آنلاین شناسه {$id} به روز شد.");
            Session::flash('success', 'آزمون با موفقیت ویرایش شد.');
        } else {
            $newId = $this->quizModel->create($data);
            Logger::info("آزمون آنلاین جدید شناسه {$newId} ثبت شد.");
            Session::flash('success', 'آزمون جدید با موفقیت ایجاد گردید.');
        }

        $this->redirect('/admin/quizzes');
    }

    public function delete(Request $request, array $params): void
    {
        $id = (int)$params['id'];
        $this->quizModel->delete($id);
        Logger::info("آزمون آنلاین شناسه {$id} حذف گردید.");
        Session::flash('success', 'آزمون حذف گردید.');
        $this->redirect('/admin/quizzes');
    }
}
