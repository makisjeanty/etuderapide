<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class MakisBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'makis:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gera um backup completo do banco de dados e arquivos de upload';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Iniciando Backup Makis Digital...');

        $date = now()->format('Y-m-d_H-i-s');
        $backupDir = storage_path('app/backups/'.$date);

        if (! file_exists($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        // 1. Backup do Banco de Dados
        if (! $this->backupDatabase($backupDir)) {
            return self::FAILURE;
        }

        // 2. Backup de Uploads
        if (! $this->backupUploads($backupDir)) {
            return self::FAILURE;
        }

        $this->info("Backup concluído com sucesso em: storage/app/backups/{$date}");

        return self::SUCCESS;
    }

    protected function backupDatabase(string $dir): bool
    {
        $this->comment('Exportando banco de dados...');

        $filename = 'database_dump.sql';
        $path = $dir.DIRECTORY_SEPARATOR.$filename;
        $connectionName = config('database.default');
        $connection = config("database.connections.{$connectionName}");

        if (! is_array($connection)) {
            $this->error("Configuração de banco inválida para a conexão [{$connectionName}].");

            return false;
        }

        try {
            $process = $this->makeDatabaseDumpProcess($connection, $path);
            $process->mustRun();
        } catch (\InvalidArgumentException $exception) {
            $this->error($exception->getMessage());

            return false;
        } catch (ProcessFailedException $exception) {
            $this->error('Falha ao exportar banco de dados.');
            $this->line(trim($exception->getProcess()->getErrorOutput()));

            return false;
        }

        $this->info("Banco de dados exportado: {$filename}");

        return true;
    }

    protected function backupUploads(string $dir): bool
    {
        $this->comment('Copiando arquivos de upload...');

        $source = storage_path('app/public/uploads');
        $destination = $dir.DIRECTORY_SEPARATOR.'uploads';

        if (! file_exists($source)) {
            $this->warn('Nenhuma pasta de uploads encontrada para backup.');

            return true;
        }

        $this->copyDirectory($source, $destination);

        $this->info('Uploads copiados com sucesso.');

        return true;
    }

    /**
     * @param  array<string, mixed>  $connection
     */
    protected function makeDatabaseDumpProcess(array $connection, string $path): Process
    {
        $driver = $connection['driver'] ?? null;
        $host = $connection['host'] ?? '127.0.0.1';
        $port = $connection['port'] ?? null;
        $database = $connection['database'] ?? null;
        $username = $connection['username'] ?? null;
        $password = $connection['password'] ?? null;

        if (! $driver || ! $database || ! $username) {
            throw new \InvalidArgumentException('A conexão padrão não possui os dados mínimos para gerar o backup.');
        }

        return match ($driver) {
            'pgsql' => $this->makePgsqlDumpProcess($host, $port, $database, $username, $password, $path),
            'mysql', 'mariadb' => $this->makeMysqlDumpProcess($host, $port, $database, $username, $password, $path),
            default => throw new \InvalidArgumentException("Driver de banco não suportado para backup: {$driver}."),
        };
    }

    protected function makePgsqlDumpProcess(
        string $host,
        string|int|null $port,
        string $database,
        string $username,
        ?string $password,
        string $path
    ): Process {
        $command = array_values(array_filter([
            'pg_dump',
            '--host='.$host,
            $port ? '--port='.$port : null,
            '--username='.$username,
            '--file='.$path,
            '--format=plain',
            '--no-owner',
            '--no-privileges',
            $database,
        ]));

        return new Process(
            $command,
            null,
            $password ? ['PGPASSWORD' => $password] : null,
        );
    }

    protected function makeMysqlDumpProcess(
        string $host,
        string|int|null $port,
        string $database,
        string $username,
        ?string $password,
        string $path
    ): Process {
        $command = array_values(array_filter([
            'mysqldump',
            '--host='.$host,
            $port ? '--port='.$port : null,
            '--user='.$username,
            '--result-file='.$path,
            '--single-transaction',
            $database,
        ]));

        return new Process(
            $command,
            null,
            $password ? ['MYSQL_PWD' => $password] : null,
        );
    }

    protected function copyDirectory(string $source, string $destination): void
    {
        if (! is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $target = $destination.DIRECTORY_SEPARATOR.$iterator->getSubPathName();

            if ($item->isDir()) {
                if (! is_dir($target)) {
                    mkdir($target, 0755, true);
                }

                continue;
            }

            copy($item->getPathname(), $target);
        }
    }
}
