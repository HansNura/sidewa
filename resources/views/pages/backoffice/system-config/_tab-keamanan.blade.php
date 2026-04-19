{{-- Tab: Keamanan & Sesi --}}
<section x-show="activeTab === 'keamanan'" x-transition.opacity.duration.300ms x-cloak>
    <div class="mb-6 border-b border-gray-100 pb-4">
        <h3 class="text-lg font-bold text-gray-900">Keamanan & Sesi</h3>
        <p class="text-sm text-gray-500">Amankan akses aplikasi dengan kebijakan password dan autentikasi ganda.</p>
    </div>

    <div class="space-y-6 max-w-2xl">
        {{-- Password Policy --}}
        <div class="p-4 bg-gray-50 border border-gray-100 rounded-xl space-y-3">
            <h4 class="text-sm font-bold text-gray-800">
                <i class="fa-solid fa-key text-gray-400 mr-2"></i>Kebijakan Kata Sandi (Password Policy)
            </h4>
            <select name="password_policy" x-model="config.passwordPolicy"
                    class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-green-500">
                <option value="low">Lemah (Minimal 6 Karakter bebas)</option>
                <option value="medium">Sedang (Minimal 8 Karakter, Alfanumerik)</option>
                <option value="strong">Kuat (Min 8, Huruf Besar, Angka, & Simbol Khusus)</option>
            </select>
            <p class="text-[10px] text-gray-500">Kebijakan ini akan diterapkan saat user baru mendaftar atau melakukan reset password.</p>
        </div>

        {{-- Session Timeout --}}
        <div class="p-4 bg-gray-50 border border-gray-100 rounded-xl space-y-3">
            <h4 class="text-sm font-bold text-gray-800">
                <i class="fa-solid fa-clock text-gray-400 mr-2"></i>Batas Waktu Sesi (Session Timeout)
            </h4>
            <div class="flex items-center gap-3">
                <input type="number" name="session_timeout"
                       x-model="config.sessionTimeout"
                       value="{{ old('session_timeout', $config['session_timeout']) }}"
                       min="5" max="1440"
                       class="w-24 border border-gray-200 rounded-lg px-3 py-2 text-sm text-center outline-none focus:ring-2 focus:ring-green-500">
                <span class="text-sm font-medium text-gray-600">Menit (Waktu Idle)</span>
            </div>
            <p class="text-[10px] text-gray-500">Sistem akan otomatis <em>logout</em> jika tidak ada interaksi selama batas waktu tersebut.</p>
        </div>

        {{-- Two-Factor Authentication --}}
        <div class="p-4 bg-gray-50 border border-gray-100 rounded-xl flex items-center justify-between">
            <div>
                <h4 class="text-sm font-bold text-gray-800 mb-1">
                    <i class="fa-solid fa-mobile-screen text-gray-400 mr-2"></i>Two-Factor Authentication (2FA)
                </h4>
                <p class="text-[10px] text-gray-500 max-w-sm">Wajibkan Admin & Operator menggunakan OTP (Google Authenticator) saat login.</p>
            </div>
            <label class="flex items-center cursor-pointer shrink-0">
                <input type="hidden" name="two_factor" value="0">
                <input type="checkbox" name="two_factor" value="1" x-model="config.twoFactor" class="sr-only">
                <div class="relative">
                    <div class="block w-12 h-7 rounded-full transition-colors"
                         :class="config.twoFactor ? 'bg-green-500' : 'bg-gray-300'"></div>
                    <div class="dot absolute left-1 top-1 bg-white w-5 h-5 rounded-full transition-transform"
                         :class="config.twoFactor ? 'transform translate-x-5' : ''"></div>
                </div>
            </label>
        </div>
    </div>
</section>
