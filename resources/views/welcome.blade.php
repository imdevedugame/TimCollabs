<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TIMVANZ - Sistem Manajemen Tugas</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Tambahkan Font Awesome untuk ikon yang lebih jelas -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom styles untuk memastikan kontras yang baik */
        .gradient-bg {
            background: linear-gradient(135deg, #0052CC 0%, #003380 100%);
        }
        .gradient-text {
            background: linear-gradient(90deg, #0052CC 0%, #3385FF 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .feature-card {
            transition: all 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .icon-circle {
            width: 64px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="antialiased bg-gray-50 dark:bg-gray-900">
    <!-- Navigation -->
    <nav class="bg-white dark:bg-gray-800 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="bg-blue-600 text-white p-2 rounded-lg shadow-lg mr-2">
                            <i class="fas fa-tasks fa-lg"></i>
                        </div>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">TIMVANZ</span>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium">Masuk</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-md">
                            <i class="fas fa-user-plus mr-2"></i> Daftar Sekarang
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative overflow-hidden gradient-bg text-white">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 py-12 sm:py-16 md:py-20 lg:py-28 lg:max-w-2xl lg:w-full">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold sm:text-5xl md:text-6xl">
                            <span class="block">Kelola Tugas Anda</span>
                            <span class="block text-yellow-300">dengan Mudah dan Efisien</span>
                        </h1>
                        <p class="mt-6 text-xl text-blue-100 max-w-lg mx-auto lg:mx-0">
                            Sederhanakan alur kerja, berkolaborasi dengan tim, dan capai tujuan Anda dengan sistem manajemen tugas yang powerful.
                        </p>
                        <div class="mt-10 flex flex-col sm:flex-row sm:justify-center lg:justify-start gap-4">
                            <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 border border-transparent text-base font-medium rounded-lg text-blue-700 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white shadow-lg">
                                <i class="fas fa-rocket mr-2"></i> Mulai Sekarang
                            </a>
                            <a href="#cara-kerja" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 border-2 border-white text-base font-medium rounded-lg text-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white">
                                <i class="fas fa-info-circle mr-2"></i> Pelajari Lebih Lanjut
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hidden lg:block lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
            <img class="h-full w-full object-cover" src="/images/hero.png?height=800&width=1200" alt="Dashboard Manajemen Tugas">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-transparent opacity-20"></div>
        </div>
    </div>

    <!-- Statistik Section -->
    <div class="bg-white dark:bg-gray-800 shadow-md -mt-10 relative z-10 mx-4 sm:mx-8 lg:mx-auto max-w-7xl rounded-xl overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-gray-200 dark:divide-gray-700">
            <div class="p-8 text-center">
                <div class="text-blue-600 mb-4">
                    <i class="fas fa-check-circle fa-3x"></i>
                </div>
                <p class="text-4xl font-bold text-gray-900 dark:text-white">10,000+</p>
                <p class="mt-2 text-lg text-gray-600 dark:text-gray-300">Tugas Selesai</p>
            </div>
            <div class="p-8 text-center">
                <div class="text-blue-600 mb-4">
                    <i class="fas fa-users fa-3x"></i>
                </div>
                <p class="text-4xl font-bold text-gray-900 dark:text-white">5,000+</p>
                <p class="mt-2 text-lg text-gray-600 dark:text-gray-300">Pengguna Aktif</p>
            </div>
            <div class="p-8 text-center">
                <div class="text-blue-600 mb-4">
                    <i class="fas fa-star fa-3x"></i>
                </div>
                <p class="text-4xl font-bold text-gray-900 dark:text-white">4.8/5</p>
                <p class="mt-2 text-lg text-gray-600 dark:text-gray-300">Rating Pengguna</p>
            </div>
        </div>
    </div>

    <!-- Cara Kerja Section -->
    <div id="cara-kerja" class="py-20 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-3 py-1 text-sm font-semibold text-blue-800 bg-blue-100 rounded-full mb-4">CARA KERJA</span>
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white sm:text-4xl">
                    Bagaimana TIMVANZ Bekerja
                </h2>
                <p class="mt-4 max-w-2xl text-xl text-gray-600 dark:text-gray-400 mx-auto">
                    Ikuti langkah-langkah sederhana ini untuk memulai dengan TIMVANZ
                </p>
            </div>

            <div class="space-y-24">
                <!-- Langkah 1 -->
                <div class="lg:grid lg:grid-cols-2 lg:gap-12 lg:items-center">
                    <div class="relative">
                        <div class="bg-blue-600 text-white text-2xl font-bold h-12 w-12 rounded-full flex items-center justify-center mb-6">1</div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                            Buat Akun Anda
                        </h3>
                        <p class="text-lg text-gray-600 dark:text-gray-400 mb-6">
                            Daftar gratis dan atur profil Anda. Anda dapat mulai mengorganisir tugas dalam hitungan menit.
                        </p>
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                            <div class="flex items-center mb-4">
                                <div class="bg-blue-100 text-blue-600 p-3 rounded-full mr-4">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Registrasi Cepat</h4>
                                    <p class="text-gray-600 dark:text-gray-400">
                                        Proses pendaftaran sederhana menggunakan email atau akun sosial media.
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="bg-blue-100 text-blue-600 p-3 rounded-full mr-4">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Keamanan Terjamin</h4>
                                    <p class="text-gray-600 dark:text-gray-400">
                                        Data Anda selalu aman dan terlindungi dengan enkripsi tingkat tinggi.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-10 lg:mt-0">
                        <img class="rounded-xl shadow-xl" src="/images/register.png?height=400&width=600" alt="Buat akun screenshot">
                    </div>
                </div>

                <!-- Langkah 2 -->
                <div class="lg:grid lg:grid-cols-2 lg:gap-12 lg:items-center">
                    <div class="mt-10 lg:mt-0 lg:order-1">
                        <div class="relative">
                            <div class="bg-blue-600 text-white text-2xl font-bold h-12 w-12 rounded-full flex items-center justify-center mb-6">2</div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                                Buat dan Atur Tugas
                            </h3>
                            <p class="text-lg text-gray-600 dark:text-gray-400 mb-6">
                                Buat tugas, atur prioritas, dan organisasikan ke dalam proyek. Tetap fokus pada pekerjaan Anda dengan antarmuka yang intuitif.
                            </p>
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                <div class="flex items-center mb-4">
                                    <div class="bg-blue-100 text-blue-600 p-3 rounded-full mr-4">
                                        <i class="fas fa-tasks"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 dark:text-white">Manajemen Tugas</h4>
                                        <p class="text-gray-600 dark:text-gray-400">
                                            Buat, edit, dan atur tugas dengan mudah. Tetapkan prioritas dan tenggat waktu.
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div class="bg-blue-100 text-blue-600 p-3 rounded-full mr-4">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 dark:text-white">Pengingat Deadline</h4>
                                        <p class="text-gray-600 dark:text-gray-400">
                                            Dapatkan notifikasi untuk tenggat waktu yang akan datang agar tidak melewatkan apapun.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lg:order-0">
                        <img class="rounded-xl shadow-xl" src="/images/2.png?height=400&width=600" alt="Buat tugas screenshot">
                    </div>
                </div>

                <!-- Langkah 3 -->
                <div class="lg:grid lg:grid-cols-2 lg:gap-12 lg:items-center">
                    <div class="relative">
                        <div class="bg-blue-600 text-white text-2xl font-bold h-12 w-12 rounded-full flex items-center justify-center mb-6">3</div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                            Berkolaborasi dengan Tim
                        </h3>
                        <p class="text-lg text-gray-600 dark:text-gray-400 mb-6">
                            Undang anggota tim, tugaskan pekerjaan, dan bekerja sama secara efisien. Update real-time menjaga semua tetap sinkron.
                        </p>
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                            <div class="flex items-center mb-4">
                                <div class="bg-blue-100 text-blue-600 p-3 rounded-full mr-4">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Kolaborasi Tim</h4>
                                    <p class="text-gray-600 dark:text-gray-400">
                                        Bekerja sama dengan mudah bersama anggota tim. Bagikan tugas dan progress secara real-time.
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="bg-blue-100 text-blue-600 p-3 rounded-full mr-4">
                                    <i class="fas fa-comments"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Diskusi Terintegrasi</h4>
                                    <p class="text-gray-600 dark:text-gray-400">
                                        Diskusikan tugas langsung di platform tanpa perlu aplikasi chat terpisah.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-10 lg:mt-0">
                        <img class="rounded-xl shadow-xl" src="/images/collab.png?height=400&width=600" alt="Kolaborasi tim screenshot">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fitur Section -->
    <div class="py-20 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-3 py-1 text-sm font-semibold text-blue-800 bg-blue-100 rounded-full mb-4">FITUR UNGGULAN</span>
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white sm:text-4xl">
                    Semua yang Anda Butuhkan untuk Mengelola Tugas
                </h2>
                <p class="mt-4 max-w-2xl text-xl text-gray-600 dark:text-gray-400 mx-auto">
                    Fitur lengkap untuk membantu Anda mengelola tugas dengan efisien
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Fitur 1 -->
                <div class="feature-card bg-gray-50 dark:bg-gray-700 rounded-xl p-8 text-center">
                    <div class="icon-circle bg-blue-100 text-blue-600 mx-auto">
                        <i class="fas fa-list-check fa-2x"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Organisasi Tugas</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Atur tugas dengan prioritas, tenggat waktu, dan kategori. Jaga semuanya teratur dan tidak pernah melewatkan deadline.
                    </p>
                </div>

                <!-- Fitur 2 -->
                <div class="feature-card bg-gray-50 dark:bg-gray-700 rounded-xl p-8 text-center">
                    <div class="icon-circle bg-blue-100 text-blue-600 mx-auto">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Manajemen Tim</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Berkolaborasi dengan anggota tim, tugaskan pekerjaan, dan pantau progress bersama secara real-time.
                    </p>
                </div>

                <!-- Fitur 3 -->
                <div class="feature-card bg-gray-50 dark:bg-gray-700 rounded-xl p-8 text-center">
                    <div class="icon-circle bg-blue-100 text-blue-600 mx-auto">
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Pelacakan Progress</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Pantau progress tugas, lihat statistik, dan hasilkan laporan untuk tetap mengawasi proyek Anda.
                    </p>
                </div>

                <!-- Fitur 4 -->
                <div class="feature-card bg-gray-50 dark:bg-gray-700 rounded-xl p-8 text-center">
                    <div class="icon-circle bg-blue-100 text-blue-600 mx-auto">
                        <i class="fas fa-bell fa-2x"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Notifikasi & Pengingat</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Dapatkan pengingat untuk tenggat waktu dan pembaruan penting tentang tugas Anda.
                    </p>
                </div>

                <!-- Fitur 5 -->
                <div class="feature-card bg-gray-50 dark:bg-gray-700 rounded-xl p-8 text-center">
                    <div class="icon-circle bg-blue-100 text-blue-600 mx-auto">
                        <i class="fas fa-mobile-alt fa-2x"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Akses Mobile</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Akses tugas Anda dari mana saja dengan aplikasi mobile yang responsif dan mudah digunakan.
                    </p>
                </div>

                <!-- Fitur 6 -->
                <div class="feature-card bg-gray-50 dark:bg-gray-700 rounded-xl p-8 text-center">
                    <div class="icon-circle bg-blue-100 text-blue-600 mx-auto">
                        <i class="fas fa-lock fa-2x"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Keamanan Data</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Data Anda selalu aman dengan enkripsi end-to-end dan backup otomatis.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonial Section -->
    <div class="py-20 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-3 py-1 text-sm font-semibold text-blue-800 bg-blue-100 rounded-full mb-4">TESTIMONI</span>
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white sm:text-4xl">
                    Apa Kata Pengguna Kami
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimoni 1 -->
                <div class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg">
                    <div class="text-yellow-400 mb-4">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        "TIMVANZ telah mengubah cara tim kami bekerja. Sekarang kami dapat dengan mudah melacak tugas dan berkolaborasi secara efisien."
                    </p>
                    <div class="flex items-center">
                        <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                            <span class="text-blue-600 font-bold">BS</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white">Budi Santoso</h4>
                            <p class="text-gray-600 dark:text-gray-400">Project Manager</p>
                        </div>
                    </div>
                </div>

                <!-- Testimoni 2 -->
                <div class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg">
                    <div class="text-yellow-400 mb-4">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        "Antarmuka yang intuitif dan fitur yang lengkap. Saya sangat merekomendasikan TIMVANZ untuk siapa saja yang ingin meningkatkan produktivitas."
                    </p>
                    <div class="flex items-center">
                        <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                            <span class="text-blue-600 font-bold">DW</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white">Dewi Wulandari</h4>
                            <p class="text-gray-600 dark:text-gray-400">Freelancer</p>
                        </div>
                    </div>
                </div>

                <!-- Testimoni 3 -->
                <div class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg">
                    <div class="text-yellow-400 mb-4">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        "Sebagai pemilik bisnis kecil, TIMVANZ membantu saya mengatur tugas dan tim dengan mudah. Layak setiap rupiah yang dikeluarkan!"
                    </p>
                    <div class="flex items-center">
                        <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                            <span class="text-blue-600 font-bold">RA</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white">Rudi Ardianto</h4>
                            <p class="text-gray-600 dark:text-gray-400">Pengusaha</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="gradient-bg py-20">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-white sm:text-4xl mb-6">
                Siap untuk Meningkatkan Produktivitas Anda?
            </h2>
            <p class="text-xl text-blue-100 mb-10">
                Bergabunglah dengan ribuan pengguna yang telah mengoptimalkan alur kerja mereka dengan TIMVANZ.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 border border-transparent text-lg font-medium rounded-lg text-blue-700 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white shadow-lg">
                    <i class="fas fa-user-plus mr-2"></i> Daftar Gratis
                </a>
                <a href="#" class="inline-flex items-center justify-center px-8 py-4 border-2 border-white text-lg font-medium rounded-lg text-white bg-transparent hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white">
                    <i class="fas fa-headset mr-2"></i> Hubungi Kami
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Logo dan Deskripsi -->
                <div class="md:col-span-2">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-600 text-white p-2 rounded-lg mr-2">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <span class="text-xl font-bold">TIMVANZ</span>
                    </div>
                    <p class="text-gray-400 mb-4">
                        Platform manajemen tugas terbaik untuk meningkatkan produktivitas dan kolaborasi tim Anda.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>

                <!-- Link Cepat -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Link Cepat</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Beranda</a></li>
                        <li><a href="#cara-kerja" class="text-gray-400 hover:text-white">Cara Kerja</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Fitur</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Harga</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Kontak</a></li>
                    </ul>
                </div>

                <!-- Kontak -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Hubungi Kami</h3>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-2 text-gray-400"></i>
                            <span class="text-gray-400">Jl. Sudirman No. 123, Jakarta Pusat</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-envelope mt-1 mr-2 text-gray-400"></i>
                            <span class="text-gray-400">info@timvanz.com</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-phone-alt mt-1 mr-2 text-gray-400"></i>
                            <span class="text-gray-400">+62 21 1234 5678</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400">
                    &copy; {{ date('Y') }} TIMVANZ. Hak Cipta Dilindungi.
                </p>
                <div class="mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-white mr-4">Kebijakan Privasi</a>
                    <a href="#" class="text-gray-400 hover:text-white">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Inisialisasi Alpine.js
        document.addEventListener('alpine:init', () => {
            // Kode Alpine.js jika diperlukan
        });
    </script>
</body>
</html>
