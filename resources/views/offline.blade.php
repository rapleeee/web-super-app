<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Offline - Sistem Internal</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center p-6">
<div class="max-w-md w-full rounded-2xl bg-white shadow-sm border border-gray-200 p-8 text-center">
    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-16 h-16 mx-auto mb-4 object-contain">
    <h1 class="text-2xl font-bold text-gray-900">Anda sedang offline</h1>
    <p class="mt-2 text-sm text-gray-600">Koneksi internet terputus. Silakan cek jaringan lalu coba lagi.</p>
    <button onclick="window.location.reload()" class="mt-6 inline-flex items-center gap-2 rounded-lg bg-[#272125] px-4 py-2 text-white hover:bg-[#3a3136] transition">
        Coba Lagi
    </button>
</div>
</body>
</html>

