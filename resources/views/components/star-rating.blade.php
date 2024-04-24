@if ($rating)
    <div class="flex items-center">
        @for ($i = 1; $i <= 5; $i++)
            @if (round($rating) >= $i)
                <svg class="w-4 h-4 fill-current text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12 2l2.4 7.4H22l-6.2 4.5 2.4 7.4L12 16.3 5.8 21.3l2.4-7.4L2 9.4h7.6z" />
                </svg>
            @else
                <svg class="w-4 h-4 fill-current text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12 2l2.4 7.4H22l-6.2 4.5 2.4 7.4L12 16.3 5.8 21.3l2.4-7.4L2 9.4h7.6z" />
                </svg>
            @endif
        @endfor
    </div>
@else
    No Rating
@endif
