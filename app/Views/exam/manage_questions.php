<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> - CIT UMS</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css?v=<?= time() ?>">
</head>
<body>

<div class="dashboard-layout">
    <?php include '../app/Views/partials/sidebar.php'; ?>

    <main class="main-content">
        <header class="topbar shadow-sm">
            <div style="display:flex; align-items:center;">
                <button class="menu-toggle" onclick="toggleSidebar()">&#9776;</button>
            <div class="welcome font-medium text-lg">Manage Questions: <?= htmlspecialchars($exam['title']) ?></div>
            </div>
            <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?= BASE_URL ?>exam" class="btn btn-secondary">Back to Exams</a>
            </div>
        </header>

        <div class="content-wrapper">
            <?php if (!empty($success)): ?>
                <div class="alert" style="background:#d1fae5;color:#065f46;border-color:#a7f3d0;"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <div class="grid" style="grid-template-columns: 1fr 2fr; gap: 1.5rem;">
                <!-- Add Question Form -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h3 class="text-lg font-bold mb-4">Add MCQ Question</h3>
                    <form action="<?= BASE_URL ?>exam?manage=<?= $exam['id'] ?>" method="POST">
                        <input type="hidden" name="action" value="add_question">
                        <div class="mb-4">
                            <label class="block text-sm mb-1">Question Text</label>
                            <textarea name="question_text" class="form-input w-full" rows="3" required></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm mb-1">Option A</label>
                            <input type="text" name="option_a" class="form-input w-full" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm mb-1">Option B</label>
                            <input type="text" name="option_b" class="form-input w-full" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm mb-1">Option C</label>
                            <input type="text" name="option_c" class="form-input w-full" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm mb-1">Option D</label>
                            <input type="text" name="option_d" class="form-input w-full" required>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm mb-1">Correct Option</label>
                                <select name="correct_option" class="form-input w-full" required>
                                    <option value="a">A</option>
                                    <option value="b">B</option>
                                    <option value="c">C</option>
                                    <option value="d">D</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm mb-1">Marks</label>
                                <input type="number" name="marks" value="1" min="1" class="form-input w-full" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-full">Add Question</button>
                    </form>
                </div>

                <!-- Existing Questions List -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h3 class="text-lg font-bold mb-4">Question Bank (<?= count($questions) ?>)</h3>
                    <div style="max-height: 600px; overflow-y: auto;">
                        <?php foreach($questions as $i => $q): ?>
                            <div class="p-4 mb-4 border border-gray-200 rounded">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="font-bold">Q<?= $i+1 ?>. <?= nl2br(htmlspecialchars($q['question_text'])) ?></div>
                                    <div class="text-sm text-gray-500">[<?= $q['marks'] ?> Marks]</div>
                                </div>
                                <div class="ml-4 text-sm mb-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                                    <div class="<?= $q['correct_option'] == 'a' ? 'font-bold text-green-600' : '' ?>">A) <?= htmlspecialchars($q['option_a']) ?></div>
                                    <div class="<?= $q['correct_option'] == 'b' ? 'font-bold text-green-600' : '' ?>">B) <?= htmlspecialchars($q['option_b']) ?></div>
                                    <div class="<?= $q['correct_option'] == 'c' ? 'font-bold text-green-600' : '' ?>">C) <?= htmlspecialchars($q['option_c']) ?></div>
                                    <div class="<?= $q['correct_option'] == 'd' ? 'font-bold text-green-600' : '' ?>">D) <?= htmlspecialchars($q['option_d']) ?></div>
                                </div>
                                <form action="<?= BASE_URL ?>exam?manage=<?= $exam['id'] ?>" method="POST" class="text-right">
                                    <input type="hidden" name="action" value="delete_question">
                                    <input type="hidden" name="question_id" value="<?= $q['id'] ?>">
                                    <button type="submit" class="text-red-500 text-sm hover:underline border-0 bg-transparent cursor-pointer">Delete Question</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                        <?php if(empty($questions)): ?>
                            <p class="text-gray-500 text-center">No questions added yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="<?= BASE_URL ?>js/sidebar.js?v=1787059998"></script>
</body>
</html>
