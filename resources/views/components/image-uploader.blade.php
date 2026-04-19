@props(['name', 'value' => null, 'label' => 'Imagem'])

<div x-data="{ 
    preview: '{{ $value }}', 
    uploading: false,
    async handleUpload(e) {
        const file = e.target.files[0];
        if (!file) return;

        this.uploading = true;
        const formData = new FormData();
        formData.append('file', file);

        try {
            const response = await fetch('{{ route('admin.media.upload') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').content
                },
                body: formData
            });

            const data = await response.json();
            if (data.status === 'success') {
                this.preview = data.url;
                document.getElementById('{{ $name }}').value = data.url;
            } else {
                alert('Erro no upload: ' + data.message);
            }
        } catch (error) {
            console.error(error);
            alert('Falha ao enviar arquivo.');
        } finally {
            this.uploading = false;
        }
    }
}" class="space-y-2">
    <x-input-label :for="$name" :value="$label" />
    
    <div class="flex items-start gap-4">
        <!-- Input e Botão de Escolha -->
        <div class="flex-1 space-y-3">
            <x-text-input :id="$name" :name="$name" type="text" class="block w-full text-sm" :value="$value" placeholder="URL da imagem ou faça upload..." />
            
            <div class="flex items-center gap-2">
                <label class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 cursor-pointer">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    <span>Fazer Upload</span>
                    <input type="file" class="hidden" @change="handleUpload" accept="image/*">
                </label>
                <template x-if="uploading">
                    <span class="text-xs text-indigo-600 animate-pulse">Enviando...</span>
                </template>
            </div>
        </div>

        <!-- Preview da Imagem -->
        <div class="w-32 h-20 rounded-lg border-2 border-dashed border-gray-300 overflow-hidden flex items-center justify-center bg-gray-50 bg-cover bg-center"
             :style="preview ? `background-image: url('${preview}')` : ''">
            <template x-if="!preview">
                <svg class="w-8 h-8 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
            </template>
        </div>
    </div>
</div>
