<aside class="sidebar">
    <div class="sidebar-brand">
        <span class="sidebar-brand-text">STMIK MARDIRA</span>
    </div>

    <nav class="sidebar-nav">
        <a href="{{ url('index/index') }}" class="sidebar-link {{ currentController == 'index' ? 'active' : '' }}">
            <span class="sidebar-icon">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            </span>
            <span>Dashboard</span>
        </a>
       <a href="{{ url('mahasiswa/index') }}" class="sidebar-link {{ currentController == 'mahasiswa' ? 'active' : '' }}">
            <span class="sidebar-icon">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </span>
            <span>Mahasiswa</span>
        </a>
        <a href="{{ url('prodi/index') }}" class="sidebar-link {{ currentController == 'prodi' ? 'active' : '' }}">
            <span class="sidebar-icon">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 2 3 3 6 3s6-1 6-3v-5"/></svg>
            </span>
            <span>Program Studi</span>
        </a>
    </nav>
</aside>
