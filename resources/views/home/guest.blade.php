{{-- 1. 히어로 영역 --}}
<div class="min-h-screen bg-white flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-8">
        <span class="text-5xl font-black text-blue-600 tracking-tighter italic">FitLog</span>
    </div>

    <div class="text-center mb-12">
        <h1 class="text-5xl font-extrabold text-gray-900 tracking-tight mb-4">
            기록이 쌓이면 <span class="text-blue-600">습관</span>이 됩니다
        </h1>
        <p class="text-xl text-gray-500 mb-10 font-medium">
            오늘 운동하셨나요? 0.5초 만에 기록하고 변화를 확인하세요.
        </p>
        
        <div class="flex justify-center items-center gap-4">
            <a href="{{ route('register') }}" class="px-10 py-4 bg-blue-600 text-white text-lg font-bold rounded-2xl shadow-xl hover:bg-blue-700 transition-all">
                회원가입하고 시작하기
            </a>
            <a href="{{ route('login') }}" class="px-10 py-4 bg-gray-50 text-gray-600 text-lg font-bold rounded-2xl hover:bg-gray-100 transition-all border border-gray-100">
                로그인
            </a>
        </div>
    </div>

    {{-- 2. 데모용 가짜 달력 --}}
    <div class="max-w-4xl mx-auto w-full relative">
        <div class="absolute -inset-4 bg-blue-50 rounded-[3rem] blur-2xl opacity-50"></div>
        <div class="relative bg-white border border-gray-100 rounded-3xl shadow-2xl p-6 pointer-events-none select-none">
            <div id='demo-calendar'></div>
        </div>
    </div>
</div>

{{-- 3. 게스트 페이지 전용 스크립트 --}}
@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const demoEl = document.getElementById('demo-calendar');
        if (demoEl) {
            const demoCalendar = new FullCalendar.Calendar(demoEl, {
                initialView: 'dayGridMonth',
                locale: 'ko',
                height: 450,
                headerToolbar: false,
                events: [
                    { start: '2026-01-14', display: 'background', backgroundColor: '#dbeafe' },
                    { start: '2026-01-14', title: '🔥', allDay: true, classNames: ['bg-transparent', 'border-none', 'text-center'] }
                ]
            });
            demoCalendar.render();
        }
    });
</script>
@endpush