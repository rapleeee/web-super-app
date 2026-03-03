<?php

namespace App\Http\Controllers\SaranaUmum;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $auditLogs = AuditLog::query()
            ->with('user')
            ->when($request->module, fn ($query, $module) => $query->where('module', $module))
            ->when($request->action, fn ($query, $action) => $query->where('action', $action))
            ->latest('created_at')
            ->paginate(20)
            ->withQueryString();

        $modules = AuditLog::query()->select('module')->distinct()->orderBy('module')->pluck('module');
        $actions = AuditLog::query()->select('action')->distinct()->orderBy('action')->pluck('action');

        return view('sarana-umum.audit-log.index', compact('auditLogs', 'modules', 'actions'));
    }
}
