<x-app-layout>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
    </div>

    @include('workouts.log-modal')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            
            // 캘린더 초기화 및 설정
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'ko',
                height: 650,
                selectable: true,
                
                // 날짜 클릭 시 모달 오픈
                dateClick: function(info) {
                    openModal(info.dateStr);
                },

                // 저장된 운동 기록 표시용 데이터 (추후 연결)
                events: [] 
            });
            
            calendar.render();
        });

        /**
         * 운동 기록 모달 표시
         */
        function openModal(dateStr) {
            const modal = document.getElementById('workoutModal');
            if (!modal) return;

            modal.classList.remove('hidden');
            document.getElementById('modalDateTitle').innerText = dateStr + " 운동 기록";
            document.getElementById('selectedDate').value = dateStr;
        }

        /**
         * 운동 기록 모달 닫기
         */
        function closeModal() {
            const modal = document.getElementById('workoutModal');
            if (modal) modal.classList.add('hidden');
        }

        /**
         * 운동 입력 필드 동적 생성 로직
         */
        function loadExercises() {
            const select = document.getElementById('template-select');
            const container = document.getElementById('exercise-fields');
            
            if (!select || !container) return;
            
            container.innerHTML = ''; 
            if (!select.value) return;

            try {
                const exercises = JSON.parse(select.value);

                exercises.forEach((exName, index) => {
                    const html = `
                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 mb-2 shadow-sm">
                            <p class="font-bold text-sm text-blue-600 mb-2">${exName}</p>
                            <input type="hidden" name="workout_results[${index}][name]" value="${exName}">
                            <div class="flex gap-2">
                                <input type="number" name="workout_results[${index}][weight]" 
                                    class="w-1/2 rounded-md border-gray-300 text-sm focus:ring-blue-500" placeholder="kg">
                                <input type="number" name="workout_results[${index}][reps]" 
                                    class="w-1/2 rounded-md border-gray-300 text-sm focus:ring-blue-500" placeholder="회">
                            </div>
                        </div>
                    `;
                    container.insertAdjacentHTML('beforeend', html);
                });
            } catch (error) {
                console.error("데이터 로드 중 오류가 발생했습니다.", error);
            }
        }
    </script>
</x-app-layout>