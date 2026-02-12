// ========== State ==========
let questions = [];
let selectedAnswers = {};

// ========== Start Quiz ==========
async function startQuiz() {
    // Reset state
    questions = [];
    selectedAnswers = {};

    // Show quiz screen, hide others
    document.getElementById('start-screen').style.display = 'none';
    document.getElementById('results-screen').style.display = 'none';
    document.getElementById('quiz-screen').style.display = 'block';
    document.getElementById('questions-container').innerHTML = '<div class="loading">Načítám otázky</div>';
    document.getElementById('submit-btn').disabled = true;

    try {
        const response = await fetch('/api/quiz/start');
        if (!response.ok) throw new Error('Chyba serveru');
        questions = await response.json();

        if (questions.length === 0) {
            throw new Error('Žádné otázky v databázi');
        }

        renderQuestions();
    } catch (err) {
        document.getElementById('questions-container').innerHTML =
            '<div class="error-msg">❌ ' + escapeHtml(err.message) +
            '. Zkontroluj, jestli běží server a databáze je naseedovaná.</div>';
    }
}

// ========== Render Questions ==========
function renderQuestions() {
    const container = document.getElementById('questions-container');
    container.innerHTML = '';

    const difficultyMap = {
        'easy':   { label: 'Lehká',   cls: 'difficulty-easy' },
        'medium': { label: 'Střední', cls: 'difficulty-medium' },
        'hard':   { label: 'Těžká',   cls: 'difficulty-hard' }
    };

    questions.forEach(function (q, index) {
        const diff = difficultyMap[q.difficulty] || { label: q.difficulty, cls: '' };

        let answersHtml = '';
        q.answers.forEach(function (a) {
            answersHtml +=
                '<button class="answer-option" data-question="' + q.id + '" data-answer="' + a.id + '" ' +
                'onclick="selectAnswer(' + q.id + ', ' + a.id + ', this)">' +
                escapeHtml(a.text) +
                '</button>';
        });

        const sectionHtml = q.section
            ? '<div class="question-section">📂 ' + escapeHtml(q.section) + '</div>'
            : '';

        const card = document.createElement('div');
        card.className = 'question-card';
        card.id = 'question-' + q.id;
        card.innerHTML =
            '<div class="question-number">Otázka ' + (index + 1) + '</div>' +
            sectionHtml +
            '<span class="difficulty ' + diff.cls + '">' + diff.label + '</span>' +
            '<div class="question-text">' + escapeHtml(q.text) + '</div>' +
            '<div class="answers-list">' + answersHtml + '</div>';

        container.appendChild(card);
    });

    updateProgress();
}

// ========== Select Answer ==========
function selectAnswer(questionId, answerId, el) {
    // Deselect all answers for this question
    var buttons = document.querySelectorAll('[data-question="' + questionId + '"]');
    buttons.forEach(function (btn) {
        btn.classList.remove('selected');
    });

    // Select this answer
    el.classList.add('selected');
    selectedAnswers[questionId] = answerId;

    updateProgress();
}

// ========== Update Progress ==========
function updateProgress() {
    var answered = Object.keys(selectedAnswers).length;
    var total = questions.length;
    var pct = total > 0 ? (answered / total) * 100 : 0;

    document.getElementById('progress-fill').style.width = pct + '%';
    document.getElementById('question-counter').textContent = 'Odpovězeno ' + answered + ' z ' + total;
    document.getElementById('submit-btn').disabled = (answered < total);
}

// ========== Submit Quiz ==========
async function submitQuiz() {
    var submitBtn = document.getElementById('submit-btn');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Odesílám...';

    var answersArray = [];
    for (var qId in selectedAnswers) {
        answersArray.push({
            question_id: parseInt(qId),
            answer_id: parseInt(selectedAnswers[qId])
        });
    }

    try {
        var response = await fetch('/api/quiz/submit', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ answers: answersArray })
        });

        if (!response.ok) throw new Error('Chyba při odesílání');
        var result = await response.json();

        showResults(result);
    } catch (err) {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Odeslat odpovědi';
        var errorDiv = document.createElement('div');
        errorDiv.className = 'error-msg';
        errorDiv.textContent = '❌ ' + err.message;
        document.getElementById('questions-container').appendChild(errorDiv);
    }
}

// ========== Show Results ==========
function showResults(result) {
    document.getElementById('quiz-screen').style.display = 'none';
    document.getElementById('results-screen').style.display = 'block';

    document.getElementById('score-number').textContent = result.percentage + '%';
    document.getElementById('score-label').textContent = 'Správně ' + result.score + ' z ' + result.total;

    var detailsContainer = document.getElementById('results-details');
    detailsContainer.innerHTML = '';

    result.results.forEach(function (r) {
        var question = questions.find(function (q) { return q.id === r.question_id; });
        var qText = question ? question.text : 'Otázka';

        var item = document.createElement('div');
        item.className = 'result-item ' + (r.is_correct ? 'correct' : 'wrong');
        item.innerHTML =
            '<div class="result-label ' + (r.is_correct ? 'correct' : 'wrong') + '">' +
                (r.is_correct ? '✅ Správně' : '❌ Špatně') +
            '</div>' +
            '<div style="font-weight:500; margin-bottom: 4px;">' + escapeHtml(qText) + '</div>' +
            '<div class="result-explanation">' + escapeHtml(r.explanation) + '</div>';

        detailsContainer.appendChild(item);
    });
}

// ========== Utility ==========
function escapeHtml(text) {
    var div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
