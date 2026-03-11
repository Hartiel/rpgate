<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Room;

new class extends Component {
    use WithPagination;

    public function with(): array
    {
        return [
            'rooms' => Room::paginate(6),
        ];
    }
}; ?>

<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <input type="text" placeholder="Buscar salas..." />
    <div class="grid gap-4 md:grid-cols-3">
        @if($rooms->isEmpty())
            <div class="col-span-full text-center text-zinc-500">
                Nenhuma sala encontrada.
            </div>
        @else
            @foreach($rooms as $room)
            <div class="aspect-video rounded-xl border border-zinc-800 bg-zinc-950 p-4">
                {{ $room->name }}
            </div>
            @endforeach
        @endif
    </div>

    {{-- Botões de paginação que você queria no final --}}
    <div class="mt-auto py-4">
        {{ $rooms->links() }}
    </div>
</div>