<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Portal - E-RTLH Kota Kediri</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo/image.png') }}">

    <!-- Google Fonts & Tailwind CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        'brand-green': '#054735',
                        'brand-khaki': '#ebb866',
                        'brand-blue': '#002b84',
                        'brand-navy': '#1e3653',
                        'brand-gold': '#ffc000',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: "Plus Jakarta Sans", ui-sans-serif, system-ui, sans-serif;
            background-color: #1e3653;
            color: #1e3653;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .bg-portal-scrim {
            background-image: linear-gradient(
                155deg,
                rgba(30, 54, 83, 0.84) 0%,
                rgba(5, 71, 53, 0.70) 48%,
                rgba(0, 43, 132, 0.78) 100%
            );
        }

        .bg-portal-glow {
            background-image:
                radial-gradient(ellipse 75% 45% at 50% 12%, rgba(30, 54, 83, 0.45) 0%, transparent 70%),
                radial-gradient(ellipse 65% 40% at 50% 88%, rgba(235, 184, 102, 0.16) 0%, transparent 72%);
        }

        .bg-portal-accent-bar {
            background-image: linear-gradient(
                90deg,
                #054735 0%,
                #ebb866 38%,
                #ffc000 56%,
                #002b84 100%
            );
        }
    </style>
</head>
<body class="relative min-h-screen w-full overflow-x-hidden flex flex-col items-center justify-center px-4 sm:px-6 py-12 selection:bg-[#ebb866]/40 selection:text-[#1e3653]">

    <!-- Full-bleed background photograph with resilient fallback -->
    <div class="fixed inset-0 -z-20 bg-[#1e3653]">
        <img
            src="{{ asset('assets/images/bg/4.jpg') }}"
            alt="Pemandangan Gedung Balai Kota Kediri"
            class="h-full w-full object-cover object-center scale-[1.02]"
        />
    </div>

    <!-- Layered atmospheric color scrim (#1e3653, #054735, #002b84, warm #ebb866) -->
    <div aria-hidden="true" class="fixed inset-0 -z-10 bg-portal-scrim"></div>
    <div aria-hidden="true" class="fixed inset-0 -z-10 bg-portal-glow"></div>

    <!-- Main Centered Auth Stage -->
    <main class="relative z-10 w-full max-w-[496px] flex flex-col items-center">
        <!-- Top Heading & Subtitle -->
        <header class="text-center mb-14 sm:mb-16 px-2">
            <h1 class="text-[1.85rem] sm:text-[2.2rem] font-bold tracking-tight text-white [text-wrap:balance] drop-shadow-[0_2px_12px_rgba(0,0,0,0.32)]">
                Mari kita mulai sekarang!
            </h1>
            <p class="mt-2.5 text-[15px] sm:text-base text-white/90 font-normal leading-relaxed drop-shadow-sm">
                Sistem E-RTLH Kota Kediri 
            </p>
        </header>

        <!-- Unified Arched White Card with Protruding Top Circular Medallion -->
        <section aria-label="Formulir autentikasi" class="relative w-full drop-shadow-[0_26px_48px_rgba(12,24,42,0.44)]">
            <!-- Protruding Seamless White Circular Medallion with PortalCrest -->
            <div class="absolute -top-[46px] left-1/2 -translate-x-1/2 z-20 flex h-[92px] w-[92px] items-center justify-center rounded-full bg-white shadow-md border border-[#1e3653]/10 p-3">
                <img src="{{ asset('assets/images/logo/image.png') }}" alt="Logo Kota Kediri" class="h-full w-full object-contain">
            </div>

            <!-- Card Body -->
            <div class="relative z-10 w-full rounded-xl bg-white overflow-hidden">
                <div class="px-7 sm:px-12 pt-14 pb-9 sm:pb-11">

                    <!-- Alert Notifikasi Session / Status -->
                    @if (session('status'))
                        <div role="status" class="flex items-start gap-2.5 rounded-md border border-emerald-200 bg-emerald-50/90 px-3.5 py-2.5 text-[13.5px] text-emerald-800 mb-5">
                            <svg class="w-4 h-4 mt-0.5 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    <!-- Alert Error Validasi -->
                    @if (isset($errors) && $errors->any())
                        <div role="alert" class="flex items-start gap-2.5 rounded-md border border-red-200 bg-red-50/90 px-3.5 py-2.5 text-[13.5px] text-red-800 mb-5">
                            <svg class="w-4 h-4 mt-0.5 shrink-0 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <circle cx="12" cy="12" r="10" stroke-width="2" />
                                <line x1="12" y1="8" x2="12" y2="12" stroke-width="2" stroke-linecap="round" />
                                <line x1="12" y1="16" x2="12.01" y2="16" stroke-width="2" stroke-linecap="round" />
                            </svg>
                            <div class="space-y-1">
                                @foreach ($errors->all() as $error)
                                    <p class="leading-snug">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Form Login -->
                    <form method="POST" action="{{ route('login') }}" id="login-form" class="space-y-5" onsubmit="handleFormSubmit()">
                        @csrf

                        <!-- Field Email -->
                        <div>
                            <label for="email" class="block text-[13.5px] font-medium text-[#1e3653]/85 mb-1.5">
                                Alamat Email
                            </label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                autocomplete="username"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                placeholder="nama@bansos.test"
                                class="w-full h-12 px-4 rounded-md bg-white border border-[#1e3653]/20 text-[15px] text-[#1e3653] placeholder:text-[#1e3653]/35 transition-all duration-150 hover:border-[#1e3653]/35 focus:outline-none focus:border-[#002b84] focus:ring-2 focus:ring-[#002b84]/15 @error('email') border-red-500 @enderror"
                            />
                        </div>

                        <!-- Field Password -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="password" class="block text-[13.5px] font-medium text-[#1e3653]/85">
                                    Kata Sandi
                                </label>
                            </div>
                            <div class="relative">
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    autocomplete="current-password"
                                    required
                                    placeholder="••••••••"
                                    class="w-full h-12 pl-4 pr-12 rounded-md bg-white border border-[#1e3653]/20 text-[15px] text-[#1e3653] placeholder:text-[#1e3653]/35 transition-all duration-150 hover:border-[#1e3653]/35 focus:outline-none focus:border-[#002b84] focus:ring-2 focus:ring-[#002b84]/15 @error('password') border-red-500 @enderror"
                                />
                                <button
                                    type="button"
                                    id="toggle-password"
                                    onclick="togglePasswordVisibility()"
                                    aria-label="Tampilkan atau sembunyikan kata sandi"
                                    class="absolute right-1 top-1/2 -translate-y-1/2 h-10 w-10 flex items-center justify-center rounded text-[#1e3653]/45 hover:text-[#054735] transition-colors duration-150 cursor-pointer focus:outline-none"
                                >
                                    <!-- Eye Icon (Default) -->
                                    <svg id="eye-open" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <!-- Eye Off Icon (Hidden) -->
                                    <svg id="eye-closed" class="w-[18px] h-[18px] hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between pt-1">
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    id="remember"
                                    class="w-4 h-4 rounded border-[#1e3653]/25 text-[#002b84] focus:ring-[#002b84]/20 focus:ring-offset-0 cursor-pointer"
                                />
                                <span class="text-[13px] text-[#1e3653]/75 font-medium">Ingat saya di perangkat ini</span>
                            </label>
                        </div>

                        <!-- Centered Primary Submit Button -->
                        <div class="pt-3 flex justify-center">
                            <button
                                type="submit"
                                id="submit-btn"
                                class="min-w-[200px] w-full sm:w-auto px-10 h-12 rounded-md bg-[#002b84] hover:bg-[#054735] active:scale-[0.99] text-white text-[15px] font-semibold tracking-[0.01em] shadow-[0_12px_24px_-8px_rgba(0,43,132,0.55)] hover:shadow-[0_12px_24px_-8px_rgba(5,71,53,0.48)] transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#ffc000] focus-visible:ring-offset-2 cursor-pointer inline-flex items-center justify-center gap-2 whitespace-nowrap"
                            >
                                <span id="btn-text">Masuk</span>
                                <svg id="btn-spinner" class="w-4 h-4 animate-spin hidden text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Centered Secondary Text Action -->
                        <div class="pt-1 text-center">
                            <a
                                href="{{ route('welcome') }}#faq"
                                class="text-[13px] font-medium text-[#1e3653]/65 hover:text-[#002b84] transition-colors duration-150 inline-block py-1"
                            >
                                Butuh bantuan akses akun? Hubungi Admin Dinas
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Subtle bottom accent hairline harmonizing all palette tones -->
                <div aria-hidden="true" class="h-1.5 w-full bg-portal-accent-bar"></div>
            </div>
        </section>
    </main>

    <!-- Client-side script for password toggle and loading state -->
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }

        function handleFormSubmit() {
            const submitBtn = document.getElementById('submit-btn');
            const btnText = document.getElementById('btn-text');
            const btnSpinner = document.getElementById('btn-spinner');

            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-80', 'cursor-not-allowed');
            btnText.textContent = 'Memproses...';
            btnSpinner.classList.remove('hidden');
        }
    </script>
</body>
</html>
