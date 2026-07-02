const questions = [
  { id: 'q1', text: 'Jantung berdetak cepat atau berdebar.', weights: { 'Generalized Anxiety Disorder': 0.05, 'Social Anxiety': 0.03, 'Panic Disorder': 0.09, 'Adjustment Anxiety': 0.04 } },
  { id: 'q2', text: 'Keringat berlebih.', weights: { 'Generalized Anxiety Disorder': 0.04, 'Social Anxiety': 0.03, 'Panic Disorder': 0.08, 'Adjustment Anxiety': 0.04 } },
  { id: 'q3', text: 'Gemetar atau tremor.', weights: { 'Generalized Anxiety Disorder': 0.04, 'Social Anxiety': 0.03, 'Panic Disorder': 0.08, 'Adjustment Anxiety': 0.03 } },
  { id: 'q4', text: 'Sesak napas.', weights: { 'Generalized Anxiety Disorder': 0.04, 'Social Anxiety': 0.03, 'Panic Disorder': 0.08, 'Adjustment Anxiety': 0.03 } },
  { id: 'q5', text: 'Pusing atau sakit kepala.', weights: { 'Generalized Anxiety Disorder': 0.04, 'Social Anxiety': 0.03, 'Panic Disorder': 0.06, 'Adjustment Anxiety': 0.04 } },
  { id: 'q6', text: 'Mual atau gangguan perut.', weights: { 'Generalized Anxiety Disorder': 0.04, 'Social Anxiety': 0.03, 'Panic Disorder': 0.06, 'Adjustment Anxiety': 0.04 } },
  { id: 'q7', text: 'Otot tegang atau kaku.', weights: { 'Generalized Anxiety Disorder': 0.05, 'Social Anxiety': 0.03, 'Panic Disorder': 0.05, 'Adjustment Anxiety': 0.05 } },
  { id: 'q8', text: 'Kelelahan berlebih.', weights: { 'Generalized Anxiety Disorder': 0.05, 'Social Anxiety': 0.03, 'Panic Disorder': 0.04, 'Adjustment Anxiety': 0.06 } },
  { id: 'q9', text: 'Sulit tidur (insomnia).', weights: { 'Generalized Anxiety Disorder': 0.06, 'Social Anxiety': 0.03, 'Panic Disorder': 0.05, 'Adjustment Anxiety': 0.05 } },
  { id: 'q10', text: 'Khawatir berlebihan tentang berbagai hal.', weights: { 'Generalized Anxiety Disorder': 0.08, 'Social Anxiety': 0.04, 'Panic Disorder': 0.03, 'Adjustment Anxiety': 0.05 } },
  { id: 'q11', text: 'Sulit berkonsentrasi atau pikiran kosong.', weights: { 'Generalized Anxiety Disorder': 0.08, 'Social Anxiety': 0.04, 'Panic Disorder': 0.03, 'Adjustment Anxiety': 0.04 } },
  { id: 'q12', text: 'Pikiran negatif terus-menerus.', weights: { 'Generalized Anxiety Disorder': 0.08, 'Social Anxiety': 0.04, 'Panic Disorder': 0.03, 'Adjustment Anxiety': 0.05 } },
  { id: 'q13', text: 'Takut dinilai negatif oleh orang lain.', weights: { 'Generalized Anxiety Disorder': 0.04, 'Social Anxiety': 0.08, 'Panic Disorder': 0.03, 'Adjustment Anxiety': 0.04 } },
  { id: 'q14', text: 'Takut memalukan diri sendiri di depan umum.', weights: { 'Generalized Anxiety Disorder': 0.04, 'Social Anxiety': 0.08, 'Panic Disorder': 0.03, 'Adjustment Anxiety': 0.03 } },
  { id: 'q15', text: 'Pikiran tentang bencana atau malapetaka.', weights: { 'Generalized Anxiety Disorder': 0.05, 'Social Anxiety': 0.03, 'Panic Disorder': 0.04, 'Adjustment Anxiety': 0.03 } },
  { id: 'q16', text: 'Perasaan tidak nyata atau terpisah dari lingkungan.', weights: { 'Generalized Anxiety Disorder': 0.04, 'Social Anxiety': 0.03, 'Panic Disorder': 0.04, 'Adjustment Anxiety': 0.03 } },
  { id: 'q17', text: 'Mudah marah atau sangat sensitif.', weights: { 'Generalized Anxiety Disorder': 0.05, 'Social Anxiety': 0.04, 'Panic Disorder': 0.04, 'Adjustment Anxiety': 0.05 } },
  { id: 'q18', text: 'Perasaan cemas yang intens secara tiba-tiba.', weights: { 'Generalized Anxiety Disorder': 0.04, 'Social Anxiety': 0.04, 'Panic Disorder': 0.09, 'Adjustment Anxiety': 0.04 } },
  { id: 'q19', text: 'Takut kehilangan kendali.', weights: { 'Generalized Anxiety Disorder': 0.04, 'Social Anxiety': 0.04, 'Panic Disorder': 0.08, 'Adjustment Anxiety': 0.03 } },
  { id: 'q20', text: 'Takut mati mendadak.', weights: { 'Generalized Anxiety Disorder': 0.04, 'Social Anxiety': 0.03, 'Panic Disorder': 0.09, 'Adjustment Anxiety': 0.03 } },
  { id: 'q21', text: 'Menghindari situasi sosial.', weights: { 'Generalized Anxiety Disorder': 0.04, 'Social Anxiety': 0.09, 'Panic Disorder': 0.03, 'Adjustment Anxiety': 0.04 } },
  { id: 'q22', text: 'Menghindari objek atau situasi tertentu.', weights: { 'Generalized Anxiety Disorder': 0.04, 'Social Anxiety': 0.07, 'Panic Disorder': 0.04, 'Adjustment Anxiety': 0.04 } },
  { id: 'q23', text: 'Sulit berpisah dengan orang tua atau orang terdekat.', weights: { 'Generalized Anxiety Disorder': 0.04, 'Social Anxiety': 0.05, 'Panic Disorder': 0.03, 'Adjustment Anxiety': 0.08 } },
  { id: 'q24', text: 'Mencari jaminan atau kepastian berulang-ulang.', weights: { 'Generalized Anxiety Disorder': 0.05, 'Social Anxiety': 0.04, 'Panic Disorder': 0.03, 'Adjustment Anxiety': 0.07 } },
  { id: 'q25', text: 'Penurunan prestasi akademik.', weights: { 'Generalized Anxiety Disorder': 0.05, 'Social Anxiety': 0.03, 'Panic Disorder': 0.03, 'Adjustment Anxiety': 0.07 } }
];

