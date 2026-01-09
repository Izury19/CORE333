<style>
a.no-underline {
  text-decoration: none !important;
}

/* 📌 This class must be relative so dropdown can be placed correctly */
.ms-3 {
  position: relative;
}

/* 📦 Dropdown positioning */
#dropdown-user {
  width: 14rem;
  position: absolute !important;
  top: calc(100% + 10px); /* appear below with spacing */
  right: 0; /* align sa kanan ng profile icon */
  z-index: 999;
  background-color: white;
  border-radius: 0.5rem;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
  overflow: hidden;
  padding-top: 0.5rem;
}

/* 🖼️ Image inside dropdown */
#dropdown-user img {
  width: 64px;
  height: 64px;
  border-radius: 9999px;
  object-fit: cover;
}

/* 📄 Text spacing inside dropdown */
#dropdown-user .text-center {
  padding: 0.5rem 1rem;
}

#dropdown-user .text-center p {
  margin: 2px 0;
}

/* 🔘 Links inside dropdown */
#dropdown-user a,
#dropdown-user button {
  padding: 0.5rem 1rem;
  width: 100%;
  display: block;
  text-align: left;
  font-size: 14px;
  color: #374151;
  background: none;
  border: none;
  cursor: pointer;
}

#dropdown-user a:hover,
#dropdown-user button:hover {
  background-color: #f3f4f6;
}
</style>



<!-- nav bar -->
<nav class="fixed bg-[#1f1f1f] top-0 z-50 w-full shadow" style="margin-left: -20px">
    <div class="px-4 py-3 lg:px-6 lg:pl-7">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end">
                <a href="#" class="flex items-center ms-2 md:me-24 no-underline">
                    <img src="{{ asset('images/logo.png') }}" class="h-8 me-2" alt="Logo">
                    <span class="self-center text-xl font-extrabold sm:text-2xl whitespace-nowrap text-white">CaliCrane</span>
                </a>
            </div>

            <div class="flex items-center">
                <!-- Philippine Time Display - Now beside notification -->
                <div class="hidden md:flex items-center text-white mr-4">
                    <svg class="w-5 h-5 mr-2 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    <span id="philippineTime" class="font-bold text-white"></span>
                </div>

                <div class="flex items-center ms-3">
                    <div>
                        <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                            <span class="sr-only">Open user menu</span>
                            <img class="w-8 h-8 rounded-full object-cover"
                                src="{{ Auth::check() ? (Auth::user()?->photo ? asset('storage/' . Auth::user()?->photo) : asset('images/uploadprof.png')) : asset('images/uploadprof.png') }}"
                                alt="Profile Photo">
                        </button>
                    </div>

                    <div class="z-50 hidden mt-1 text-base list-none divide-y divide-gray-100 rounded-sm shadow-sm bg-white shadow" id="dropdown-user">
                        <!-- Profile Image in dropdown -->
                        <div class="flex justify-center items-center p-2">
                            <img class="w-20 h-20 rounded-full shadow-lg object-cover"
                                src="{{ Auth::check() ? (Auth::user()?->photo ? asset('storage/' . Auth::user()?->photo) : asset('images/uploadprof.png')) : asset('images/uploadprof.png') }}"
                                alt="Profile Photo">
                        </div>

                        <!-- User Info -->
                        <div class="px-4 py-3 text-center" role="none">
                            <p class="text-sm font-semibold text-gray-900">
                                {{ Auth::user()?->name }} {{ Auth::user()?->lastname }}
                            </p>
                            <p class="text-sm font-medium text-gray-500 truncate">
                                {{ Auth::user()?->email }}
                            </p>
                        </div>

                        <!-- Dropdown Links -->
                        <ul class="py-1" role="none">
                            <li>
                                <a href="{{ route('profile') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Profile
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('settings') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Settings
                                </a>
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">
                                        Sign out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
<!-- nav bar -->