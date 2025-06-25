<x-layout.base>
    <x-slot name="title">Not Found</x-slot>
    <div class="row">
        <x-layout.not-found :msg="request()->query('msg', 'Página não encontrada')" :width="210" />
    </div>
</x-layout.base>