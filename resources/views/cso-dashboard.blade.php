<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::user()->name }}'s Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-bold mb-4">Report New Violation</h3>
                    
                    {{-- <form method="POST" action="{{ route('violations.store') }}"> --}}
                        @csrf
                        <div class="mb-4">
    <x-input-label for="rfid_input" value="Scan RFID or Search Student" />
    <input type="text" id="rfid_listener" class="opacity-0 absolute" autofocus>
    
    <select name="student_id" id="student_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm select2">
        <option value="">-- Select or Scan --</option>
        @foreach(\App\Models\User::where('role', 1)->get() as $student)
            <option value="{{ $student->id }}" data-rfid="{{ $student->rfid_number }}">
                {{ $student->name }} ({{ $student->rfid_number ?? 'No RFID' }})
            </option>
        @endforeach
    </select>
</div>

                        <div class="mb-4">
                            <x-input-label for="type" value="Violation Type" />
                            <x-text-input id="type" name="type" type="text" class="block mt-1 w-full" placeholder="e.g., Improper Uniform" required />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="description" value="Details" />
                            <textarea name="description" id="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3" required></textarea>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>Submit Report</x-primary-button>

                            @if (session('status'))
                                <p class="text-sm text-green-600">{{ session('status') }}</p>
                            @endif
                        </div>
                    {{-- </form> --}}
                </div>
            </div>
        </div>
    </div>

    
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const rfidListener = document.getElementById('rfid_listener');
        const studentSelect = document.getElementById('student_id');

        // Initial focus
        rfidListener.focus();

        // FIX: Only steal focus back if we AREN'T clicking the dropdown
        document.addEventListener('click', (event) => {
            if (event.target !== studentSelect) {
                rfidListener.focus();
            }
        });

        rfidListener.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                const scannedValue = rfidListener.value.trim();
                
                const options = studentSelect.options;
                let found = false;

                for (let i = 0; i < options.length; i++) {
                    if (options[i].getAttribute('data-rfid') === scannedValue) {
                        studentSelect.selectedIndex = i;
                        found = true;
                        break;
                    }
                }

                if(!found && scannedValue !== "") {
                    alert("RFID " + scannedValue + " not recognized.");
                }

                rfidListener.value = ''; 
            }
        });
    });
</script>
</x-app-layout>
