<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class CreateTestUser extends Command
{
    protected $signature = 'user:create-test';
    protected $description = 'Cria um usuário de teste no banco de dados';

    public function handle()
    {
        $this->info('Criando usuário de teste...');

        try {
            $userData = [
                'firstName' => 'Test',
                'lastName' => 'User',
                'email' => 'test@example.com',
                'password' => bcrypt('123456'),
            ];

            // Verificar se o usuário já existe pelo e-mail
            $existingUser = User::where('email', $userData['email'])->first();

            if ($existingUser) {
                $this->info('Usuário de teste já existe: ' . $userData['email']);
                Log::info('Usuário de teste já existe', ['email' => $userData['email']]);
                return;
            }

            // Criar o usuário
            $user = User::create($userData);
            $this->info('Usuário de teste criado com sucesso: ' . $user->email);
            Log::info('Usuário de teste criado com sucesso', ['user' => $user->toArray()]);
        } catch (\Exception $e) {
            Log::error('Erro ao criar usuário de teste: ' . $e->getMessage(), ['exception' => $e]);
            $this->error('Erro ao criar usuário de teste: ' . $e->getMessage());
        }
    }
}
?>