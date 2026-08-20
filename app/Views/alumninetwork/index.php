<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> - CIT UMS</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css?v=<?= time() ?>">
    <script>
        function toggleTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('modern-tab-active'));
            
            document.getElementById(tabId).style.display = 'block';
            document.getElementById('btn-' + tabId).classList.add('modern-tab-active');
        }

        window.onload = function() {
            toggleTab('directory');
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
            <div class="welcome font-medium text-lg">Alumni Network & Career Hub</div>
            </div>
            
            <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-primary">Logout</a>
            </div>

        </header>

        <div class="content-wrapper">
            <?php if (!empty($success)): ?>
                <div class="alert" style="background:#d1fae5;color:#065f46;border-color:#a7f3d0;margin-bottom:1rem;font-weight:bold;"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <!-- Navigation Tabs -->
            <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); padding: 1.5rem; text-align: center; border: 1px solid #e2e8f0; border-radius: 1rem; margin-bottom: 1.5rem;">
                <div class="pill-tabs-container" style="display: inline-flex; background: #e2e8f0; padding: 0.35rem; border-radius: 9999px; gap: 0.5rem; flex-wrap: wrap; justify-content: center; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);">
                    <button id="btn-directory" class="tab-btn pill-tab-btn" onclick="toggleTab('directory')">
                        <span style="margin-right:5px; font-size:1.1rem;">🎓</span> Alumni Directory
                    </button>
                    <button id="btn-jobs" class="tab-btn pill-tab-btn" onclick="toggleTab('jobs')">
                        <span style="margin-right:5px; font-size:1.1rem;">💼</span> Job Board & Referrals
                    </button>
                    <button id="btn-events" class="tab-btn pill-tab-btn" onclick="toggleTab('events')">
                        <span style="margin-right:5px; font-size:1.1rem;">🎉</span> Events & Reunions
                    </button>
                    <?php if ($user['role'] === 'alumni'): ?>
                        <button id="btn-myprofile" class="tab-btn pill-tab-btn" onclick="toggleTab('myprofile')">
                            <span style="margin-right:5px; font-size:1.1rem;">👤</span> My Profile
                        </button>
                        <button id="btn-giveback" class="tab-btn pill-tab-btn" onclick="toggleTab('giveback')">
                            <span style="margin-right:5px; font-size:1.1rem;">🎁</span> Give Back
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Tab: Directory -->
            <div id="directory" class="tab-content">
                <div class="grid grid-cols-3 gap-4" style="grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));">
                    <?php foreach($alumni_list as $alumnus): ?>
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-bold text-lg text-cit-blue"><?= htmlspecialchars($alumnus['username']) ?></h3>
                                <?php if($alumnus['mentor_opt_in']): ?>
                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded font-bold">Available to Mentor</span>
                                <?php endif; ?>
                            </div>
                            <div class="text-sm text-gray-600 space-y-1 mb-4">
                                <p><strong>🎓 Graduated:</strong> <?= htmlspecialchars($alumnus['graduation_year'] ?? 'N/A') ?></p>
                                <p><strong>📚 Degree:</strong> <?= htmlspecialchars($alumnus['degree'] ?? 'N/A') ?></p>
                                <p><strong>💼 Current Role:</strong> <?= htmlspecialchars($alumnus['job_title'] ?? 'N/A') ?> at <?= htmlspecialchars($alumnus['company'] ?? 'N/A') ?></p>
                            </div>
                            <?php if($alumnus['bio']): ?>
                                <p class="text-sm text-gray-700 italic border-l-4 border-gray-200 pl-3 mb-4"><?= htmlspecialchars($alumnus['bio']) ?></p>
                            <?php endif; ?>
                            <div class="flex gap-2">
                                <a href="mailto:<?= htmlspecialchars($alumnus['email']) ?>" class="btn btn-primary" style="padding: 0.25rem 0.75rem; font-size: 0.75rem;">Email</a>
                                <?php if($alumnus['linkedin_url']): ?>
                                    <a href="<?= htmlspecialchars($alumnus['linkedin_url']) ?>" target="_blank" class="btn btn-primary" style="background:#0077b5; border:none; padding: 0.25rem 0.75rem; font-size: 0.75rem;">LinkedIn</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Tab: Job Board -->
            <div id="jobs" class="tab-content" style="display:none;">
                <?php if ($user['role'] === 'alumni'): ?>
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-6">
                        <h3 class="font-bold text-lg mb-4">Post a Job or Referral</h3>
                        <form action="<?= BASE_URL ?>alumninetwork" method="POST" class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <input type="hidden" name="action" value="post_job">
                            <div><label class="block text-sm mb-1">Company</label><input type="text" name="company" class="form-input w-full" value="<?= htmlspecialchars($alumni_profile['company'] ?? '') ?>" required></div>
                            <div><label class="block text-sm mb-1">Position / Title</label><input type="text" name="position" class="form-input w-full" required></div>
                            <div style="grid-column: span 2;"><label class="block text-sm mb-1">Job Description</label><textarea name="description" class="form-input w-full" rows="3" required></textarea></div>
                            <div style="grid-column: span 2;"><label class="block text-sm mb-1">Application Link (or Email)</label><input type="text" name="link" class="form-input w-full" required></div>
                            <div><button type="submit" class="btn btn-primary">Post Opportunity</button></div>
                        </form>
                    </div>
                <?php endif; ?>

                <div class="grid gap-4">
                    <?php if(empty($jobs)): ?><p class="text-gray-500">No jobs posted yet.</p><?php endif; ?>
                    <?php foreach($jobs as $job): ?>
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-l-4 border-cit-blue">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-bold text-xl mb-1"><?= htmlspecialchars($job['position']) ?> <span class="text-gray-500 font-normal text-sm ml-2">at <?= htmlspecialchars($job['company']) ?></span></h3>
                                    <p class="text-sm text-gray-500 mb-3">Posted by <?= htmlspecialchars($job['poster_name']) ?> on <?= date('M d, Y', strtotime($job['created_at'])) ?></p>
                                </div>
                                <a href="<?= htmlspecialchars($job['link']) ?>" target="_blank" class="btn btn-primary">Apply Now</a>
                            </div>
                            <p class="text-sm text-gray-800 whitespace-pre-wrap"><?= htmlspecialchars($job['description']) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Tab: Events -->
            <div id="events" class="tab-content" style="display:none;">
                <h2 class="text-2xl font-bold mb-6">Upcoming Reunions & Alumni Events</h2>
                <div class="grid gap-4">
                    <?php if(empty($alumni_events)): ?><p class="text-gray-500">No upcoming events specifically tailored for alumni.</p><?php endif; ?>
                    <?php foreach($alumni_events as $event): ?>
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                            <h3 class="font-bold text-lg mb-1"><?= htmlspecialchars($event['title']) ?></h3>
                            <div class="text-sm text-gray-500 mb-3">📅 <?= date('l, F j, Y \a\t h:i A', strtotime($event['event_date'])) ?> | 📍 <?= htmlspecialchars($event['venue']) ?></div>
                            <p class="text-sm text-gray-800"><?= nl2br(htmlspecialchars($event['description'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if ($user['role'] === 'alumni'): ?>
                <!-- Tab: My Profile -->
                <div id="myprofile" class="tab-content" style="display:none;">
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 max-w-2xl">
                        <h3 class="font-bold text-lg mb-4">Update Alumni Profile</h3>
                        <form action="<?= BASE_URL ?>alumninetwork" method="POST" class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <input type="hidden" name="action" value="update_profile">
                            <div><label class="block text-sm mb-1">Graduation Year</label><input type="number" name="graduation_year" class="form-input w-full" value="<?= htmlspecialchars($alumni_profile['graduation_year'] ?? '') ?>"></div>
                            <div><label class="block text-sm mb-1">Degree Earned</label><input type="text" name="degree" class="form-input w-full" value="<?= htmlspecialchars($alumni_profile['degree'] ?? '') ?>"></div>
                            <div><label class="block text-sm mb-1">Current Company</label><input type="text" name="company" class="form-input w-full" value="<?= htmlspecialchars($alumni_profile['company'] ?? '') ?>"></div>
                            <div><label class="block text-sm mb-1">Job Title</label><input type="text" name="job_title" class="form-input w-full" value="<?= htmlspecialchars($alumni_profile['job_title'] ?? '') ?>"></div>
                            <div style="grid-column: span 2;"><label class="block text-sm mb-1">LinkedIn Profile URL</label><input type="url" name="linkedin_url" class="form-input w-full" value="<?= htmlspecialchars($alumni_profile['linkedin_url'] ?? '') ?>"></div>
                            <div style="grid-column: span 2;"><label class="block text-sm mb-1">Short Bio</label><textarea name="bio" class="form-input w-full" rows="3"><?= htmlspecialchars($alumni_profile['bio'] ?? '') ?></textarea></div>
                            <div style="grid-column: span 2;" class="flex items-center gap-2 mt-2">
                                <input type="checkbox" name="mentor_opt_in" id="mentor_opt_in" value="1" <?= ($alumni_profile['mentor_opt_in'] ?? 0) ? 'checked' : '' ?> style="width:16px;height:16px;">
                                <label for="mentor_opt_in" class="text-sm font-bold text-green-700">Opt-in to mentor current students and recent graduates</label>
                            </div>
                            <div style="grid-column: span 2;"><button type="submit" class="btn btn-primary">Save Profile</button></div>
                        </form>
                    </div>
                </div>

                <!-- Tab: Give Back -->
                <div id="giveback" class="tab-content" style="display:none;">
                    <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                            <h3 class="font-bold text-2xl mb-2 text-cit-blue">Support the University</h3>
                            <p class="text-gray-600 mb-6">Your contributions help fund scholarships, campus development, and research initiatives. The total alumni endowment currently stands at <strong class="text-green-600 text-lg">₹<?= number_format($total_donations, 2) ?></strong>.</p>
                            
                            <form action="<?= BASE_URL ?>alumninetwork" method="POST">
                                <input type="hidden" name="action" value="donate">
                                <div class="mb-4">
                                    <label class="block text-sm mb-1">Donation Amount (₹)</label>
                                    <input type="number" step="0.01" name="amount" class="form-input w-full" required>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm mb-1">Purpose / Designation</label>
                                    <select name="purpose" class="form-input w-full" required>
                                        <option value="General Endowment">General Endowment</option>
                                        <option value="Scholarship Fund">Scholarship Fund</option>
                                        <option value="Infrastructure Development">Infrastructure Development</option>
                                        <option value="Research Sponsorship">Research Sponsorship</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary w-full" style="background:#059669; border-color:#059669;">Donate Securely</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>
<script src="<?= BASE_URL ?>js/sidebar.js?v=1787059998"></script>
</body>
</html>
