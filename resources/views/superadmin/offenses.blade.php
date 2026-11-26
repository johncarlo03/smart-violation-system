<x-app-layout>
    <div class="min-h-screen bg-gray-50 flex flex-col md:flex-row">
        @include('layouts.superadmin-nav')

        <div class="flex-1 flex flex-col min-w-0">
            <main class="p-4 sm:p-6 lg:p-10">

                <div class="max-w-7xl mx-auto">

                    {{-- Header --}}
                    <div class="mb-6 sm:mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="min-w-0">
                            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">
                                Violation Management
                            </h2>
                            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                                Create, view, and modify school violations and offenses.
                            </p>
                        </div>
                    </div>

                    {{-- Flash Success --}}
                    @if(session('success'))
                        <div class="mb-4 sm:mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-xs sm:text-sm font-semibold text-emerald-900 shadow-xs">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Duplicate Error --}}
                    @error('name')
                        <div class="mb-4 sm:mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl text-xs sm:text-sm text-red-700">
                            <p class="font-medium">This <b class="font-black">offense</b> already exists.</p>
                        </div>
                    @enderror

                    {{-- Offenses Card --}}
                    <div class="w-full bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden">
                        <div class="h-1 w-full bg-gradient-to-r from-red-700 to-amber-500"></div>
                        <div class="p-4 sm:p-6 border-b border-gray-100 bg-gray-50/70 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                            <h1 class="text-base sm:text-lg font-black text-gray-900">Student Handbook Offenses & Rules</h1>
                            <button type="button" onclick="document.getElementById('create-modal').showModal()"
                                class="w-full sm:w-auto inline-flex items-center justify-center bg-[#1f2937] hover:bg-gray-800 text-white px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider shadow-xs transition shrink-0">
                                + Add New Rule
                            </button>
                        </div>

                        <div class="min-w-0">
                            <livewire:offense-table />
                        </div>
                    </div>

                </div>

            </main>
        </div>
    </div>

    <dialog id="create-modal"
        class="backdrop:bg-gray-900/60 rounded-2xl p-0 w-[calc(100%-1.5rem)] max-w-md max-h-[90vh] overflow-y-auto shadow-2xl border border-gray-200/80 m-auto">
        <div class="h-1 w-full bg-gradient-to-r from-red-700 to-amber-500"></div>
        <div class="p-4 sm:p-6">
            <div class="flex justify-between items-center gap-3 mb-4">
                <h3 class="text-base sm:text-lg font-black text-gray-900">Create New Rule</h3>
                <button type="button" onclick="document.getElementById('create-modal').close()"
                    class="w-8 h-8 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 flex items-center justify-center text-xl leading-none transition shrink-0">&times;</button>
            </div>

            <form id="create-offense-form" action="{{ route('superadmin.offenses.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-gray-600 mb-1.5">Offense Name</label>
                    <input type="text" name="name" required
                        class="w-full border-gray-200 rounded-xl shadow-xs text-base sm:text-sm text-gray-800 focus:border-[#1f2937] focus:ring-[#1f2937]"
                        placeholder="e.g., Dress code violation">
                </div>

                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-gray-600 mb-1.5">Category</label>
                    <select name="category" required
                        class="w-full border-gray-200 rounded-xl shadow-xs text-base sm:text-sm text-gray-800 focus:border-[#1f2937] focus:ring-[#1f2937]">
                        <option value="" disabled selected>Select a category</option>
                        <option value="Academic">Academic</option>
                        <option value="Non-Academic">Non-Academic</option>
                        <option value="Serious">Serious</option>
                        <option value="Very Serious">Very Serious</option>
                    </select>
                </div>

                <div class="border-t border-gray-100 pt-3 mt-3">
                    <label class="block text-xs font-black uppercase tracking-wider text-red-700 mb-2.5">Configure Penalties</label>
                    <div class="space-y-3">
                        @for ($i = 1; $i <= 3; $i++)
                            @php
                                $suffix = match ($i) {
                                    1 => 'st',
                                    2 => 'nd',
                                    3 => 'rd',
                                    default => 'th',
                                };
                            @endphp
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">
                                    {{ $i . $suffix }} Penalty
                                </label>

                                {{-- Array naming structure keeps input data tied directly to the correct penalty row ID --}}
                                <textarea name="penalties[{{ $i }}][description]" required rows="2"
                                    class="w-full border-gray-200 rounded-xl shadow-xs focus:border-[#1f2937] focus:ring-[#1f2937] p-2.5 text-base sm:text-sm text-gray-800"></textarea>
                            </div>
                        @endfor
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('create-modal').close()"
                        class="w-full sm:w-auto bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition">Cancel</button>
                    <button type="submit"
                        class="w-full sm:w-auto bg-[#1f2937] hover:bg-gray-800 text-white px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider shadow-xs transition">Submit</button>
                </div>
            </form>
        </div>
    </dialog>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        document.addEventListener('submit', function (event) {
            const form = event.target.closest('.delete-offense-form');
            if (!form) return;

            event.preventDefault();

            const offenseName = form.dataset.name;

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to permanently delete "${offenseName}". This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#b91c1c',
                cancelButtonColor: '#4b5563',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                background: '#ffffff',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider',
                    cancelButton: 'px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider'
                }
            }).then((result) => {

                if (result.isConfirmed) {
                    form.submit();
                }

            });
        });

    });
</script>