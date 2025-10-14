<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="w-full min-h-screen flex justify-center items-center bg-blue-950 p-4">

<div class="bg-white shadow-lg rounded-2xl px-8 pt-6 pb-8 w-full max-w-md">
    <h2 class="text-3xl font-bold mb-6 text-center text-blue-700">🔐 Verify Your OTP</h2>

    {{-- Alert Messages --}}
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 mb-4 rounded">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 mb-4 rounded">
            {{ session('error') }}
        </div>
    @endif

    {{-- OTP Form --}}
    <form method="POST" action="{{ route('otp.verify.submit') }}">
        @csrf
        <div class="mb-4">
            <label for="otp" class="block text-gray-700 text-sm font-bold mb-2">One-Time Password</label>
            <input type="text" name="otp" id="otp" required maxlength="6"
                placeholder="Enter 6-digit code"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight 
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:shadow-outline">
        </div>
        <div class="flex items-center justify-between">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 transition-colors text-white font-bold py-2 px-6 rounded-full 
                       focus:outline-none focus:ring-2 focus:ring-blue-300">
                Verify
            </button>
        </div>
    </form>

    {{-- Resend Button --}}
    <div class="text-center mt-6">
        <button id="resendBtn" onclick="resendOTP()" disabled
            class="bg-gray-400 text-white font-bold py-2 px-4 rounded-full cursor-not-allowed transition-colors">
            Resend OTP (<span id="timer">30</span>s)
        </button>
    </div>

    {{-- Toast Message --}}
    <div id="toast" 
        class="hidden fixed bottom-5 right-5 bg-green-600 text-white px-5 py-3 rounded-lg shadow-lg text-sm animate-bounce">
        ✅ OTP has been resent to your email!
    </div>

    {{-- Back to Login --}}
    <form method="POST" action="{{ route('logout') }}" class="text-center mt-6">
        @csrf
        <button type="submit"
            class="inline-block bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-full 
                   focus:outline-none focus:ring-2 focus:ring-gray-400 transition-colors">
            Back to Login
        </button>
    </form>
</div>

<script>
    // Timer for resend button
    let timer = 30;
    const timerElement = document.getElementById('timer');
    const resendBtn = document.getElementById('resendBtn');

    const countdown = setInterval(() => {
        timer--;
        timerElement.textContent = timer;
        if (timer <= 0) {
            clearInterval(countdown);
            enableResend();
        }
    }, 1000);

    function enableResend() {
        resendBtn.disabled = false;
        resendBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
        resendBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
        resendBtn.textContent = 'Resend OTP';
    }

    // Show toast notification
    function showToast() {
        const toast = document.getElementById('toast');
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 3000);
    }

    // Resend OTP function
    function resendOTP() {
        resendBtn.disabled = true;
        resendBtn.textContent = 'Sending...';
        resendBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
        resendBtn.classList.add('bg-gray-400', 'cursor-not-allowed');

        fetch("{{ route('otp.resend') }}")
            .then(response => response.text())
            .then(data => {
                showToast();
                // Reset timer
                timer = 30;
                timerElement.textContent = timer;
                const newCountdown = setInterval(() => {
                    timer--;
                    timerElement.textContent = timer;
                    if (timer <= 0) {
                        clearInterval(newCountdown);
                        enableResend();
                    }
                }, 1000);
            })
            .catch(error => {
                alert('❌ Failed to resend OTP. Please try again.');
                console.error(error);
                enableResend();
            });
    }
</script>

</body>
</html>
