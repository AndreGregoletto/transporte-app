<div class="container-fluid">
    <div class="text-center">
        <div class="error mx-auto" data-text="404" {{ $width ? 'style=width:'.$width.'px' : '' }}>404</div>
        <p class="lead text-gray-800 mb-5">{{ $msg }}</p>
        <p class="text-gray-500 mb-0">It looks like you found a glitch in the matrix...</p>
        <a href="{{ route('dashboard.index') }}">&larr; Back to Dashboard</a>
    </div>
</div>