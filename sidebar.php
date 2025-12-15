<div class="sidebar">
    <div class="logo_content">
        <div class="logo">
            <img class='logo_aepm' src="./logo/aepm.png" style="height: 30px; width: 30px;" alt="Logo">
            <div class="logo_name">AEPM WEB SITE</div>
        </div>
        <i class='bx bx-menu' id="btn"></i>
    </div>
    <ul class="nav_list">
        
        <li>
            <i class='bx bx-search' ></i>
            <input type="text" placeholder="Search...">
            <span class="tooltip">Search</span>
        </li>

        <li>
            <a href="accueil.php">
                <i class='bx bx-home' ></i>
                <span class="links_name">Accueil</span>
            </a>
            <span class="tooltip">Accueil</span>
        </li>

        <li>
            <a href="articles.php">
                <i class='bx bx-book' ></i>
                <span class="links_name">Articles</span>
            </a>
            <span class="tooltip">Articles</span>
        </li>

        <li>
            <a href="galerie.php">
                <i class='bx bx-images'></i> <span class="links_name">Galerie</span>
            </a>
            <span class="tooltip">Galerie</span>
        </li>

        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <li>
            <a href="publier_article.php">
                <i class='bx bx-plus-circle'></i>
                <span class="links_name">Publier article</span>
            </a>
            <span class="tooltip">Publier article</span>
        </li>
        <?php endif; ?>
        <li>
            <a href="calendrier.php">
                <i class='bx bx-calendar' ></i>
                <span class="links_name">Calendrier</span>
            </a>
            <span class="tooltip">Calendrier</span>
        </li>

        <li>
            <a href="bureau.php">
                <i class='bx bx-group'></i> <span class="links_name">Bureau</span>
            </a>
            <span class="tooltip">Bureau</span>
        </li>

        <li>
            <a href="contact.php">
                <i class='bx bx-message-detail'></i>
                <span class="links_name">Contact</span>
            </a>
            <span class="tooltip">Contact</span>
        </li>

        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <li>
            <a href="messagerie.php">
                <i class='bx bx-envelope'></i>
                <span class="links_name">Messagerie</span>
            </a>
            <span class="tooltip">Messagerie</span>
        </li>
        <?php endif; ?>
    </ul>
    
    <div class="profile_content">
        <div class="profile">
            <div class="profile_details">
                <div class="name_job">
                    <div class="name"><?php echo htmlspecialchars($_SESSION['nom']); ?></div>
                    <div class="job"><?php echo htmlspecialchars($_SESSION['role']); ?></div>
                </div>
            </div>
            <a href="logout.php" style="color: white;"><i class='bx bx-log-out' id="log_out"></i></a>
        </div>
    </div>
</div>

<script>
    let btn = document.querySelector("#btn");
    let sidebar = document.querySelector(".sidebar");
    btn.addEventListener('click', function(){
        sidebar.classList.toggle('active');
    });
</script>