@extends('layouts.app')

@section('content')
<div class="bg-white p-6">
    <div class="h-fit md:ml-40 md:justify-around md:items-start mt-0 mb-10 gap-2">
        <h2 class="text-xl font-bold text-green-400 mb-4">Kelola Anggota HMPS: {{ $hmps->nama }}</h2>

        <!-- Button to Add Members -->
        <div class="my-4">
            <button id="add-member-btn"
                class="bg-purple-500 text-white px-3 py-1 rounded hover:bg-purple-600 add-member-btn">
                Tambah Anggota
            </button>
        </div>

        @if(session('error'))
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
        @endif

        <!-- Current Members Table -->
        <div class="overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-white uppercase bg-purple-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nama</th>
                        <th scope="col" class="px-6 py-3">NIM</th>
                        <th scope="col" class="px-6 py-3">Jabatan</th>
                        <th scope="col" class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($hmps->members as $member)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ $member->nama }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $member->nim }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $member->pivot->position ?? 'Tidak Ditentukan' }}
                        </td>
                        <td class="px-6 py-4 flex space-x-2">
                            <button type="button"
                                class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 edit-member-btn"
                                data-id="{{ $member->id }}" data-name="{{ $member->nama }}"
                                data-nim="{{ $member->nim }}" data-jabatan="{{ $member->pivot->position ?? '' }}">
                                Edit
                            </button>

                            <form action="{{ route('hmps.members.remove', [$hmps->id, $member->id]) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus anggota ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr class="bg-white border-b">
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada anggota saat ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Member Modal -->
    <div id="add-member-modal" class="hidden h-fit md:ml-40 md:justify-center md:items-center mt-0 mb-10 gap-2">
        <div class="bg-white p-6 rounded shadow-lg w-[600px] max-h-[80vh] overflow-y-auto">
            <h3 class="text-lg font-bold text-gray-700 mb-4">Tambah Anggota Baru</h3>
            <form action="{{ route('hmps.members.add', $hmps->id) }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label for="searchMahasiswa" class="block text-sm font-bold mb-2">Cari Mahasiswa</label>
                    <input type="text" id="searchMahasiswa"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                        placeholder="Cari mahasiswa berdasarkan nama/NIM">
                </div>
                <div class="overflow-x-auto max-h-[300px]">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-white uppercase bg-purple-400 sticky top-0">
                            <tr>
                                <th class="px-6 py-3">NIM</th>
                                <th class="px-6 py-3">Nama</th>
                                <th class="px-6 py-3">Prodi</th>
                                <th class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="mahasiswaTable">
                            <!-- Search results will be populated here -->
                        </tbody>
                    </table>
                </div>

                <div id="selectedMahasiswaContainer" class="mt-6">
                    <h4 class="text-lg font-bold text-gray-700 mb-4">Mahasiswa yang Dipilih</h4>
                    <div id="selectedMahasiswaForms"></div>
                </div>

                <div class="flex justify-end space-x-2 mt-5">
                    <button type="button" id="cancel-add-member"
                        class="bg-purple-500 text-white px-3 py-1 rounded hover:bg-purple-600 ">Batal</button>
                    <button type="submit"
                        class="bg-purple-500 text-white px-3 py-1 rounded hover:bg-purple-600 ">Tambah</button>
                </div>
            </form>
        </div>
    </div>

    <div id="edit-member-modal" class="hidden h-fit md:ml-40 md:justify-center md:items-center mt-0 mb-10 gap-2">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Edit Member Position</h3>
                <form id="edit-member-form" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                        <p id="edit-member-name" class="text-gray-600"></p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">NIM:</label>
                        <p id="edit-member-nim" class="text-gray-600"></p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Jabatan:</label>
                        <input type="text" name="position"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            placeholder="Masukan jabatan baru">

                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <button type="button" id="cancel-edit-member"
                            class="bg-purple-500 text-white px-4 py-2 rounded mr-2 hover:bg-purple-600">
                            batal
                        </button>
                        <button type="submit" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600">
                            Perbarui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative h-fit md:ml-40"
        role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none';">
            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20">
                <path
                    d="M14.348 5.652a1 1 0 00-1.414-1.414L10 7.586 7.066 4.652a1 1 0 00-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 101.414 1.414L10 12.414l2.934 2.934a1 1 0 001.414-1.414L11.414 10l2.934-2.934z" />
            </svg>
        </span>
    </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
    // Modal Elements
    const addMemberBtn = document.getElementById('add-member-btn');
    const addMemberModal = document.getElementById('add-member-modal');
    const cancelAddMember = document.getElementById('cancel-add-member');
    const selectedMahasiswaForms = document.getElementById('selectedMahasiswaForms');
    const editMemberModal = document.getElementById('edit-member-modal');
    const editMemberForm = document.getElementById('edit-member-form');
    const cancelEditMember = document.getElementById('cancel-edit-member');
    const editMemberName = document.getElementById('edit-member-name');
    const editMemberNim = document.getElementById('edit-member-nim');

    // Add Member Modal Functions
    addMemberBtn.addEventListener('click', () => {
    addMemberModal.classList.remove('hidden'); // Show add modal
    editMemberModal.classList.add('hidden');   // Hide edit modal
});
    cancelAddMember.addEventListener('click', () => {
        addMemberModal.classList.add('hidden');

    });

    // Close add modal on outside click
    addMemberModal.addEventListener('click', (e) => {
        if (e.target === addMemberModal) {
            addMemberModal.classList.add('hidden');
        }
    });

    // Search Functionality
    document.getElementById('searchMahasiswa').addEventListener('keyup', debounce(function() {
        const search = this.value.trim();
        const hmpsId = window.location.pathname.split('/')[2];

        fetch(`/hmps/${hmpsId}/search-mahasiswa?search=${encodeURIComponent(search)}`)
            .then(response => response.json())
            .then(data => {
                let mahasiswaTable = document.getElementById('mahasiswaTable');
                mahasiswaTable.innerHTML = '';

                if (data.length === 0) {
                    mahasiswaTable.innerHTML = `
                        <tr>
                            <td colspan="4" class="text-center py-4">Tidak ada hasil ditemukan.</td>
                        </tr>`;
                } else {
                    data.forEach(mahasiswa => {
                        mahasiswaTable.innerHTML += `
                            <tr class="hover:bg-gray-100">
                                <td class="px-6 py-3">${mahasiswa.nim}</td>
                                <td class="px-6 py-3">${mahasiswa.nama}</td>
                                <td class="px-6 py-3">${mahasiswa.prodi}</td>
                                <td class="px-6 py-3">
                                    <button type="button"
                                        class="bg-purple-500 text-white px-4 py-1 rounded-lg hover:bg-purple-600 select-mahasiswa"
                                        data-id="${mahasiswa.id}"
                                        data-nama="${mahasiswa.nama}">
                                        Pilih
                                    </button>
                                </td>
                            </tr>`;
                    });

                    // Add event listeners to new select buttons
                    attachSelectMahasiswaListeners();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mencari mahasiswa.');
            });
    }, 300));

    function attachSelectMahasiswaListeners() {
    document.querySelectorAll('.select-mahasiswa').forEach(button => {
        button.addEventListener('click', function() {
            const mahasiswaId = this.dataset.id;
            const mahasiswaNama = this.dataset.nama;

            const formHtml = `
                <div class="p-4 bg-gray-100 rounded mb-4">
                    <p class="text-gray-800 text-sm mb-2">Nama: ${mahasiswaNama}</p>
                    <input type="hidden" name="members[${mahasiswaId}][id]" value="${mahasiswaId}">
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Position</label>
                        <input type="text"
                            name="members[${mahasiswaId}][position]"
                            class="w-full border rounded px-3 py-2"
                            placeholder="Enter position"
                            required>
                    </div>
                </div>`;

            document.getElementById('selectedMahasiswaForms').insertAdjacentHTML('beforeend', formHtml);
            this.closest('tr').remove();
        });
    });
}

document.querySelector('form').addEventListener('submit', function(e) {
    const selectedMembers = document.getElementById('selectedMahasiswaForms').children;
    if (selectedMembers.length === 0) {
        e.preventDefault();
        alert('Please select at least one member to add');
        return;
    }
});

    // Edit Member Modal Functions
    document.querySelectorAll('.edit-member-btn').forEach(button => {
    button.addEventListener('click', function() {
        const memberId = this.dataset.id;
        const memberName = this.dataset.name;
        const memberNim = this.dataset.nim;
        const memberPosition = this.dataset.position;
        const hmpsId = window.location.pathname.split('/')[2];
        // Update modal content
        document.getElementById('edit-member-name').textContent = memberName;
        document.getElementById('edit-member-nim').textContent = memberNim;


        // Update form action URL
        const form = document.getElementById('edit-member-form');
        form.action = `/hmps/${hmpsId}/members/${memberId}/update`;

        // Show modal
        document.getElementById('edit-member-modal').classList.remove('hidden');
    });
});

    // Close edit modal
    cancelEditMember.addEventListener('click', () => {
        editMemberModal.classList.add('hidden');
    });

    // Close edit modal on outside click
    editMemberModal.addEventListener('click', (e) => {
        if (e.target === editMemberModal) {
            editMemberModal.classList.add('hidden');
        }
    });

    // Handle edit form submission

    // Utility Functions
    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    // Handle form validation
    function validateForm(form) {
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('border-red-500');

                // Add error message if it doesn't exist
                if (!field.nextElementSibling?.classList.contains('error-message')) {
                    const errorMessage = document.createElement('p');
                    errorMessage.className = 'text-red-500 text-xs mt-1 error-message';
                    errorMessage.textContent = 'Field ini harus diisi';
                    field.parentNode.insertBefore(errorMessage, field.nextSibling);
                }
            } else {
                field.classList.remove('border-red-500');
                const errorMessage = field.nextElementSibling;
                if (errorMessage?.classList.contains('error-message')) {
                    errorMessage.remove();
                }
            }
        });

        return isValid;
    }

    // Add validation to all forms
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
            }
        });
    });

    // Handle success message dismissal
    document.querySelectorAll('[role="alert"]').forEach(alert => {
        const closeButton = alert.querySelector('svg[role="button"]');
        if (closeButton) {
            closeButton.addEventListener('click', () => {
                alert.remove();
            });
        }
    });
});
</script>
@endsection
