document.addEventListener('DOMContentLoaded', () => {
  const API_URL = 'http://localhost:8000/admin_api.php';

  const symptomForm = document.getElementById('symptomForm');
  const symptomIdInput = document.getElementById('symptomId');
  const symptomCodeInput = document.getElementById('symptomCode');
  const symptomDescInput = document.getElementById('symptomDesc');
  const symptomWeightInput = document.getElementById('symptomWeight');
  const saveSymptomBtn = document.getElementById('saveSymptomBtn');
  const cancelEditBtn = document.getElementById('cancelEditBtn');
  const symptomsTableBody = document.getElementById('symptomsTableBody');
  const historyTableBody = document.getElementById('historyTableBody');
  const downloadPdfBtn = document.getElementById('downloadPdfBtn');

  // -------------------------------------------------------------
  // 1. KELOLA GEJALA
  // -------------------------------------------------------------
  async function fetchSymptoms() {
    try {
      const res = await fetch(`${API_URL}?action=get_symptoms`);
      const result = await res.json();
      if (result.success) {
        renderSymptoms(result.data);
      }
    } catch (err) {
      console.error('Error fetching symptoms:', err);
    }
  }

  function renderSymptoms(symptoms) {
    symptomsTableBody.innerHTML = symptoms.map(s => `
      <tr>
        <td><b>${s.code}</b></td>
        <td>${s.description}</td>
        <td>${s.weight}</td>
        <td>
          <button class="btn btn-warning" onclick="editSymptom(${s.id}, '${s.code}', '${s.description}', ${s.weight})">Edit</button>
          <button class="btn btn-danger" onclick="deleteSymptom(${s.id})">Hapus</button>
        </td>
      </tr>
    `).join('');
  }

  window.editSymptom = (id, code, desc, weight) => {
    symptomIdInput.value = id;
    symptomCodeInput.value = code;
    symptomDescInput.value = desc;
    symptomWeightInput.value = weight;
    saveSymptomBtn.textContent = 'Update Gejala';
    cancelEditBtn.style.display = 'inline-block';
  };

  cancelEditBtn.addEventListener('click', () => {
    resetSymptomForm();
  });

  function resetSymptomForm() {
    symptomIdInput.value = '';
    symptomForm.reset();
    saveSymptomBtn.textContent = 'Tambah Gejala';
    cancelEditBtn.style.display = 'none';
  }

  symptomForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const id = symptomIdInput.value;
    const action = id ? 'update_symptom' : 'add_symptom';

    const payload = {
      id: id ? parseInt(id) : null,
      code: symptomCodeInput.value,
      description: symptomDescInput.value,
      weight: parseFloat(symptomWeightInput.value)
    };

    try {
      const res = await fetch(`${API_URL}?action=${action}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });
      const result = await res.json();
      if (result.success) {
        alert(result.message);
        resetSymptomForm();
        fetchSymptoms();
      } else {
        alert(result.message);
      }
    } catch (err) {
      console.error('Error saving symptom:', err);
    }
  });

  window.deleteSymptom = async (id) => {
    if (!confirm('Apakah Anda yakin ingin menghapus gejala ini?')) return;

    try {
      const res = await fetch(`${API_URL}?action=delete_symptom`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id })
      });
      const result = await res.json();
      if (result.success) {
        alert(result.message);
        fetchSymptoms();
      } else {
        alert(result.message);
      }
    } catch (err) {
      console.error('Error deleting symptom:', err);
    }
  };

  // -------------------------------------------------------------
  // 2. KELOLA RIWAYAT DIAGNOSA
  // -------------------------------------------------------------
  async function fetchHistory() {
    try {
      const res = await fetch(`${API_URL}?action=get_history`);
      const result = await res.json();
      if (result.success) {
        renderHistory(result.data);
      }
    } catch (err) {
      console.error('Error fetching history:', err);
    }
  }

  function renderHistory(history) {
    if (history.length === 0) {
      historyTableBody.innerHTML = '<tr><td colspan="7">Belum ada riwayat.</td></tr>';
      return;
    }

    historyTableBody.innerHTML = history.map(h => `
      <tr>
        <td>${h.id}</td>
        <td>${h.patient_name}</td>
        <td>${h.patient_age} th / ${h.patient_gender}</td>
        <td><b>${h.disease}</b></td>
        <td>${h.level_name} (${h.score}%)</td>
        <td><small>${h.created_at}</small></td>
        <td class="no-print">
          <button class="btn btn-danger" onclick="deleteHistory(${h.id})">Hapus</button>
        </td>
      </tr>
    `).join('');
  }

  window.deleteHistory = async (id) => {
    if (!confirm('Apakah Anda yakin ingin menghapus riwayat diagnosa ini?')) return;

    try {
      const res = await fetch(`${API_URL}?action=delete_history`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id })
      });
      const result = await res.json();
      if (result.success) {
        alert(result.message);
        fetchHistory();
      } else {
        alert(result.message);
      }
    } catch (err) {
      console.error('Error deleting history:', err);
    }
  };

  // -------------------------------------------------------------
  // 3. MENDOWNLOAD LAPORAN RIWAYAT KE PDF
  // -------------------------------------------------------------
  downloadPdfBtn.addEventListener('click', () => {
    const element = document.getElementById('pdfExportArea');
    const pdfTitle = document.getElementById('pdfTitle');
    
    // Tampilkan judul untuk dokumen PDF
    pdfTitle.style.display = 'block';

    // Sembunyikan kolom aksi 'Hapus' pada file PDF
    const noPrintElems = document.querySelectorAll('.no-print');
    noPrintElems.forEach(el => el.style.display = 'none');

    const opt = {
      margin:       0.5,
      filename:     `Laporan_Riwayat_Diagnosa_${new Date().toISOString().slice(0,10)}.pdf`,
      image:        { type: 'jpeg', quality: 0.98 },
      html2canvas:  { scale: 2 },
      jsPDF:        { unit: 'in', format: 'a4', orientation: 'landscape' }
    };

    html2pdf().set(opt).from(element).save().then(() => {
      // Tampilkan kembali tombol setelah file selesai diekspor
      noPrintElems.forEach(el => el.style.display = '');
      pdfTitle.style.display = 'none';
    });
  });

  // Inisialisasi awal
  fetchSymptoms();
  fetchHistory();
}); 