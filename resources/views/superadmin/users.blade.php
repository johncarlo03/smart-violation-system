<x-app-layout>
    <div class="min-h-screen bg-gray-50 flex flex-col md:flex-row">

        @include('layouts.superadmin-nav')

        <div class="flex-1 flex flex-col min-w-0">
            <main class="p-4 sm:p-6 lg:p-10">

                <div class="max-w-7xl mx-auto">

                    {{-- Header --}}
                    <div class="mb-6 sm:mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="min-w-0">
                            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">
                                User Management
                            </h2>
                            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                                Create, view, and modify system users and role based access.
                            </p>
                        </div>

                        {{-- Mobile Modal Trigger (Visible below md) --}}
                        <button type="button" id="open-mobile-user-modal-btn"
                            class="md:hidden w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#1f2937] hover:bg-gray-800 text-white rounded-xl text-xs font-black uppercase tracking-wider shadow-xs transition shrink-0">
                            <span>+ Register User</span>
                        </button>
                    </div>

                    {{-- Flash Success --}}
                    @if (session('success'))
                        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 shadow-xs">
                            <div class="w-8 h-8 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-700 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <p class="text-xs sm:text-sm font-semibold text-emerald-900 break-words">{{ session('success') }}</p>
                        </div>
                    @endif

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl text-xs sm:text-sm text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <p class="break-words">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    {{-- Register / Edit User Card (Inline on md+, moved into #user-modal-body on mobile) --}}
                    <div id="user-form-card" class="w-full bg-white rounded-2xl shadow-xs border border-gray-200/80 overflow-hidden mb-6 sm:mb-10">
                        <div class="h-1 w-full bg-gradient-to-r from-red-700 to-amber-500"></div>
                        <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-100 bg-gray-50/70 flex flex-wrap justify-between items-center gap-3">
                            <h3 id="form-title" class="text-base sm:text-lg font-black text-gray-900">
                                Register User
                            </h3>
                            <div class="flex items-center gap-2">
                                <button type="button" id="cancel-edit-btn"
                                    class="hidden px-3.5 py-2 rounded-xl border border-gray-300 bg-white hover:bg-gray-100 text-gray-700 text-xs font-bold uppercase tracking-wider transition">
                                    Cancel Edit
                                </button>
                                <button type="button" id="close-mobile-user-modal-btn"
                                    class="md:hidden w-8 h-8 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 flex items-center justify-center text-xl leading-none transition"
                                    aria-label="Close modal">&times;</button>
                            </div>
                        </div>

                        <form id="user-form" action="{{ route('superadmin.users.store') }}" method="POST" class="p-4 sm:p-6 space-y-6">
                            @csrf
                            <div id="method-container"></div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                <div class="min-w-0">
                                    <label class="block text-xs font-black uppercase tracking-wider text-gray-600 mb-2">
                                        Full Name
                                    </label>
                                    <input type="text" name="name" id="input-name" value="{{ old('name') }}" required
                                        class="w-full rounded-xl border-gray-200 text-base sm:text-sm font-medium text-gray-800 shadow-xs focus:border-[#1f2937] focus:ring-[#1f2937] transition duration-200">
                                    @error('name')
                                        <p class="text-xs font-bold text-red-700 mt-1.5 pl-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="min-w-0">
                                    <label class="block text-xs font-black uppercase tracking-wider text-gray-600 mb-2">
                                        ID Number:
                                    </label>
                                    <input type="number" name="id_number" id="input-id_number"
                                        value="{{ old('id_number') }}" required
                                        class="w-full rounded-xl border-gray-200 text-base sm:text-sm font-medium text-gray-800 shadow-xs focus:border-[#1f2937] focus:ring-[#1f2937] transition duration-200">
                                    @error('id_number')
                                        <p class="text-xs font-bold text-red-700 mt-1.5 pl-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="min-w-0">
                                    <label class="block text-xs font-black uppercase tracking-wider text-gray-600 mb-2">
                                        RFID (if applicable)
                                    </label>
                                    <input type="number" name="rfid_number" id="input-rfid_number"
                                        value="{{ old('rfid_number') }}"
                                        class="w-full rounded-xl border-gray-200 text-base sm:text-sm font-medium text-gray-800 shadow-xs focus:border-[#1f2937] focus:ring-[#1f2937] transition duration-200">
                                    @error('rfid_number')
                                        <p class="text-xs font-bold text-red-700 mt-1.5 pl-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Institutional Email Address Input Field --}}
                                <div class="min-w-0">
                                    <label class="block text-xs font-black uppercase tracking-wider text-gray-600 mb-2">
                                        Email
                                    </label>
                                    <input type="email" name="email" id="input-email" value="{{ old('email') }}" required
                                        class="w-full rounded-xl border-gray-200 text-base sm:text-sm font-medium text-gray-800 shadow-xs focus:border-[#1f2937] focus:ring-[#1f2937] transition duration-200">
                                    @error('email')
                                        <p class="text-xs font-bold text-red-700 mt-1.5 pl-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                {{-- Account Security Access Password Input --}}
                                <div class="min-w-0">
                                    <label id="password-label"
                                        class="block text-xs font-black uppercase tracking-wider text-gray-600 mb-2">
                                        Password
                                    </label>
                                    <input type="password" name="password" id="input-password" required
                                        class="w-full rounded-xl border-gray-200 text-base sm:text-sm font-medium text-gray-800 shadow-xs focus:border-[#1f2937] focus:ring-[#1f2937] transition duration-200">
                                    @error('password')
                                        <p class="text-xs font-bold text-red-700 mt-1.5 pl-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Confirm Password Input Field --}}
                                <div class="min-w-0">
                                    <label id="password-confirm-label"
                                        class="block text-xs font-black uppercase tracking-wider text-gray-600 mb-2">
                                        Confirm Password
                                    </label>
                                    <input type="password" name="password_confirmation" id="input-password_confirmation"
                                        required
                                        class="w-full rounded-xl border-gray-200 text-base sm:text-sm font-medium text-gray-800 shadow-xs focus:border-[#1f2937] focus:ring-[#1f2937] transition duration-200">
                                </div>
                            </div>

                            <hr class="border-gray-100">

                            {{-- Structural Role Assignment Radio Nodes --}}
                            <div>
                                <label class="block text-xs font-black uppercase tracking-wider text-gray-600 mb-3">
                                    User Roles
                                </label>

                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-7 gap-3 sm:gap-4">
                                    <label
                                        class="relative flex items-start p-3.5 sm:p-4 rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer transition duration-200 min-w-0">
                                        <div class="flex items-center h-5 shrink-0">
                                            <input type="radio" name="role" value="1" {{ old('role') == '1' ? 'checked' : '' }} class="h-4 w-4 text-[#1f2937] border-gray-300 focus:ring-[#1f2937]">
                                        </div>
                                        <div class="ml-3 min-w-0">
                                            <span class="block text-xs font-black uppercase tracking-wider text-[#1f2937]">Student</span>
                                            <span class="block text-[11px] font-medium text-gray-400 mt-0.5">Logs incidents & data</span>
                                        </div>
                                    </label>

                                    <label
                                        class="relative flex items-start p-3.5 sm:p-4 rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer transition duration-200 min-w-0">
                                        <div class="flex items-center h-5 shrink-0">
                                            <input type="radio" name="role" value="2" {{ old('role') == '2' ? 'checked' : '' }} class="h-4 w-4 text-amber-600 border-gray-300 focus:ring-amber-500">
                                        </div>
                                        <div class="ml-3 min-w-0">
                                            <span class="block text-xs font-black uppercase tracking-wider text-amber-600">CSO Officer</span>
                                            <span class="block text-[11px] font-medium text-gray-400 mt-0.5">Logs incidents & data</span>
                                        </div>
                                    </label>

                                    <label
                                        class="relative flex items-start p-3.5 sm:p-4 rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer transition duration-200 min-w-0">
                                        <div class="flex items-center h-5 shrink-0">
                                            <input type="radio" name="role" value="6" {{ old('role') == '6' ? 'checked' : '' }} class="h-4 w-4 text-amber-600 border-gray-300 focus:ring-amber-500">
                                        </div>
                                        <div class="ml-3 min-w-0">
                                            <span class="block text-xs font-black uppercase tracking-wider text-amber-600">Chairman</span>
                                            <span class="block text-[11px] font-medium text-gray-400 mt-0.5">Logs incidents & data</span>
                                        </div>
                                    </label>

                                    <label
                                        class="relative flex items-start p-3.5 sm:p-4 rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer transition duration-200 min-w-0">
                                        <div class="flex items-center h-5 shrink-0">
                                            <input type="radio" name="role" value="7" {{ old('role') == '7' ? 'checked' : '' }} class="h-4 w-4 text-amber-600 border-gray-300 focus:ring-amber-500">
                                        </div>
                                        <div class="ml-3 min-w-0">
                                            <span class="block text-xs font-black uppercase tracking-wider text-amber-600">Dean</span>
                                            <span class="block text-[11px] font-medium text-gray-400 mt-0.5">Logs incidents & data</span>
                                        </div>
                                    </label>

                                    <label
                                        class="relative flex items-start p-3.5 sm:p-4 rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer transition duration-200 min-w-0">
                                        <div class="flex items-center h-5 shrink-0">
                                            <input type="radio" name="role" value="5" {{ old('role') == '5' ? 'checked' : '' }} class="h-4 w-4 text-amber-600 border-gray-300 focus:ring-amber-500">
                                        </div>
                                        <div class="ml-3 min-w-0">
                                            <span class="block text-xs font-black uppercase tracking-wider text-amber-600">Teaching Staff</span>
                                            <span class="block text-[11px] font-medium text-gray-400 mt-0.5">Logs incidents & data</span>
                                        </div>
                                    </label>

                                    <label
                                        class="relative flex items-start p-3.5 sm:p-4 rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer transition duration-200 min-w-0">
                                        <div class="flex items-center h-5 shrink-0">
                                            <input type="radio" name="role" value="3" {{ old('role') == '3' ? 'checked' : '' }} class="h-4 w-4 text-emerald-700 border-gray-300 focus:ring-emerald-600">
                                        </div>
                                        <div class="ml-3 min-w-0">
                                            <span class="block text-xs font-black uppercase tracking-wider text-emerald-700">SAO Faculty</span>
                                            <span class="block text-[11px] font-medium text-gray-400 mt-0.5">Manages system scopes</span>
                                        </div>
                                    </label>

                                    <label
                                        class="relative flex items-start p-3.5 sm:p-4 rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer transition duration-200 min-w-0">
                                        <div class="flex items-center h-5 shrink-0">
                                            <input type="radio" name="role" value="4" {{ old('role') == '4' ? 'checked' : '' }} class="h-4 w-4 text-red-700 border-gray-300 focus:ring-red-700">
                                        </div>
                                        <div class="ml-3 min-w-0">
                                            <span class="block text-xs font-black uppercase tracking-wider text-red-700">Super Admin</span>
                                            <span class="block text-[11px] font-medium text-gray-400 mt-0.5">Complete root control</span>
                                        </div>
                                    </label>
                                </div>
                                @error('role')
                                    <p class="text-xs font-bold text-red-700 mt-2 pl-1">{{ $message }}</p>
                                @enderror

                                <div id="student-fields" class="hidden mt-6">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                                        <div id="department-field" class="min-w-0">
                                            <label
                                                class="block text-xs font-black uppercase tracking-wider text-gray-500 mb-2">
                                                Department
                                            </label>
                                            <select id="department" name="department_id"
                                                class="w-full rounded-xl border-gray-200 text-base sm:text-sm text-gray-800 focus:border-[#1f2937] focus:ring-[#1f2937]">
                                                <option value="">Select Department</option>
                                                @foreach($departments as $department)
                                                    <option
                                                        value="{{ $department->id }}"
                                                        {{ old('department_id') == $department->id ? 'selected' : '' }}
                                                    >
                                                        {{ $department->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div id="student-extra-fields" class="col-span-1 md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                            <div class="min-w-0">
                                                <label
                                                    class="block text-xs font-black uppercase tracking-wider text-gray-500 mb-2">
                                                    Course
                                                </label>
                                                <select id="course" name="course_id" disabled
                                                    class="w-full rounded-xl border-gray-200 text-base sm:text-sm text-gray-800 focus:border-[#1f2937] focus:ring-[#1f2937]">
                                                    <option value="">Select Department First</option>
                                                </select>
                                            </div>
                                            <div class="min-w-0">
                                                <label
                                                    class="block text-xs font-black uppercase tracking-wider text-gray-500 mb-2">
                                                    Year Level
                                                </label>
                                                <select id="year_level" name="year_level"
                                                    class="w-full rounded-xl border-gray-200 text-base sm:text-sm text-gray-800 focus:border-[#1f2937] focus:ring-[#1f2937]">
                                                    <option value="">Select Year</option>
                                                    @foreach([1 => '1st Year', 2 => '2nd Year', 3 => '3rd Year', 4 => '4th Year'] as $value => $label)
                                                        <option value="{{ $value }}" {{ old('year_level') == $value ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Interface Commit Form Submission Panel --}}
                            <div class="pt-4 border-t border-gray-100 flex justify-end">
                                <button type="submit" id="submit-btn"
                                    class="w-full sm:w-auto px-6 py-3 bg-[#1f2937] hover:bg-gray-800 text-white rounded-xl text-xs font-black uppercase tracking-widest transition duration-200 shadow-xs active:scale-[0.99]">
                                    Register User
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Anchor & Wrapper for Moving Form Card on Mobile --}}
                    <div id="desktop-user-form-slot" class="hidden md:block"></div>

                    {{-- Mobile Dialog Host for #user-form-card --}}
                    <dialog id="user-form-modal"
                        class="backdrop:bg-gray-900/60 rounded-2xl p-0 w-[calc(100%-1.5rem)] max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl border border-gray-200/80 m-auto bg-transparent">
                        <div id="mobile-user-form-slot"></div>
                    </dialog>

                    {{-- Active Users Table Container --}}
                    <div class="w-full bg-white rounded-2xl shadow-xs border border-gray-200/80 overflow-hidden">
                        <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-100 bg-gray-50/70">
                            <h3 class="text-base sm:text-lg font-black text-gray-900">Active Users</h3>
                        </div>

                        <div class="min-w-0">
                            <livewire:user-table />
                        </div>
                    </div>

                </div>

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
        const userFormCard = document.getElementById('user-form-card');
        const desktopSlot = document.getElementById('desktop-user-form-slot');
        const mobileSlot = document.getElementById('mobile-user-form-slot');
        const mobileModal = document.getElementById('user-form-modal');
        const openMobileBtn = document.getElementById('open-mobile-user-modal-btn');
        const closeMobileBtn = document.getElementById('close-mobile-user-modal-btn');
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
        const storeRoute = "{{ route('superadmin.users.store') }}";
        const mdQuery = window.matchMedia('(min-width: 768px)');

        function syncFormPlacement() {
            if (!userFormCard || !desktopSlot || !mobileSlot) return;

            if (mdQuery.matches) {
                if (mobileModal && mobileModal.open) {
                    mobileModal.close();
                }
                userFormCard.classList.remove('mb-0');
                userFormCard.classList.add('mb-10');
                desktopSlot.appendChild(userFormCard);
            } else {
                userFormCard.classList.remove('mb-10');
                userFormCard.classList.add('mb-0');
                mobileSlot.appendChild(userFormCard);
            }
        }

        function openMobileFormModal() {
            syncFormPlacement();
            if (!mdQuery.matches && mobileModal && !mobileModal.open) {
                mobileModal.showModal();
            }
        }

        function closeMobileFormModal() {
            if (mobileModal && mobileModal.open) {
                mobileModal.close();
            }
        }

        syncFormPlacement();
        mdQuery.addEventListener('change', syncFormPlacement);

        if (openMobileBtn) {
            openMobileBtn.addEventListener('click', function () {
                resetFormState();
                openMobileFormModal();
            });
        }

        if (closeMobileBtn) {
            closeMobileBtn.addEventListener('click', closeMobileFormModal);
        }

        if (mobileModal) {
            mobileModal.addEventListener('click', function (event) {
                if (event.target === mobileModal) {
                    closeMobileFormModal();
                }
            });
        }

        function toggleRoleFields(roleValue) {
            const extra = document.getElementById('student-extra-fields');
            const isStudent = roleValue == '1';
            const needsDepartment = isStudent || ['1', '5', '6', '7'].includes(String(roleValue));

            studentFields.classList.toggle('hidden', !needsDepartment);
            extra.classList.toggle('hidden', !isStudent);

            department.disabled = !needsDepartment;
            if (!needsDepartment) department.value = '';

            yearLevel.disabled = !isStudent;
            if (!isStudent) {
                yearLevel.value = '';
                course.value = '';
                course.disabled = true;
            }
        }

        roleRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                toggleRoleFields(this.value);
            });
        });

        function loadCourses(departmentId, selectedCourseId = null) {
            course.innerHTML = '';

            if (!departmentId) {
                course.disabled = true;
                course.innerHTML = '<option value="">Select Department First</option>';
                return;
            }

            course.disabled = false;
            course.innerHTML = '<option value="">Select Course</option>';

            (courses[departmentId] || []).forEach(function (item) {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                if (selectedCourseId && item.id == selectedCourseId) {
                    option.selected = true;
                }
                course.appendChild(option);
            });
        }

        department.addEventListener('change', function () {
            loadCourses(this.value);
        });

        document.addEventListener('click', function (event) {
            const button = event.target.closest('.edit-user-btn');
            if (!button) return;

            const data = button.dataset;

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

            roleRadios.forEach(radio => {
                if (radio.value == data.role) {
                    radio.checked = true;
                    toggleRoleFields(data.role);
                }
            });

            if (['1', '5', '6', '7'].includes(String(data.role))) {
                department.value = data.department_id;
            }

            if (data.role == '1') {
                loadCourses(data.department_id, data.course_id);
                yearLevel.value = data.year_level;
            }

            if (!mdQuery.matches) {
                openMobileFormModal();
            } else {
                userForm.scrollIntoView({ behavior: 'smooth' });
            }
        });

        document.addEventListener('click', function (event) {
            const button = event.target.closest('.delete-user-btn');
            if (!button) return;

            const userId = button.dataset.id;
            const userName = button.dataset.name;
            const deleteForm = document.getElementById('global-delete-form');

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to permanently delete ${userName}. This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#b91c1c',
                cancelButtonColor: '#4b5563',
                confirmButtonText: 'Yes, delete them!',
                cancelButtonText: 'Cancel',
                background: '#ffffff',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider',
                    cancelButton: 'px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider'
                }
            }).then((result) => {
                if (result.isConfirmed && deleteForm) {
                    deleteForm.action = `/superadmin/users/${userId}`;
                    deleteForm.submit();
                }
            });
        });

        cancelEditBtn.addEventListener('click', function () {
            resetFormState();
            closeMobileFormModal();
        });

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

        const oldRole = "{{ old('role') }}";
        const oldDepartment = "{{ old('department_id') }}";
        const oldCourse = "{{ old('course_id') }}";
        const hasValidationOrOldInput = {{ ($errors->any() || old('name') || old('email') || old('role')) ? 'true' : 'false' }};

        if (oldRole) {
            toggleRoleFields(oldRole);
        }

        if (oldRole == '1' && oldDepartment) {
            loadCourses(oldDepartment, oldCourse);
        }

        if (!mdQuery.matches && hasValidationOrOldInput) {
            openMobileFormModal();
        }
    });
</script>