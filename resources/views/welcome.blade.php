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
                let userExercises = {};
                let savedLogs = [];

                document.addEventListener('DOMContentLoaded', function() {
                    const dataStore = document.getElementById('exercise-data-store');
                    
                    try {
                        userExercises = JSON.parse(dataStore.dataset.exercises || '{}');
                        const rawLogs = JSON.parse(dataStore.dataset.logs || '[]');
                        
                        // ★ 중복 방지: 동일 날짜의 기록이 여러 개일 경우 마지막 기록 하나만 사용
                        const uniqueMap = {};
                        rawLogs.forEach(log => {
                            // FullCalendar는 'start' 속성을 날짜 기준으로 인식함
                            uniqueMap[log.start] = log;
                        });
                        savedLogs = Object.values(uniqueMap);
                        
                    } catch (e) {
                        console.error("데이터 로드 실패:", e);
                    }

                    const calendarEl = document.getElementById('calendar');
                    const calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        locale: 'ko',
                        height: 650,
                        headerToolbar: { 
                            left: 'title', 
                            center: '', 
                            right: 'prev,next' 
                        },
                        
                        // 시간 표시(오전 9시 등) 숨기기
                        displayEventTime: false,
                        
                        // 정제된 로그 데이터 연결
                        events: savedLogs, 
                        
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

                // 상세보기 모달 (저장된 기록 보여주기)
                function showDetailModal(log, dateStr) {
                    const modal = document.getElementById('workoutModal');
                    modal.classList.remove('hidden');
                    
                    document.getElementById('modalDateTitle').innerText = dateStr + " 운동 요약";
                    
                    // 입력 관련 섹션 숨기기
                    document.getElementById('weight-input-section').classList.add('hidden');
                    document.getElementById('category-select-section').classList.add('hidden');
                    document.getElementById('diet-input-section').classList.add('hidden');
                    document.getElementById('submit-button').classList.add('hidden');

                    // 결과 데이터 렌더링
                    const resultsHtml = log.extendedProps.results.map(ex => `
                        <div class="p-2 bg-gray-50 border rounded text-sm mb-1 flex justify-between">
                            <span><strong>${ex.name}</strong></span>
                            <span class="text-sky-600 font-mono">${ex.weight}kg x ${ex.reps}회</span>
                        </div>
                    `).join('');

                    document.getElementById('exercise-fields').innerHTML = `
                        <div class="bg-sky-50 p-3 rounded mb-4 text-sm border border-sky-100">
                            <p class="mb-1"><strong>⚖️ 체중:</strong> ${log.extendedProps.weight}kg</p>
                            <p><strong>📝 메모:</strong> ${log.extendedProps.diet || '내용 없음'}</p>
                        </div>
                        <p class="font-bold text-sm mb-2 text-gray-600">🏋️ 운동 기록</p>
                        <div class="max-h-48 overflow-y-auto">${resultsHtml}</div>
                    `;
                }

                // 입력 모달 (새 기록 작성하기)
                function openModal(dateStr) {
                    const modal = document.getElementById('workoutModal');
                    modal.classList.remove('hidden');
                    
                    document.getElementById('modalDateTitle').innerText = dateStr + " 운동 기록";
                    document.getElementById('selectedDate').value = dateStr;
                    document.getElementById('exercise-fields').innerHTML = '';
                    
                    // 입력 섹션 다시 보이기
                    document.getElementById('weight-input-section').classList.remove('hidden');
                    document.getElementById('category-select-section').classList.remove('hidden');
                    document.getElementById('diet-input-section').classList.remove('hidden');
                    document.getElementById('submit-button').classList.remove('hidden');
                }

                function closeModal() {
                    document.getElementById('workoutModal').classList.add('hidden');
                }

                // 카테고리 선택 시 운동 항목 추가
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
                            <div class="p-3 bg-sky-50 rounded border border-sky-100 mb-2 relative group">
                                <button type="button" onclick="this.parentElement.remove()" class="absolute top-1 right-2 text-gray-400 hover:text-red-500">×</button>
                                <p class="font-bold text-sm text-sky-700 mb-2">${ex.name}</p>
                                <input type="hidden" name="workout_results[${index}][name]" value="${ex.name}">
                                <div class="flex gap-2">
                                    <input type="number" name="workout_results[${index}][weight]" step="0.1" class="w-1/2 p-1 border rounded text-sm focus:ring-sky-500" placeholder="kg">
                                    <input type="number" name="workout_results[${index}][reps]" class="w-1/2 p-1 border rounded text-sm focus:ring-sky-500" placeholder="회">
                                </div>
                            </div>`;
                        container.insertAdjacentHTML('beforeend', html);
                    });
                }
            </script>
        @endpush
    @endauth
</x-app-layout>