const rules = [
  {
    disease: 'Generalized Anxiety Disorder',
    explanation: 'Kecemasan berlebihan, kesulitan mengendalikan khawatir, dan gangguan fokus memperkuat indikasi GAD.'
  },
  {
    disease: 'Social Anxiety',
    explanation: 'Takut dinilai negatif dan menghindari situasi sosial sangat menonjol.'
  },
  {
    disease: 'Panic Disorder',
    explanation: 'Jantung berdebar, tidak nyaman, dan serangan panik memperkuat indikasi panic disorder.'
  },
  {
    disease: 'Adjustment Anxiety',
    explanation: 'Kesulitan beradaptasi terhadap perubahan dan stres lingkungan mendukung adjustment anxiety.'
  }
];

const diagnosisForm = document.getElementById('diagnosisForm');
const resultPanel = document.getElementById('resultPanel');
const resultContent = document.getElementById('resultContent');
const resultBadge = document.getElementById('resultBadge');
const progressBar = document.getElementById('progressBar');
const progressLabel = document.getElementById('progressLabel');
const resetFormBtn = document.getElementById('resetFormBtn');
const contactForm = document.getElementById('contactForm');
const contactMessage = document.getElementById('contactMessage');
const historyList = document.getElementById('historyList');
const totalDiagnoses = document.getElementById('totalDiagnoses');
const mostCommonDisease = document.getElementById('mostCommonDisease');
const avgScore = document.getElementById('avgScore');
const downloadAllBtn = document.getElementById('downloadAllBtn');
const clearHistoryBtn = document.getElementById('clearHistoryBtn');
const prevStepBtn = document.getElementById('prevStepBtn');
const nextStepBtn = document.getElementById('nextStepBtn');
const submitStepBtn = document.getElementById('submitStepBtn');
const wizardSteps = Array.from(document.querySelectorAll('.wizard-step'));
const stepPills = Array.from(document.querySelectorAll('[data-step-pill]'));
const STORAGE_KEY = 'anxietyDiagnoses';
let currentResult = null;
let currentStep = 0;

