<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Maintenance - Sistem Internal</title>
    @vite(['resources/css/app.css'])
</head>
<body class="relative min-h-screen overflow-hidden bg-slate-950 text-slate-100">
    <div aria-hidden="true" class="pointer-events-none absolute inset-0">
        <div class="absolute -left-20 top-[-5rem] h-72 w-72 rounded-full bg-cyan-500/20 blur-3xl"></div>
        <div class="absolute -right-20 bottom-[-6rem] h-80 w-80 rounded-full bg-orange-500/20 blur-3xl"></div>
        <div class="absolute left-1/2 top-1/2 h-96 w-96 -translate-x-1/2 -translate-y-1/2 rounded-full bg-indigo-500/10 blur-3xl"></div>
    </div>

    <main class="relative mx-auto flex min-h-screen w-full max-w-3xl items-center px-6 py-10">
        <section class="w-full rounded-3xl border border-white/15 bg-white/10 p-7 shadow-2xl backdrop-blur-md md:p-10">
            <span class="inline-flex rounded-full border border-orange-300/40 bg-orange-500/20 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-orange-100">
                Under Maintenance
            </span>

            <h1 class="mt-4 text-3xl font-bold leading-tight text-white md:text-4xl">
                Sistem Sedang Dalam Pemeliharaan
            </h1>

            <p class="mt-3 text-sm leading-relaxed text-slate-200 md:text-base">
                Tim kami sedang melakukan pembaruan agar layanan lebih stabil dan cepat.
                Terima kasih sudah menunggu, silakan coba lagi beberapa saat.
            </p>

            <article class="mt-8 rounded-2xl border border-white/10 bg-slate-900/60 p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-cyan-200">Quote Hari Ini</p>
                <blockquote class="mt-3 text-lg font-medium leading-relaxed text-slate-100 md:text-xl">
                    "{{ $quote['text'] ?? 'Sistem yang baik dibangun dari perbaikan kecil yang dilakukan berulang.' }}"
                </blockquote>
                <p class="mt-3 text-sm text-slate-300">- {{ $quote['author'] ?? 'Unknown' }}</p>
            </article>

            <div class="mt-7 flex flex-wrap items-center gap-3">
                <button type="button"
                        onclick="window.location.reload()"
                        class="inline-flex items-center rounded-xl bg-cyan-500 px-4 py-2.5 text-sm font-semibold text-slate-950 transition hover:bg-cyan-400">
                    Coba Lagi
                </button>
                <a href="{{ route('login') }}"
                   class="inline-flex items-center rounded-xl border border-white/20 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/10">
                    Login
                </a>
            </div>
        </section>
    </main>
</body>
</html>
