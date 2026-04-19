<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class SyncAdminRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-admin-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza usuários is_admin com o cargo admin do Spatie';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $role = Role::findOrCreate('admin');

        $users = User::where('is_admin', true)->get();

        $this->info('Sincronizando '.$users->count().' usuários...');

        foreach ($users as $user) {
            if (! $user->hasRole('admin')) {
                $user->assignRole($role);
                $this->line("Cargo admin atribuído a: {$user->email}");
            }
        }

        $this->info('Sincronização concluída!');
    }
}
