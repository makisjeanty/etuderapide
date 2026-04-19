<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Envia uma notificação de "Deep Link" para o administrador.
     * Este link, ao ser clicado, abre o WhatsApp com uma mensagem pronta para o cliente.
     */
    public function notifyAdminAboutLead($lead): string
    {
        $adminPhone = config('services.whatsapp.admin_phone', '5511999999999');
        $clientName = $lead->name;
        $clientPhone = $lead->phone ?? 'Não informado';
        $interest = $lead->service_interest ?? 'Geral';

        $message = "Olá Admin Makis! 🚀\n\nNovo Lead Recebido:\nNome: {$clientName}\nInteresse: {$interest}\n\nClique aqui para falar com o cliente: https://wa.me/{$clientPhone}";

        $encodedMessage = urlencode($message);
        $whatsappLink = "https://wa.me/{$adminPhone}?text={$encodedMessage}";

        Log::info('WhatsApp Notification Generated for Admin', ['link' => $whatsappLink]);

        return $whatsappLink;
    }

    /**
     * Retorna o link direto para o WhatsApp do cliente.
     */
    public function getClientWhatsAppLink($lead): string
    {
        $phone = preg_replace('/[^0-9]/', '', $lead->phone);
        if (empty($phone)) {
            return '#';
        }

        $message = "Olá {$lead->name}! Recebemos seu interesse na Makis Digital sobre {$lead->service_interest}. Como podemos ajudar?";

        return "https://wa.me/{$phone}?text=".urlencode($message);
    }
}
