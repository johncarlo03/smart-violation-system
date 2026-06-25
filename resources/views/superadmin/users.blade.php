<style>
/* Pagination Container */
nav[role="navigation"] {
    display: flex;
    justify-content: center;
}

/* All Buttons */
nav[role="navigation"] a,
nav[role="navigation"] span[aria-current="page"] span {
    border-radius: 0.75rem !important;
    margin: 0 2px;
    font-weight: 500;
}

/* Normal Pages */
nav[role="navigation"] a {
    background: white !important;
    color: #4b5563 !important;       /* gray-600 */
    border: 1px solid #e5e7eb !important; /* gray-200 */
}

/* Hover */
nav[role="navigation"] a:hover {
    background: #eef2ff !important;  /* indigo-50 */
    color: #4338ca !important;       /* indigo-700 */
    border-color: #c7d2fe !important; /* indigo-200 */
}

/* Active Page */
nav[role="navigation"] span[aria-current="page"] span {
    background: #4f46e5 !important;  /* indigo-600 */
    color: white !important;
    border: 1px solid #4f46e5 !important;
    box-shadow: 0 1px 3px rgb(0 0 0 / 0.1);
}

/* Previous / Next */
nav[role="navigation"] a[rel="prev"],
nav[role="navigation"] a[rel="next"] {
    background: white !important;
    color: #4f46e5 !important;
}

