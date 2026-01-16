<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('운동 종목 관리') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <form action="{{ route('exercises.store') }}" method="POST" autocomplete="off" 
                class="mb-8 p-6 bg-white shadow sm:rounded-lg flex flex-col md:flex-row gap-4 items-end">
                @csrf
                
                <div class="w-full md:flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">카테고리</label>
                    <select name="category" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 transition">
                        <option value="상체">상체</option>
                        <option value="하체">하체</option>
                        <option value="유산소">유산소</option>
                    </select>
                </div>
                
                <div class="w-full md:flex-[2]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">운동 이름</label>
                    <input type="text" name="name" placeholder="예: 벤치프레스" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 transition">
                </div>

                <button type="submit" 
                    class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-8 py-2 rounded-md font-bold transition shadow-sm">
                    추가하기
                </button>
            </form>

            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @forelse($exercises as $exercise)
                        <li class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition">
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-3">
                                    {{ $exercise->category }}
                                </span>
                                <span class="text-lg font-semibold text-gray-900">{{ $exercise->name }}</span>
                            </div>

                            <form action="{{ route('exercises.destroy', $exercise->id) }}" method="POST" 
                                onsubmit="return confirm('이 운동 종목을 삭제하시겠습니까?\n(해당 종목이 포함된 과거 기록이 모두 삭제될 수 있습니다.)');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                    class="text-red-500 hover:text-white hover:bg-red-500 border border-red-500 px-3 py-1 rounded text-sm transition">
                                    삭제
                                </button>
                            </form>
                        </li>
                    @empty
                        <li class="px-6 py-12 text-center text-gray-500 italic">
                            등록된 운동이 없습니다. 새로운 운동을 추가해 보세요!
                        </li>
                    @endforelse
                </ul>
            </div>

        </div>
    </div>
</x-app-layout>