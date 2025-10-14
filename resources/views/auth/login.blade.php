<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <title>Login</title>
</head>
<body class="overflow-x-hidden bg-gray-50">

<section class="relative w-full min-h-screen flex justify-center items-center p-4">
    <!-- Background -->
    <div class="absolute inset-0 bg-[url('/images/login.jpg')] bg-cover bg-center filter blur-sm opacity-200 z-0"></div>

    <!-- Main Container -->
    <div class="relative z-10 bg-slate-200 shadow-md w-full max-w-5xl min-h-96 rounded-lg flex flex-col md:flex-row overflow-hidden">
        
        <!-- Left Image Side -->
        <div class="w-full md:w-1/2 bg-white flex justify-center items-center p-4 md:rounded-l-lg">
            <img class="rounded-md w-full h-60 md:h-full object-cover shadow-md" src="{{ asset('images/login.jpg') }}" alt="Logo">
        </div>

        <!-- Right Form Side -->
        <div class="w-full md:w-1/2 p-6 flex flex-col justify-center">

            {{-- Google or login error --}}
            @if ($errors->has('msg'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-xs text-center">
                    {{ $errors->first('msg') }}
                </div>
            @endif

            {{-- Account banned --}}
            @if(session('acc_banned'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-xs text-center">
                    Your account has been banned.
                </div>
            @endif

            {{-- Success message --}}
            @if(session('success'))
                <div id="successMessage" class="mb-4 p-3 bg-green-100 text-green-800 text-sm rounded-md text-center">
                    {{ session('success') }}
                </div>

                <script>
                    setTimeout(() => {
                        const msg = document.getElementById('successMessage');
                        if (msg) msg.style.display = 'none';
                    }, 4000);
                </script>
            @endif

            <!-- Header -->
            <div class="text-center mb-4">
                <h1 class="font-bold text-xl md:text-2xl text-blue-950">Welcome Back to CaliCrane!</h1>
                <p class="opacity-50 font-bold text-xs text-gray-900 mt-2">Sign in your account</p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login.submit') }}" class="space-y-3 mt-3 w-full">
                @csrf

                <!-- Email -->
                <div class="relative z-0 w-full group">
                    <input type="email" name="email" id="email"
                        class="block py-2 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-black peer"
                        placeholder=" " value="{{ old('email') }}" required />
                    <label for="email"
                        class="absolute text-xs text-blue-950 duration-300 transform scale-75 -translate-y-6 top-3 origin-[0] peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:scale-75 peer-focus:-translate-y-6">
                        Email address
                    </label>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="relative z-0 w-full group">
                    <input type="password" name="password" id="password"
                        class="block py-2 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-black peer pr-8"
                        placeholder=" " required />
                    <label for="password"
                        class="absolute text-xs text-blue-950 duration-300 transform scale-75 -translate-y-6 top-3 origin-[0] peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:scale-75 peer-focus:-translate-y-6">
                        Password
                    </label>

                    <!-- Eye Toggle -->
                    <button type="button" onclick="togglePassword()" class="absolute right-0 bottom-2 text-gray-500 hover:text-black">
                        <!-- Eye (visible) -->
                        <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>

                        <!-- Eye Slash (hidden) -->
                        <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 hidden" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.969 9.969 0 012.233-3.568m3.13-2.564A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.973 9.973 0 01-4.043 5.04M3 3l18 18" />
                        </svg>
                    </button>

                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember + Forgot -->
                <div class="flex items-center justify-between text-xs mt-1">
                    <label class="flex items-center gap-1 text-gray-900">
                        <input type="checkbox" name="remember" class="h-3 w-3 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <span>Remember me</span>
                    </label>
                    <button type="button" data-modal-target="forgotPasswordModal" data-modal-toggle="forgotPasswordModal" class="opacity-60 text-gray-900 font-semibold hover:underline">
                        Forgot Password?
                    </button>
                </div>

                <button type="submit"
                    class="w-full text-white bg-gray-900 hover:bg-gray-950 focus:ring-4 focus:ring-orange-300 font-medium rounded-lg text-sm px-5 py-2.5 mt-3">
                    Sign In
                </button>

                <div class="flex items-center my-4">
                    <div class="flex-grow border-t border-gray-300"></div>
                    <span class="mx-3 text-xs font-bold text-gray-400">OR</span>
                    <div class="flex-grow border-t border-gray-300"></div>
                </div>

                <!-- Social Logins -->
                <div class="flex flex-col sm:flex-row justify-center gap-3">
                    <a href="{{ route('google.login') }}"
                        class="flex items-center justify-center gap-2 border border-gray-300 px-4 py-2 rounded-md hover:bg-gray-100 text-xs w-full">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5" alt="Google">
                        Google
                    </a>
                    <button type="button"
                        class="flex items-center justify-center gap-2 border border-gray-300 px-4 py-2 rounded-md hover:bg-gray-100 text-xs w-full">
                        <img src="https://www.svgrepo.com/show/475647/facebook-color.svg" class="w-5 h-5" alt="Facebook">
                        Facebook
                    </button>
                </div>

                <!-- Register -->
                <div class="text-center text-xs text-gray-900 font-bold mt-4">
                    <span class="opacity-50">Don't have an account?</span>
                    <a href="{{ route('register') }}" class="text-blue-600 hover:underline ml-1">Register</a>
                </div>

            </form>
        </div>
    </div>
</section>

<!-- Forgot Password Modal -->
<div id="forgotPasswordModal" tabindex="-1" aria-hidden="true" class="hidden fixed top-0 left-0 right-0 z-50 flex justify-center items-center w-full h-full bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-md p-6 w-full max-w-sm">
        <h3 class="text-lg font-bold mb-2 text-gray-900">Reset Password</h3>
        <p class="text-xs text-gray-600 mb-4">Enter your email to receive a password reset link.</p>
        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <input type="email" name="reset_email" class="w-full text-sm border border-gray-300 rounded-md p-2 mb-3 focus:ring-2 focus:ring-blue-500" placeholder="Enter your email" required>
            <div class="flex justify-end gap-2">
                <button type="button" data-modal-hide="forgotPasswordModal" class="text-xs px-3 py-1 rounded-md bg-gray-200 hover:bg-gray-300">Cancel</button>
                <button type="submit" class="text-xs px-3 py-1 rounded-md bg-blue-600 hover:bg-blue-700 text-white">Send Link</button>
            </div>
        </form>
    </div>
</div>

<!-- JS -->
<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const eyeOpen = document.getElementById('eyeOpen');
        const eyeClosed = document.getElementById('eyeClosed');

        if (input.type === "password") {
            input.type = "text";
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            input.type = "password";
            eyeClosed.classList.add('hidden');
            eyeOpen.classList.remove('hidden');
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>
</html>
