<div class="modal periode" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ URL::full() }}" method="get">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Periode</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">

                            {{-- Tahun --}}
                            <div class="form-group mb-3">
                                <small>Tahun</small>
                                <select name="tahun" class="form-control" id="tahunSelect" required>
                                    <option value="">- pilih tahun -</option>
                                    @for($i = 2022; $i <= date('Y'); $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            {{-- Jenis Periode --}}
                            <div class="form-group mb-3">
                                <small>Jenis Periode</small>
                                <select name="jenis_periode" id="jenisPeriode" class="form-control" disabled>
                                    <option value="">- pilih jenis periode -</option>
                                    <option value="tahun">Tahunan</option>
                                    <option value="semester">Semester</option>
                                    <option value="triwulan">Triwulan</option>
                                    <option value="bulan">Bulanan</option>
                                </select>
                            </div>

                            {{-- Periode (satu field saja) --}}
                            <div class="form-group mb-3 d-none" id="periodeField">
                                <small>Pilih Periode</small>
                                <select name="periode" class="form-control" id="periodeSelect">
                                    <option value="">- pilih -</option>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Lihat Hasil</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const tahunSelect = document.getElementById('tahunSelect');
        const jenisPeriode = document.getElementById('jenisPeriode');
        const periodeField = document.getElementById('periodeField');
        const periodeSelect = document.getElementById('periodeSelect');

        tahunSelect.addEventListener('change', function () {
            if (this.value) {
                jenisPeriode.removeAttribute('disabled');
            } else {
                jenisPeriode.setAttribute('disabled', true);
                jenisPeriode.value = '';
                periodeField.classList.add('d-none');
            }
        });

        jenisPeriode.addEventListener('change', function () {
            const type = this.value;
            periodeSelect.innerHTML = '<option value="">- pilih -</option>';

            if (!type || type === 'tahun') {
                periodeField.classList.add('d-none');
                return;
            }

            periodeField.classList.remove('d-none');

            let options = '';

            if (type === 'semester') {
                options += '<option value="1">Semester I (Jan - Jun)</option>';
                options += '<option value="7">Semester II (Jul - Des)</option>';
            }

            if (type === 'triwulan') {
                options += '<option value="1">Triwulan I (Jan - Mar)</option>';
                options += '<option value="4">Triwulan II (Apr - Jun)</option>';
                options += '<option value="7">Triwulan III (Jul - Sep)</option>';
                options += '<option value="10">Triwulan IV (Okt - Des)</option>';
            }

            if (type === 'bulan') {
                const bulan = {
                    1: 'Januari', 2: 'Februari', 3: 'Maret', 4: 'April', 5: 'Mei', 6: 'Juni',
                    7: 'Juli', 8: 'Agustus', 9: 'September', 10: 'Oktober', 11: 'November', 12: 'Desember'
                };
                for (let i = 1; i <= 12; i++) {
                    options += `<option value="${i}">${bulan[i]}</option>`;
                }
            }

            periodeSelect.innerHTML += options;
        });
    });
</script>