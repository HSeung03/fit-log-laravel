<x-app-layout>
    @auth
        {{-- [로그인 상태] 실제 운동 기록 달력 --}}
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('FitLog - 운동 달력') }}
            </h2>
        </x-slot>

        <style>
            .fc .fc-day-today { background-color: transparent !important; }
            .fc .fc-day-today .fc-daygrid-day-number {
                color: #1d4ed8 !important;
                font-weight: 800 !important;
                background-color: #eff6ff;
                border-radius: 50%;
                padding: 2px 6px;
            }
            .fc-bg-event { background-color: #dbeafe !important; opacity: 0.85 !important; border: none !important; }
            .fc { font-family: 'Pretendard', sans-serif; }
            .fc-daygrid-day { cursor: pointer; }
            .fc-daygrid-day:hover { background-color: #f9fafb; }
        </style>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>

        <div id="exercise-data-store" 
             data-exercises="{{ json_encode($exercisesByCategory ?? []) }}" 
             data-logs="{{ json_encode($logs ?? []) }}" 
             class="hidden">
        </div>

        @include('workouts.partials.log-modal')

    @else
    {{-- [게스트 상태] 헤더와 내비게이션 없이 중앙 집중형 히어로 영역 --}}
    <div class="min-h-screen bg-white flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
        
        {{-- 로고 강조 (중앙 상단) --}}
        <div class="text-center mb-8">
            <span class="text-4xl font-black text-blue-600 tracking-tighter">FitLog</span>
        </div>

        <div class="text-center mb-12">
            <h1 class="text-5xl font-extrabold text-gray-900 tracking-tight mb-4">
                기록이 쌓이면 <span class="text-blue-600">습관</span>이 됩니다
            </h1>
            <p class="text-xl text-gray-600 mb-10">
                오늘 운동하셨나요? 0.5초 만에 기록하고 변화를 확인하세요.
            </p>
            
            {{-- 중앙 집중 버튼: 회원가입(시작하기) & 로그인 --}}
            <div class="flex justify-center items-center gap-4">
                <a href="{{ route('register') }}" class="px-10 py-4 bg-blue-600 text-white text-lg font-bold rounded-2xl shadow-lg hover:bg-blue-700 hover:-translate-y-1 transition-all">
                    회원가입하고 시작하기
                </a>
                <a href="{{ route('login') }}" class="px-10 py-4 bg-gray-100 text-gray-700 text-lg font-bold rounded-2xl hover:bg-gray-200 transition-all">
                    로그인
                </a>
            </div>
        </div>

        {{-- 데모용 가짜 달력 영역 --}}
        <div class="max-w-4xl mx-auto w-full relative">
            <div class="absolute -inset-1 bg-gradient-to-r from-blue-100 to-indigo-100 rounded-3xl blur opacity-25"></div>
            <div class="relative bg-white border border-gray-100 rounded-2xl shadow-2xl p-4 pointer-events-none select-none">
                <div id='demo-calendar'></div>
                <div class="absolute top-4 right-6 bg-blue-50 text-blue-600 text-[10px] font-bold px-2 py-1 rounded-md tracking-wider">PREVIEW ONLY</div>
            </div>
        </div>
    </div>
@endauth

    @push('scripts')
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // 1. 실제 달력 로직 (Auth 전용)
                const calendarEl = document.getElementById('calendar');
                if (calendarEl) {
                    const dataStore = document.getElementById('exercise-data-store');
                    const userExercises = JSON.parse(dataStore.dataset.exercises || '{}');
                    const savedLogs = JSON.parse(dataStore.dataset.logs || '[]');

                    const calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        locale: 'ko',
                        height: 650,
                        headerToolbar: { left: 'title', center: '', right: 'prev,next' },
                        displayEventTime: false,
                        events: savedLogs,
                        dateClick: function(info) {
                            const existingLog = savedLogs.find(l => l.start === info.dateStr);
                            if (existingLog) showDetailModal(existingLog, info.dateStr);
                            else openModal(info.dateStr);
                        }
                    });
                    calendar.render();
                }

                // 2. 데모 달력 로직 (Guest 전용)
                const demoEl = document.getElementById('demo-calendar');
                if (demoEl) {
                    const today = new Date();
                    const getIso = (offset) => {
                        const d = new Date();
                        d.setDate(today.getDate() - offset);
                        return d.toISOString().split('T')[0];
                    };

                    const demoCalendar = new FullCalendar.Calendar(demoEl, {
                        initialView: 'dayGridMonth',
                        locale: 'ko',
                        height: 500,
                        headerToolbar: false,
                        events: [
                            { start: getIso(1), display: 'background', backgroundColor: '#dbeafe' },
                            { start: getIso(2), display: 'background', backgroundColor: '#dbeafe' },
                            { start: getIso(4), display: 'background', backgroundColor: '#dbeafe' },
                            { start: getIso(1), title: '🔥', allDay: true, classNames: ['bg-transparent', 'border-none', 'text-center'] }
                        ],
                        dayCellDidMount: function(info) {
                            if (info.isToday) {
                                const num = info.el.querySelector('.fc-daygrid-day-number');
                                if (num) { num.style.color = '#2563eb'; num.style.fontWeight = '800'; }
                            }
                        }
                    });
                    demoCalendar.render();
                }
            });

            // 기존 함수들 (showDetailModal, openModal, closeModal, addCategoryExercises)
            // ... (기존과 동일하므로 유지)
        </script>
    @endpush
</x-app-layout>