<?php

namespace App\Http\Controllers\Web;

use App\Enums\ProjectStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeadRequest;
use App\Mail\AdminNotificationMail;
use App\Mail\LeadAutoResponder;
use App\Mail\LeadConfirmationCustomer;
use App\Models\Lead;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\Testimonial;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        // Usamos try-catch para garantir que a Home Page carregue
        // mesmo se houver falha na conexão com o banco de dados.
        try {
            $featuredProjects = Project::query()
                ->where('status', ProjectStatus::Published)
                ->where('is_featured', true)
                ->latest()
                ->take(3)
                ->get();

            $services = Service::query()
                ->where('is_active', true)
                ->take(6)
                ->get();

            $latestPosts = Post::query()
                ->where('is_published', true)
                ->latest('published_at')
                ->take(3)
                ->get();

            $testimonials = Testimonial::where('is_featured', true)
                ->latest()
                ->get();
        } catch (\Exception $e) {
            // Logamos o erro para análise técnica, mas o site continua no ar
            \Log::error('Falha de banco na Home Page: '.$e->getMessage());

            $featuredProjects = collect();
            $services = collect();
            $latestPosts = collect();
            $testimonials = collect();
        }

        return view('public.home', compact('featuredProjects', 'services', 'latestPosts', 'testimonials'));
    }

    public function about(): View
    {
        return view('public.about');
    }

    public function contact(): View
    {
        return view('public.contact');
    }

    public function contactSuccess(): View
    {
        return view('public.contact-success');
    }

    public function submitContact(StoreLeadRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $lead = Lead::create($request->validated());

                // Serviços de Notificação
                $whatsappService = new WhatsAppService;
                $adminWhatsAppLink = $whatsappService->notifyAdminAboutLead($lead);

                // Notifica o Admin com o link do WhatsApp
                Mail::to(config('mail.from.address'))->send(new AdminNotificationMail($lead, $adminWhatsAppLink));

                // Confirmação para o Cliente
                Mail::to($lead->email)->send(new LeadConfirmationCustomer($lead));

                // Resposta Automática
                Mail::to($lead->email)->send(new LeadAutoResponder($lead));
            });

            return redirect()->route('contact.success');
        } catch (\Exception $e) {
            \Log::error('Erro ao processar lead: '.$e->getMessage());

            return back()->withInput()->with('error', 'Ocorreu um problema ao processar sua solicitação. Por favor, tente novamente.');
        }
    }
}
