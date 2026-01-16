<x-app-layout>
    @auth
        {{-- [로그인 상태] 실제 운동 기록 달력 --}}
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('운동 달력') }}
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
            .fc { font-family: 'Pretendard', sans-serif; height: 700px; }
            .fc-daygrid-day { cursor: pointer; }
            .fc-daygrid-day:hover { background-color: #f9fafb; }
        </style>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border border-gray-100">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>

        <div id="exercise-data-store" 
             data-exercises="{{ json_encode($exercisesByCategory ?? []) }}" 
             data-logs="{{ json_encode($logs ?? []) }}" 
             class="hidden">
        </div>

        @include('workouts.log-modal')

    @else
        {{-- [게스트 상태] 중앙 집중형 히어로 영역 --}}
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

            {{-- 데모용 가짜 달력 --}}
            <div class="max-w-4xl mx-auto w-full relative">
                <div class="absolute -inset-4 bg-blue-50 rounded-[3rem] blur-2xl opacity-50"></div>
                <div class="relative bg-white border border-gray-100 rounded-3xl shadow-2xl p-6 pointer-events-none select-none">
                    <div id='demo-calendar'></div>
                    
                </div>
            </div>
        </div>
    @endauth

    @push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. 실제 달력 로직 (로그인 시에만 작동)
            const calendarEl = document.getElementById('calendar');
            if (calendarEl) {
                const dataStore = document.getElementById('exercise-data-store');
                const savedLogs = JSON.parse(dataStore.dataset.logs || '[]');

                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: 'ko',
                    height: 700,
                    headerToolbar: { left: 'title', center: '', right: 'prev,next' },
                    events: savedLogs,
                    dateClick: function(info) {
                        openModal(info.dateStr);
                    }
                });
                calendar.render();
            }

            // 2. 데모용 달력 로직 (비로그인 시에만 작동)
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

        // 모달 제어 함수들 (기존과 동일)
        function openModal(date) {
            const modal = document.getElementById('workoutModal');
            if (!modal) return;
            document.getElementById('modalDateTitle').innerText = date + " 운동 기록";
            document.getElementById('selectedDate').value = date;
            document.getElementById('exercise-fields').innerHTML = '';
            modal.classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('workoutModal').classList.add('hidden');
        }

        function addCategoryExercises(category) {
    const dataStore = document.getElementById('exercise-data-store');
    const userExercises = JSON.parse(dataStore.dataset.exercises || '{}');
    const exercises = userExercises[category] || [];
    const fieldContainer = document.getElementById('exercise-fields');

    
    fieldContainer.innerHTML = ''; 

    if (exercises.length === 0) {
        alert(category + " 카테고리에 등록된 운동이 없습니다.");
        return;
    }

    exercises.forEach(ex => {
        const div = document.createElement('div');
        div.className = "p-3 bg-gray-50 rounded-xl border border-gray-100 mb-2 shadow-sm animate-fadeIn"; // 애니메이션 추가
        div.innerHTML = `
            <div class="flex items-center justify-between mb-2 text-sm font-bold text-gray-700">
                <span>${ex.name}</span>
                <input type="hidden" name="exercise_ids[]" value="${ex.id}">
            </div>
            <div class="flex gap-2">
                <input type="number" name="sets[]" placeholder="세트" class="w-full rounded-md border-gray-300 text-sm" required>
                <input type="number" name="reps[]" placeholder="회" class="w-full rounded-md border-gray-300 text-sm" required>
                <input type="number" name="weights[]" placeholder="kg" class="w-full rounded-md border-gray-300 text-sm">
            </div>
        `;
        fieldContainer.appendChild(div);
    });
}
    </script>
    @endpush
</x-app-layout>