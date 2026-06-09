<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Assignment;
use App\Models\Collaborator;
use App\Models\Equipment;
use App\Models\Notification;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class DashboardController extends Controller
{
    private const CACHE_TTL = 300; // 5 minutes

    public function index(): View
    {
        $stats = Cache::remember('dashboard.stats', self::CACHE_TTL, function () {
            return [
                'total'       => Equipment::count(),
                'available'   => Equipment::where('status', 'available')->count(),
                'assigned'    => Equipment::where('status', 'assigned')->count(),
                'maintenance' => Equipment::where('status', 'maintenance')->count(),
                'damaged'     => Equipment::where('status', 'damaged')->count(),
                'lost'        => Equipment::where('status', 'lost')->count(),
                'retired'     => Equipment::where('status', 'retired')->count(),
                'total_value' => Equipment::sum('cost'),
                'collaborators' => Collaborator::where('status', 'active')->count(),
                'departments'   => \App\Models\Department::where('active', true)->count(),
            ];
        });

        $byType = Cache::remember('dashboard.by_type', self::CACHE_TTL, function () {
            return Equipment::selectRaw('type, count(*) as total')
                ->groupBy('type')
                ->pluck('total', 'type');
        });

        $byStatus = Cache::remember('dashboard.by_status', self::CACHE_TTL, function () {
            return Equipment::selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');
        });

        $warrantyExpiring = Equipment::whereNotNull('warranty_expiry')
            ->where('warranty_expiry', '>=', now())
            ->where('warranty_expiry', '<=', now()->addDays(30))
            ->with('mainPhoto')
            ->orderBy('warranty_expiry')
            ->limit(5)
            ->get();

        $overdueAssignments = Assignment::where('status', 'active')
            ->whereNotNull('expected_return')
            ->where('expected_return', '<', now())
            ->with(['equipment', 'collaborator'])
            ->limit(5)
            ->get();

        $recentActivity = ActivityLog::with('user')
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'stats', 'byType', 'byStatus',
            'warrantyExpiring', 'overdueAssignments',
            'recentActivity'
        ));
    }
}
