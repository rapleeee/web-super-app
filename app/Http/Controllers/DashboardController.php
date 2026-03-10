<?php

namespace App\Http\Controllers;

use App\Http\Middleware\EnsureApplicationNotInCustomMaintenanceMode;
use App\Http\Requests\Dashboard\ToggleMaintenanceModeRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = Auth::user();

        $menus = [
            [
                'icon' => 'computer-desktop',
                'title' => 'Sarpras Lab',
                'desc' => 'Manajemen sarana prasarana laboratorium komputer.',
                'route' => route('laboran.index'),
                'color' => 'blue',
                'active' => true,
            ],
            [
                'icon' => 'users',
                'title' => 'Administrasi Guru',
                'desc' => 'Manajemen data guru, nilai, dan administrasi lainnya.',
                'route' => '#',
                'color' => 'purple',
                'active' => false,
            ],
            [
                'icon' => 'book-open',
                'title' => 'Kepegawaian TU',
                'desc' => 'Manajemen data kepegawaian dan administrasi tata usaha.',
                'route' => route('kepegawaian-tu.izin-karyawan.index'),
                'color' => 'green',
                'active' => true,
            ],
            [
                'icon' => 'building-library',
                'title' => 'Sarana Umum',
                'desc' => 'Kelola data sarana dan prasarana umum sekolah.',
                'route' => route('sarana-umum.dashboard'),
                'color' => 'orange',
                'active' => true,
            ],
        ];

        $announcements = [
            [
                'title' => 'Jadwal Maintenance Sistem',
                'date' => '2026-02-15',
                'category' => 'Info Teknis',
            ],
            [
                'title' => 'Update Modul Kepegawaian',
                'date' => '2026-02-10',
                'category' => 'Pembaruan',
            ],
            [
                'title' => 'Libur Nasional Hari Raya',
                'date' => '2026-02-05',
                'category' => 'Umum',
            ],
        ];

        $isMaintenanceMode = (bool) Cache::get(EnsureApplicationNotInCustomMaintenanceMode::cacheKey(), false);
        $canManageMaintenance = $user?->role === 'admin' || $user?->email === 'test@example.com';

        return view('dashboard', compact('user', 'menus', 'announcements', 'isMaintenanceMode', 'canManageMaintenance'));
    }

    public function updateMaintenanceMode(ToggleMaintenanceModeRequest $request): RedirectResponse
    {
        if ($request->boolean('enabled')) {
            Cache::forever(EnsureApplicationNotInCustomMaintenanceMode::cacheKey(), true);

            return redirect()
                ->route('dashboard.index')
                ->with('success', 'Mode maintenance aktif.');
        }

        Cache::forget(EnsureApplicationNotInCustomMaintenanceMode::cacheKey());

        return redirect()
            ->route('dashboard.index')
            ->with('success', 'Mode maintenance dinonaktifkan.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
