<?php

namespace App\Http\Controllers;

use App\Models\Backup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function index()
    {
        $backups = Backup::latest()->get();
        return view('backups.index', compact('backups'));
    }

    public function createBackup()
    {
        try {
            $filename = 'backup_' . date('Y-m-d_His') . '.sql';
            $path = storage_path('backups/' . $filename);

            if (!file_exists(storage_path('backups'))) {
                mkdir(storage_path('backups'), 0755, true);
            }

            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPass = config('database.connections.mysql.password');
            $dbHost = config('database.connections.mysql.host');

            $command = sprintf(
                'mysqldump -h %s -u %s %s %s > %s',
                escapeshellarg($dbHost),
                escapeshellarg($dbUser),
                $dbPass ? '-p' . escapeshellarg($dbPass) : '',
                escapeshellarg($dbName),
                escapeshellarg($path)
            );

            exec($command, $output, $returnVar);

            if ($returnVar !== 0) {
                return back()->with('error', 'Gagal membuat backup database');
            }

            Backup::create(['filename' => $filename]);

            return back()->with('success', 'Backup berhasil dibuat: ' . $filename);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat backup: ' . $e->getMessage());
        }
    }

    public function downloadBackup($id)
    {
        $backup = Backup::findOrFail($id);
        $path = storage_path('backups/' . $backup->filename);

        if (!file_exists($path)) {
            return back()->with('error', 'File backup tidak ditemukan');
        }

        return response()->download($path);
    }
}

