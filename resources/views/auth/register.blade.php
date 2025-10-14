<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <title>Register</title>
    <style>
        body {
            overflow-x: hidden;
        }
        .eye-icon {
            position: absolute;
            right: 10px;
            top: 10px;
            cursor: pointer;
            color: #4b5563;
        }
        .eye-icon:hover {
            color: #111827;
        }
    </style>
</head>
<body>
    <section class="relative w-full min-h-screen overflow-hidden flex flex-col justify-center items-center p-4">
        <!-- ✅ Background -->
        <div class="absolute inset-0 bg-[url('/images/login.jpg')] bg-cover bg-center filter blur-sm opacity-200 z-0"></div>

        <!-- ✅ Foreground -->
        <div class="relative z-10 bg-slate-200 shadow-md w-full max-w-4xl min-h-96 rounded-lg flex flex-col md:flex-row overflow-hidden">
            
            <!-- Left -->
            <div class="w-full md:w-1/2 bg-white flex justify-center items-center p-4 rounded-t-lg md:rounded-l-lg md:rounded-tr-none">
                <img class="rounded-md w-full h-full shadow-md object-cover" src="{{ asset('images/login.jpg') }}" alt="Logo">
            </div>

            <!-- Right -->
            <div class="w-full md:w-1/2 p-6 flex flex-col justify-center">
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-xs text-center">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="text-center mb-4">
                    <h1 class="font-bold text-2xl text-blue-950">Sign Up to CaliCrane!</h1>
                    <p class="opacity-50 font-bold text-xs text-gray-900 mt-3">Sign up for a new account</p>
                </div>

                <form method="POST" action="{{ route('register.submit') }}" enctype="multipart/form-data" class="p-4 w-full">
                    @csrf

                    <!-- Profile -->
                    <div class="w-full justify-center items-center flex mb-6">
                        <div class="w-25 h-25 rounded-full relative">
                            <img id="profilePreview" class="w-full h-full object-cover rounded-full shadow-lg relative" src="{{ asset('images/uploadprof.png') }}" alt="Default Profile Preview">
                            <input class="hidden" id="inputFile" name="photo" type="file" accept="image/*">
                            <label for="inputFile" class="bg-blue-950 w-8 h-8 absolute bottom-[-8px] right-1 z-50 cursor-pointer rounded-full shadow-lg flex justify-center items-center">
                                <svg class="w-5 h-5" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M3 3H0V14H16V3H13L11 1H5L3 3ZM8 11C9.65685 11 11 9.65685 11 8C11 6.34315 9.65685 5 8 5C6.34315 5 5 6.34315 5 8C5 9.65685 6.34315 11 8 11Z"
                                        fill="#ffffff" />
                                </svg>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="relative z-0 w-full mb-5 group">
                            <input type="text" name="name" id="name"
                                class="block py-2.5 px-0 w-full text-xs text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 focus:outline-none focus:border-black peer"
                                placeholder=" " required />
                            <label for="name"
                                class="absolute text-xs text-blue-950 duration-300 transform -translate-y-6 scale-75 top-3 peer-focus:scale-75 peer-focus:-translate-y-6">
                                First Name
                            </label>
                        </div>

                        <div class="relative z-0 w-full mb-5 group">
                            <input type="text" name="lastname" id="lastname"
                                class="block py-2.5 px-0 w-full text-xs text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 focus:outline-none focus:border-black peer"
                                placeholder=" " required />
                            <label for="lastname"
                                class="absolute text-xs text-blue-950 duration-300 transform -translate-y-6 scale-75 top-3 peer-focus:scale-75 peer-focus:-translate-y-6">
                                Last Name
                            </label>
                        </div>

                        <div class="relative z-0 w-full mb-5 group">
                            <input type="email" name="email" id="email"
                                class="block py-2.5 px-0 w-full text-xs text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 focus:outline-none focus:border-black peer"
                                placeholder=" " required />
                            <label for="email"
                                class="absolute text-xs text-blue-950 duration-300 transform -translate-y-6 scale-75 top-3 peer-focus:scale-75 peer-focus:-translate-y-6">
                                Email Address
                            </label>
                        </div>

                        <div class="relative z-0 w-full mb-5 group">
                            <input type="tel" name="phone" id="phone"
                                class="block py-2.5 px-0 w-full text-xs text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 focus:outline-none focus:border-black peer"
                                placeholder=" " required />
                            <label for="phone"
                                class="absolute text-xs text-blue-950 duration-300 transform -translate-y-6 scale-75 top-3 peer-focus:scale-75 peer-focus:-translate-y-6">
                                Phone Number
                            </label>
                        </div>

                        <!-- ✅ Password with Strength Meter + Toggle -->

                        <div class="relative z-0 w-full mb-5 group">
                            <input type="password" name="password" id="password"
                                class="block py-2.5 px-0 w-full pr-8 text-xs text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 focus:outline-none focus:border-black peer"
                                placeholder=" " required />
                            <label for="password"
                                class="absolute text-xs text-blue-950 duration-300 transform -translate-y-6 scale-75 top-3 peer-focus:scale-75 peer-focus:-translate-y-6">
                                Password
                            </label>

                            <div id="passwordStrength" class="mt-2 h-2 w-full bg-gray-200 rounded overflow-hidden">
                                <div id="strengthBar" class="h-2 w-0 bg-red-500 transition-all duration-300"></div>
                            </div>
                            <p id="strengthText" class="text-[10px] mt-1 font-semibold"></p>
                        </div>

                        <!-- ✅ Confirm Password -->
                        <div class="relative z-0 w-full mb-5 group">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="block py-2.5 px-0 w-full pr-8 text-xs text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 focus:outline-none focus:border-black peer"
                                placeholder=" " required />
                            <label for="password_confirmation"
                                class="absolute text-xs text-blue-950 duration-300 transform -translate-y-6 scale-75 top-3 peer-focus:scale-75 peer-focus:-translate-y-6">
                                Confirm Password
                            </label>
                        </div>

                        <div class="relative z-0 w-full mb-5 group">
                            <input type="text" name="position" id="position"
                                class="block py-2.5 px-0 w-full text-xs text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 focus:outline-none focus:border-black peer"
                                placeholder=" " required />
                            <label for="position"
                                class="absolute text-xs text-blue-950 duration-300 transform -translate-y-6 scale-75 top-3 peer-focus:scale-75 peer-focus:-translate-y-6">
                                Position
                            </label>
                        </div>

                        <div class="relative z-0 w-full mb-5 group">
                            <select name="account_type" id="account_type" class="block py-2.5 px-0 w-full text-xs text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 focus:outline-none focus:border-black peer" required>
                                <option value="" disabled selected></option>
                                <option value="1">Admin</option>
                                <option value="2">User</option>
                            </select>
                            <label for="account_type" class="absolute text-sm text-blue-950 duration-300 transform -translate-y-6 scale-75 top-3 peer-focus:scale-75 peer-focus:-translate-y-6">
                                Account Type
                            </label>
                        </div>
                    </div>

                    <!-- Terms -->
                    <div class="flex items-center mb-4 text-xs text-gray-700">
                        <input id="terms" type="checkbox" required class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                        <label for="terms" class="ml-2">
                            I agree to the 
                            <button type="button" data-modal-target="termsModal" data-modal-toggle="termsModal" class="text-blue-600 hover:underline">
                                Terms and Conditions
                            </button>
                        </label>
                    </div>

                    <button type="submit" class="w-full text-white bg-gray-900 hover:bg-gray-950 focus:ring-4 focus:ring-orange-300 font-medium rounded-lg text-sm px-5 py-2.5">
                        Sign - Up
                    </button>

                    <div class="text-center text-[10px] text-gray-900 font-bold mt-6">
                        <span class="opacity-50">Already have an account? </span>
                        <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login Here</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- ✅ Terms Modal -->
    <div id="termsModal" tabindex="-1" aria-hidden="true" class="hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full inset-0 h-[calc(100%-1rem)] max-h-full bg-black/50">
        <div class="relative p-4 w-full max-w-sm sm:max-w-md max-h-full">
            <div class="relative bg-slate-200 rounded-lg shadow">
                <div class="flex items-start justify-between p-4 border-b rounded-t">
                    <h3 class="text-lg font-semibold text-gray-900">Terms and Conditions</h3>
                    <button type="button" class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="termsModal">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                <div class="p-4 space-y-4 text-sm text-gray-700">
                    <p>By registering, you agree to CaliCrane’s Terms and Conditions. Your information will be securely stored and will only be used for internal purposes.</p>
                    <p>Please read everything carefully before proceeding with account creation.</p>
                </div>
                <div class="flex justify-end items-center p-4 border-t border-gray-200 rounded-b">
                    <button data-modal-hide="termsModal" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputFile = document.getElementById('inputFile');
    const profilePreview = document.getElementById('profilePreview');
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');
    const form = document.querySelector('form');
    const alertModal = document.getElementById('alertModal');
    const alertMessage = document.getElementById('alertMessage');
    const alertModalObj = new Modal(alertModal);

    // ✅ Show modal function
    function showModal(message, title = 'Validation Error') {
        document.getElementById('alertTitle').textContent = title;
        alertMessage.textContent = message;
        alertModalObj.show();
    }

    // ✅ Profile Preview
    inputFile.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            if (!file.type.match('image.*')) {
                showModal('Please select an image file only.');
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                showModal('Image size should be less than 5MB.');
                return;
            }
            const reader = new FileReader();
            reader.onload = function (e) {
                profilePreview.src = e.target.result;
                profilePreview.style.transition = 'transform 0.3s ease';
                profilePreview.style.transform = 'scale(1.05)';
                setTimeout(() => profilePreview.style.transform = 'scale(1)', 300);
            };
            reader.readAsDataURL(file);
        }
    });

    // ✅ Password Strength Meter
    passwordInput.addEventListener('input', function () {
        const value = passwordInput.value;
        let strength = 0;

        if (value.length >= 8) strength++;
        if (/[0-9]/.test(value)) strength++;
        if (/[!@#$%^&*()_\-+=<>?]/.test(value)) strength++;
        if (/[A-Z]/.test(value)) strength++;

        switch (strength) {
            case 0:
            case 1:
                strengthBar.style.width = '25%';
                strengthBar.style.backgroundColor = '#ef4444';
                strengthText.textContent = 'Weak 🔴';
                strengthText.style.color = '#ef4444';
                break;
            case 2:
                strengthBar.style.width = '50%';
                strengthBar.style.backgroundColor = '#f59e0b';
                strengthText.textContent = 'Fair 🟠';
                strengthText.style.color = '#f59e0b';
                break;
            case 3:
                strengthBar.style.width = '75%';
                strengthBar.style.backgroundColor = '#3b82f6';
                strengthText.textContent = 'Good 🔵';
                strengthText.style.color = '#3b82f6';
                break;
            case 4:
                strengthBar.style.width = '100%';
                strengthBar.style.backgroundColor = '#10b981';
                strengthText.textContent = 'Strong 🟢';
                strengthText.style.color = '#10b981';
                break;
        }
    });

    // ✅ Validation before submit
    form.addEventListener('submit', function (e) {
        const password = passwordInput.value;
        const confirmPassword = confirmInput.value;
        const passwordRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*()_\-+=<>?])[A-Za-z\d!@#$%^&*()_\-+=<>?]{8,}$/;

        if (!passwordRegex.test(password)) {
            e.preventDefault();
            showModal('Password must be at least 8 characters long and contain uppercase, lowercase, number, and special character.');
        } else if (password !== confirmPassword) {
            e.preventDefault();
            showModal('Passwords do not match.');
        }
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

</body>
<!-- ✅ Validation Alert Modal -->
<div id="alertModal" tabindex="-1" aria-hidden="true"
    class="hidden fixed inset-0 z-50 flex justify-center items-center bg-black/40">
    <div class="bg-white rounded-lg shadow-lg w-80 text-center p-6">
        <h3 id="alertTitle" class="text-lg font-semibold text-red-600 mb-2">Validation Error</h3>
        <p id="alertMessage" class="text-sm text-gray-700 mb-4"></p>
        <button data-modal-hide="alertModal"
            class="text-white bg-red-600 hover:bg-red-700 rounded-lg px-4 py-2 text-sm font-medium">
            OK
        </button>
    </div>
</div>

</html>
