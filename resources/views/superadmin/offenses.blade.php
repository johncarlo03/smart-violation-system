<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex">
        @include('layouts.superadmin-nav')

        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto max-h-screen">
            <main class="p-8 lg:p-10">

                <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-3xl font-black text-gray-800 tracking-tight">
                            Violation Management
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">
                            Create, view, and modify school violations and offenses.
                        </p>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto bg-white rounded-xl border border-gray-200 shadow-sm">
                    <div class="m-5 bg-gray-50 flex justify-between items-center">
                        <h1 class="text-lg font-black text-gray-800">Student Handbook Offenses & Rules</h1>
                        <button onclick="document.getElementById('create-modal').showModal()"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow">
                            + Add New Rule
                        </button>
                    </div>
                    <livewire:offense-table/> 
                </div>

            </main>
        </div>
    </div>

    <dialog id="create-modal"
        class="backdrop:bg-black/50 rounded-xl p-6 w-full max-w-md shadow-2xl border border-gray-100">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">Create New Rule</h3>
            <button onclick="document.getElementById('create-modal').close()"
                class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        <form action="{{ route('superadmin.offenses.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold uppercase text-gray-600 mb-1">Offense Name</label>
                <input type="text" name="name" required
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="e.g., Dress code violation">
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase text-gray-600 mb-1">Category</label>
                <select name="category" required
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="" disabled selected>Select a category</option>
                    <option value="Academic">Academic</option>
                    <option value="Non-Academic">Non-Academic</option>
                    <option value="Serious">Serious</option>
                    <option value="Very Serious">Very Serious</option>
                </select>
            </div>
            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="document.getElementById('create-modal').close()"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">Cancel</button>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">Submit</button>
            </div>
        </form>
    </dialog>
</x-app-layout>