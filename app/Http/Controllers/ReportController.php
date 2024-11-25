<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FileActivity;
use App\Models\Fichero;

class ReportController extends Controller
{
    public function fileActivityReport($fileId)
    {
        $activities = FileActivity::where('file_id', $fileId)->with('user')->get();
        return view('reports.file_activity', compact('activities'));
    }
    
    public function usageStatistics()
{
    $stats = [
        'total_files' => Fichero::count(),
        'total_shared' => FileActivity::where('action', 'share')->count(),
        'total_views' => FileActivity::where('action', 'view')->count(),
        'total_downloads' => FileActivity::where('action', 'download')->count(),
    ];
    return view('reports.usage_statistics', compact('stats'));
}
}
