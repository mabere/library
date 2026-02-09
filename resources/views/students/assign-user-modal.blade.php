<div class="modal fade"
     id="assignUserModal-{{ $student->id }}"
     tabindex="-1"
     data-bs-backdrop="static"
     data-bs-keyboard="false">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-0 border-0">

            {{-- WRAPPER SENDIRI --}}
            <div class="modal-box">

                <form method="POST"
                      action="{{ route('admin.students.assign_user', $student) }}">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">
                            Buat Akun Mahasiswa
                        </h5>
                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <p>
                            Akun untuk:
                            <strong>{{ $student->nama }}</strong>
                            ({{ $student->nim }})
                        </p>

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email"
                                   name="email"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label>Password (opsional)</label>
                            <input type="password"
                                   name="password"
                                   class="form-control">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary"
                                data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button class="btn btn-primary">
                            Buat Akun
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
