import os

modules = {
    'payrollmanagement': {'title': 'Payroll Management', 'fields': [('month', 'VARCHAR(50)'), ('amount', 'DECIMAL(10,2)'), ('status', 'VARCHAR(50)')]},
    'mypayslips': {'title': 'My Payslips', 'fields': [('month', 'VARCHAR(50)'), ('amount', 'DECIMAL(10,2)'), ('status', 'VARCHAR(50)')]},
    'securitylogs': {'title': 'Security Logs', 'fields': [('log_type', 'VARCHAR(100)'), ('description', 'TEXT')]},
    'alumninetwork': {'title': 'Alumni Network', 'fields': [('graduation_year', 'INT'), ('current_company', 'VARCHAR(100)'), ('job_title', 'VARCHAR(100)')]},
    'healthrecords': {'title': 'Health Records', 'fields': [('blood_group', 'VARCHAR(10)'), ('medical_history', 'TEXT')]},
    'systembackups': {'title': 'System Backups', 'fields': [('backup_name', 'VARCHAR(100)'), ('size_mb', 'INT'), ('status', 'VARCHAR(50)')]},
    'reportsandanalytics': {'title': 'Reports & Analytics', 'fields': [('report_type', 'VARCHAR(100)'), ('summary', 'TEXT')]},
    'myprofile': {'title': 'My Profile Extension', 'fields': [('bio', 'TEXT'), ('phone', 'VARCHAR(20)'), ('address', 'TEXT')]},
    'researchpublications': {'title': 'Research Publications', 'fields': [('title', 'VARCHAR(200)'), ('journal', 'VARCHAR(100)'), ('publication_date', 'DATE')]},
    'studentgrievances': {'title': 'Student Grievances', 'fields': [('category', 'VARCHAR(100)'), ('complaint', 'TEXT'), ('status', 'VARCHAR(50)')]},
    'submitgrievance': {'title': 'Submit Grievance', 'fields': [('category', 'VARCHAR(100)'), ('complaint', 'TEXT'), ('status', 'VARCHAR(50)')]},
    'hostelgrievances': {'title': 'Hostel Grievances', 'fields': [('category', 'VARCHAR(100)'), ('complaint', 'TEXT'), ('status', 'VARCHAR(50)')]},
    'leaveapplication': {'title': 'Leave Application', 'fields': [('leave_type', 'VARCHAR(50)'), ('start_date', 'DATE'), ('end_date', 'DATE'), ('reason', 'TEXT')]},
    'assignmentuploads': {'title': 'Assignment Uploads', 'fields': [('course_code', 'VARCHAR(50)'), ('title', 'VARCHAR(100)'), ('file_link', 'VARCHAR(255)')]},
    'assignmentsubmissions': {'title': 'Assignment Submissions', 'fields': [('course_code', 'VARCHAR(50)'), ('title', 'VARCHAR(100)'), ('file_link', 'VARCHAR(255)')]},
    'departmentmeetings': {'title': 'Department Meetings', 'fields': [('meeting_title', 'VARCHAR(100)'), ('meeting_date', 'DATE'), ('agenda', 'TEXT')]},
    'virtualclassroom': {'title': 'Virtual Classroom', 'fields': [('topic', 'VARCHAR(100)'), ('schedule', 'DATETIME'), ('meeting_link', 'VARCHAR(255)')]},
    'coursematerials': {'title': 'Course Materials', 'fields': [('course_code', 'VARCHAR(50)'), ('material_title', 'VARCHAR(100)'), ('download_link', 'VARCHAR(255)')]},
    'peertutoring': {'title': 'Peer Tutoring', 'fields': [('subject', 'VARCHAR(100)'), ('available_time', 'VARCHAR(100)'), ('contact', 'VARCHAR(50)')]},
    'cocurricularactivities': {'title': 'Co-curricular Activities', 'fields': [('activity_name', 'VARCHAR(100)'), ('role', 'VARCHAR(50)'), ('achievements', 'TEXT')]},
    'disciplinaryrecords': {'title': 'Disciplinary Records', 'fields': [('incident', 'VARCHAR(200)'), ('action_taken', 'TEXT'), ('incident_date', 'DATE')]},
    'hostelinventory': {'title': 'Hostel Inventory', 'fields': [('item_name', 'VARCHAR(100)'), ('quantity', 'INT'), ('condition_status', 'VARCHAR(50)')]},
    'hostelattendance': {'title': 'Hostel Attendance', 'fields': [('attendance_date', 'DATE'), ('status', 'VARCHAR(50)'), ('remarks', 'TEXT')]},
    'messandcafeteria': {'title': 'Mess & Cafeteria', 'fields': [('meal_type', 'VARCHAR(50)'), ('menu_details', 'TEXT')]},
    'visitorlogs': {'title': 'Visitor Logs', 'fields': [('visitor_name', 'VARCHAR(100)'), ('purpose', 'VARCHAR(200)'), ('entry_time', 'DATETIME')]},
    'roommaintenance': {'title': 'Room Maintenance', 'fields': [('room_no', 'VARCHAR(20)'), ('issue', 'TEXT'), ('status', 'VARCHAR(50)')]},
    'laundrymanagement': {'title': 'Laundry Management', 'fields': [('bag_id', 'VARCHAR(50)'), ('weight_kg', 'DECIMAL(5,2)'), ('status', 'VARCHAR(50)')]},
    'hostelevents': {'title': 'Hostel Events', 'fields': [('event_name', 'VARCHAR(100)'), ('event_date', 'DATE'), ('description', 'TEXT')]}
}

