<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('exercises.store') }}" method="POST" class="mb-6">
                @csrf
                <input type="text" name="name" placeholder="운동 이름 (예: 벤치프레스)" required>
                <select name="category">
                    <option value="상체">상체</option>
                    <option value="하체">하체</option>
                    <option value="유산소">유산소</option>
                </select>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">추가하기</button>
            </form>

            <ul class="bg-white shadow sm:rounded-md">
                @foreach($exercises as $exercise)
                    <li class="px-4 py-4 border-b border-gray-200">
                        [{{ $exercise->category }}] {{ $exercise->name }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</x-app-layout>