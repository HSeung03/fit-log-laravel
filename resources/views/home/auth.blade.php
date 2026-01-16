{{-- 1. 상단 헤더 (제목) --}}
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('운동 달력') }}
    </h2>
</x-slot>

{{-- 2. 달력 전용 스타일 --}}
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

{{-- 3. 메인 콘텐츠 (달력 본체) --}}
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border border-gray-100">
            <div id='calendar'></div>
        </div>
    </div>
</div>

{{-- 4. 숨겨진 데이터 저장소 (JS에서 사용) --}}
<div id="exercise-data-store" 
     data-exercises="{{ json_encode($exercisesByCategory ?? []) }}" 
     data-logs="{{ json_encode($logs ?? []) }}" 
     class="hidden">
</div>

{{-- 5. 운동 기록 입력 모달 (외부 파일 include) --}}
@include('workouts.log-modal')

{{-- 6. 로그인 사용자 전용 스크립트 --}}
@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
    });

    // 모달 제어 함수
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

    // 카테고리별 운동 필드 생성 함수
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
            div.className = "p-3 bg-gray-50 rounded-xl border border-gray-100 mb-2 shadow-sm animate-fadeIn";
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