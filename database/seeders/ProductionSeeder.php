<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@admin.com')->first() ?? User::factory()->admin()->create(['email' => 'admin@admin.com']);

        // Categorias Estratégicas
        $catMarketing = Category::updateOrCreate(['slug' => 'marketing-digital'], ['name' => 'Marketing & Escala', 'type' => 'general']);
        $catAuto = Category::updateOrCreate(['slug' => 'automacao'], ['name' => 'Automação & IA', 'type' => 'general']);

        // 🚀 PACOTE 1: START (Landing Page High-Ticket)
        Service::updateOrCreate(['slug' => 'landing-page-alta-conversao'], [
            'user_id' => $admin->id,
            'category_id' => $catMarketing->id,
            'name' => '🚀 Pacote START: Landing Page de Elite',
            'short_description' => 'Transformamos visitantes em leads com uma página ultra-rápida e focada em vendas.',
            'full_description' => "O que está incluso:\n- Design Premium e Responsivo\n- Copywriting Focado em Persuasão\n- Integração com WhatsApp e CRM\n- Otimização de Velocidade (Score 90+ Mobile)\n- Ideal para campanhas de Tráfego Pago.",
            'price_from' => 997.00,
            'delivery_time' => '7 dias úteis',
            'is_active' => true,
            'call_to_action' => 'Quero Minha Landing Page',
            'featured_image' => 'uploads/start_package.png',
        ]);

        // 📈 PACOTE 2: GROWTH (O Ecossistema Completo)
        Service::updateOrCreate(['slug' => 'ecossistema-vendas-growth'], [
            'user_id' => $admin->id,
            'category_id' => $catMarketing->id,
            'name' => '📈 Pacote GROWTH: Máquina de Vendas',
            'short_description' => 'Site completo + Gestão de Tráfego + Funil de Vendas Automatizado.',
            'full_description' => "O que está incluso:\n- Site Institucional ou Blog de Autoridade\n- Gestão de Tráfego (Meta + Google Ads - 1º mês incluso)\n- Implementação de Funil de E-mail/WhatsApp\n- Relatórios Mensais de Performance\n- Suporte Prioritário.",
            'price_from' => 2497.00,
            'delivery_time' => '15-20 dias úteis',
            'is_active' => true,
            'call_to_action' => 'Escalar Meu Negócio',
            'featured_image' => 'uploads/growth_package.png',
        ]);

        // 🤖 PACOTE 3: ENTERPRISE (IA & Automação sob medida)
        Service::updateOrCreate(['slug' => 'automacao-ia-enterprise'], [
            'user_id' => $admin->id,
            'category_id' => $catAuto->id,
            'name' => '🤖 Pacote ENTERPRISE: IA & Automação',
            'short_description' => 'Reduza custos e aumente a produtividade com Inteligência Artificial personalizada.',
            'full_description' => "O que está incluso:\n- Criação de Chatbots de IA treinados com seus dados\n- Automação de Processos Internos (n8n/Make)\n- Integração de IA no seu Site/App\n- Consultoria de Fluxo de Trabalho Digital\n- Treinamento para sua equipe.",
            'price_from' => 4997.00,
            'delivery_time' => 'Sob consulta (Média 30 dias)',
            'is_active' => true,
            'call_to_action' => 'Solicitar Orçamento Customizado',
            'featured_image' => 'uploads/enterprise_package.png',
        ]);
    }
}