function collectAnswers() {
  const answers = {};
  questions.forEach((question) => {
    const selected = diagnosisForm.querySelector(`input[name="${question.id}"]:checked`);
    answers[question.id] = selected ? Number(selected.value) : 0;
  });
  return answers;
}

function updateProgress() {
  const answeredCount = questions.filter((question) => diagnosisForm.querySelector(`input[name="${question.id}"]:checked`) !== null).length;
  const percentage = Math.round((answeredCount / questions.length) * 100);
  progressLabel.textContent = `Langkah ${currentStep + 1} dari ${wizardSteps.length} • ${answeredCount}/${questions.length} pertanyaan terjawab`;
  progressBar.style.width = `${percentage}%`;

  wizardSteps.forEach((step, index) => {
    step.classList.toggle('active', index === currentStep);
  });
  stepPills.forEach((pill, index) => {
    pill.classList.toggle('active', index === currentStep);
  });

  prevStepBtn.disabled = currentStep === 0;
  nextStepBtn.style.display = currentStep === wizardSteps.length - 1 ? 'none' : 'inline-block';
  submitStepBtn.classList.toggle('active', currentStep === wizardSteps.length - 1);
}

function renderPlaceholder(message) {
  resultContent.innerHTML = `<p class="result-placeholder">${message}</p>`;
  resultBadge.textContent = 'Menunggu';
  resultBadge.className = 'status-badge';
}

