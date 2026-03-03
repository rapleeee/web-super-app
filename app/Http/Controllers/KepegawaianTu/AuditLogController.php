<?php

namespace App\Http\Controllers\KepegawaianTu;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $auditLogs = AuditLog::query()
            ->with('user')
            ->where('module', 'like', 'tu-%')
            ->when($request->module, fn ($query, $module) => $query->where('module', $module))
            ->when($request->action, fn ($query, $action) => $query->where('action', $action))
            ->when($request->user_id, fn ($query, $userId) => $query->where('user_id', $userId))
            ->latest('created_at')
            ->paginate(20)
            ->withQueryString();

        $moduleSource = AuditLog::query()->where('module', 'like', 'tu-%');

        $modules = (clone $moduleSource)
            ->select('module')
            ->distinct()
            ->orderBy('module')
            ->pluck('module');

        $actions = (clone $moduleSource)
            ->select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        $users = User::query()
            ->whereIn('id', (clone $moduleSource)->select('user_id')->whereNotNull('user_id')->distinct())
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('kepegawaian-tu.audit-log.index', compact('auditLogs', 'modules', 'actions', 'users'));
    }
}
