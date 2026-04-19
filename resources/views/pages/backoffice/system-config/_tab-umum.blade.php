{{-- Tab: Pengaturan Umum --}}
<section x-show="activeTab === 'umum'" x-transition.opacity.duration.300ms>
    <div class="mb-6 border-b border-gray-100 pb-4">
        <h3 class="text-lg font-bold text-gray-900">Pengaturan Umum</h3>
        <p class="text-sm text-gray-500">Konfigurasi dasar tampilan dan preferensi lokalisasi aplikasi.</p>
    </div>

    <div class="space-y-6 max-w-2xl">
        {{-- Nama Aplikasi --}}
        <div class="space-y-1">
            <label for="app_name" class="text-sm font-bold text-gray-700">Nama Aplikasi Sistem</label>
            <input type="text" id="app_name" name="app_name"
                   x-model="config.appName"
                   value="{{ old('app_name', $config['app_name']) }}"
                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all"
                   required>
            <p class="text-[10px] text-gray-500">Nama ini akan muncul pada judul tab browser dan meta tag.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            {{-- Timezone --}}
            <div class="space-y-1">
                <label for="timezone" class="text-sm font-bold text-gray-700">Zona Waktu (Timezone)</label>
                <select id="timezone" name="timezone" x-model="config.timezone"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none cursor-pointer">
                    <option value="Asia/Jakarta">Asia/Jakarta (WIB)</option>
                    <option value="Asia/Makassar">Asia/Makassar (WITA)</option>
                    <option value="Asia/Jayapura">Asia/Jayapura (WIT)</option>
                </select>
            </div>

            {{-- Date Format --}}
            <div class="space-y-1">
                <label for="date_format" class="text-sm font-bold text-gray-700">Format Tanggal Default</label>
                <select id="date_format" name="date_format" x-model="config.dateFormat"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none cursor-pointer">
                    <option value="DD/MM/YYYY">DD/MM/YYYY (18/04/2026)</option>
                    <option value="DD MMMM YYYY">DD MMMM YYYY (18 April 2026)</option>
                    <option value="YYYY-MM-DD">YYYY-MM-DD (2026-04-18)</option>
                </select>
            </div>
        </div>

        {{-- Language --}}
        <div class="space-y-1">
            <label for="language" class="text-sm font-bold text-gray-700">Bahasa Sistem Default</label>
            <select id="language" name="language" x-model="config.language"
                    class="w-full sm:w-1/2 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none cursor-pointer">
                <option value="id">Bahasa Indonesia</option>
                <option value="en">English (US)</option>
            </select>
        </div>
    </div>
</section>
