<!-- Mobile Hamburger Button -->
<button id="mobile-menu-btn" style="position: fixed; top: 1rem; left: 1rem; z-index: 1000; background: var(--cit-blue); color: white; border: none; padding: 0.5rem; border-radius: 0.25rem; cursor: pointer; display: none;">
    <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
</button>

<!-- Mobile Overlay -->
<div id="mobile-overlay" style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 90; display: none;"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('mobile-menu-btn');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.getElementById('mobile-overlay');
    
    // Check if we are on mobile
    if (window.innerWidth <= 768) {
        btn.style.display = 'block';
    }
    window.addEventListener('resize', () => {
        btn.style.display = window.innerWidth <= 768 ? 'block' : 'none';
    });

    btn.addEventListener('click', () => {
        sidebar.style.left = '0px';
        overlay.style.display = 'block';
    });

    overlay.addEventListener('click', () => {
        sidebar.style.left = '-260px';
        overlay.style.display = 'none';
    });
});
</script>
<?php $uri = $_SERVER['REQUEST_URI']; ?>
<div class="sidebar-overlay" onclick="document.querySelector('.sidebar').classList.remove('open'); this.classList.remove('open');"></div>
    <aside class="sidebar" id="chaos-sidebar">
        <div class="sidebar-header">CIT UMS</div>
        <nav class="sidebar-nav" style="overflow-y: auto; max-height: calc(100vh - 80px);">
            <?php if($user['role'] !== 'security'): ?>
                <a href="<?= BASE_URL ?>dashboard" class="nav-link <?= strpos($uri, '/cit_ums/dashboard') === 0 ? 'active' : '' ?>" ><span style="margin-right:8px; font-size:1.1rem;">🏠</span>Dashboard</a>
            <a href="<?= BASE_URL ?>message" class="nav-link <?= strpos($uri, '/cit_ums/message') === 0 ? 'active' : '' ?>" ><span style="margin-right:8px; font-size:1.1rem;">💬</span>Messages & Forums</a>
            <?php endif; ?>

            <?php if($user['role'] === 'admin'): ?>
                <div class="text-xs text-gray-500 font-bold uppercase mt-4 mb-2 px-4">Admin Features</div>
                  <a href="<?= BASE_URL ?>hostelattendance" class="nav-link <?= strpos($uri, '/cit_ums/hostelattendance') === 0 ? 'active' : '' ?>"><span style="margin-right:8px; font-size:1.1rem;">🌙</span>Hostel Roll Call</a>
                <a href="<?= BASE_URL ?>student" class="nav-link <?= strpos($uri, '/cit_ums/student') === 0 ? 'active' : '' ?>" ><span style="margin-right:8px; font-size:1.1rem;">🎓</span>Student Management</a>
                <a href="<?= BASE_URL ?>faculty" class="nav-link <?= strpos($uri, '/cit_ums/faculty') === 0 ? 'active' : '' ?>" ><span style="margin-right:8px; font-size:1.1rem;">👨‍🏫</span>Faculty Management</a>
                <a href="<?= BASE_URL ?>warden" class="nav-link <?= strpos($uri, '/cit_ums/warden') === 0 ? 'active' : '' ?>" ><span style="margin-right:8px; font-size:1.1rem;">🛡️</span>Warden Management</a>
                <a href="<?= BASE_URL ?>securityguard" class="nav-link <?= strpos($uri, '/cit_ums/securityguard') === 0 ? 'active' : '' ?>" ><span style="margin-right:8px; font-size:1.1rem;">👮</span>Security Guard Management</a>
                  <a href="<?= BASE_URL ?>fee" class="nav-link <?= strpos($uri, '/cit_ums/fee') === 0 ? 'active' : '' ?>" ><span style="margin-right:8px; font-size:1.1rem;">💸</span>Finance & Fees</a>
                <a href="<?= BASE_URL ?>hostel" class="nav-link <?= strpos($uri, '/cit_ums/hostel') === 0 ? 'active' : '' ?>" ><span style="margin-right:8px; font-size:1.1rem;">🏢</span>Hostel Management</a>
                <a href="<?= BASE_URL ?>library" class="nav-link <?= strpos($uri, '/cit_ums/library') === 0 ? 'active' : '' ?>" ><span style="margin-right:8px; font-size:1.1rem;">📚</span>Library Management</a>
                <a href="<?= BASE_URL ?>exam" class="nav-link <?= strpos($uri, '/cit_ums/exam') === 0 ? 'active' : '' ?>" ><span style="margin-right:8px; font-size:1.1rem;">📝</span>Exam Management</a>
                <a href="<?= BASE_URL ?>transport/admin" class="nav-link <?= strpos($uri, '/cit_ums/transport/admin') === 0 ? 'active' : '' ?>" ><span style="margin-right:8px; font-size:1.1rem;">🚌</span>Transport Mgmt</a>
                <a href="<?= BASE_URL ?>placement" class="nav-link <?= strpos($uri, '/cit_ums/placement') === 0 ? 'active' : '' ?>" ><span style="margin-right:8px; font-size:1.1rem;">💼</span>Placement Center</a>
                <a href="<?= BASE_URL ?>event/admin" class="nav-link <?= strpos($uri, '/cit_ums/event/admin') === 0 ? 'active' : '' ?>" ><span style="margin-right:8px; font-size:1.1rem;">📌</span>Notice Board</a>
                
                <a href="<?= BASE_URL ?>course" class="nav-link <?= strpos($uri, '/cit_ums/course') === 0 ? 'active' : '' ?>" ><span style="margin-right:8px; font-size:1.1rem;">📘</span>Course Management</a>
            <?php endif; ?>

            <?php if($user['role'] === 'faculty'): ?>
                <div class="text-xs text-gray-500 font-bold uppercase mt-4 mb-2 px-4">Faculty Features</div>
                <a href="<?= BASE_URL ?>student" class="nav-link <?= strpos($uri, '/cit_ums/student') === 0 ? 'active' : '' ?>" ><span style="margin-right:8px; font-size:1.1rem;">🎓</span>Student Management</a>
                <a href="<?= BASE_URL ?>dashboard" class="nav-link <?= strpos($uri, '/cit_ums/dashboard') === 0 ? 'active' : '' ?>" >My Timetable</a>
                <a href="<?= BASE_URL ?>attendance" class="nav-link <?= strpos($uri, '/cit_ums/attendance') === 0 ? 'active' : '' ?>" >Attendance Entry</a>
                <a href="<?= BASE_URL ?>exam" class="nav-link <?= strpos($uri, '/cit_ums/exam') === 0 ? 'active' : '' ?>" >Exams & Grading</a>
                <a href="<?= BASE_URL ?>assignmentuploads" class="nav-link <?= strpos($uri, '/cit_ums/assignmentuploads') === 0 ? 'active' : '' ?>" >Assignment Uploads</a>
                
                <a href="<?= BASE_URL ?>event" class="nav-link <?= strpos($uri, '/cit_ums/event') === 0 ? 'active' : '' ?>" ><span style="margin-right:8px; font-size:1.1rem;">📌</span>Notice Board</a>
                <a href="<?= BASE_URL ?>course" class="nav-link <?= strpos($uri, '/cit_ums/course') === 0 ? 'active' : '' ?>" >My Courses</a>
            <?php endif; ?>

            <?php if($user['role'] === 'student'): ?>
                <div class="text-xs text-gray-500 font-bold uppercase mt-4 mb-2 px-4">Student Features</div>
                <a href="<?= BASE_URL ?>student/profile" class="nav-link <?= strpos($uri, '/cit_ums/student/profile') === 0 ? 'active' : '' ?>" >My Profile</a>
                <a href="<?= BASE_URL ?>fee" class="nav-link <?= strpos($uri, '/cit_ums/fee') === 0 ? 'active' : '' ?>" >My Fees & Payments</a>
                <a href="<?= BASE_URL ?>attendance" class="nav-link <?= strpos($uri, '/cit_ums/attendance') === 0 ? 'active' : '' ?>" >My Attendance</a>
                <a href="<?= BASE_URL ?>exam" class="nav-link <?= strpos($uri, '/cit_ums/exam') === 0 ? 'active' : '' ?>" >Exams & Results</a>
                <a href="<?= BASE_URL ?>hostel" class="nav-link <?= strpos($uri, '/cit_ums/hostel') === 0 ? 'active' : '' ?>" >Hostel Allocation</a>
                <a href="<?= BASE_URL ?>outpass" class="nav-link <?= strpos($uri, '/cit_ums/outpass') === 0 ? 'active' : '' ?>" >Outpass Requests</a>
                <a href="<?= BASE_URL ?>roommaintenance" class="nav-link <?= strpos($uri, '/cit_ums/roommaintenance') === 0 ? 'active' : '' ?>" ><!-- Room Maintenance Link -->Room Maintenance</a>
                <a href="<?= BASE_URL ?>assignmentsubmissions" class="nav-link <?= strpos($uri, '/cit_ums/assignmentsubmissions') === 0 ? 'active' : '' ?>" >Assignment Submissions</a>
                <a href="<?= BASE_URL ?>library" class="nav-link <?= strpos($uri, '/cit_ums/library') === 0 ? 'active' : '' ?>" >E-Library</a>
                <a href="<?= BASE_URL ?>event" class="nav-link <?= strpos($uri, '/cit_ums/event') === 0 ? 'active' : '' ?>" ><span style="margin-right:8px; font-size:1.1rem;">📌</span>Notice Board</a>
                <a href="<?= BASE_URL ?>course" class="nav-link <?= strpos($uri, '/cit_ums/course') === 0 ? 'active' : '' ?>" >My Courses & Timetable</a>
            <?php endif; ?>

            <?php if($user['role'] === 'parent'): ?>
                <div class="text-xs text-gray-500 font-bold uppercase mt-4 mb-2 px-4">Parent Features</div>
                <a href="<?= BASE_URL ?>parent" class="nav-link <?= strpos($uri, '/cit_ums/parent') === 0 ? 'active' : '' ?>" >My Children & Grades</a>
            <?php endif; ?>

            <?php if($user['role'] === 'warden'): ?>
                <div class="text-xs text-gray-500 font-bold uppercase mt-4 mb-2 px-4">Warden Features</div>
                <a href="<?= BASE_URL ?>hostel" class="nav-link <?= strpos($uri, '/cit_ums/hostel') === 0 ? 'active' : '' ?>" ><span style="margin-right:8px; font-size:1.1rem;">🏢</span>Hostel Management</a>
                <a href="<?= BASE_URL ?>outpass" class="nav-link <?= strpos($uri, '/cit_ums/outpass') === 0 ? 'active' : '' ?>" >Outpass Approvals</a>
                <a href="<?= BASE_URL ?>roommaintenance" class="nav-link <?= strpos($uri, '/cit_ums/roommaintenance') === 0 ? 'active' : '' ?>" ><!-- Room Maintenance Link -->Room Maintenance Tracking</a>
                <a href="<?= BASE_URL ?>hostelgrievances" class="nav-link <?= strpos($uri, '/cit_ums/hostelgrievances') === 0 ? 'active' : '' ?>" >Hostel Grievances</a>
                <a href="<?= BASE_URL ?>hostelinventory" class="nav-link <?= strpos($uri, '/cit_ums/hostelinventory') === 0 ? 'active' : '' ?>" >Hostel Inventory</a>
                <a href="<?= BASE_URL ?>messandcafeteria" class="nav-link <?= strpos($uri, '/cit_ums/messandcafeteria') === 0 ? 'active' : '' ?>" >Mess & Cafeteria</a>
                <a href="<?= BASE_URL ?>event/admin" class="nav-link <?= strpos($uri, '/cit_ums/event/admin') === 0 ? 'active' : '' ?>" ><span style="margin-right:8px; font-size:1.1rem;">📌</span>Notice Board</a>
                
            <?php endif; ?>

            <?php if($user['role'] === 'security'): ?>
                <a href="<?= BASE_URL ?>gate" class="nav-link <?= strpos($uri, '/cit_ums/gate') === 0 ? 'active' : '' ?>" >Scanner Dashboard</a>
            <?php endif; ?>
        </nav>
    </aside>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const links = document.querySelectorAll('.sidebar-nav .nav-link');
        const currentPath = window.location.pathname;
        links.forEach(link => {
            if (link.getAttribute('href') === currentPath || (currentPath.startsWith(link.getAttribute('href')) && link.getAttribute('href') !== '/cit_ums/dashboard')) {
                link.classList.add('active');
            } else if (currentPath === '/cit_ums/dashboard' && link.getAttribute('href') === '/cit_ums/dashboard') {
                link.classList.add('active');
            }
        });
    });
</script>
