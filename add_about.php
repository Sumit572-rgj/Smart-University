<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/home/index.php';
$c = file_get_contents($f);

$aboutSection = <<<HTML
    <!-- About Us / Developer -->
    <section id="about" class="features-section" style="background: #f8fafc;">
        <h2 class="section-title animate-on-scroll">About the Developer</h2>
        <div style="max-width: 800px; margin: 0 auto; background: white; border-radius: 1rem; padding: 3rem; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); display: flex; flex-direction: column; align-items: center; text-align: center; border: 1px solid #e2e8f0;" class="animate-on-scroll delay-100">
            <div style="width: 150px; height: 150px; border-radius: 50%; overflow: hidden; margin-bottom: 1.5rem; border: 4px solid var(--cit-orange); box-shadow: 0 4px 10px rgba(249,115,22,0.2);">
                <img src="developer.jpg" alt="Developer Profile" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <h3 style="font-size: 1.8rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem;">Antigravity Developer</h3>
            <p style="color: var(--cit-orange); font-weight: 600; font-size: 1.1rem; margin-bottom: 1.5rem;">Meet The Developer</p>
            <p style="color: #64748b; line-height: 1.7; font-size: 1.05rem; margin-bottom: 2rem; max-width: 600px;">
                Passionate about building highly scalable, modern web applications that solve real-world problems. Designed CIT UMS from the ground up to revolutionize university management with clean architecture and seamless UX.
            </p>
            
            <div style="display: flex; gap: 1.5rem; justify-content: center; flex-wrap: wrap;">
                <a href="https://github.com/Sumit572-rgj" style="background: #24292e; color: white; padding: 0.75rem 1.5rem; border-radius: 999px; text-decoration: none; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem; transition: transform 0.2s;">
                    GitHub
                </a>
                <a href="www.linkedin.com/in/sumit-kumar-chaurasiya-041a77429" style="background: #0a66c2; color: white; padding: 0.75rem 1.5rem; border-radius: 999px; text-decoration: none; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem; transition: transform 0.2s;">
                    LinkedIn
                </a>
                <a href="https://www.instagram.com/rhythm773_1/" style="background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); color: white; padding: 0.75rem 1.5rem; border-radius: 999px; text-decoration: none; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem; transition: transform 0.2s;">
                    Instagram
                </a>
                <a href="https://leetcode.com/u/SUMIT773/" style="background: #ffa116; color: white; padding: 0.75rem 1.5rem; border-radius: 999px; text-decoration: none; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem; transition: transform 0.2s;">
                    LeetCode

            </div>
        </div>
    </section>

    <!-- Footer -->
HTML;

$c = str_replace('<!-- Footer -->', $aboutSection, $c);
file_put_contents($f, $c);
echo "Added About section.\n";
