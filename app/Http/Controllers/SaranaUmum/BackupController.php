<?php

namespace App\Http\Controllers\SaranaUmum;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupController extends Controller
{
    /**
     * Display the backup management page.
     */
    public function index()
    {
        $tables = $this->getTables();
        $backupPath = storage_path('app/backups');
        $existingBackups = [];

        if (is_dir($backupPath)) {
            $files = glob($backupPath.'/*.sql');
            foreach ($files as $file) {
                $existingBackups[] = [
                    'name' => basename($file),
                    'size' => $this->formatBytes(filesize($file)),
                    'date' => date('Y-m-d H:i:s', filemtime($file)),
                    'path' => $file,
                ];
            }

            usort($existingBackups, fn ($a, $b) => strtotime($b['date']) - strtotime($a['date']));
        }

        return view('sarana-umum.backup.index', compact('tables', 'existingBackups'));
    }

    /**
     * Create and download database backup.
     */
    public function download(): RedirectResponse|StreamedResponse
    {
        $driver = config('database.default');

        if ($driver !== 'mysql') {
            return redirect()->route('sarana-umum.backup.index')
                ->with('error', 'Backup hanya tersedia untuk database MySQL.');
        }

        $filename = 'backup_'.config('app.name').'_'.date('Y-m-d_H-i-s').'.sql';
        AuditLog::record('backup', 'download', null, null, ['filename' => $filename]);

        return new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');

            fwrite($handle, "-- Database Backup\n");
            fwrite($handle, '-- Generated: '.now()->format('Y-m-d H:i:s')."\n");
            fwrite($handle, '-- Application: '.config('app.name')."\n");
            fwrite($handle, "-- --------------------------------------------------------\n\n");
            fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n\n");

            $tables = $this->getTables();

            foreach ($tables as $table) {
                if ($table === 'migrations') {
                    continue;
                }

                fwrite($handle, "-- --------------------------------------------------------\n");
                fwrite($handle, "-- Table structure for `{$table}`\n");
                fwrite($handle, "-- --------------------------------------------------------\n\n");

                $createTable = DB::select("SHOW CREATE TABLE `{$table}`");
                if (! empty($createTable)) {
                    $createSql = $createTable[0]->{'Create Table'} ?? '';
                    fwrite($handle, "DROP TABLE IF EXISTS `{$table}`;\n");
                    fwrite($handle, $createSql.";\n\n");
                }

                $rows = DB::table($table)->get();

                if ($rows->count() > 0) {
                    fwrite($handle, "-- Dumping data for table `{$table}`\n\n");

                    foreach ($rows as $row) {
                        $rowArray = (array) $row;
                        $columns = array_keys($rowArray);
                        $values = array_map(function ($value) {
                            if ($value === null) {
                                return 'NULL';
                            }

                            return "'".addslashes((string) $value)."'";
                        }, array_values($rowArray));

                        $columnsStr = '`'.implode('`, `', $columns).'`';
                        $valuesStr = implode(', ', $values);

                        fwrite($handle, "INSERT INTO `{$table}` ({$columnsStr}) VALUES ({$valuesStr});\n");
                    }
                    fwrite($handle, "\n");
                }
            }

            fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
            fwrite($handle, "\n-- Backup completed\n");

            fclose($handle);
        }, 200, [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    /**
     * Save backup to server storage.
     */
    public function store(): RedirectResponse
    {
        $driver = config('database.default');

        if ($driver !== 'mysql') {
            return redirect()->route('sarana-umum.backup.index')
                ->with('error', 'Backup hanya tersedia untuk database MySQL.');
        }

        $backupPath = storage_path('app/backups');

        if (! is_dir($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        $filename = 'backup_'.config('app.name').'_'.date('Y-m-d_H-i-s').'.sql';
        $filepath = $backupPath.'/'.$filename;

        $handle = fopen($filepath, 'w');

        fwrite($handle, "-- Database Backup\n");
        fwrite($handle, '-- Generated: '.now()->format('Y-m-d H:i:s')."\n");
        fwrite($handle, '-- Application: '.config('app.name')."\n");
        fwrite($handle, "-- --------------------------------------------------------\n\n");
        fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n\n");

        $tables = $this->getTables();

        foreach ($tables as $table) {
            if ($table === 'migrations') {
                continue;
            }

            fwrite($handle, "-- --------------------------------------------------------\n");
            fwrite($handle, "-- Table structure for `{$table}`\n");
            fwrite($handle, "-- --------------------------------------------------------\n\n");

            $createTable = DB::select("SHOW CREATE TABLE `{$table}`");
            if (! empty($createTable)) {
                $createSql = $createTable[0]->{'Create Table'} ?? '';
                fwrite($handle, "DROP TABLE IF EXISTS `{$table}`;\n");
                fwrite($handle, $createSql.";\n\n");
            }

            $rows = DB::table($table)->get();

            if ($rows->count() > 0) {
                fwrite($handle, "-- Dumping data for table `{$table}`\n\n");

                foreach ($rows as $row) {
                    $rowArray = (array) $row;
                    $columns = array_keys($rowArray);
                    $values = array_map(function ($value) {
                        if ($value === null) {
                            return 'NULL';
                        }

                        return "'".addslashes((string) $value)."'";
                    }, array_values($rowArray));

                    $columnsStr = '`'.implode('`, `', $columns).'`';
                    $valuesStr = implode(', ', $values);

                    fwrite($handle, "INSERT INTO `{$table}` ({$columnsStr}) VALUES ({$valuesStr});\n");
                }
                fwrite($handle, "\n");
            }
        }

        fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
        fwrite($handle, "\n-- Backup completed\n");

        fclose($handle);
        AuditLog::record('backup', 'create', null, null, ['filename' => $filename]);

        return redirect()->route('sarana-umum.backup.index')
            ->with('success', 'Backup berhasil disimpan: '.$filename);
    }

    /**
     * Download existing backup file.
     */
    public function downloadFile(string $filename)
    {
        $filepath = storage_path('app/backups/'.$filename);

        if (! file_exists($filepath)) {
            return redirect()->route('sarana-umum.backup.index')
                ->with('error', 'File backup tidak ditemukan.');
        }

        AuditLog::record('backup', 'download-file', null, null, ['filename' => $filename]);

        return response()->download($filepath);
    }

    /**
     * Delete existing backup file.
     */
    public function destroy(string $filename): RedirectResponse
    {
        $filepath = storage_path('app/backups/'.$filename);

        if (file_exists($filepath)) {
            unlink($filepath);
            AuditLog::record('backup', 'delete', null, ['filename' => $filename], null);

            return redirect()->route('sarana-umum.backup.index')
                ->with('success', 'Backup berhasil dihapus.');
        }

        return redirect()->route('sarana-umum.backup.index')
            ->with('error', 'File backup tidak ditemukan.');
    }

    /**
     * Get all database tables.
     *
     * @return array<string>
     */
    private function getTables(): array
    {
        $driver = config('database.default');

        if ($driver === 'mysql') {
            $result = DB::select('SHOW TABLES');
            $tables = [];
            foreach ($result as $row) {
                $tables[] = array_values((array) $row)[0];
            }

            return $tables;
        }

        return Schema::getTableListing();
    }

    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision).' '.$units[$pow];
    }
}
