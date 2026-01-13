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


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'ko', // 한국어 설정
                height: 650,
                selectable: true, // 날짜 선택 가능하게
                
                // 날짜를 클릭했을 때 실행되는 함수
                dateClick: function(info) {
                    openModal(info.dateStr); // 클릭한 날짜를 팝업에 넘김
                },

                // (나중에 여기에 저장된 운동 기록을 불러와서 표시할 겁니다)
                events: [] 
            });
            calendar.render();
        });

        // 팝업 열기 함수
        function openModal(dateStr) {
            document.getElementById('workoutModal').classList.remove('hidden');
            document.getElementById('modalDateTitle').innerText = dateStr + " 운동 기록";
            document.getElementById('selectedDate').value = dateStr; // 숨겨진 input에 날짜 주입
        }

        // 팝업 닫기 함수
        function closeModal() {
            document.getElementById('workoutModal').classList.add('hidden');
        }

        // 아까 만든 운동 목록 불러오는 함수 (그대로 재사용)
        function loadExercises() {
            const select = document.getElementById('template-select');
            const container = document.getElementById('exercise-fields');
            container.innerHTML = ''; 

            if (!select.value) return;
            const exercises = JSON.parse(select.value);

            exercises.forEach((exName, index) => {
                const html = `
                    <div class="p-3 bg-gray-50 rounded border">
                        <p class="font-bold text-sm text-blue-600 mb-2">${exName}</p>
                        <input type="hidden" name="workout_results[${index}][name]" value="${exName}">
                        <div class="flex gap-2">
                            <input type="number" name="workout_results[${index}][weight]" class="w-1/2 p-1 border rounded text-sm" placeholder="kg">
                            <input type="number" name="workout_results[${index}][reps]" class="w-1/2 p-1 border rounded text-sm" placeholder="회">
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', html);
            });
        }
    </script>
</x-app-layout>