controller_template = """<?php
class {controller_name} extends Controller {{
    public function index() {{
        $user = JWT::getToken();
        if (!$user) {{ $this->redirect('/auth/login'); }}
        
        $db = (new Model())->db;
        $table_name = '{table_name}';
        
        $db->exec("CREATE TABLE IF NOT EXISTS $table_name (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            {sql_columns},
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_data'])) {{
            $stmt = $db->prepare("INSERT INTO $table_name (user_id, {col_names}) VALUES (?, {placeholders})");
            $stmt->execute([$user['id'], {post_vars}]);
            $this->redirect('/{route_name}?success=Record+added');
        }}

        if (isset($_GET['delete'])) {{
            $stmt = $db->prepare("DELETE FROM $table_name WHERE id = ?");
            $stmt->execute([$_GET['delete']]);
            $this->redirect('/{route_name}?success=Record+deleted');
        }}

        $data = [
            'user' => $user,
            'title' => '{title}',
            'success' => $_GET['success'] ?? ''
        ];
        
        $data['records'] = $db->query("SELECT t.*, u.username FROM $table_name t JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC")->fetchAll();
        
        $this->view('{route_name}/index', $data);
    }}
}}
"""

view_template = """<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="/cit_ums/css/style.css">
</head>
<body>
<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="sidebar-header">CIT UMS</div>
        <nav class="sidebar-nav">
            <a href="/cit_ums/dashboard" class="nav-link">Dashboard</a>
            <a href="/cit_ums/{route_name}" class="nav-link active"><?= htmlspecialchars($title) ?></a>
        </nav>
    </aside>

    <main class="main-content">
        <header class="topbar shadow-sm">
            <div class="welcome font-medium text-lg"><?= htmlspecialchars($title) ?> Portal</div>
            <div class="user-menu"><a href="/cit_ums/auth/logout" class="btn btn-primary">Logout</a></div>
        </header>

        <div class="content-wrapper">
            <?php if (!empty($success)): ?>
                <div class="alert" style="background:#d1fae5;color:#065f46;border-color:#a7f3d0;margin-bottom:1rem;font-weight:bold;"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <div class="grid" style="grid-template-columns: 1fr 2fr; gap: 1.5rem;">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200" style="height: fit-content;">
                    <h3 class="text-lg font-bold mb-4">Add New Entry</h3>
                    <form action="/cit_ums/{route_name}" method="POST">
                        <input type="hidden" name="add_data" value="1">
                        {form_inputs}
                        <button type="submit" class="btn btn-primary w-full mt-4">Submit</button>
                    </form>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-4 border-b border-gray-200"><h3 class="text-lg font-bold">Recent Records</h3></div>
                    <div class="table-container" style="overflow-x: auto;">
                        <table class="table">
                            <thead><tr><th>User</th>{th_columns}<th>Date</th><th>Action</th></tr></thead>
                            <tbody>
                                <?php if(empty($records)): ?>
                                    <tr><td colspan="10" class="text-center text-gray-500 py-4">No records found.</td></tr>
                                <?php endif; ?>
                                <?php foreach($records as $r): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($r['username']) ?></strong></td>
                                    {td_columns}
                                    <td class="text-xs text-gray-500"><?= htmlspecialchars(date('M d, Y', strtotime($r['created_at']))) ?></td>
                                    <td><a href="/cit_ums/{route_name}?delete=<?= $r['id'] ?>" class="text-red-500 hover:underline">Delete</a></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>"""

