<div 
    id="workoutModal" 
    class="fixed inset-0 z-[100] hidden overflow-y-auto" 
    aria-labelledby="modal-title" 
    role="dialog" 
    aria-modal="true"
>
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg p-6">
            
            <h3 class="text-xl font-bold text-gray-900 mb-4" id="modalDateTitle">날짜 선택됨</h3>
            
            <form action="{{ route('logs.store') }}" method="POST">
                @csrf
                <input type="hidden" name="record_date" id="selectedDate">

                <div id="category-select-section" class="mb-4 text-left">
                    <label class="block text-sm font-bold text-gray-700 mb-2">카테고리 선택</label>
                    <div class="flex gap-2">
                        <button type="button" onclick="addCategoryExercises('상체')" 
                            class="flex-1 py-2 bg-blue-100 text-blue-700 rounded-lg font-bold hover:bg-blue-200 transition">
                            상체
                        </button>
                        <button type="button" onclick="addCategoryExercises('하체')" 
                            class="flex-1 py-2 bg-blue-100 text-blue-700 rounded-lg font-bold hover:bg-blue-200 transition">
                            하체
                        </button>
                        <button type="button" onclick="addCategoryExercises('유산소')" 
                            class="flex-1 py-2 bg-blue-100 text-blue-700 rounded-lg font-bold hover:bg-blue-200 transition">
                            유산소
                        </button>
                    </div>
                </div>

                <div 
                    id="exercise-fields" 
                    class="space-y-3 mb-4 max-h-60 overflow-y-auto border-t border-b py-2 empty:hidden"
                >
                </div>

                <div id="diet-input-section" class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-1">식단 및 메모</label>
                    <textarea 
                        name="diet_content" 
                        rows="3" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                        placeholder="메모를 적어주세요"
                    ></textarea>
                </div>

                <div class="flex gap-3 mt-5">
                    <button type="button" onclick="closeModal()" 
                        class="flex-1 px-4 py-3 bg-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-300 transition">
                        닫기
                    </button>
                    <button type="submit" id="submit-button" 
                        class="flex-[2] px-4 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition shadow-lg">
                        기록 저장하기
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>