<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateLeadRequest;
use App\Models\Lead;
use App\Services\WhatsAppService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            abort_unless($request->user()?->canManageLeads(), 403);

            return $next($request);
        });
    }

    public function index(): View
    {
        $leads = Lead::query()
            ->latest()
            ->paginate(15);

        return view('admin.leads.index', compact('leads'));
    }

    public function show(Lead $lead): View
    {
        if ($lead->status === 'new') {
            $lead->update(['status' => 'read']);
        }

        $whatsappService = new WhatsAppService;
        $whatsappLink = $whatsappService->getClientWhatsAppLink($lead);

        return view('admin.leads.show', compact('lead', 'whatsappLink'));
    }

    public function updateStatus(UpdateLeadRequest $request, Lead $lead): RedirectResponse
    {
        $lead->update($request->validated());

        return redirect()->route('admin.leads.show', $lead)->with('success', 'Lead atualizado com sucesso.');
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $lead->delete();

        return redirect()->route('admin.leads.index')->with('success', 'Lead removido com sucesso.');
    }
}
