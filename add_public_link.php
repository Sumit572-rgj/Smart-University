<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/auth/login.php';
$c = file_get_contents($f);

$pattern = '/<a href="\/cit_ums\/auth\/forgot" style="font-size: 0\.875rem; color: #f97316; text-decoration: none; font-weight: 600;">Forgot password\?<\/a>/';
$newLinks = <<<'HTML'
                <a href="/cit_ums/auth/forgot" style="font-size: 0.875rem; color: #f97316; text-decoration: none; font-weight: 600;">Forgot password?</a>
            </div>
            
            <!-- Quick Links -->
            <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px dashed #e2e8f0; text-align: center;">
                <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 0.5rem; font-weight: 600;">STUDENT QUICK LINKS</p>
                <a href="/cit_ums/exam/results" style="display: inline-block; padding: 0.5rem 1rem; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 0.5rem; color: #334155; text-decoration: none; font-size: 0.9rem; font-weight: 600; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                    🎓 Check Exam Results
                </a>
HTML;

$c = preg_replace($pattern, $newLinks, $c);
file_put_contents($f, $c);
echo "Added public results link to login page.\n";
