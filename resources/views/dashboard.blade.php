<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('운동 기록') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
    </div>

    @include('workouts.partials.log-modal')

    @push('scripts')
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: 'ko',
                    height: 650,
                    selectable: true,
                    headerToolbar: false,
                    dateClick: function(info) {
                        openModal(info.dateStr);
                    },
                    events: [] 
                });
                calendar.render();
            });

            function openModal(dateStr) {
                document.getElementById('workoutModal').classList.remove('hidden');
                document.getElementById('modalDateTitle').innerText = dateStr + " 운동 기록";
                document.getElementById('selectedDate').value = dateStr;
            }

            function closeModal() {
                document.getElementById('workoutModal').classList.add('hidden');
            }

            function loadExercises() {
                const select = document.getElementById('template-select');
                const container = document.getElementById('exercise-fields');
                container.innerHTML = ''; 

                if (!select.value) return;
                const exercises = JSON.parse(select.value);

                exercises.forEach((exName, index) => {
                    const html = `
                        <div class="p-3 bg-gray-50 rounded border mb-2">
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
    @endpush
</x-app-layout>