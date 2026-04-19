<?php

namespace Database\Seeders;

use App\Enums\ProjectStatus;
use App\Models\Category;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProfessionalContentSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        if (! $admin) {
            return;
        }

        // --- CATEGORIES ---
        $catWeb = Category::updateOrCreate(['slug' => 'desenvolvimento-web'], ['name' => 'Desenvolvimento Web', 'type' => 'general']);
        $catIA = Category::updateOrCreate(['slug' => 'inteligencia-artificial'], ['name' => 'Inteligência Artificial', 'type' => 'general']);
        $catNegocios = Category::updateOrCreate(['slug' => 'negocios-digitais'], ['name' => 'Negócios Digitais', 'type' => 'general']);

        // --- SERVICES (Os 3 Pacotes Estratégicos) ---

        // 1. PACOTE BRONZE
        Service::updateOrCreate(['slug' => 'pacote-bronze-express'], [
            'name' => 'Pacote Bronze: Presença Digital Express',
            'user_id' => $admin->id,
            'category_id' => $catWeb->id,
            'short_description' => 'Ideal para quem precisa estar no Google hoje. Landing Page rápida e otimizada.',
            'full_description' => "### O que você recebe:\n- Landing Page de alta conversão.\n- Otimização para dispositivos móveis.\n- Configuração de Google Meu Negócio.\n- Hospedagem e E-mail profissional inclusos por 1 ano.\n\n### Prazo de entrega: 7 dias úteis.\n### Investimento único: R$ 1.900,00",
            'price_from' => 1900.00,
            'delivery_time' => '7 dias',
            'is_active' => true,
            'call_to_action' => 'Começar agora com Pacote Bronze',
            'seo_title' => 'Landing Page Rápida e Profissional | Makis Digital',
            'seo_description' => 'Coloque sua empresa na internet em 7 dias com o Pacote Bronze.',
        ]);

        // 2. PACOTE PRATA
        Service::updateOrCreate(['slug' => 'pacote-prata-vendas'], [
            'name' => 'Pacote Prata: Máquina de Vendas Pro',
            'user_id' => $admin->id,
            'category_id' => $catWeb->id,
            'short_description' => 'Nosso campeão de vendas. Site completo + Blog + Captação de Leads via IA.',
            'full_description' => "### O que você recebe:\n- Site Institucional Completo (Até 5 páginas).\n- Blog Integrado para SEO.\n- Sistema de Captação de Leads (Formulários inteligentes).\n- Integração com WhatsApp e CRM.\n- Treinamento para usar a IA do painel.\n\n### Prazo de entrega: 15 dias úteis.\n### Investimento único: R$ 4.500,00",
            'price_from' => 4500.00,
            'delivery_time' => '15 dias',
            'is_active' => true,
            'call_to_action' => 'Escalar meu negócio com Pacote Prata',
            'seo_title' => 'Site Profissional + Gestão de Leads | Makis Digital',
            'seo_description' => 'Transforme seu site em uma máquina de vendas com o Pacote Prata.',
        ]);

        // 3. PACOTE OURO
        Service::updateOrCreate(['slug' => 'pacote-ouro-ia'], [
            'name' => 'Pacote Ouro: Ecossistema IA & Automação',
            'user_id' => $admin->id,
            'category_id' => $catIA->id,
            'short_description' => 'Para empresas que querem o próximo nível. Automação total de processos com IA.',
            'full_description' => "### O que você recebe:\n- Sistema Web Customizado sob medida.\n- Chatbots inteligentes integrados ao seu banco de dados.\n- Automação de processos repetitivos (E-mails, Relatórios, Propostas).\n- Dashboard de análise de dados em tempo real.\n- Suporte prioritário 24/7.\n\n### Prazo de entrega: 30 a 45 dias.\n### Investimento: Sob Consulta (Inicia em R$ 9.000,00)",
            'price_from' => 9000.00,
            'delivery_time' => '30-45 dias',
            'is_active' => true,
            'call_to_action' => 'Solicitar Consultoria para Pacote Ouro',
            'seo_title' => 'Sistemas Web Customizados e IA | Makis Digital',
            'seo_description' => 'Automação total e inteligência artificial para grandes resultados.',
        ]);

        // --- PROJECTS (Os 3 Cases Correspondentes) ---

        // CASE BRONZE
        Project::updateOrCreate(['slug' => 'landing-page-local-vendas'], [
            'title' => 'Landing Page: Do Zero a 15 Leads por Semana',
            'category_id' => $catWeb->id,
            'user_id' => $admin->id,
            'summary' => 'Case Bronze: Como um negócio local lotou a agenda com uma única página de alta conversão.',
            'description' => "### O Problema\nO cliente dependia apenas de indicação e estava com a agenda vazia 3 dias por semana.\n\n### A Solução\nImplementamos o nosso **Pacote Bronze**: Uma Landing Page ultra-rápida focada em conversão direta para WhatsApp.\n\n### O Resultado\nAumento imediato na procura, resultando em agenda cheia para os próximos 15 dias.",
            'status' => ProjectStatus::Published,
            'is_featured' => true,
            'tech_stack' => ['HTML5', 'TailwindCSS'],
            'seo_title' => 'Case Bronze: Landing Page de Resultados | Makis Digital',
        ]);

        // CASE PRATA
        Project::updateOrCreate(['slug' => 'e-commerce-alta-conversao'], [
            'title' => 'E-commerce: Aumento de 25% no Faturamento',
            'category_id' => $catWeb->id,
            'user_id' => $admin->id,
            'summary' => 'Case Prata: Reconstrução de plataforma com foco em Core Web Vitals e Checkout simplificado.',
            'description' => "### O Problema\nA loja anterior era lenta, resultando em 60% de abandono de carrinho.\n\n### A Solução\nMigração para um ecossistema **Pacote Prata** otimizado para mobile e com SEO de ponta.\n\n### O Resultado\nAumento de 25% no faturamento bruto no primeiro mês após o lançamento.",
            'status' => ProjectStatus::Published,
            'is_featured' => true,
            'tech_stack' => ['Laravel', 'Livewire', 'Stripe'],
            'seo_title' => 'Case Prata: Otimização de E-commerce | Makis Digital',
        ]);

        // CASE OURO
        Project::updateOrCreate(['slug' => 'plataforma-gestao-leads-ia'], [
            'title' => 'IA: Redução de 70% no Custo de Atendimento',
            'category_id' => $catIA->id,
            'user_id' => $admin->id,
            'summary' => 'Case Ouro: Automação total de triagem de leads usando inteligência artificial GPT-4.',
            'description' => "### O Problema\nA empresa gastava milhares de reais com equipe de pré-vendas para filtrar leads qualificados.\n\n### A Solução\nImplementamos o **Pacote Ouro** com um cérebro de IA que qualifica e agenda reuniões automaticamente.\n\n### O Resultado\nRedução de 70% nos custos operacionais de atendimento e aumento na qualidade dos leads agendados.",
            'status' => ProjectStatus::Published,
            'is_featured' => true,
            'tech_stack' => ['Laravel', 'OpenAI', 'Python'],
            'seo_title' => 'Case Ouro: Inteligência Artificial nos Negócios | Makis Digital',
        ]);

        // --- BLOG POSTS ---
        Post::updateOrCreate(['slug' => 'como-ia-vai-salvar-seu-negocio'], [
            'title' => 'Como a Inteligência Artificial pode salvar 10h da sua semana',
            'category_id' => $catIA->id,
            'user_id' => $admin->id,
            'body' => '<p>Explore como automações simples podem liberar seu tempo para o que realmente importa.</p>',
            'is_published' => true,
            'published_at' => now(),
            'seo_title' => 'IA na Prática | Blog Makis Digital',
        ]);

        // --- TESTIMONIALS ---
        Testimonial::updateOrCreate(['client_name' => 'Ricardo Silva'], [
            'company_name' => 'TechSolutions Brasil',
            'role' => 'CEO',
            'content' => 'A Makis Digital transformou nossa operação. A automação com IA que eles implementaram nos economizou mais de 40 horas semanais de trabalho manual.',
            'rating' => 5,
            'is_featured' => true,
        ]);

        Testimonial::updateOrCreate(['client_name' => 'Mariana Costa'], [
            'company_name' => 'Clínica Bem Estar',
            'role' => 'Diretora Operacional',
            'content' => 'O site que eles construíram não é apenas bonito, ele converte! Nossa agenda lotou em menos de 15 dias após o lançamento da nova Landing Page.',
            'rating' => 5,
            'is_featured' => true,
        ]);

        Testimonial::updateOrCreate(['client_name' => 'Eduardo Oliveira'], [
            'company_name' => 'Imobiliária Prime',
            'role' => 'Sócio Fundador',
            'content' => 'Atendimento impecável e entrega extremamente rápida. O nome "Makis Digital" realmente faz jus ao serviço prestado. Recomendo fortemente.',
            'rating' => 5,
            'is_featured' => true,
        ]);
    }
}
