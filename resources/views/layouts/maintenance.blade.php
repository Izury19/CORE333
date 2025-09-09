<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>{{ $title ?? 'Cali' }}</title>
  <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png" />

  {{-- Tailwind and Flowbite --}}
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <script src="//unpkg.com/alpinejs" defer></script>
  <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />

  {{-- ✅ Bootstrap CSS (Placed after Tailwind to minimize conflicts) --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

  @stack('styles')
  <style>
  .modal-backdrop.show {
    z-index: 1040 !important;
  }
  .modal.show {
    z-index: 1050 !important;
  }
</style>

</head>
<body x-data="{ sidebarOpen: false }">

  {{-- Navbar --}}
  @include('partials.navbar')

  {{-- Sidebar --}}
  @include('partials.sidebar2')

  {{-- Page Content --}}
  <main class="max-w-screen-xl mx-auto p-4 mt-14 mb-20">
    {{-- Flash Message --}}
    {{-- @if (session('success'))
      <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-100" role="alert">
        {{ session('success') }}
      </div> 
    @endif --}}

    {{-- Main Blade Content --}}
    @yield('content')
  </main>

  {{-- Footer --}}
<footer class="bg-[#1f1f1f] text-white fixed bottom-0 w-full p-3 shadow-lg flex justify-center items-center">
  <div class="flex items-center justify-center space-x-3 w-full">
    <img class="rounded-full w-10 h-10" src="{{ asset('images/logo.png') }}" alt="Logo" />
    <p class="text-sm text-gray-300 text-center relative top-[10px]">© 2025 CaliCrane All rights reserved.</p>
  </div>
</footer>


  {{-- Scripts --}}
  <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

  {{-- ✅ Bootstrap JS (Placed after everything else) --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  {{-- Philippine Time Script --}}
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

  {{-- Sidebar Toggle Script --}}
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

  @stack('scripts')
</body>
</html>
