<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <title>{{ $title ?? 'Cali Tests' }}</title>
  <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

  {{-- Tailwind and Flowbite --}}
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <script src="//unpkg.com/alpinejs" defer></script>
  <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />

  @stack('styles')
  
  {{-- Loading Spinner Styles --}}
  <style>
    /* Loading Spinner */
    #loadingSpinner {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }
    
    #loadingSpinner.active {
        opacity: 1;
        visibility: visible;
    }
    
    .spinner-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 16px;
    }
    
    .spinner-logo {
        width: 60px;
        height: 60px;
        animation: pulse 2s infinite;
    }
    
    .spinner-text {
        font-size: 16px;
        font-weight: 600;
        color: #1f1f1f;
        animation: fadeInOut 2s infinite;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.8; }
        100% { transform: scale(1); opacity: 1; }
    }
    
    @keyframes fadeInOut {
        0% { opacity: 0.5; }
        50% { opacity: 1; }
        100% { opacity: 0.5; }
    }
    
    /* Smooth scrolling for anchor links */
    html {
        scroll-behavior: smooth;
    }
  </style>
</head>
<body class="bg-gray-50">
  {{-- Loading Spinner --}}
  <div id="loadingSpinner">
    <div class="spinner-container">
      <img src="{{ asset('images/logo.png') }}" class="spinner-logo" alt="CaliCrane Logo">
      <div class="spinner-text">Loading...</div>
    </div>
  </div>

  {{-- Navbar --}}
  @include('partials.navbar')

  {{-- Sidebar --}}
  @include('partials.sidebar2')

  {{-- Page Content --}}
  <div class="p-4 sm:ml-72 min-h-screen pb-16">
    <div class="p-4 mt-14">
      {{-- Flash Message --}}
     {{-- @if (session('success'))
        <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-100" role="alert">
          {{ session('success') }}
        </div> 
      @endif --}}

      {{-- Main Blade Content --}}
      @yield('content')
    </div>
  </div>

  {{-- Footer --}}
  <footer class="bg-[#1f1f1f] text-white fixed bottom-0 w-full p-3 shadow-lg flex justify-center items-center">
    <div class="flex gap-5 items-center justify-center">
      <img class="rounded-full w-10 h-10" src="{{ asset('images/logo.png') }}" alt="">
      <p class="text-sm text-gray-300">© 2025 CaliCrane All rights reserved.</p>
    </div>
  </footer>

  {{-- Scripts --}}
  <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

  <script>
    function displayPhilippineTime() {
        const options = {
            timeZone: 'Asia/Manila',
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        };

        const philippineDateTime = new Date().toLocaleString('en-PH', options);
        const timeElement = document.getElementById('philippineTime');
        if (timeElement) {
            timeElement.textContent = philippineDateTime;
        }
    }

    displayPhilippineTime();
    setInterval(displayPhilippineTime, 1000);
  </script>

  <script>
    const sidebar = document.getElementById('logo-sidebar');
    const toggleBtn = document.getElementById('toggleSidebar');
    const arrowIcon = document.getElementById('arrowIcon');

    toggleBtn?.addEventListener('click', () => {
        const isHidden = sidebar.classList.contains('-translate-x-full');
        sidebar.classList.toggle('-translate-x-full');
        sidebar.classList.toggle('translate-x-0');
        arrowIcon?.classList.toggle('rotate-180', !isHidden);
    });
  </script>

  {{-- Loading Spinner Script --}}
  <script>
    // Show loading spinner on page load
    document.addEventListener('DOMContentLoaded', function() {
        const loadingSpinner = document.getElementById('loadingSpinner');
        loadingSpinner.classList.add('active');
        
        // Hide loading spinner when page is fully loaded
        window.addEventListener('load', function() {
            setTimeout(() => {
                loadingSpinner.classList.remove('active');
            }, 300);
        });
    });

    // Show loading spinner on internal link clicks (anchor links)
    document.addEventListener('click', function(e) {
        const target = e.target.closest('a[href^="#"]');
        if (target) {
            e.preventDefault();
            const sectionId = target.getAttribute('href');
            const section = document.querySelector(sectionId);
            
            if (section) {
                // Show loading spinner briefly
                const loadingSpinner = document.getElementById('loadingSpinner');
                loadingSpinner.classList.add('active');
                
                // Scroll to section after brief delay
                setTimeout(() => {
                    loadingSpinner.classList.remove('active');
                    section.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }, 100);
            }
        }
    });
  </script>

  @stack('scripts')

<script>
    let inactivityTimer;
    const INACTIVITY_LIMIT = 7200000; // 5 seconds

    function startInactivityTimer() {
        inactivityTimer = setTimeout(() => {
            alert('🔒 System auto-locked due to inactivity!');
            window.location.href = '{{ route("auto.logout") }}'; // ✅ CORRECT ROUTE NAME
        }, INACTIVITY_LIMIT);
    }

    function resetInactivityTimer() {
        clearTimeout(inactivityTimer);
        startInactivityTimer();
    }

    document.addEventListener('DOMContentLoaded', () => {
        startInactivityTimer();
        ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart'].forEach(event => {
            document.addEventListener(event, resetInactivityTimer, true);
        });
    });
</script>
</body>
</html>