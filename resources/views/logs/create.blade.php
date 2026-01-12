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

    <div id="workoutModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalDateTitle">날짜 선택됨</h3>
                
                <div class="mt-2 px-7 py-3">
                    <form action="{{ route('logs.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="record_date" id="selectedDate">
                        
                        <div class="mb-4 text-left">
                            <label class="block text-sm font-medium text-gray-700">오늘 체중 (kg)</label>
                            <input type="number" name="current_weight" step="0.1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div class="mb-4 text-left">
                            <label class="block text-sm font-medium text-gray-700">루틴 선택</label>
                            <select id="template-select" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" onchange="loadExercises()">
                                <option value="">-- 선택 안 함 --</option>
                                @foreach(Auth::user()->templates as $template)
                                    <option value="{{ json_encode($template->routine_contents) }}">{{ $template->template_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="exercise-fields" class="space-y-4 mb-4 text-left max-h-60 overflow-y-auto"></div>

                        <div class="mb-4 text-left">
                            <label class="block text-sm font-medium text-gray-700">식단 및 메모</label>
                            <textarea name="diet_content" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                        </div>

                        <div class="items-center px-4 py-3">
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                                저장하기
                            </button>
                            <button type="button" onclick="closeModal()" class="mt-3 px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                닫기
                            </button>
                        </div>
                    </form>
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