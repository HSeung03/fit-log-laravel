<x-app-layout>
    @auth
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('운동 기록') }}
            </h2>
        </x-slot>

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

        @push('scripts')
            <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
            <script>
                // 2. JS 변수 선언 (순수 JS 문법만 사용)
                let userExercises = {};
                let savedLogs = [];

                document.addEventListener('DOMContentLoaded', function() {
                    const dataStore = document.getElementById('exercise-data-store');
                    
                    try {
                        // HTML 데이터 속성에서 데이터 읽어오기
                        userExercises = JSON.parse(dataStore.dataset.exercises || '{}');
                        savedLogs = JSON.parse(dataStore.dataset.logs || '[]');
                    } catch (e) {
                        console.error("데이터 로드 실패:", e);
                    }

                    const calendarEl = document.getElementById('calendar');
                    const calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        locale: 'ko',
                        displayEventTime: false,
                        height: 650,
                        headerToolbar: { left: 'title', center: '', right: 'prev,next' },
                        events: savedLogs, // 달력에 저장된 기록 표시 (파란색 바)
                        dateClick: function(info) {
                            // 클릭한 날짜에 기록이 있는지 확인
                            const existingLog = savedLogs.find(l => l.start === info.dateStr);
                            if (existingLog) {
                                showDetailModal(existingLog, info.dateStr);
                            } else {
                                openModal(info.dateStr);
                            }
                        },
                    });
                    calendar.render();
                });

                // 상세보기 모달 표시 함수
                function showDetailModal(log, dateStr) {
                    const modal = document.getElementById('workoutModal');
                    modal.classList.remove('hidden');
                    document.getElementById('modalDateTitle').innerText = dateStr + " 운동 요약";
                    
                    // 입력 폼 숨기기 및 상세 데이터 표시
                    document.getElementById('weight-input-section').classList.add('hidden');
                    document.getElementById('category-select-section').classList.add('hidden');
                    document.getElementById('diet-input-section').classList.add('hidden');
                    document.getElementById('submit-button').classList.add('hidden');

                    const resultsHtml = log.extendedProps.results.map(ex => `
                        <div class="p-2 bg-gray-50 border rounded text-sm mb-1 flex justify-between">
                            <span><strong>${ex.name}</strong></span>
                            <span>${ex.weight}kg x ${ex.reps}회</span>
                        </div>
                    `).join('');

                    document.getElementById('exercise-fields').innerHTML = `
                        <div class="bg-blue-50 p-3 rounded mb-4 text-sm">
                            <p><strong>⚖️ 체중:</strong> ${log.extendedProps.weight}kg</p>
                            <p><strong>📝 메모:</strong> ${log.extendedProps.diet || '없음'}</p>
                        </div>
                        <p class="font-bold text-sm mb-2">🏋️ 운동 기록</p>
                        ${resultsHtml}
                    `;
                }

                // 입력 모달 열기 함수
                function openModal(dateStr) {
                    const modal = document.getElementById('workoutModal');
                    modal.classList.remove('hidden');
                    document.getElementById('modalDateTitle').innerText = dateStr + " 운동 기록";
                    document.getElementById('selectedDate').value = dateStr;
                    document.getElementById('exercise-fields').innerHTML = '';
                    
                    // 입력 폼 다시 보이기
                    document.getElementById('weight-input-section').classList.remove('hidden');
                    document.getElementById('category-select-section').classList.remove('hidden');
                    document.getElementById('diet-input-section').classList.remove('hidden');
                    document.getElementById('submit-button').classList.remove('hidden');
                }

                function closeModal() {
                    document.getElementById('workoutModal').classList.add('hidden');
                }

                function addCategoryExercises(category) {
                    const container = document.getElementById('exercise-fields');
                    container.innerHTML = '';
                    const exercises = userExercises[category];

                    if (!exercises || exercises.length === 0) {
                        alert(category + " 카테고리에 등록된 운동이 없습니다.");
                        return;
                    }

                    exercises.forEach((ex, index) => {
                        const html = `
                            <div class="p-3 bg-blue-50 rounded border border-blue-100 mb-2 relative group">
                                <button type="button" onclick="this.parentElement.remove()" class="absolute top-1 right-2 text-gray-400">×</button>
                                <p class="font-bold text-sm text-blue-600 mb-2">${ex.name}</p>
                                <input type="hidden" name="workout_results[${index}][name]" value="${ex.name}">
                                <div class="flex gap-2">
                                    <input type="number" name="workout_results[${index}][weight]" step="0.1" class="w-1/2 p-1 border rounded text-sm" placeholder="kg">
                                    <input type="number" name="workout_results[${index}][reps]" class="w-1/2 p-1 border rounded text-sm" placeholder="회">
                                </div>
                            </div>`;
                        container.insertAdjacentHTML('beforeend', html);
                    });
                }
            </script>
        @endpush
    @endauth
</x-app-layout>