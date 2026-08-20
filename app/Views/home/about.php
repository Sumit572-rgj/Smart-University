<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - CIT UMS</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { margin: 0; font-family: 'Inter', sans-serif; background: #f8fafc; }
        .top-nav { display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 5%; background: white; border-bottom: 1px solid #e2e8f0; position: fixed; width: 100%; top: 0; z-index: 1000; box-sizing: border-box; }
        .nav-links a { margin: 0 1.5rem; text-decoration: none; color: #334155; font-weight: 600; transition: color 0.3s; }
        .nav-links a:hover { color: var(--cit-orange); }
                .about-container { margin-top: 100px; padding: 4rem 2rem; min-height: 80vh; }

        .dev-profile-wrapper {
            width: 150px; 
            height: 150px; 
            border-radius: 50%; 
            overflow: hidden; 
            margin-bottom: 1.5rem; 
            border: 4px solid var(--cit-orange); 
            box-shadow: 0 4px 10px rgba(249,115,22,0.2);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
        }
        
        .dev-profile-wrapper:hover {
            transform: scale(1.1) translateY(-5px);
            box-shadow: 0 15px 30px rgba(249,115,22,0.6), 0 0 20px rgba(249,115,22,0.4);
            border-color: #ff983f;
        }

        .dev-profile-wrapper img {
            width: 100%; 
            height: 100%; 
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .dev-profile-wrapper:hover img {
            transform: scale(1.05);
        }
    </style>
</head>
<body>

    <nav class="top-nav">
        <div style="font-weight: 800; font-size: 1.5rem; color: #0f172a; letter-spacing: -1px;">
            CIT<span>UMS</span>
        </div>
        <div class="nav-links">
            <a href="/cit_ums">Home</a>
            <a href="/cit_ums#features">Features</a>
            <a href="<?= BASE_URL ?>about" style="color: var(--cit-orange);">About</a>
            <a href="/cit_ums#contact">Contact</a>
        </div>
        <a href="<?= BASE_URL ?>auth/login" class="login-btn-top" style="background: var(--cit-orange); color: white; padding: 0.5rem 1.5rem; border-radius: 999px; text-decoration: none; font-weight: 600;">Login</a>
    </nav>

    <div class="about-container">
        <!-- About Us / Developer -->
    <section id="about" class="features-section" style="background: #f8fafc;">
        <h2 class="section-title animate-on-scroll">About the Developer</h2>
        <div style="max-width: 800px; margin: 0 auto; background: white; border-radius: 1rem; padding: 3rem; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); display: flex; flex-direction: column; align-items: center; text-align: center; border: 1px solid #e2e8f0;" class="animate-on-scroll delay-100">
            <div class="dev-profile-wrapper">
                <img src="<?= BASE_URL ?>public/developer.jpg" alt="Developer Profile" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            
            <p style="color: var(--cit-orange); font-weight: 600; font-size: 1.1rem; margin-bottom: 1.5rem;">Meet The Developer</p>
            <p style="color: #64748b; line-height: 1.7; font-size: 1.05rem; margin-bottom: 2rem; max-width: 600px;">
                Passionate about building highly scalable, modern web applications that solve real-world problems. Designed CIT UMS from the ground up to revolutionize university management with clean architecture and seamless UX.
            </p>
            
            <div style="display: flex; gap: 1.5rem; justify-content: center; flex-wrap: wrap;">
                <a href="https://github.com/Sumit572-rgj" style="background: #24292e; color: white; padding: 0.75rem 1.5rem; border-radius: 999px; text-decoration: none; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem; transition: transform 0.2s;"> GitHub </a>
                <a href="https://www.linkedin.com/in/sumit-kumar-chaurasiya-041a77429" style="background: #0a66c2; color: white; padding: 0.75rem 1.5rem; border-radius: 999px; text-decoration: none; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem; transition: transform 0.2s;"> LinkedIn </a>
                <a href="https://www.instagram.com/rhythm773_1/" style="background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); color: white; padding: 0.75rem 1.5rem; border-radius: 999px; text-decoration: none; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem; transition: transform 0.2s;"> Instagram </a>
                <a href="https://leetcode.com/u/SUMIT773/" style="background: #ffa116; color: white; padding: 0.75rem 1.5rem; border-radius: 999px; text-decoration: none; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem; transition: transform 0.2s;"> LeetCode </a>
                <a href="#" style="background: #1da1f2; color: white; padding: 0.75rem 1.5rem; border-radius: 999px; text-decoration: none; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem; transition: transform 0.2s;">
                    Twitter/X
                </a>
            </div>
        </div>
    </section>

    
    </div>

</body>
</html>
