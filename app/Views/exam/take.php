<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> - CIT UMS</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css?v=<?= time() ?>">
    <style>
        .timer-fixed { position: fixed; top: 1rem; right: 1rem; background: #ef4444; color: white; padding: 0.5rem 1rem; font-weight: bold; border-radius: 0.5rem; z-index: 100; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="bg-gray-50">

<div class="timer-fixed" id="timer">Time Remaining: Loading...</div>

<div style="max-width: 800px; margin: 2rem auto; background: white; padding: 2rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <header class="border-b border-gray-200 pb-4 mb-6">
        <h1 class="text-2xl font-bold text-cit-blue"><?= htmlspecialchars($exam['title']) ?></h1>
        <p class="text-gray-500">Course: <?= htmlspecialchars($exam['course_code']) ?> | Duration: <?= $exam['duration_minutes'] ?> mins</p>
    </header>

    <form id="examForm" action="<?= BASE_URL ?>exam/take/<?= $exam['id'] ?>" method="POST">
        <input type="hidden" name="action" value="submit_exam">
        
        <?php foreach($questions as $i => $q): ?>
            <div class="mb-8 p-4 border border-gray-100 rounded bg-gray-50">
                <p class="font-bold mb-3 text-lg">Q<?= $i+1 ?>. <?= nl2br(htmlspecialchars($q['question_text'])) ?></p>
                <div class="grid grid-cols-1 gap-2 ml-4">
                    <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-white rounded">
                        <input type="radio" name="answers[<?= $q['id'] ?>]" value="a" required>
                        <span><?= htmlspecialchars($q['option_a']) ?></span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-white rounded">
                        <input type="radio" name="answers[<?= $q['id'] ?>]" value="b" required>
                        <span><?= htmlspecialchars($q['option_b']) ?></span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-white rounded">
                        <input type="radio" name="answers[<?= $q['id'] ?>]" value="c" required>
                        <span><?= htmlspecialchars($q['option_c']) ?></span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-white rounded">
                        <input type="radio" name="answers[<?= $q['id'] ?>]" value="d" required>
                        <span><?= htmlspecialchars($q['option_d']) ?></span>
                    </label>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if(empty($questions)): ?>
            <p class="text-red-500 text-center mb-6">No questions are assigned to this exam. Please contact your instructor.</p>
        <?php else: ?>
            <button type="submit" class="btn btn-primary w-full text-lg py-3" onclick="return confirm('Are you sure you want to submit your exam?');">Submit Exam</button>
        <?php endif; ?>
    </form>
</div>

<script>
    // Timer logic
    const endTime = <?= $end_time ?> * 1000;
    const timerEl = document.getElementById('timer');
    const formEl = document.getElementById('examForm');
    
    function updateTimer() {
        const now = new Date().getTime();
        const distance = endTime - now;
        
        if (distance < 0) {
            timerEl.innerHTML = "Time's Up!";
            timerEl.style.background = "#000";
            // Auto submit
            formEl.submit();
            return;
        }
        
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        timerEl.innerHTML = "Time Remaining: " + minutes + "m " + seconds + "s";
    }
    
    setInterval(updateTimer, 1000);
    updateTimer();
</script>

<script src="<?= BASE_URL ?>js/sidebar.js?v=1787059998"></script>
</body>
</html>
