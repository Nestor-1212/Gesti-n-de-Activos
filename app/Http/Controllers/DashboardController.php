<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total'       => \App\Models\Equipment::count(),
            'available'   => \App\Models\Equipment::where('status', 'available')->count(),
            'assigned'    => \App\Models\Equipment::where('status', 'assigned')->count(),
            'maintenance' => \App\Models\Equipment::where('status', 'maintenance')->count(),
            'damaged'     => \App\Models\Equipment::where('status', 'damaged')->count(),
            'lost'        => \App\Models\Equipment::where('status', 'lost')->count(),
            'retired'     => \App\Models\Equipment::where('status', 'retired')->count(),
            'total_value' => \App\Models\Equipment::sum('cost'),
            'collaborators' => \App\Models\Collaborator::where('status', 'active')->count(),
            'departments'   => \App\Models\Department::where('active', true)->count(),
        ];

        $byType = \App\Models\Equipment::selectRaw('type, count(*) as total')
            ->groupBy('type')->pluck('total', 'type');

        $byStatus = \App\Models\Equipment::selectRaw('status, count(*) as total')
            ->groupBy('status')->pluck('total', 'status');

        $warrantyExpiring = \App\Models\Equipment::whereNotNull('warranty_expiry')
            ->where('warranty_expiry', '>=', now())
            ->where('warranty_expiry', '<=', now()->addDays(30))
            ->with('mainPhoto')
            ->orderBy('warranty_expiry')
            ->limit(5)
            ->get();

        $overdueAssignments = \App\Models\Assignment::where('status', 'active')
            ->whereNotNull('expected_return')
            ->where('expected_return', '<', now())
            ->with(['equipment', 'collaborator'])
            ->limit(5)
            ->get();

        $recentActivity = \App\Models\ActivityLog::with('user')
            ->latest()
            ->limit(10)
            ->get();

        $notifications = \App\Models\Notification::where('user_id', auth()->id())
            ->unread()
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'stats', 'byType', 'byStatus',
            'warrantyExpiring', 'overdueAssignments',
            'recentActivity', 'notifications'
        ));
    }
}
