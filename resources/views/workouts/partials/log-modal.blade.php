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