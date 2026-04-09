<div class="overflow-x-auto border rounded">
    <table class="min-w-full bg-white divide-y divide-gray-200">

        <!-- Header -->
        <thead class="bg-gray-100">
            <tr>
                @foreach($headers as $header)
                    <th class="px-4 py-2 text-left">
                        {{ $header }}
                    </th>
                @endforeach
            </tr>
        </thead>

        <!-- Body -->
        <tbody class="divide-y divide-gray-200">
            {{ $slot }}
        </tbody>

    </table>
</div>