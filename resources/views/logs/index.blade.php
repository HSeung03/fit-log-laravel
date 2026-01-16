<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('운동기록 확인') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="mb-6">
                    <p class="text-sm text-gray-600">
                        * 기록을 클릭하면 해당 날짜의 상세 운동 내용을 확인할 수 있습니다.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($logs as $log)
                        <a href="{{ route('logs.show', $log->record_date->format('Y-m-d')) }}" 
                           class="group block p-6 bg-white border border-gray-200 rounded-xl shadow-sm 
                                  hover:shadow-md hover:border-blue-300 transition-all duration-200">
                            
                            <div class="flex justify-between items-center">
                                <div>
                                    <h5 class="text-xl font-bold text-gray-900 mt-1">
                                        {{ $log->record_date->format('Y년 m월 d일') }}
                                    </h5>
                                </div>
                                
                                <div class="text-gray-400 group-hover:text-blue-500 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <div class="text-gray-400 mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <p class="text-gray-500 text-lg">아직 등록된 운동 기록이 없습니다.</p>
                            <a href="{{ route('home') }}" class="mt-4 inline-block text-blue-600 hover:underline transition">
                                첫 기록 등록하러 가기
                            </a>
                        </div>
                    @endforelse
                </div>
                </div>
        </div>
    </div>
</x-app-layout>