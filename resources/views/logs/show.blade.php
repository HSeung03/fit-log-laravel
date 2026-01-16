<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $log->record_date->format('Y년 m월 d일') }} 기록
            </h2>
            <a href="{{ route('logs.index') }}" class="text-sm text-gray-500 hover:text-blue-600 transition">
                &larr; 목록으로 돌아가기
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-blue-600 text-white px-6 py-4 rounded-t-xl shadow-sm">
                <p class="font-bold text-lg">운동 기록 완료</p>
                <p class="text-blue-100 text-sm">성실함은 배신하지 않습니다.</p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-b-xl p-6 space-y-8">
                
                <section>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-1 h-5 bg-blue-600 rounded-full mr-2"></span>
                        오늘의 운동
                    </h3>
                    
                    <div class="grid gap-4">
                        @forelse($log->workout_results as $result)
                            <div class="bg-gray-50 border border-gray-100 rounded-lg p-5 flex justify-between items-center hover:bg-gray-100 transition">
                                <div>
                                    <h4 class="text-lg font-extrabold text-gray-800">
                                        {{ $exerciseNames[$result['exercise_id']] ?? '삭제된 운동' }}
                                    </h4>
                                    <p class="text-gray-600 mt-1">
                                        <span class="font-semibold text-blue-600">{{ $result['sets'] }}</span>세트 × 
                                        <span class="font-semibold text-blue-600">{{ $result['reps'] }}</span>회
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="text-2xl font-black text-gray-300 italic">
                                        {{ $result['weight'] }}kg
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 italic py-4 text-center">저장된 운동 세부 기록이 없습니다.</p>
                        @endforelse
                    </div>
                </section>

                <hr class="border-gray-100">

                @if($log->diet_content)
                <section>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-1 h-5 bg-green-500 rounded-full mr-2"></span>
                        식단 기록
                    </h3>
                    <div class="bg-green-50 border border-green-100 rounded-lg p-5">
                        <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                            {{ $log->diet_content }}
                        </p>
                    </div>
                </section>
                @endif
            </div>
            
            <p class="text-center text-gray-400 text-xs mt-6">
                * 이 기록은 수정할 수 없는 읽기 전용 데이터입니다.
            </p>
        </div>
    </div>
</x-app-layout>