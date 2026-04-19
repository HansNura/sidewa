<footer class="bg-[#2E7D32] text-white pt-16 pb-8" x-data="footerData()">
    <div class="px-6 mx-auto max-w-7xl md:px-10">
        <div class="grid grid-cols-1 gap-12 pb-10 border-b md:grid-cols-3 border-white/20">
            <!-- Identitas Desa -->
            <div>
                <div class="flex items-center gap-4 mb-4">
                    <img :src="identity.logoSrc" alt="Logo Desa"
                        class="border-2 rounded-full w-14 h-14 border-white/70" />
                    <div>
                        <h2 class="text-2xl font-semibold" x-text="identity.name"></h2>
                        <p class="text-sm text-yellow-300" x-text="identity.tagline"></p>
                    </div>
                </div>
                <p class="text-sm leading-relaxed text-gray-100" x-text="identity.description"></p>
            </div>

            <!-- Navigasi Cepat -->
            <div>
                <h3 class="mb-4 text-xl font-semibold text-yellow-300">
                    Navigasi Cepat
                </h3>
                <ul class="gap-8 space-y-3 text-sm text-gray-100 columns-2">
                    <template x-for="link in quickLinks" :key="link.href">
                        <li>
                            <a :href="link.href"
                                class="inline-block transition-colors hover:text-yellow-300 hover:underline underline-offset-4"
                                x-text="link.label">
                            </a>
                        </li>
                    </template>
                </ul>
            </div>

            <!-- Kontak & Sosial -->
            <div>
                <h3 class="mb-4 text-xl font-semibold text-yellow-300">
                    Kontak & Sosial
                </h3>
                <ul class="space-y-3 text-sm text-gray-100">
                    <template x-for="item in contactInfo" :key="item.text">
                        <li class="flex items-start gap-3">
                            <span class="w-5 h-5 flex-shrink-0 mt-0.5 opacity-80" x-html="item.icon"></span>
                            <a :href="item.href" class="transition-colors hover:text-yellow-300"
                                x-text="item.text"></a>
                        </li>
                    </template>
                </ul>
                <div class="flex gap-3 mt-6">
                    <template x-for="social in socialLinks" :key="social.title">
                        <a :href="social.href" :title="social.title" target="_blank" rel="noopener noreferrer"
                            class="p-2 transition-colors rounded-full bg-white/10 text-white hover:bg-yellow-400 hover:text-[#2E7D32]">
                            <span class="sr-only" x-text="social.title"></span>
                            <div class="w-5 h-5" x-html="social.icon"></div>
                        </a>
                    </template>
                </div>
            </div>
        </div>
        <!-- Copyright -->
        <div class="pt-6 text-sm text-center text-gray-200" x-cloak>
            &copy;
            <span x-text="copyright.year"></span>
            <span x-text="copyright.text"></span>
            <a :href="copyright.developerLink" target="_blank" rel="noopener noreferrer"
                class="font-medium text-yellow-300 transition-colors hover:text-white"
                x-text="copyright.developerName"></a>.
        </div>
    </div>
</footer>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('footerData', () => ({
        identity: {
            logoSrc: '{{ asset('assets/img/logo.webp') }}',
            name: 'Desa Sindangmukti',
            tagline: 'Kec. Panumbangan, Kab. Ciamis',
            description: 'Website resmi Pemerintah Desa Sindangmukti sebagai wujud transparansi dan layanan publik berbasis digital.'
        },
        quickLinks: [
            { href: '{{ url('/') }}', label: 'Beranda' },
            { href: '{{ url('profil') }}', label: 'Profil Desa' },
            { href: '{{ url('data') }}', label: 'Data Penduduk' },
            { href: '{{ url('transparansi') }}', label: 'Transparansi' },
            { href: '{{ url('layanan') }}', label: 'Layanan Publik' },
            { href: '{{ url('lapak') }}', label: 'Lapak Desa' },
            { href: '{{ url('galeri') }}', label: 'Galeri' }
        ],
        contactInfo: [
            { icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>', text: 'Jl. Raya Sukakerta No. 123', href: '#' },
            { icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.896-1.596-5.54-4.24-7.136-7.136l1.292-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" /></svg>', text: '0812-3456-7890', href: 'tel:081234567890' },
            { icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>', text: 'info@sindangmukti.desa.id', href: 'mailto:info@sindangmukti.desa.id' }
        ],
        socialLinks: [
            { icon: '<svg fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>', title: 'Twitter', href: '#' },
            { icon: '<svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.88z"/></svg>', title: 'Instagram', href: '#' },
            { icon: '<svg fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>', title: 'Facebook', href: '#' },
        ],
        copyright: {
            year: new Date().getFullYear(),
            text: ' Pemerintah Desa Sindangmukti. Dikembangkan oleh ',
            developerName: 'Developer',
            developerLink: '#'
        }
    }));
});
</script>
@endpush
