<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css?v=<?= time() ?>">
    <script>
        function toggleTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('modern-tab-active'));
            
            document.getElementById(tabId).style.display = 'block';
            document.getElementById('btn-' + tabId).classList.add('modern-tab-active');
        }

        window.onload = function() {
            <?php if(isset($active_forum) && $active_forum): ?>
                toggleTab('forums');
            <?php endif; ?>
        };
    </script>
</head>
<body>
<div class="dashboard-layout">
    <?php include '../app/Views/partials/sidebar.php'; ?>

    <main class="main-content">
        <header class="topbar shadow-sm">
            <div style="display:flex; align-items:center;">
                <button class="menu-toggle" onclick="toggleSidebar()">&#9776;</button>
            <div class="welcome font-medium text-lg">Messaging Center</div>
            </div>
            
            <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-primary">Logout</a>
            </div>

        </header>

        <div class="content-wrapper">
            <?php if (!empty($success)): ?>
                <div class="alert" style="background:#d1fae5;color:#065f46;border-color:#a7f3d0;margin-bottom:1rem;font-weight:bold;"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="alert alert-error" style="margin-bottom:1rem;font-weight:bold;"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="grid" style="grid-template-columns: 1fr 2fr; gap: 1.5rem;">
                
                <!-- Compose Block -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200" style="height: fit-content;">
                    <h3 class="text-lg font-bold mb-4">Compose Message</h3>
                    <form action="<?= BASE_URL ?>message" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="send_message" value="1">
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">To (Username or Group)</label>
                            <?php if($user['role'] === 'admin' || $user['role'] === 'faculty'): ?>
                            <select name="broadcast_group" class="form-input w-full mb-2 bg-blue-50">
                                <option value="" disabled selected>-- Select a Broadcast Group --</option>
                                <option value="@GROUP:ALL">Broadcast to ALL USERS</option>
                                <option value="@GROUP:STUDENTS">Broadcast to ALL STUDENTS</option>
                                <option value="@GROUP:FACULTY">Broadcast to ALL FACULTY</option>
                                  <option value="@GROUP:PARENTS">Broadcast to ALL PARENTS</option>
                            </select>
                            <div class="text-center text-gray-500 text-xs mb-2">OR enter specific username below</div>
                            <?php endif; ?>
                            <input type="text" name="receiver_username" class="form-input w-full" placeholder="e.g. admin, STU101">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Subject</label>
                            <input type="text" name="subject" class="form-input w-full" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Message</label>
                            <textarea name="body" class="form-input w-full" rows="5" required></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Attachment (Notes/Files)</label>
                            <input type="file" name="attachment" class="form-input w-full">
                        </div>
                        <button type="submit" class="btn btn-primary w-full">Send Message</button>
                    </form>
                </div>

                <!-- Inbox/Sent/Forums Block -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); padding: 1.5rem; text-align: center; border-bottom: 1px solid #e2e8f0; border-radius: 0.5rem 0.5rem 0 0;">
                        <div class="pill-tabs-container" style="display: inline-flex; background: #e2e8f0; padding: 0.35rem; border-radius: 9999px; gap: 0.5rem; flex-wrap: wrap; justify-content: center; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);">
                            <button id="btn-inbox" class="tab-btn pill-tab-btn" onclick="toggleTab('inbox')">
                                <span style="margin-right:5px; font-size:1.1rem;">📥</span> Inbox
                            </button>
                            <button id="btn-sent" class="tab-btn pill-tab-btn" onclick="toggleTab('sent')">
                                <span style="margin-right:5px; font-size:1.1rem;">📤</span> Sent
                            </button>
                            <button id="btn-forums" class="tab-btn pill-tab-btn" onclick="toggleTab('forums')">
                                <span style="margin-right:5px; font-size:1.1rem;">💬</span> Discussion Forums
                            </button>
                        </div>
                    </div>

                    <div id="inbox" class="tab-content">
                        <?php if(empty($received)): ?>
                            <div class="p-6 text-gray-500 text-center">Your inbox is empty.</div>
                        <?php endif; ?>
                        <?php foreach($received as $msg): ?>
                            <div class="p-4 border-b border-gray-100 <?= $msg['is_read'] ? 'bg-white' : 'bg-blue-50' ?>">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <strong class="text-gray-900"><?= htmlspecialchars($msg['sender_name']) ?></strong>
                                        <span class="text-xs text-gray-500 ml-2"><?= htmlspecialchars(date('M d, g:i A', strtotime($msg['created_at']))) ?></span>
                                    </div>
                                    <div class="flex gap-2">
                                        <?php if(!$msg['is_read']): ?>
                                            <a href="<?= BASE_URL ?>message?read=<?= $msg['id'] ?>" class="text-xs text-blue-600 hover:underline font-bold">Mark as Read</a>
                                        <?php endif; ?>
                                        <form action="<?= BASE_URL ?>message" method="POST" onsubmit="return confirm('Delete this message?');">
                                            <input type="hidden" name="delete_message" value="<?= $msg['id'] ?>">
                                            <button type="submit" class="text-xs text-red-500 hover:underline">Delete</button>
                                        </form>
                                    </div>
                                </div>
                                <h4 class="font-bold text-md mb-1 cursor-pointer hover:text-blue-600" onclick="document.getElementById('msg-body-recv-<?= $msg['id'] ?>').style.display = document.getElementById('msg-body-recv-<?= $msg['id'] ?>').style.display === 'none' ? 'block' : 'none';">
                                    <?= htmlspecialchars($msg['subject']) ?> <span class="text-xs text-gray-400 font-normal ml-2">(Click to open)</span>
                                </h4>
                                <div id="msg-body-recv-<?= $msg['id'] ?>" style="display:none;" class="mt-2 bg-white p-3 rounded border border-gray-100">
                                    <p class="text-gray-700 text-sm whitespace-pre-wrap mb-2"><?= htmlspecialchars($msg['body']) ?></p>
                                    <?php if($msg['attachment_path']): ?>
                                        <a href="<?= htmlspecialchars($msg['attachment_path']) ?>" target="_blank" class="inline-flex items-center gap-1 text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded">View Attachment</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div id="sent" class="tab-content" style="display:none;">
                        <?php if(empty($sent)): ?>
                            <div class="p-6 text-gray-500 text-center">No sent messages.</div>
                        <?php endif; ?>
                        <?php foreach($sent as $msg): ?>
                            <div class="p-4 border-b border-gray-100">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <span class="text-gray-500">To:</span> <strong class="text-gray-900"><?= htmlspecialchars($msg['receiver_name']) ?></strong>
                                        <span class="text-xs text-gray-500 ml-2"><?= htmlspecialchars(date('M d, g:i A', strtotime($msg['created_at']))) ?></span>
                                    </div>
                                    <div class="flex gap-2 items-center">
                                        <span class="text-xs <?= $msg['is_read'] ? 'text-green-500' : 'text-gray-400' ?>"><?= $msg['is_read'] ? 'Read' : 'Delivered' ?></span>
                                        <form action="<?= BASE_URL ?>message" method="POST" onsubmit="return confirm('Delete this message?');">
                                            <input type="hidden" name="delete_message" value="<?= $msg['id'] ?>">
                                            <button type="submit" class="text-xs text-red-500 hover:underline">Delete</button>
                                        </form>
                                    </div>
                                </div>
                                <h4 class="font-bold text-md mb-1 cursor-pointer hover:text-blue-600" onclick="document.getElementById('msg-body-sent-<?= $msg['id'] ?>').style.display = document.getElementById('msg-body-sent-<?= $msg['id'] ?>').style.display === 'none' ? 'block' : 'none';">
                                    <?= htmlspecialchars($msg['subject']) ?> <span class="text-xs text-gray-400 font-normal ml-2">(Click to open)</span>
                                </h4>
                                <div id="msg-body-sent-<?= $msg['id'] ?>" style="display:none;" class="mt-2 bg-gray-50 p-3 rounded border border-gray-100">
                                    <p class="text-gray-700 text-sm whitespace-pre-wrap mb-2"><?= htmlspecialchars($msg['body']) ?></p>
                                    <?php if($msg['attachment_path']): ?>
                                        <a href="<?= htmlspecialchars($msg['attachment_path']) ?>" target="_blank" class="inline-flex items-center gap-1 text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded">View Attachment</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div id="forums" class="tab-content" style="display: <?= $active_forum ? 'block' : 'none' ?>;">
                        <?php if ($active_forum): ?>
                            <div class="p-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                                <h3 class="font-bold">Discussion Thread</h3>
                                
                            </div>
                            <div class="p-4" style="max-height: 400px; overflow-y: auto;">
                                <?php foreach($forum_posts as $post): ?>
                                    <div class="mb-4">
                                        <div class="flex items-center gap-2 mb-1">
                                            <strong class="text-sm"><?= htmlspecialchars($post['author_name']) ?></strong>
                                            <span class="text-xs text-gray-500"><?= htmlspecialchars(date('M d, g:i A', strtotime($post['created_at']))) ?></span>
                                        </div>
                                        <div class="bg-gray-100 p-3 rounded-lg text-sm text-gray-800 whitespace-pre-wrap"><?= htmlspecialchars($post['content']) ?></div>
                                        <?php if($post['attachment_path']): ?>
                                            <a href="<?= htmlspecialchars($post['attachment_path']) ?>" target="_blank" class="inline-block mt-1 text-xs text-blue-600 hover:underline">📎 Attachment</a>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="p-4 border-t border-gray-200">
                                <form action="<?= BASE_URL ?>message" method="POST" enctype="multipart/form-data" class="flex flex-col gap-2">
                                    <input type="hidden" name="post_forum" value="1">
                                    <input type="hidden" name="forum_id" value="<?= $active_forum ?>">
                                    <textarea name="content" class="form-input w-full" rows="2" placeholder="Write a reply..." required></textarea>
                                    <div class="flex gap-2 items-center">
                                        <input type="file" name="attachment" class="form-input text-sm w-1/2">
                                        <button type="submit" class="btn btn-primary w-1/2">Post Reply</button>
                                    </div>
                                </form>
                            </div>
                        <?php else: ?>
                            <div class="p-4 border-b border-gray-200">
                                <form action="<?= BASE_URL ?>message" method="POST" class="flex gap-2">
                                    <input type="hidden" name="create_forum" value="1">
                                    <input type="text" name="title" class="form-input w-1/3" placeholder="Forum Topic" required>
                                    <input type="text" name="description" class="form-input w-2/3" placeholder="Description (Optional)">
                                    <button type="submit" class="btn btn-primary whitespace-nowrap">Create New Topic</button>
                                </form>
                            </div>
                            <div class="p-0">
                                <?php if(empty($forums)): ?>
                                    <div class="p-6 text-gray-500 text-center">No discussion forums created yet.</div>
                                <?php endif; ?>
                                <?php foreach($forums as $forum): ?>
                                    <div class="p-4 border-b border-gray-100 hover:bg-gray-50 flex justify-between items-center">
                                        <div>
                                            <a href="<?= BASE_URL ?>message?forum_id=<?= $forum['id'] ?>" class="font-bold text-blue-600 hover:underline text-lg block"><?= htmlspecialchars($forum['title']) ?></a>
                                            <span class="text-xs text-gray-500">Created by <?= htmlspecialchars($forum['creator_name']) ?> • <?= $forum['post_count'] ?> Replies</span>
                                            <?php if($forum['description']): ?>
                                                <p class="text-sm text-gray-600 mt-1"><?= htmlspecialchars($forum['description']) ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
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
