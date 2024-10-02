<!-- resources/views/filament/bacsec/logo.blade.php -->
<img x-data="{ theme: localStorage.getItem('theme') || 'light' }"
     :src="theme === 'dark' ? '{{ asset('images/logo-dark.svg') }}' : '{{ asset('images/logo-light.svg') }}'"
     alt="Logo"
     class="h-10">