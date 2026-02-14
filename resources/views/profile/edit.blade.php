<x-app-layout>
    <div class="py-12 bg-white md:bg-transparent">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Header -->
             <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <h1 class="text-3xl font-serif font-bold text-gray-800">Pengaturan Profil</h1>
                 <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-gray-500 hover:text-emerald-600 font-bold transition">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Dashboard
                </a>
            </div>

            <!-- Profile Info -->
            <div class="p-4 sm:p-8 bg-white shadow-xl sm:rounded-3xl border border-gray-100">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Password -->
            <div class="p-4 sm:p-8 bg-white shadow-xl sm:rounded-3xl border border-gray-100">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete (Optional, maybe specific to super admin, but keeping style consistent) -->
            <div class="p-4 sm:p-8 bg-white shadow-xl sm:rounded-3xl border border-gray-100">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
