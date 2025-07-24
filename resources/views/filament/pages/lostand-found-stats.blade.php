<x-filament-panels::page>
    <div>
        <div class="text-2xl">
            Welcome <span class="font-semibold">{{ auth()->user()->name }}</span>!
        </div>
        <div class="text">
            <p id="time"></p>
        </div>
    </div>
    @livewire(\App\Livewire\LoasAndFoundOverview::class)

    @livewire(\App\Livewire\LostandFoundChart::class)

</x-filament-panels::page>
<script>
    function startTime() {
        const today = new Date();
        document.getElementById('time').innerHTML = 'Today is ' + today;
        setTimeout(startTime, 1000);
    }
    startTime()
</script>
