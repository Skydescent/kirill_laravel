<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReportsController extends Controller
{
    protected $reportsConfig;

    public function __construct()
    {
        $this->reportsConfig = config('reports.reports');
    }

    public function index()
    {
        return view('admin.reports.index', ['reports' => $this->reportsConfig]);
    }

    public function make($reportAlias)
    {
        $report = $this->reportsConfig[$reportAlias] ?? false;
        if ($report) {
            return view('admin.reports.make', compact( 'report', 'reportAlias'));
        }
    }

    public function sendReport(\Illuminate\Http\Request $request, $reportAlias)
    {
        $report = $this->reportsConfig[$reportAlias];
        $reportFields = array_intersect_key($report['reportable'], $request->input());
        $data = [
            'user' => Auth::user(),
            'report_fields' => $reportFields,
        ];
        $report['job']::dispatch($data);
        flash('Отчёт добавлен в очередь для обработки', 'warning');
        return redirect()->route('admin.reports.index');
    }
}
