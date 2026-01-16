<x-app-layout>
    @auth
        {{-- 로그인한 사용자에게 보여줄 화면 --}}
        @include('home.auth')
    @else
        {{-- 로그인하지 않은 게스트에게 보여줄 화면 --}}
        @include('home.guest')
    @endauth
</x-app-layout>