@extends('layouts.app')

@section('content')
<div class="container py-6 max-w-6xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Incoming Invitations -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Undangan Masuk</h2>
                        <p class="text-sm text-gray-500">Permintaan pertemanan yang belum direspon</p>
                    </div>
                    @if($pendingInvitations->isNotEmpty())
                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $pendingInvitations->count() }}
                    </span>
                    @endif
                </div>
                
                <div class="p-0">
                    @if($pendingInvitations->isEmpty())
                    <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                        <div class="bg-blue-50 rounded-full p-3 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-gray-600">Tidak ada undangan masuk saat ini.</p>
                    </div>
                    @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 text-xs font-semibold uppercase text-gray-500">
                                <tr>
                                    <th class="px-6 py-3 text-left">Nama Pengirim</th>
                                    <th class="px-6 py-3 text-left">Email</th>
                                    <th class="px-6 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($pendingInvitations as $invitation)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0 mr-3">
                                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <span class="font-medium text-blue-800">{{ substr($invitation->sender->name, 0, 1) }}</span>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $invitation->sender->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $invitation->sender->email }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end space-x-2">
                                            <form action="{{ route('friends.accept', $invitation->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Terima
                                                </button>
                                            </form>
                                            <form action="{{ route('friends.reject', $invitation->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Friend Statistics (Replacement for Friend Suggestions) -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mt-6">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800">Statistik Pertemanan</h2>
                    <p class="text-sm text-gray-500">Ringkasan aktivitas pertemanan Anda</p>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="border border-gray-100 rounded-lg p-4 text-center">
                        <div class="text-3xl font-bold text-blue-600">{{ $friends->count() }}</div>
                        <div class="text-sm text-gray-500">Total Teman</div>
                    </div>
                    <div class="border border-gray-100 rounded-lg p-4 text-center">
                        <div class="text-3xl font-bold text-green-600">{{ $pendingInvitations->count() }}</div>
                        <div class="text-sm text-gray-500">Undangan Masuk</div>
                    </div>
                    <div class="border border-gray-100 rounded-lg p-4 text-center">
                        <div class="text-3xl font-bold text-purple-600">{{ $sentInvitations ?? 0 }}</div>
                        <div class="text-sm text-gray-500">Undangan Terkirim</div>
                    </div>
                    <div class="border border-gray-100 rounded-lg p-4 text-center">
                        <div class="text-3xl font-bold text-orange-600">{{ $newFriendsThisMonth ?? 0 }}</div>
                        <div class="text-sm text-gray-500">Teman Baru Bulan Ini</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column: Friends List -->
        <div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Daftar Teman</h2>
                        <p class="text-sm text-gray-500">{{ $friends->count() }} teman</p>
                    </div>
                    <button class="inline-flex items-center justify-center p-2 rounded-full text-blue-600 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
                
                <div class="p-0">
                    @if($friends->isEmpty())
                    <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                        <div class="bg-blue-50 rounded-full p-3 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <p class="text-gray-600">Belum ada teman.</p>
                        <button class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Teman
                        </button>
                    </div>
                    @else
                    <div class="divide-y divide-gray-100">
                        @foreach($friends as $friend)
                        <div class="flex items-center justify-between p-4 hover:bg-gray-50">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                                    <span class="font-medium text-indigo-800">{{ substr($friend->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $friend->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $friend->email }}</div>
                                </div>
                            </div>
                            <div class="flex space-x-1">
                                <button class="p-1 rounded-full text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                </button>
                                <button class="p-1 rounded-full text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                
                @if($friends->isNotEmpty())
                <div class="p-4 bg-gray-50 border-t border-gray-100">
                    <button class="w-full py-2 px-4 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Lihat Semua Teman
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Send Invitation Section -->
    <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-bold text-gray-800">Kirim Undangan Pertemanan</h2>
            <p class="text-sm text-gray-500">Undang teman dengan email mereka</p>
        </div>
        
        <div class="p-6">
            <form class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="email@example.com">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Kirim Undangan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection