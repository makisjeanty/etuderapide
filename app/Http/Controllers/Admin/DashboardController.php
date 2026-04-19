<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        // Estatísticas Comerciais
        $totalPipelineValue = Lead::where('status', 'replied')->sum('quoted_value');
        $newLeadsCount = Lead::where('status', 'new')->count();
        $totalLeadsCount = Lead::count();

        // Dados para Gráfico: Leads nos últimos 30 dias
        $leadsHistory = Lead::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Dados para Gráfico: Distribuição por Status
        $leadsByStatus = Lead::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Dados para Gráfico: Interesse de Serviço
        $leadsByInterest = Lead::select('service_interest', DB::raw('count(*) as count'))
            ->groupBy('service_interest')
            ->orderByDesc('count')
            ->take(5)
            ->get();

        // Estatísticas de Conteúdo
        $postsCount = Post::count();
        $projectsCount = Project::count();
        $servicesCount = Service::count();
        $recentLeads = Lead::latest()->take(5)->get();

        // Métricas de Saúde (Infra)
        $health = [
            'database' => true,
            'cache' => Cache::put('health_check', true, 10),
            'storage' => is_writable(storage_path('app')),
        ];
        $systemHealth = $health;

        // Métricas de Negócio (Conversão)
        $business = [
            'total_leads' => $totalLeadsCount,
            'pending_leads' => $newLeadsCount,
            'conversion_rate' => $this->calculateConversionRate(),
            'latest_leads' => $recentLeads,
        ];

        return view('admin.dashboard', compact(
            'totalPipelineValue',
            'newLeadsCount',
            'totalLeadsCount',
            'leadsHistory',
            'leadsByStatus',
            'leadsByInterest',
            'postsCount',
            'projectsCount',
            'servicesCount',
            'recentLeads',
            'systemHealth',
            'health',
            'business'
        ));
    }

    protected function calculateConversionRate(): float
    {
        $totalLeads = Lead::count();

        if ($totalLeads === 0) {
            return 0.0;
        }

        $qualifiedLeads = Lead::whereIn('status', ['replied', 'archived'])->count();

        return round(($qualifiedLeads / $totalLeads) * 100, 1);
    }
}