function goToStep(step) {
  currentStep = Math.max(0, Math.min(step, wizardSteps.length - 1));
  updateProgress();
  const formCard = document.querySelector('.diagnosis-card');
  if (formCard) {
    formCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
}

function resetConsultation() {
  diagnosisForm.reset();
  document.querySelectorAll('input[type="radio"]').forEach((input) => {
    input.checked = false;
  });
  document.getElementById('patientGender').value = '';
  goToStep(0);
  renderPlaceholder('Belum ada hasil. Jawab semua pertanyaan dan klik tombol proses diagnosa.');
  currentResult = null;
}

function runInference(answers) {
  const scoreMap = {
    'Generalized Anxiety Disorder': 0,
    'Social Anxiety': 0,
    'Panic Disorder': 0,
    'Adjustment Anxiety': 0
  };

  questions.forEach((question) => {
    const value = Number(answers[question.id] || 0);
    if (value > 0) {
      Object.entries(question.weights).forEach(([disease, weight]) => {
        scoreMap[disease] += weight * value;
      });
    }
  });

  if ((answers.q10 || 0) >= 3 && (answers.q11 || 0) >= 3 && (answers.q12 || 0) >= 3) {
    scoreMap['Generalized Anxiety Disorder'] += 0.2;
  }
  if ((answers.q13 || 0) >= 3 && (answers.q14 || 0) >= 3 && (answers.q21 || 0) >= 3) {
    scoreMap['Social Anxiety'] += 0.2;
  }
  if ((answers.q1 || 0) >= 3 && (answers.q18 || 0) >= 3 && (answers.q20 || 0) >= 3) {
    scoreMap['Panic Disorder'] += 0.2;
  }
  if ((answers.q23 || 0) >= 3 && (answers.q24 || 0) >= 3 && (answers.q25 || 0) >= 3) {
    scoreMap['Adjustment Anxiety'] += 0.2;
  }

  Object.keys(scoreMap).forEach((disease) => {
    scoreMap[disease] = Math.min(1, scoreMap[disease]);
  });

  const matchedRules = rules.filter((rule) => {
    if (rule.disease === 'Generalized Anxiety Disorder') return (answers.q10 || 0) >= 3 && (answers.q11 || 0) >= 3;
    if (rule.disease === 'Social Anxiety') return (answers.q13 || 0) >= 3 && (answers.q21 || 0) >= 3;
    if (rule.disease === 'Panic Disorder') return (answers.q1 || 0) >= 3 && (answers.q18 || 0) >= 3;
    if (rule.disease === 'Adjustment Anxiety') return (answers.q23 || 0) >= 3 && (answers.q24 || 0) >= 3;
    return false;
  });

  return { matchedRules, scoreMap };
}

function getRecommendation(disease) {
  const recommendations = {
    'Generalized Anxiety Disorder': [
      'Luangkan waktu untuk relaksasi dan latihan pernapasan.',
      'Catat kekhawatiran Anda agar tidak berputar terus menerus.'
    ],
    'Social Anxiety': [
      'Mulai latihan interaksi sosial secara bertahap.',
      'Jangan terlalu keras pada diri sendiri saat berada di lingkungan baru.'
    ],
    'Panic Disorder': [
      'Cari dukungan profesional jika panik sering terjadi.',
      'Praktikkan teknik grounding ketika gejala muncul.'
    ],
    'Adjustment Anxiety': [
      'Identifikasi sumber stres dan buat prioritas aktivitas.',
      'Jaga rutinitas tidur, makan, dan istirahat yang teratur.'
    ]
  };

  return recommendations[disease] || [
    'Jaga pola tidur dan konsumsi makanan bergizi.',
    'Jika gejala berlanjut, konsultasikan dengan tenaga profesional.'
  ];
}

function loadHistory() {
  const stored = localStorage.getItem(STORAGE_KEY);
  if (stored) {
    try {
      return JSON.parse(stored);
    } catch (error) {
      return [];
    }
  }

  const legacy = localStorage.getItem('anxietyHistory');
  if (!legacy) return [];

  try {
    return JSON.parse(legacy).map((item) => ({
      id: item.id || `${Date.now()}-${Math.random()}`,
      patientName: item.patientName || 'Pasien',
      patientAge: item.patientAge || '—',
      disease: item.disease,
      score: item.score,
      level: item.level || '—',
      time: item.time,
      timestamp: item.timestamp || new Date().toISOString(),
      answers: item.answers || {},
      recommendations: item.recommendations || []
    }));
  } catch (error) {
    return [];
  }
}

function saveHistory(result) {
  const history = loadHistory();
  history.unshift(result);
  const limited = history.slice(0, 8);
  localStorage.setItem(STORAGE_KEY, JSON.stringify(limited));
  localStorage.removeItem('anxietyHistory');
}

function getRiskLevel(score) {
  if (score >= 80) return 'Tinggi';
  if (score >= 50) return 'Sedang';
  return 'Rendah';
}

function downloadResult(result) {
  const payload = {
    ...result,
    exportedAt: new Date().toISOString()
  };
  const blob = new Blob([JSON.stringify(payload, null, 2)], { type: 'application/json' });
  const url = URL.createObjectURL(blob);
  const link = document.createElement('a');
  link.href = url;
  link.download = `hasil-diagnosa-${(result.patientName || 'pasien').toLowerCase().replace(/\s+/g, '-')}.json`;
  link.click();
  URL.revokeObjectURL(url);
}

function downloadAllHistory() {
  const history = loadHistory();
  const blob = new Blob([JSON.stringify(history, null, 2)], { type: 'application/json' });
  const url = URL.createObjectURL(blob);
  const link = document.createElement('a');
  link.href = url;
  link.download = 'riwayat-diagnosa-anxiety.json';
  link.click();
  URL.revokeObjectURL(url);
}

function updateStatistics() {
  const history = loadHistory();
  if (!history.length) {
    totalDiagnoses.textContent = '0';
    mostCommonDisease.textContent = '-';
    avgScore.textContent = '0%';
    return;
  }

  const scores = history.map((item) => Number(item.score || 0));
  const diseaseCount = history.reduce((acc, item) => {
    acc[item.disease] = (acc[item.disease] || 0) + 1;
    return acc;
  }, {});
  const topDisease = Object.entries(diseaseCount).sort((a, b) => b[1] - a[1])[0]?.[0] || '-';

  totalDiagnoses.textContent = String(history.length);
  mostCommonDisease.textContent = topDisease;
  avgScore.textContent = `${Math.round(scores.reduce((a, b) => a + b, 0) / scores.length)}%`;
}

function renderHistory() {
  const history = loadHistory();

  if (!history.length) {
    historyList.innerHTML = '<div class="history-item">Belum ada riwayat diagnosa. Lakukan diagnosa pertama Anda untuk melihat catatan di sini.</div>';
    return;
  }

  historyList.innerHTML = history
    .map((item) => `
      <div class="history-item">
        <div class="history-meta">
          <strong>${item.patientName || 'Pasien'} (${item.patientAge || '—'} tahun)</strong>
          <span>${item.level || '—'}</span>
        </div>
        <div><strong>Diagnosa:</strong> ${item.disease}</div>
        <div><strong>Keyakinan:</strong> ${item.score}%</div>
        <div><strong>Waktu:</strong> ${item.time || new Date(item.timestamp).toLocaleString('id-ID')}</div>
        <div class="history-actions">
          <button type="button" class="btn btn-small btn-secondary" data-download-id="${item.id}">Unduh Hasil</button>
        </div>
      </div>
    `)
    .join('');
  updateStatistics();
}

function renderResult(answers) {
  const { matchedRules, scoreMap } = runInference(answers);
  const hasAnswer = questions.some((question) => diagnosisForm.querySelector(`input[name="${question.id}"]:checked`) !== null);
  const patientName = document.getElementById('patientName').value.trim();
  const patientAge = document.getElementById('patientAge').value;

  if (!hasAnswer) {
    renderPlaceholder('Jawab semua pertanyaan terlebih dahulu untuk melihat hasil diagnosa.');
    return;
  }

  const diseaseEntries = Object.entries(scoreMap).sort((a, b) => b[1] - a[1]);
  const [topDisease, topScore] = diseaseEntries[0];
  const percentage = Math.round(topScore * 100);
  const patientGender = document.getElementById('patientGender').value || '—';
  const answerSummary = questions
    .filter((question) => Number(answers[question.id] || 0) > 0)
    .map((question) => `<li>${question.text}</li>`)
    .join('');
  const explanationList = matchedRules.map((rule) => `<li>${rule.explanation}</li>`).join('');
  const recommendationList = getRecommendation(topDisease).map((item) => `<li class="result-recommendation">${item}</li>`).join('');
  const summaryText = percentage >= 80
    ? `Berdasarkan gejala yang Anda pilih, sistem menilai bahwa ${topDisease} memiliki indikasi yang kuat dan perlu diperhatikan lebih lanjut.`
    : percentage >= 50
      ? `Sistem melihat ada gejala yang cukup menonjol dan mendukung kemungkinan ${topDisease}.`
      : `Gejala yang Anda pilih menunjukkan kemungkinan ${topDisease} yang relatif ringan, tetapi tetap penting untuk dipantau.`;

  const result = {
    id: `${Date.now()}`,
    patientName: patientName || 'Pasien',
    patientAge: patientAge || '—',
    patientGender,
    disease: topDisease,
    score: percentage,
    level: getRiskLevel(percentage),
    time: new Date().toLocaleString('id-ID'),
    timestamp: new Date().toISOString(),
    answers,
    recommendations: getRecommendation(topDisease),
    evidence: matchedRules.map((rule) => rule.explanation)
  };

  currentResult = result;
  saveHistory(result);
  renderHistory();

  let badgeClass = 'status-badge';
  if (result.level === 'Tinggi') badgeClass = 'status-badge status-badge-danger';
  else if (result.level === 'Sedang') badgeClass = 'status-badge status-badge-warning';
  else badgeClass = 'status-badge status-badge-success';

  resultBadge.className = badgeClass;
  resultBadge.textContent = result.level;

  resultContent.innerHTML = `
    <div class="result-summary">
      <div class="result-highlight">
        <div>
          <p class="result-title">${result.patientName}</p>
          <p class="result-subtitle">${result.patientAge} tahun • ${result.patientGender}</p>
        </div>
        <span class="status-badge ${badgeClass.replace('status-badge ', '')}">${result.level}</span>
      </div>
      <div class="result-metrics">
        <div class="metric-pill">
          <span>Jenis kemungkinan</span>
          <strong>${topDisease}</strong>
        </div>
        <div class="metric-pill">
          <span>Keyakinan sistem</span>
          <strong>${percentage}%</strong>
        </div>
        <div class="metric-pill">
          <span>Level risiko</span>
          <strong>${result.level}</strong>
        </div>
      </div>
    </div>

    <div class="result-section">
      <h4>Ringkasan hasil</h4>
      <p>${summaryText}</p>
      <div class="result-meter">
        <strong>Tingkat kepercayaan hasil</strong>
        <div class="result-meter-bar">
          <div class="result-meter-fill" style="width: ${Math.min(percentage, 100)}%"></div>
        </div>
      </div>
    </div>

    <div class="result-section">
      <h4>Respon yang Anda pilih</h4>
      <ul class="result-list">${answerSummary}</ul>
    </div>

    <div class="result-section">
      <h4>Alur forward chaining</h4>
      <ul class="result-list">${explanationList}</ul>
    </div>

    <div class="result-section">
      <h4>Rekomendasi awal</h4>
      <ul class="recommendation-list">${recommendationList}</ul>
    </div>

    <div class="result-actions">
      <button type="button" id="downloadResultBtn" class="btn btn-secondary">Unduh Hasil</button>
    </div>
  `;

  const downloadButton = document.getElementById('downloadResultBtn');
  if (downloadButton) {
    downloadButton.addEventListener('click', () => downloadResult(currentResult));
  }
}

diagnosisForm.addEventListener('submit', (event) => {
  event.preventDefault();
  const answers = collectAnswers();
  renderResult(answers);
});

diagnosisForm.addEventListener('change', updateProgress);
diagnosisForm.addEventListener('input', updateProgress);
prevStepBtn.addEventListener('click', () => goToStep(currentStep - 1));
nextStepBtn.addEventListener('click', () => {
  const patientName = document.getElementById('patientName').value.trim();
  const patientAge = document.getElementById('patientAge').value.trim();

  if (currentStep === 0 && (!patientName || !patientAge)) {
    alert('Isi nama dan usia sebelum melanjutkan konsultasi.');
    return;
  }

  goToStep(currentStep + 1);
});
resetFormBtn.addEventListener('click', resetConsultation);

contactForm.addEventListener('submit', (event) => {
  event.preventDefault();
  contactMessage.textContent = 'Terima kasih, pesan Anda telah diterima. Kami akan segera merespons.';
  contactForm.reset();
});

historyList.addEventListener('click', (event) => {
  const button = event.target.closest('[data-download-id]');
  if (!button) return;
  const item = loadHistory().find((entry) => entry.id === button.getAttribute('data-download-id'));
  if (item) downloadResult(item);
});

downloadAllBtn.addEventListener('click', downloadAllHistory);
clearHistoryBtn.addEventListener('click', () => {
  localStorage.removeItem(STORAGE_KEY);
  renderHistory();
});

updateProgress();
renderPlaceholder('Belum ada hasil. Jawab semua pertanyaan dan klik tombol proses diagnosa.');
renderHistory();
