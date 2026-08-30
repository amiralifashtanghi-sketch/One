<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class CaseStudyController extends Controller {
    public function show(string $slug): void {
        try {
            $caseStudy = Database::fetch("SELECT * FROM eafd_case_studies WHERE slug = :slug LIMIT 1", ['slug' => $slug]);
        } catch (\Exception $e) {
            $caseStudy = null;
        }

        if (!$caseStudy) {
            // Fallback check in portfolio table
            try {
                $portfolio = Database::fetch("SELECT * FROM eafd_portfolio WHERE slug = :slug LIMIT 1", ['slug' => $slug]);
            } catch (\Exception $e) {
                $portfolio = null;
            }

            if ($portfolio) {
                $this->render('pages/portfolio_single', [
                    'pageTitle' => $portfolio['title'] . ' — مطالعه موردی EAFD',
                    'metaDescription' => $portfolio['summary'],
                    'item' => $portfolio
                ]);
                return;
            }

            http_response_code(404);
            $this->render('pages/404', ['pageTitle' => 'مطالعه موردی یافت نشد']);
            return;
        }

        $this->render('pages/case_study_single', [
            'pageTitle' => $caseStudy['title'] . ' — کیس استدی EAFD',
            'metaDescription' => $caseStudy['objective'],
            'caseStudy' => $caseStudy
        ]);
    }
}
