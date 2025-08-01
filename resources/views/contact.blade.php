@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-b from-orange-50 to-gray-50">
    <div class="container mx-auto px-4 py-24">
        <!-- Header Section with Animated Gradient Text -->
        <div class="text-center mb-20">
            <h1 class="text-4xl md:text-6xl font-bold mb-4">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-red-600 to-orange-500">
                    Hubungi Kami
                </span>
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed">
                Punya pertanyaan, saran, atau hanya ingin menyapa? Tim PizzaBoxx siap membantu Anda!
            </p>
        </div>

        <!-- Contact Container with Floating Effect -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden transform transition-all hover:shadow-2xl hover:-translate-y-1">
            <div class="grid md:grid-cols-2 gap-0">
                <!-- Left Column: Contact Info with Gradient Background -->
                <div class="bg-gradient-to-br from-red-600 to-orange-500 p-12 text-white">
                    <h2 class="text-3xl font-bold mb-6">Mari Berbincang</h2>
                    <p class="text-red-100 mb-10 text-lg leading-relaxed">
                        Kami selalu senang mendengar dari pelanggan kami. Hubungi kami melalui informasi di bawah ini atau isi formulir kontak.
                    </p>

                    <div class="space-y-8">
                        <!-- Address -->
                        <div class="flex items-start gap-5">
                            <div class="bg-white bg-opacity-20 p-3 rounded-xl">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-xl mb-1">Alamat</h3>
                                <p class="text-red-100">Jl. Pizza Raya No. 123, Depok<br>Jawa Barat, Indonesia</p>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="flex items-start gap-5">
                            <div class="bg-white bg-opacity-20 p-3 rounded-xl">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-xl mb-1">Telepon</h3>
                                <p class="text-red-100">(021) 123-4567</p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex items-start gap-5">
                            <div class="bg-white bg-opacity-20 p-3 rounded-xl">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-xl mb-1">Email</h3>
                                <p class="text-red-100">halo@pizzaboxx.com</p>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="mt-14 pt-8 border-t border-red-400 border-opacity-30">
                        <h3 class="font-bold text-xl mb-5">Ikuti Kami</h3>
                        <div class="flex gap-5">
                            <a href="#" class="bg-white bg-opacity-20 hover:bg-opacity-30 p-3 rounded-full transition-all">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <a href="#" class="bg-white bg-opacity-20 hover:bg-opacity-30 p-3 rounded-full transition-all">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.71v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                                </svg>
                            </a>
                            <a href="#" class="bg-white bg-opacity-20 hover:bg-opacity-30 p-3 rounded-full transition-all">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.024.06 1.378.06 3.808s-.012 2.784-.06 3.808c-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.024.048-1.378.06-3.808.06s-2.784-.013-3.808-.06c-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.048-1.024-.06-1.378-.06-3.808s.012-2.784.06-3.808c.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 016.345 2.525c.636-.247 1.363-.416 2.427-.465C9.792 2.013 10.146 2 12.315 2zm-1.161 1.043c-1.061.049-1.694.21-2.227.427-.636.254-1.055.596-1.465 1.005-.41.41-.752.83-1.005 1.465-.217.533-.378 1.166-.427 2.227-.049 1.024-.06 1.35-.06 3.63s.012 2.606.06 3.63c.049 1.061.21 1.694.427 2.227.254.636.596 1.055 1.005 1.465.41.41.83.752 1.465 1.005.533.217 1.166.378 2.227.427 1.024.049 1.35.06 3.63.06s2.606-.012 3.63-.06c1.061-.049 1.694-.21 2.227-.427.636-.254 1.055-.596 1.465-1.005.41-.41.752-.83 1.005-1.465.217-.533.378-1.166.427-2.227.049-1.024.06-1.35.06-3.63s-.012-2.606-.06-3.63c-.049-1.061-.21-1.694-.427-2.227-.254-.636-.596-1.055-1.005-1.465-.41-.41-.83-.752-1.465-1.005-.533-.217-1.166-.378-2.227-.427-1.024-.049-1.35-.06-3.63-.06s-2.606.012-3.63.06zM12 6.865a5.135 5.135 0 100 10.27 5.135 5.135 0 000-10.27zm0 8.468a3.333 3.333 0 110-6.666 3.333 3.333 0 010 6.666zm5.338-9.87a1.2 1.2 0 100 2.4 1.2 1.2 0 000-2.4z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Contact Form -->
                <div class="p-12">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Kirim Pesan</h2>
                    <p class="text-gray-500 mb-8">Isi formulir di bawah ini dan kami akan segera merespons Anda.</p>

                    <form action="#" method="POST" class="space-y-6">
                        @csrf
                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <input type="text" name="name" id="name" placeholder="John Doe" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all">
                            </div>
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <input type="email" name="email" id="email" placeholder="anda@email.com" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all">
                            </div>
                        </div>

                        <!-- Message Field -->
                        <div>
                            <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">Pesan Anda</label>
                            <div class="relative">
                                <div class="absolute top-3 left-3">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                    </svg>
                                </div>
                                <textarea name="message" id="message" rows="5" placeholder="Tuliskan pesan Anda di sini..." class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all"></textarea>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit" class="w-full flex items-center justify-center gap-3 bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white font-bold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300">
                                <span class="text-lg">Kirim Pesan</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
