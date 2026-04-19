import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.data('aiAssistant', (endpoint, contextFieldId = 'title') => ({
        loading: {},
        async generate(command, targetId, mode = 'replace') {
            const contextElement = document.getElementById(contextFieldId);
            const targetElement = document.getElementById(targetId);
            const context = contextElement?.value?.trim() || '';

            if (!context || !targetElement) {
                alert('Preencha o campo principal antes de usar a IA.');
                return;
            }

            this.loading[targetId] = true;

            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        command,
                        context,
                    }),
                });

                const data = await response.json();

                if (!response.ok || data.status !== 'success') {
                    throw new Error(data.message || 'Erro ao processar a solicitação.');
                }

                if (mode === 'append' && targetElement.tagName === 'TEXTAREA') {
                    targetElement.value = [targetElement.value, data.result].filter(Boolean).join('\n\n');
                } else {
                    targetElement.value = data.result;
                }
            } catch (error) {
                console.error(error);
                alert(error.message || 'Falha na comunicação com o servidor de IA.');
            } finally {
                this.loading[targetId] = false;
            }
        },
    }));

    Alpine.data('aiAuditor', (endpoint = '/analyze') => ({
        text: '',
        email: '',
        website_url: '',
        loading: false,
        result: null,
        error: null,
        async runAudit() {
            if (!this.text.trim() || !this.email.trim()) {
                alert('Por favor, preencha o texto e seu e-mail.');
                return;
            }

            this.loading = true;
            this.error = null;

            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        text: this.text,
                        email: this.email,
                        website_url: this.website_url,
                        command: 'business_audit',
                    }),
                });

                const data = await response.json();

                if (!response.ok || !data.analysis) {
                    throw new Error(data.error || 'Erro ao processar análise.');
                }

                this.result = data.analysis;
            } catch (error) {
                console.error(error);
                this.error = error.message || 'Erro de conexão.';
                alert(this.error);
            } finally {
                this.loading = false;
            }
        },
    }));
});

Alpine.start();
