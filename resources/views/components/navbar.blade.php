<style>
    /* SIDEBAR DESKTOP STYLES (screens 768px and up) */
    @media (min-width: 768px) {
        .app-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 72px;
            background-color: #ffffff;
            border-right: none;
            z-index: 1050;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 20px 12px;
            transition: width 0.3s cubic-bezier(0.2, 0, 0, 1);
            overflow: hidden;
            white-space: nowrap;
            box-shadow: none;
        }

        .app-sidebar:hover {
            width: 240px;
            box-shadow: none;
        }

        .nav-icon-box {
            min-width: 24px;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .app-sidebar .nav-label {
            opacity: 0;
            visibility: hidden;
            transform: translateX(-12px);
            transition: opacity 0.25s ease, transform 0.25s ease, visibility 0.25s ease;
            font-weight: 500;
            font-size: 0.95rem;
            margin-left: 16px;
            color: #262626;
        }

        .app-sidebar:hover .nav-label {
            opacity: 1;
            visibility: visible;
            transform: translateX(0);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            height: 48px;
            padding: 0 12px;
            color: #262626;
            text-decoration: none;
            margin-bottom: 16px;
        }

        .nav-item-link {
            display: flex;
            align-items: center;
            height: 48px;
            padding: 0 12px;
            border-radius: 8px;
            color: #262626;
            text-decoration: none;
            transition: background-color 0.2s ease, color 0.2s ease;
            margin-bottom: 4px;
            cursor: pointer;
        }

        .nav-item-link:hover {
            background-color: #f7f7f7;
            color: #000000;
        }

        /* Active state for the current route */
        .nav-item-link.is-active {
            color: #8e8e8e !important;
        }

        .nav-item-link.is-active .nav-label {
            color: #8e8e8e !important;
        }
    }

    /* BOTTOM NAV MOBILE STYLES (screens below 768px) */
    @media (max-width: 767px) {
        .app-sidebar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60px;
            background-color: #ffffff;
            border-top: none; /* Sin línea divisoria */
            z-index: 1050;
            display: flex;
            flex-direction: row;
            justify-content: space-around;
            align-items: center;
            padding: 0 8px;
            box-shadow: none;
        }

        .sidebar-brand,
        .nav-label {
            display: none !important;
        }

        .nav-item-link {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 44px;
            width: 44px;
            border-radius: 50%;
            color: #262626;
            text-decoration: none;
        }

        .nav-item-link.is-active {
            color: #8e8e8e !important;
        }

        .nav-item-link:active {
            background-color: #f0f0f0;
        }
    }
</style>

<aside class="app-sidebar">
    <!-- Logout Hidden Form -->
    <form action="{{ route('logout') }}" method="POST" id="logout-form" class="d-none">
        @csrf
    </form>

    <!-- Top Section: Logo and Main Links -->
    <div>
        <!-- Logo with Lightning inside the Square -->
        <a href="{{ route('feed') }}" class="sidebar-brand">
            <div class="nav-icon-box">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path>
                </svg>
            </div>
            <span class="nav-label fw-bold fs-5">Metarugos</span>
        </a>

        <!-- Navigation -->
        <nav class="d-flex flex-row flex-md-column justify-content-around w-100">
            <!-- 1. Home / Feed -->
            <a href="{{ route('feed') }}" class="nav-item-link {{ request()->routeIs('feed') ? 'is-active' : '' }}">
                <div class="nav-icon-box">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" 
                         fill="none" 
                         stroke="{{ request()->routeIs('feed') ? '#8e8e8e' : 'currentColor' }}" 
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                </div>
                <span class="nav-label">Home</span>
            </a>

            <!-- 2. Create / Upload -->
            <a href="{{ route('photos.create') }}" class="nav-item-link {{ request()->routeIs('photos.create') ? 'is-active' : '' }}">
                <div class="nav-icon-box">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" 
                         fill="none" 
                         stroke="{{ request()->routeIs('photos.create') ? '#8e8e8e' : 'currentColor' }}" 
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="12" y1="8" x2="12" y2="16"></line>
                        <line x1="8" y1="12" x2="16" y2="12"></line>
                    </svg>
                </div>
                <span class="nav-label">Create</span>
            </a>

            <!-- 3. Profile -->
            <a href="{{ route('profile.show', auth()->user()->username ?? '') }}" class="nav-item-link {{ request()->routeIs('profile.show') ? 'is-active' : '' }}">
                <div class="nav-icon-box">
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" 
                         style="width: 24px; height: 24px; font-size: 0.75rem; background-color: {{ request()->routeIs('profile.show') ? '#8e8e8e' : '#212529' }}; color: #ffffff;">
                        {{ strtoupper(substr(auth()->user()->username ?? 'U', 0, 1)) }}
                    </div>
                </div>
                <span class="nav-label">Profile</span>
            </a>

            <!-- 4. Log out -->
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-item-link d-md-none">
                <div class="nav-icon-box">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                </div>
            </a>
        </nav>
    </div>

    <!-- Bottom Section: Log out (Only Desktop) -->
    <div class="d-none d-md-block">
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-item-link">
            <div class="nav-icon-box">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
            </div>
            <span class="nav-label">Log out</span>
        </a>
    </div>
</aside>