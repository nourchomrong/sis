<nav>
  <ul class="flex -space-x-px text-sm">

    {{-- Previous --}}
    <li>
      <button wire:click="previous"
        class="px-3 h-10 border rounded-s-base
        {{ $currentPage == 1 ? 'opacity-50' : '' }}">
        Previous
      </button>
    </li>

    {{-- Pages --}}
    @for ($i = 1; $i <= $totalPages; $i++)
      <li>
        <button wire:click="gotoPage({{ $i }})"
          class="w-10 h-10 border
          {{ $currentPage == $i ? 'bg-gray-300 font-bold' : '' }}">
          {{ $i }}
        </button>
      </li>
    @endfor

    {{-- Next --}}
    <li>
      <button wire:click="next"
        class="px-3 h-10 border rounded-e-base
        {{ $currentPage == $totalPages ? 'opacity-50' : '' }}">
        Next
      </button>
    </li>

  </ul>
</nav>