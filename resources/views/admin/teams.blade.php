<x-app-layout>
    <livewire:admin.teams/>
    
    @push('modals')
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @stack('js')
    @endpush
</x-app-layout>