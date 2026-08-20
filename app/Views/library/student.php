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
            <div class="welcome font-medium text-lg">E-Library System</div>
            </div>
            
            <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-primary">Logout</a>
            </div>

        </header>

        <div class="content-wrapper">
            <?php if (!empty($success)): ?>
                <div class="alert" style="background:#d1fae5;color:#065f46;border-color:#a7f3d0;"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                
                <!-- Book Search & Catalog -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                        <h3 class="text-lg font-bold">Library Catalog</h3>
                        <form action="<?= BASE_URL ?>library" method="GET" class="flex gap-2 w-1/2">
                            <input type="text" name="q" class="form-input w-full" placeholder="Search title or author..." value="<?= htmlspecialchars($search_query) ?>">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                    </div>
                    <div class="p-6">
                        <div class="grid" style="grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem;">
                            <?php foreach($books as $book): ?>
                            <div style="border: 1px solid #e2e8f0; border-radius: 0.5rem; overflow: hidden; display: flex; flex-direction: column;">
                                <div style="background: #f1f5f9; height: 120px; display: flex; align-items: center; justify-content: center; color: #94a3b8;">
                                    <svg style="width:48px;height:48px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                </div>
                                <div class="p-4" style="flex: 1; display: flex; flex-direction: column;">
                                    <h4 class="font-bold text-sm mb-1 line-clamp-2"><?= htmlspecialchars($book['title']) ?></h4>
                                    <p class="text-xs text-gray-500 mb-1"><?= htmlspecialchars($book['author']) ?></p>
                                    <span class="text-[10px] uppercase bg-gray-100 px-1 py-0.5 rounded border inline-block mb-2"><?= htmlspecialchars($book['resource_type']) ?></span>
                                    
                                    <div class="mt-auto">
                                        <?php if($book['resource_type'] === 'digital'): ?>
                                            <a href="<?= htmlspecialchars($book['digital_link']) ?>" target="_blank" class="btn btn-primary w-full text-center" style="padding: 0.25rem 0.5rem; font-size: 0.75rem; display:block;">Read E-Book</a>
                                        <?php else: ?>
                                            <div class="text-xs mb-2 <?php echo $book['available_copies'] > 0 ? 'text-green-600' : 'text-red-600'; ?>">
                                                <?= $book['available_copies'] ?> copies available
                                            </div>
                                            <?php if($book['available_copies'] > 0): ?>
                                                <form action="<?= BASE_URL ?>library" method="POST">
                                                    <input type="hidden" name="action" value="issue">
                                                    <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                                                    <button type="submit" class="btn btn-primary w-full" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Request Issue</button>
                                                </form>
                                            <?php else: ?>
                                                <button disabled class="btn w-full" style="padding: 0.25rem 0.5rem; font-size: 0.75rem; background: #e2e8f0; color: #94a3b8;">Out of Stock</button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- My Issued Books -->
                <div>
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-bold">My Issued Books</h3>
                        </div>
                        <div class="p-4">
                            <?php if(empty($issued_books)): ?>
                                <p class="text-gray-500 text-sm text-center">You have no active book issues.</p>
                            <?php else: ?>
                                <ul style="list-style: none; padding: 0; margin: 0;">
                                    <?php foreach($issued_books as $iss): ?>
                                    <li class="mb-4 pb-4 border-b border-gray-100 last:border-0 last:mb-0 last:pb-0">
                                        <h4 class="font-bold text-sm text-cit-blue"><?= htmlspecialchars($iss['title']) ?></h4>
                                        <div class="text-xs text-gray-500 mt-1">Issued: <?= date('d M Y', strtotime($iss['issue_date'])) ?></div>
                                        <div class="text-xs mt-1">
                                            Due: 
                                            <?php 
                                                if($iss['status'] === 'overdue'): 
                                            ?>
                                                <span class="text-red-600 font-bold"><?= date('d M Y', strtotime($iss['due_date'])) ?> (Overdue)</span>
                                            <?php else: ?>
                                                <span class="text-green-600 font-medium"><?= date('d M Y', strtotime($iss['due_date'])) ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if($iss['fine_amount'] > 0): ?>
                                            <div class="text-xs mt-1 text-red-600 font-bold">Fine: ₹<?= number_format($iss['fine_amount'], 2) ?></div>
                                        <?php endif; ?>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>

<script src="<?= BASE_URL ?>js/sidebar.js?v=1787059998"></script>
</body>
</html>
