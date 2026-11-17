<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class BackupCommandTest extends TestCase
{
    public function test_database_backup_command_creates_sqlite_copy(): void
    {
        $source = sys_get_temp_dir().'/yayasan-source-'.uniqid().'.sqlite';
        $path = sys_get_temp_dir().'/yayasan-backups-'.uniqid();

        File::put($source, 'sqlite-data');

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => $source,
            'backup.path' => $path,
            'backup.retention_days' => 14,
        ]);

        try {
            $this->artisan('backup:database')->assertSuccessful();

            $this->assertDirectoryExists($path);
            $this->assertNotEmpty(glob($path.'/database-*.sqlite'));
        } finally {
            File::delete($source);
            File::deleteDirectory($path);
        }
    }
}