/* Disabled Buttons */
nav[role="navigation"] span.text-gray-500,
nav[role="navigation"] span.text-gray-400 {
    background: #f9fafb !important; /* gray-50 */
    color: #9ca3af !important;      /* gray-400 */
    border-radius: 0.75rem;
    border-color: rgb(187, 187, 187);
}
</style>
<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex">

        @include('layouts.superadmin-nav')

        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto max-h-screen">
            <main class="p-8 lg:p-10">

                <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-3xl font-black text-gray-800 tracking-tight">
                            User Management
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">
                            Create, view, and modify system users and their clearanced layers.
                        </p>
                    </div>
                </div>

                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-100 rounded-2xl flex items-center gap-3 shadow-sm">
                        <div
                            class="w-8 h-8 rounded-xl bg-green-100 flex items-center justify-center text-green-600 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
                    </div>
                @endif

                <div class="w-full bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-10">
                    <div
                        class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100/50 flex justify-between items-center">
                        <h3 id="form-title" class="text-lg font-black text-gray-800">
                            Register User
                        </h3>
                        <button type="button" id="cancel-edit-btn"
                            class="hidden text-xs font-bold text-red-500 hover:text-red-700 uppercase tracking-wider">
                            Cancel Edit
                        </button>
                    </div>

                    <form id="user-form" action="{{ route('user.store') }}" method="POST" class="p-6 space-y-6">
                        @csrf
                        <div id="method-container"></div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Full Name Input Field --}}
                            <div>
                                <label class="block text-xs font-black uppercase tracking-[0.15em] text-gray-400 mb-2">
                                    Full Name
                                </label>
                                <input type="text" name="name" id="input-name" value="{{ old('name') }}" required
                                    class="w-full rounded-xl border-gray-200 text-sm font-medium text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200">
                                @error('name')
                                    <p class="text-xs font-bold text-red-500 mt-1.5 pl-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-black uppercase tracking-[0.15em] text-gray-400 mb-2">
                                    ID Number:
                                </label>
                                <input type="number" name="id_number" id="input-id_number"
                                    value="{{ old('id_number') }}" required
                                    class="w-full rounded-xl border-gray-200 text-sm font-medium text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200">
                                @error('id_number')
                                    <p class="text-xs font-bold text-red-500 mt-1.5 pl-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-black uppercase tracking-[0.15em] text-gray-400 mb-2">
                                    rfid(if applicable)
                                </label>
                                <input type="number" name="rfid_number" id="input-rfid_number"
                                    value="{{ old('rfid_number') }}" required
                                    class="w-full rounded-xl border-gray-200 text-sm font-medium text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200">
                                @error('rfid_number')
                                    <p class="text-xs font-bold text-red-500 mt-1.5 pl-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Institutional Email Address Input Field --}}
                            <div>
                                <label class="block text-xs font-black uppercase tracking-[0.15em] text-gray-400 mb-2">
                                    Email
                                </label>
                                <input type="email" name="email" id="input-email" value="{{ old('email') }}" required
                                    class="w-full rounded-xl border-gray-200 text-sm font-medium text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200">
                                @error('email')
                                    <p class="text-xs font-bold text-red-500 mt-1.5 pl-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Account Security Access Password Input --}}
                            <div>
                                <label id="password-label"
                                    class="block text-xs font-black uppercase tracking-[0.15em] text-gray-400 mb-2">
                                    Password
                                </label>
                                <input type="password" name="password" id="input-password" required
                                    class="w-full rounded-xl border-gray-200 text-sm font-medium text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200">
                                @error('password')
                                    <p class="text-xs font-bold text-red-500 mt-1.5 pl-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Confirm Password Input Field --}}
                            <div>
                                <label id="password-confirm-label"
                                    class="block text-xs font-black uppercase tracking-[0.15em] text-gray-400 mb-2">
                                    Confirm Password
                                </label>
                                <input type="password" name="password_confirmation" id="input-password_confirmation"
                                    required
                                    class="w-full rounded-xl border-gray-200 text-sm font-medium text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200">
                            </div>
                        </div>

                        <hr class="border-gray-100">

                        {{-- Structural Role Assignment Radio Nodes --}}
                        <div>
                            <label class="block text-xs font-black uppercase tracking-[0.15em] text-gray-400 mb-3">
                                Structural Clearance Layer Assignment
                            </label>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <label
                                    class="relative flex items-start p-4 rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer transition duration-200">
                                    <div class="flex items-center h-5">
                                        <input type="radio" name="role" value="1" {{ old('role') == '1' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    </div>
                                    <div class="ml-3">
                                        <span
                                            class="block text-xs font-black uppercase tracking-wider text-blue-600">Student</span>
                                        <span class="block text-[11px] font-medium text-gray-400 mt-0.5">Logs incidents
                                            & data</span>
                                    </div>
                                </label>

                                <label
                                    class="relative flex items-start p-4 rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer transition duration-200">
                                    <div class="flex items-center h-5">
                                        <input type="radio" name="role" value="2" {{ old('role') == '2' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    </div>
                                    <div class="ml-3">
                                        <span
                                            class="block text-xs font-black uppercase tracking-wider text-orange-600">CSO
                                            Officer</span>
                                        <span class="block text-[11px] font-medium text-gray-400 mt-0.5">Logs incidents
                                            & data</span>
                                    </div>
                                </label>

                                <label
                                    class="relative flex items-start p-4 rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer transition duration-200">
                                    <div class="flex items-center h-5">
                                        <input type="radio" name="role" value="3" {{ old('role') == '3' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    </div>
                                    <div class="ml-3">
                                        <span
                                            class="block text-xs font-black uppercase tracking-wider text-green-600">SAO
                                            Faculty</span>
                                        <span class="block text-[11px] font-medium text-gray-400 mt-0.5">Manages system
                                            scopes</span>
                                    </div>
                                </label>

                                <label
                                    class="relative flex items-start p-4 rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer transition duration-200">
                                    <div class="flex items-center h-5">
                                        <input type="radio" name="role" value="4" {{ old('role') == '4' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    </div>
                                    <div class="ml-3">
                                        <span
                                            class="block text-xs font-black uppercase tracking-wider text-indigo-600">Super
                                            Admin</span>
                                        <span class="block text-[11px] font-medium text-gray-400 mt-0.5">Complete root
                                            control</span>
                                    </div>
                                </label>
                            </div>
                            @error('role')
                                <p class="text-xs font-bold text-red-500 mt-2 pl-1">{{ $message }}</p>
                            @enderror

                            <div id="student-fields" class="hidden mt-6">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label
                                            class="block text-xs font-black uppercase tracking-[0.15em] text-gray-400 mb-2">
                                            Department
                                        </label>
                                        <select id="department" name="department_id"
                                            class="w-full rounded-xl border-gray-200">
                                            <option value="">Select Department</option>
                                            @foreach($departments as $department)
                                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-xs font-black uppercase tracking-[0.15em] text-gray-400 mb-2">
                                            Course
                                        </label>
                                        <select id="course" name="course_id" disabled
                                            class="w-full rounded-xl border-gray-200">
                                            <option value="">Select Department First</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-xs font-black uppercase tracking-[0.15em] text-gray-400 mb-2">
                                            Year Level
                                        </label>
                                        <select id="year_level" name="year_level"
                                            class="w-full rounded-xl border-gray-200">
                                            <option value="">Select Year</option>
                                            <option value="1">1st Year</option>
                                            <option value="2">2nd Year</option>
                                            <option value="3">3rd Year</option>
                                            <option value="4">4th Year</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Interface Commit Form Submission Panel --}}
                        <div class="pt-4 border-t border-gray-100 flex justify-end">
                            <button type="submit" id="submit-btn"
                                class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white rounded-xl text-xs font-black uppercase tracking-widest transition duration-200 shadow-md hover:shadow-lg active:scale-[0.98]">
                                Register User
                            </button>
                        </div>
                    </form>
                </div>

                <div class="w-full bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100/50">
                        <h3 class="text-lg font-black text-gray-800">Active Users</h3>
                    </div>
                    
                    <livewire:user-table />  {{-- v4 syntax --}}

            </main>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const roleRadios = document.querySelectorAll('input[name="role"]');
        const studentFields = document.getElementById('student-fields');
        const department = document.getElementById('department');
        const course = document.getElementById('course');
        const yearLevel = document.getElementById('year_level');

        // Form elements for mutating state (Create vs Edit)
        const userForm = document.getElementById('user-form');
        const formTitle = document.getElementById('form-title');
        const submitBtn = document.getElementById('submit-btn');
        const cancelEditBtn = document.getElementById('cancel-edit-btn');
        const methodContainer = document.getElementById('method-container');

        // Form input references
        const inputName = document.getElementById('input-name');
        const inputIdNumber = document.getElementById('input-id_number');
        const inputRfidNumber = document.getElementById('input-rfid_number');
        const inputEmail = document.getElementById('input-email');
        const inputPassword = document.getElementById('input-password');
        const inputPasswordConfirm = document.getElementById('input-password_confirmation');
        const passwordLabel = document.getElementById('password-label');

        const courses = @json($courses);
        const storeRoute = "{{ route('user.store') }}";

        // Handle structural role logic toggles
        function toggleRoleFields(roleValue) {
            if (roleValue == '1') {
                studentFields.classList.remove('hidden');
            } else {
                studentFields.classList.add('hidden');
            }
        }

        roleRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                toggleRoleFields(this.value);
            });
        });

        // Handle cascading dynamic dropdowns (Department -> Course)
        department.addEventListener('change', function (e, selectedCourseId = null) {
            course.innerHTML = '';
            let selected = this.value;

            if (!selected) {
                course.disabled = true;
                course.innerHTML = '<option value="">Select Department First</option>';
                return;
            }

            course.disabled = false;
            course.innerHTML = '<option value="">Select Course</option>';

            if (courses[selected]) {
                courses[selected].forEach(function (item) {
                    let option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.name;
                    if (selectedCourseId && item.id == selectedCourseId) {
                        option.selected = true;
                    }
                    course.appendChild(option);
                });
            }
        });

        document.querySelectorAll('.edit-user-btn').forEach(button => {
            button.addEventListener('click', function () {
                const data = this.dataset;

                formTitle.textContent = "Edit User: " + data.name;
                submitBtn.textContent = "Update User";
                cancelEditBtn.classList.remove('hidden');

                userForm.action = `/superadmin/users/${data.id}`;
                methodContainer.innerHTML = '@method("PUT")';

                inputName.value = data.name;
                inputIdNumber.value = data.id_number;
                inputRfidNumber.value = data.rfid_number;
                inputEmail.value = data.email;

                inputPassword.required = false;
                inputPasswordConfirm.required = false;
                passwordLabel.textContent = "Password (Leave blank to keep current)";

                // 4. Check matching role radio box
                roleRadios.forEach(radio => {
                    if (radio.value == data.role) {
                        radio.checked = true;
                        toggleRoleFields(data.role);
                    }
                });

                // 5. Build dropdown selections out if student layout tier applies
                if (data.role == '1') {
                    department.value = data.department_id;
                    // Trigger custom change pass-through down to Course parsing handler
                    department.dispatchEvent(new Event('change'));

                    // Small delay sequence ensures elements mount options securely prior to matching selection hooks
                    setTimeout(() => {
                        course.value = data.course_id;
                    }, 50);

                    yearLevel.value = data.year_level;
                }

                // Scroll smoothly up to form panel
                userForm.scrollIntoView({ behavior: 'smooth' });
            });
        });

        // ================== SWEETALERT DETONATION LOGIC ==================
        document.querySelectorAll('.delete-user-btn').forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.dataset.id;
                const userName = this.dataset.name;
                const deleteForm = document.getElementById('global-delete-form');

                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to permanently delete ${userName}. This action cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete them!',
                    cancelButtonText: 'Cancel',
                    background: '#ffffff',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider',
                        cancelButton: 'px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {

                        deleteForm.action = `/superadmin/users/${userId}`;

                        deleteForm.submit();
                    }
                });
            });
        });

        // Cancel editing mode function reset state back cleanly
        cancelEditBtn.addEventListener('click', resetFormState);

        function resetFormState() {
            userForm.reset();
            formTitle.textContent = "Register User";
            submitBtn.textContent = "Register User";
            cancelEditBtn.classList.add('hidden');
            userForm.action = storeRoute;
            methodContainer.innerHTML = '';
            studentFields.classList.add('hidden');
            course.disabled = true;
            course.innerHTML = '<option value="">Select Department First</option>';
            inputPassword.required = true;
            inputPasswordConfirm.required = true;
            passwordLabel.textContent = "Password";
        }
    });
</script>