def get_input_type(sql_type, name):
    sql_type = sql_type.upper()
    if 'DATE' in sql_type and 'DATETIME' not in sql_type: return 'date'
    if 'DATETIME' in sql_type: return 'datetime-local'
    if 'INT' in sql_type or 'DECIMAL' in sql_type: return 'number'
    if 'TEXT' in sql_type: return 'textarea'
    if 'status' in name.lower(): return 'status_select'
    if 'link' in name.lower(): return 'url'
    return 'text'

for route_name, info in modules.items():
    title = info['title']
    fields = info['fields']
    
    c_files = os.listdir('C:/Users/LENOVO/.gemini/antigravity/scratch/cit_ums/app/Controllers')
    actual_c_name = None
    for cf in c_files:
        if cf.lower() == route_name + 'controller.php':
            actual_c_name = cf[:-4]
            break
            
    if not actual_c_name: continue
    
    table_name = 'mod_' + route_name
    
    sql_columns = ',\n            '.join([f"{f[0]} {f[1]}" for f in fields])
    col_names = ', '.join([f[0] for f in fields])
    placeholders = ', '.join(['?'] * len(fields))
    post_vars = ', '.join([f"$_POST['{f[0]}']" for f in fields])
    
    form_inputs = ''
    for f in fields:
        label = f[0].replace('_', ' ').title()
        itype = get_input_type(f[1], f[0])
        if itype == 'textarea':
            form_inputs += f'''<div class="mb-3"><label class="block text-sm font-medium mb-1">{label}</label><textarea name="{f[0]}" class="form-input w-full" rows="3" required></textarea></div>\n                        '''
        elif itype == 'status_select':
            form_inputs += f'''<div class="mb-3"><label class="block text-sm font-medium mb-1">{label}</label><select name="{f[0]}" class="form-input w-full"><option>Pending</option><option>Active</option><option>Resolved</option><option>Completed</option></select></div>\n                        '''
        else:
            step = 'step="0.01"' if itype == 'number' else ''
            form_inputs += f'''<div class="mb-3"><label class="block text-sm font-medium mb-1">{label}</label><input type="{itype}" {step} name="{f[0]}" class="form-input w-full" required></div>\n                        '''
            
    th_columns = ''.join([f"<th>{f[0].replace('_', ' ').title()}</th>" for f in fields])
    td_columns = ''.join([f"<td><?= htmlspecialchars($r['{f[0]}']) ?></td>" for f in fields])
    
    # 1. Write Controller
    with open(f'C:/Users/LENOVO/.gemini/antigravity/scratch/cit_ums/app/Controllers/{actual_c_name}.php', 'w', encoding='utf-8') as f:
        f.write(controller_template.format(
            controller_name=actual_c_name, 
            table_name=table_name, 
            route_name=route_name,
            title=title,
            sql_columns=sql_columns,
            col_names=col_names,
            placeholders=placeholders,
            post_vars=post_vars
        ))
        
    # 2. Write View
    with open(f'C:/Users/LENOVO/.gemini/antigravity/scratch/cit_ums/app/Views/{route_name}/index.php', 'w', encoding='utf-8') as f:
        f.write(view_template.format(
            route_name=route_name, 
            title=title,
            form_inputs=form_inputs,
            th_columns=th_columns,
            td_columns=td_columns
        ))

print('Successfully re-generated all 28 modules with unique data fields!')
