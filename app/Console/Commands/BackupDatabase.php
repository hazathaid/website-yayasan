<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use RuntimeException;
use Symfony\Component\Process\Process;
use Throwable;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database';

    protected $description = 'Backup database ke folder backup yang dikonfigurasi';

    public function handle(): int
    {
        $path = config('backup.path', storage_path('app/backups'));

        if (! File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true);
        }

        $driver = config('database.default');
        $connection = config("database.connections.{$driver}", []);
        $timestamp = now()->format('Y-m-d_His');

        try {
            $file = match ($driver) {
                'sqlite' => $this->backupSqlite($connection, $path, $timestamp),
                'mysql', 'mariadb' => $this->backupMysql($connection, $path, $timestamp),
                'pgsql' => $this->backupPgsql($connection, $path, $timestamp),
                default => throw new RuntimeException("Driver database [{$driver}] belum didukung."),
            };
        } catch (Throwable $e) {
            report($e);
            $this->error('Backup gagal: '.$e->getMessage());

            return self::FAILURE;
        }

        $this->pruneOldBackups($path);

        $this->info('Backup dibuat: '.$file);

        return self::SUCCESS;
    }

    private function backupSqlite(array $connection, string $path, string $timestamp): string
    {
        $source = $connection['database'] ?? null;

        if (! $source || $source === ':memory:' || ! File::exists($source)) {
            throw new RuntimeException('File database SQLite tidak ditemukan.');
        }

        $target = $path.'/database-'.$timestamp.'.sqlite';

        File::copy($source, $target);

        return $target;
    }

    private function backupMysql(array $connection, string $path, string $timestamp): string
    {
        $target = $path.'/database-'.$timestamp.'.sql';

        $args = [
            'mysqldump',
            '--host='.($connection['host'] ?? '127.0.0.1'),
            '--port='.($connection['port'] ?? '3306'),
            '--user='.($connection['username'] ?? ''),
            '--single-transaction',
            '--no-tablespaces',
            '--default-character-set=utf8mb4',
            '--result-file='.$target,
            $connection['database'],
        ];

        $env = [];

        if (! empty($connection['password'])) {
            $env['MYSQL_PWD'] = $connection['password'];
        }

        $process = new Process($args, base_path(), $env);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new RuntimeException(trim($process->getErrorOutput()) ?: 'mysqldump gagal.');
        }

        return $target;
    }

    private function backupPgsql(array $connection, string $path, string $timestamp): string
    {
        $target = $path.'/database-'.$timestamp.'.sql';

        $args = [
            'pg_dump',
            '--host='.($connection['host'] ?? '127.0.0.1'),
            '--port='.($connection['port'] ?? '5432'),
            '--username='.($connection['username'] ?? ''),
            '--no-owner',
            '--file='.$target,
            $connection['database'],
        ];

        $env = [];

        if (! empty($connection['password'])) {
            $env['PGPASSWORD'] = $connection['password'];
        }

        $process = new Process($args, base_path(), $env);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new RuntimeException(trim($process->getErrorOutput()) ?: 'pg_dump gagal.');
        }

        return $target;
    }

    private function pruneOldBackups(string $path): void
    {
        $days = (int) config('backup.retention_days', 14);
        $threshold = now()->subDays($days)->getTimestamp();

        collect(File::files($path))
            ->filter(fn ($file) => $file->getMTime() < $threshold)
            ->each(fn ($file) => File::delete($file->getPathname()));
    }
}
