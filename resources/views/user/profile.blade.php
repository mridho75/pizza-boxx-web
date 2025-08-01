@extends('layouts.app')

@section('title', 'Profile Saya')

@section('content')
<div class="max-w-6xl mx-auto py-10 mt-32">
  <h2 class="text-3xl font-extrabold mb-8 text-center text-red-600 tracking-tight">Akun Saya</h2>
  <div class="flex flex-col md:flex-row gap-8">
    <!-- Sidebar Navigasi -->
    <nav class="md:w-1/4 w-full mb-8 md:mb-0">
      <div class="bg-white rounded-2xl shadow-lg p-6 md:sticky md:top-24">
        <ul class="space-y-2">
          <li>
            <button id="sidebar-profile-btn" class="w-full text-left px-4 py-2 rounded-lg font-semibold text-gray-700 hover:bg-red-100 transition">Informasi Profil</button>
          </li>
          <li>
            <button id="sidebar-address-btn" class="w-full text-left px-4 py-2 rounded-lg font-semibold text-gray-700 hover:bg-red-100 transition">Alamat Tersimpan</button>
          </li>
        </ul>
      </div>
    </nav>
    <!-- Konten Utama -->
    <main class="md:w-3/4 w-full">
      <!-- Informasi Profil -->
      <div id="profile-section">
        <div class="bg-white rounded-2xl shadow-lg p-8 transition-all duration-300 hover:shadow-2xl flex flex-col justify-center">
          <form method="POST" action="{{ route('user.profile.update') }}" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="mb-4">
              <label class="block text-gray-700 font-semibold mb-2">Nama</label>
              <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-400 focus:outline-none transition-all duration-200" required>
            </div>
            <div class="mb-4">
              <label class="block text-gray-700 font-semibold mb-2">Email</label>
              <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-100 cursor-not-allowed" required readonly>
            </div>
            <button type="submit" class="flex items-center gap-2 bg-red-600 text-white px-6 py-3 rounded-lg shadow hover:bg-red-700 focus:ring-2 focus:ring-red-400 transition-all duration-200">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
              Simpan
            </button>
          </form>
        </div>
      </div>
      <!-- Alamat Tersimpan (hidden by default) -->
      <div id="address-section" style="display:none;">
        <div class="bg-white rounded-2xl shadow-lg p-8 transition-all duration-300 hover:shadow-2xl">
          <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7l6 6-6 6M21 7l-6 6 6 6" /></svg>
            Alamat Tersimpan
          </h2>
          <a href="{{ route('user.address.create') }}" class="inline-flex items-center gap-2 mb-6 bg-green-600 text-white px-5 py-2.5 rounded-lg shadow hover:bg-green-700 focus:ring-2 focus:ring-green-400 transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Tambah Alamat Baru
          </a>
          <ul class="space-y-6">
            @forelse($addresses as $address)
              <li class="relative group bg-gray-50 border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-all duration-200 animate-fade-in">
                <div class="flex items-center gap-3 mb-1">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.88 3.549A9 9 0 013 12c0 4.418 3.134 8.166 7.5 8.938" /></svg>
                  <span class="font-semibold text-base">{{ $address->label }}</span>
                </div>
                <div class="text-gray-700 text-sm mb-1">{{ $address->address }}</div>
                <div class="text-gray-500 text-xs mb-1">{{ $address->city }}, {{ $address->province }}</div>
                <div class="text-gray-500 text-xs flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10l1.553-1.553A2 2 0 017.414 8h9.172a2 2 0 012.828 0L21 10m-9 4v4m0 0h4m-4 0H7" /></svg>{{ $address->phone }}</div>
                <div class="mt-2 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                  <form action="{{ route('user.address.delete', $address) }}" method="POST" class="delete-address-form">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="flex items-center gap-1 text-red-600 hover:bg-red-50 px-3 py-1.5 rounded btn-delete-address transition-all duration-150">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                      Hapus
                    </button>
                  </form>
                </div>
              </li>
            @empty
              <li class="text-gray-500">Belum ada alamat tersimpan.</li>
            @endforelse
          </ul>
        </div>
      </div>
    </main>
  </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Sidebar tab switching
    const profileBtn = document.getElementById('sidebar-profile-btn');
    const addressBtn = document.getElementById('sidebar-address-btn');
    const profileSection = document.getElementById('profile-section');
    const addressSection = document.getElementById('address-section');
    profileBtn.addEventListener('click', function() {
        profileSection.style.display = '';
        addressSection.style.display = 'none';
        profileBtn.classList.add('bg-red-100');
        addressBtn.classList.remove('bg-red-100');
    });
    addressBtn.addEventListener('click', function() {
        profileSection.style.display = 'none';
        addressSection.style.display = '';
        addressBtn.classList.add('bg-red-100');
        profileBtn.classList.remove('bg-red-100');
    });
    // Set default active
    profileBtn.classList.add('bg-red-100');

    // SweetAlert for delete
    document.querySelectorAll('.btn-delete-address').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            const form = btn.closest('form');
            Swal.fire({
                title: 'Hapus alamat ini?',
                text: 'Alamat yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush
