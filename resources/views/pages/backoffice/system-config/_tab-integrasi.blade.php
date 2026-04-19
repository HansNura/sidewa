{{-- Tab: Integrasi & API --}}
<section x-show="activeTab === 'integrasi'" x-transition.opacity.duration.300ms x-cloak>
    <div class="mb-6 border-b border-gray-100 pb-4">
        <h3 class="text-lg font-bold text-gray-900">Integrasi Pihak Ketiga</h3>
        <p class="text-sm text-gray-500">Kelola API Keys dan koneksi data (Misal: Integrasi Kemendagri / OpenDK).</p>
    </div>

    <div class="space-y-6 max-w-3xl">
        {{-- Dukcapil Endpoint --}}
        <article class="p-5 border border-gray-200 rounded-xl bg-white shadow-sm relative overflow-hidden">
            {{-- Status Badge --}}
            <div class="absolute top-4 right-4 flex items-center gap-1.5 bg-gray-50 text-gray-500 px-2.5 py-1 rounded border border-gray-200">
                <div class="w-1.5 h-1.5 rounded-full bg-gray-400"></div>
                <span class="text-[10px] font-bold uppercase tracking-wider">Konfigurasi Manual</span>
            </div>

            <h4 class="font-bold text-gray-900 flex items-center gap-2 mb-4">
                <i class="fa-solid fa-building-columns text-gray-400"></i> Endpoint Dukcapil Pusat
            </h4>

            <div class="space-y-4">
                {{-- Base URL --}}
                <div class="space-y-1">
                    <label for="dukcapil_url" class="text-xs font-bold text-gray-600">Base URL (Endpoint)</label>
                    <input type="url" id="dukcapil_url" name="dukcapil_url"
                           x-model="config.dukcapilUrl"
                           value="{{ old('dukcapil_url', $config['dukcapil_url']) }}"
                           class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                </div>

                {{-- API Key --}}
                <div class="space-y-1">
                    <label for="dukcapil_api_key" class="text-xs font-bold text-gray-600">Authentication Token (Bearer API Key)</label>
                    <div class="flex gap-2">
                        <input :type="config.showApiKey ? 'text' : 'password'"
                               id="dukcapil_api_key" name="dukcapil_api_key"
                               x-model="config.dukcapilApiKey"
                               value="{{ old('dukcapil_api_key', $config['dukcapil_api_key']) }}"
                               class="flex-1 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono text-gray-600 focus:ring-2 focus:ring-green-500 outline-none">
                        <button type="button" @click="config.showApiKey = !config.showApiKey"
                            class="w-10 h-10 shrink-0 border border-gray-200 rounded-lg bg-white text-gray-500 hover:bg-gray-50 flex items-center justify-center transition-colors cursor-pointer">
                            <i class="fa-solid" :class="config.showApiKey ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                        <button type="button"
                            @click="navigator.clipboard.writeText(config.dukcapilApiKey)"
                            class="w-10 h-10 shrink-0 border border-gray-200 rounded-lg bg-white text-gray-500 hover:text-green-600 hover:bg-green-50 flex items-center justify-center transition-colors cursor-pointer"
                            title="Salin">
                            <i class="fa-regular fa-copy"></i>
                        </button>
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>
