<?php
require_once 'config.php';
require_once 'Memory.php';

// Instanciar modelo
$memory = new Memory();

// Obter todas as memórias
$result = $memory->getAll();
$memories = $result['success'] ? $result['data'] : [];

// Obter estatísticas
$statsResult = $memory->getStatistics();
$stats = $statsResult['success'] ? $statsResult['data'] : [];

// Obter mensagem flash
$flashMessage = getFlashMessage();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nosso Amor Abençoado por Deus ✝️</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', serif;
            background: linear-gradient(135deg, #ffc0cb 0%, #ffb6c1 50%, #ffa0b4 100%);
            min-height: 100vh;
            color: #333;
            overflow-x: hidden;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .nav-bar {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 2000;
            display: flex;
            gap: 10px;
        }

        .nav-btn {
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 25px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #8b4f6b;
            box-shadow: 0 2px 10px rgba(255, 182, 193, 0.3);
        }

        .nav-btn:hover {
            background: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(255, 182, 193, 0.4);
        }

        .nav-btn.active {
            background: linear-gradient(45deg, #ff69b4, #ffb6c1);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 105, 180, 0.4);
        }

        .flash-message {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 3000;
            padding: 15px 25px;
            border-radius: 10px;
            color: white;
            font-weight: bold;
            max-width: 300px;
            animation: slideIn 0.5s ease-out;
        }

        .flash-success {
            background: linear-gradient(45deg, #ff69b4, #ffb6c1);
        }

        .flash-error {
            background: linear-gradient(45deg, #dc3545, #c82333);
        }

        .flash-warning {
            background: linear-gradient(45deg, #ffc107, #e0a800);
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            margin-top: 60px;
            padding: 50px 20px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(255, 182, 193, 0.2);
        }

        .header h1 {
            font-size: 3em;
            color: #8b4f6b;
            text-shadow: 2px 2px 4px rgba(255, 182, 193, 0.3);
            margin-bottom: 15px;
            animation: fadeInUp 1s ease-out;
        }

        .header .subtitle {
            font-size: 1.3em;
            color: #8b4f6b;
            font-style: italic;
            margin-bottom: 20px;
            animation: fadeInUp 1s ease-out 0.3s both;
        }

        .bible-verse {
            font-size: 1.1em;
            color: #8b4f6b;
            padding: 20px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 15px;
            border-left: 4px solid #ff69b4;
            margin: 20px 0;
            animation: fadeInUp 1s ease-out 0.6s both;
        }

        .bible-verse .reference {
            font-size: 0.9em;
            color: #8b4f6b;
            margin-top: 10px;
            text-align: right;
            font-style: italic;
        }

        .stats {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.2);
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            color: #8b4f6b;
            backdrop-filter: blur(10px);
            min-width: 120px;
            box-shadow: 0 4px 15px rgba(255, 182, 193, 0.2);
        }

        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #ff69b4;
        }

        .stat-label {
            font-size: 0.9em;
            margin-top: 5px;
        }

        .timeline {
            position: relative;
            margin: 40px 0;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 50%;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(to bottom, #ff69b4, #ffb6c1, #ff69b4);
            transform: translateX(-50%);
            border-radius: 2px;
        }

        .memory-card {
            position: relative;
            margin: 40px 0;
            animation: fadeInUp 0.8s ease-out;
        }

        .memory-card:nth-child(odd) {
            padding-right: 60%;
        }

        .memory-card:nth-child(even) {
            padding-left: 60%;
        }

        .memory-content {
            background: rgba(255, 255, 255, 0.98);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(255, 182, 193, 0.3);
            position: relative;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .memory-content:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(255, 182, 193, 0.4);
        }

        .memory-content::before {
            content: '';
            position: absolute;
            top: 20px;
            width: 0;
            height: 0;
            border: 15px solid transparent;
        }

        .memory-card:nth-child(odd) .memory-content::before {
            right: -30px;
            border-left-color: rgba(255, 255, 255, 0.98);
        }

        .memory-card:nth-child(even) .memory-content::before {
            left: -30px;
            border-right-color: rgba(255, 255, 255, 0.98);
        }

        .memory-date {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(45deg, #ff69b4, #ffb6c1);
            color: white;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 0.9em;
            box-shadow: 0 4px 15px rgba(255, 105, 180, 0.3);
            z-index: 10;
        }

        .memory-title {
            font-size: 1.5em;
            color: #8b4f6b;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .memory-description {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .memory-verse {
            background: linear-gradient(135deg, #fce4ec, #f8bbd9);
            padding: 15px;
            border-radius: 10px;
            border-left: 4px solid #ff69b4;
            font-style: italic;
            color: #8b4f6b;
            margin: 15px 0;
        }

        .memory-verse .verse-reference {
            font-size: 0.85em;
            color: #8b4f6b;
            margin-top: 8px;
            text-align: right;
        }

        .media-container {
            margin: 15px 0;
        }

        .media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin: 15px 0;
        }

        .memory-photo {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(255, 182, 193, 0.3);
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .memory-photo:hover {
            transform: scale(1.02);
        }

        .memory-video {
            width: 100%;
            max-height: 300px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(255, 182, 193, 0.3);
        }

        .memory-video:focus {
            outline: 2px solid #ff69b4;
            outline-offset: 2px;
        }

        .media-placeholder {
            width: 100%;
            height: 200px;
            background: linear-gradient(45deg, #fce4ec, #f8bbd9);
            border: 2px dashed #ffb6c1;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #8b4f6b;
            font-style: italic;
            margin: 15px 0;
        }

        .media-type-badge {
            display: inline-block;
            background: linear-gradient(45deg, #ff69b4, #ffb6c1);
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.7em;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .empty-state {
            text-align: center;
            color: #8b4f6b;
            padding: 60px 20px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            backdrop-filter: blur(10px);
        }

        .empty-state h3 {
            font-size: 2em;
            margin-bottom: 20px;
            color: #ff69b4;
        }

        .empty-state p {
            font-size: 1.2em;
            margin-bottom: 30px;
        }

        .empty-state .btn {
            display: inline-block;
            padding: 15px 30px;
            background: linear-gradient(45deg, #ff69b4, #ffb6c1);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 105, 180, 0.3);
        }

        .empty-state .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 105, 180, 0.4);
        }

        .prayer-section {
            text-align: center;
            margin: 60px 0;
            padding: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(255, 182, 193, 0.2);
        }

        .prayer-section h3 {
            color: #8b4f6b;
            font-size: 1.8em;
            margin-bottom: 20px;
        }

        .prayer-section p {
            font-size: 1.2em;
            color: #8b4f6b;
            font-style: italic;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .prayer-section .amen {
            font-size: 1.1em;
            color: #ff69b4;
            font-weight: bold;
            margin-top: 20px;
        }

        .blessing-animation {
            position: fixed;
            pointer-events: none;
            z-index: 1000;
        }

        .blessing {
            color: #ff69b4;
            font-size: 24px;
            animation: float 4s ease-in-out infinite;
        }

        /* Animação de pétalas de rosas caindo */
        .rose-petal {
            position: fixed;
            pointer-events: none;
            z-index: 500;
            font-size: 20px;
            opacity: 0.8;
            animation: fallAndSway 8s linear infinite;
        }

        .rose-petal-1 { color: #ff69b4; font-size: 18px; }
        .rose-petal-2 { color: #ffb6c1; font-size: 22px; }
        .rose-petal-3 { color: #ffc0cb; font-size: 16px; }
        .rose-petal-4 { color: #ff1493; font-size: 24px; }
        .rose-petal-5 { color: #da70d6; font-size: 20px; }

        @keyframes fallAndSway {
            0% {
                transform: translateY(-100px) translateX(0px) rotate(0deg);
                opacity: 1;
            }
            25% {
                transform: translateY(25vh) translateX(20px) rotate(90deg);
                opacity: 0.8;
            }
            50% {
                transform: translateY(50vh) translateX(-15px) rotate(180deg);
                opacity: 0.6;
            }
            75% {
                transform: translateY(75vh) translateX(25px) rotate(270deg);
                opacity: 0.4;
            }
            100% {
                transform: translateY(100vh) translateX(-10px) rotate(360deg);
                opacity: 0;
            }
        }

        /* Variações de animação para movimento mais natural */
        .rose-petal.sway-1 {
            animation: fallAndSway1 10s linear infinite;
        }

        .rose-petal.sway-2 {
            animation: fallAndSway2 12s linear infinite;
        }

        .rose-petal.sway-3 {
            animation: fallAndSway3 9s linear infinite;
        }

        @keyframes fallAndSway1 {
            0% {
                transform: translateY(-100px) translateX(0px) rotate(0deg) scale(1);
                opacity: 1;
            }
            20% {
                transform: translateY(20vh) translateX(-30px) rotate(72deg) scale(0.9);
            }
            40% {
                transform: translateY(40vh) translateX(35px) rotate(144deg) scale(1.1);
            }
            60% {
                transform: translateY(60vh) translateX(-20px) rotate(216deg) scale(0.8);
            }
            80% {
                transform: translateY(80vh) translateX(15px) rotate(288deg) scale(1.2);
            }
            100% {
                transform: translateY(110vh) translateX(-5px) rotate(360deg) scale(0.6);
                opacity: 0;
            }
        }

        @keyframes fallAndSway2 {
            0% {
                transform: translateY(-100px) translateX(0px) rotate(0deg) scale(1);
                opacity: 1;
            }
            15% {
                transform: translateY(15vh) translateX(40px) rotate(54deg) scale(1.3);
            }
            35% {
                transform: translateY(35vh) translateX(-25px) rotate(126deg) scale(0.7);
            }
            55% {
                transform: translateY(55vh) translateX(30px) rotate(198deg) scale(1.1);
            }
            75% {
                transform: translateY(75vh) translateX(-35px) rotate(270deg) scale(0.9);
            }
            100% {
                transform: translateY(110vh) translateX(10px) rotate(360deg) scale(0.5);
                opacity: 0;
            }
        }

        @keyframes fallAndSway3 {
            0% {
                transform: translateY(-100px) translateX(0px) rotate(0deg) scale(1);
                opacity: 1;
            }
            30% {
                transform: translateY(30vh) translateX(-40px) rotate(108deg) scale(1.2);
            }
            50% {
                transform: translateY(50vh) translateX(20px) rotate(180deg) scale(0.8);
            }
            70% {
                transform: translateY(70vh) translateX(-15px) rotate(252deg) scale(1.4);
            }
            100% {
                transform: translateY(110vh) translateX(25px) rotate(360deg) scale(0.4);
                opacity: 0;
            }
        }

        /* Modal para visualizar imagens */
        .image-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 182, 193, 0.9);
            backdrop-filter: blur(5px);
        }

        .image-modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 90%;
            max-height: 90%;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(255, 105, 180, 0.5);
        }

        .image-modal-close {
            position: absolute;
            top: 20px;
            right: 30px;
            color: #8b4f6b;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            z-index: 10000;
            text-shadow: 0 2px 4px rgba(255, 255, 255, 0.8);
        }

        .image-modal-close:hover {
            opacity: 0.7;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0% {
                transform: translateY(0px) scale(1);
                opacity: 1;
            }
            50% {
                transform: translateY(-30px) scale(1.1);
                opacity: 0.8;
            }
            100% {
                transform: translateY(-60px) scale(0.8);
                opacity: 0;
            }
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 2em;
            }
            
            .timeline::before {
                left: 30px;
            }
            
            .memory-card:nth-child(odd),
            .memory-card:nth-child(even) {
                padding-left: 70px;
                padding-right: 20px;
            }
            
            .memory-date {
                left: 30px;
                transform: translateX(-50%);
            }
            
            .memory-content::before {
                left: -30px;
                border-right-color: rgba(255, 255, 255, 0.98);
                border-left-color: transparent;
            }

            .nav-bar {
                position: relative;
                top: 0;
                right: 0;
                justify-content: center;
                margin-bottom: 20px;
            }

            .stats {
                flex-direction: column;
                align-items: center;
            }

            .media-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php if ($flashMessage): ?>
        <div class="flash-message flash-<?php echo $flashMessage['type']; ?>" id="flashMessage">
            <?php echo htmlspecialchars($flashMessage['message']); ?>
        </div>
    <?php endif; ?>

    <div class="nav-bar">
        <a href="view.php" class="nav-btn active">👀 Visualizar</a>
        <a href="edit.php" class="nav-btn">✏️ Editar</a>
    </div>

    <div class="container">
        <div class="header">
            <h1>Nosso Amor Abençoado por Deus ✝️</h1>
            <p class="subtitle">Unidos pela fé, fortalecidos pelo amor</p>
            <div class="bible-verse">
                "O amor é paciente, o amor é bondoso. Não inveja, não se vangloria, não se orgulha."
                <div class="reference">1 Coríntios 13:4</div>
            </div>
        </div>

        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['total_memories'] ?? count($memories); ?></div>
                <div class="stat-label">Memórias</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['memories_with_photos'] ?? count(array_filter($memories, fn($m) => !empty($m['photo']))); ?></div>
                <div class="stat-label">Fotos</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['memories_with_videos'] ?? count(array_filter($memories, fn($m) => !empty($m['video']))); ?></div>
                <div class="stat-label">Vídeos</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['memories_with_verses'] ?? count(array_filter($memories, fn($m) => !empty($m['verse']))); ?></div>
                <div class="stat-label">Versículos</div>
            </div>
        </div>

        <?php if (empty($memories)): ?>
            <div class="empty-state">
                <h3>Ainda não há memórias cadastradas 💝</h3>
                <p>Comece criando sua primeira memória de amor abençoada por Deus!</p>
                <a href="edit.php" class="btn">✏️ Criar Primeira Memória</a>
            </div>
        <?php else: ?>
            <div class="timeline">
                <?php foreach ($memories as $memory): ?>
                    <div class="memory-card">
                        <div class="memory-date"><?php echo htmlspecialchars($memory['date']); ?></div>
                        <div class="memory-content">
                            <h3 class="memory-title"><?php echo htmlspecialchars($memory['title']); ?></h3>
                            <p class="memory-description"><?php echo nl2br(htmlspecialchars($memory['description'])); ?></p>
                            
                            <?php if (!empty($memory['verse'])): ?>
                                <div class="memory-verse">
                                    "<?php echo htmlspecialchars($memory['verse']); ?>"
                                    <div class="verse-reference"><?php echo htmlspecialchars($memory['verse_reference'] ?? 'Versículo especial'); ?></div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($memory['photo']) || !empty($memory['video'])): ?>
                                <div class="media-container">
                                    <div class="media-grid">
                                        <?php if (!empty($memory['photo'])): ?>
                                            <div>
                                                <div class="media-type-badge">📸 Foto</div>
                                                <img src="<?php echo UPLOAD_DIR . htmlspecialchars($memory['photo']); ?>" 
                                                     alt="<?php echo htmlspecialchars($memory['title']); ?>" 
                                                     class="memory-photo"
                                                     onclick="openImageModal(this.src)"
                                                     onerror="this.parentNode.innerHTML='<div class=\'media-placeholder\'>📸 Imagem não encontrada</div>'">
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($memory['video'])): ?>
                                            <div>
                                                <div class="media-type-badge">🎥 Vídeo</div>
                                                <video controls class="memory-video" preload="metadata">
                                                    <source src="<?php echo UPLOAD_DIR . htmlspecialchars($memory['video']); ?>" type="video/mp4">
                                                    <source src="<?php echo UPLOAD_DIR . htmlspecialchars($memory['video']); ?>" type="video/webm">
                                                    <source src="<?php echo UPLOAD_DIR . htmlspecialchars($memory['video']); ?>" type="video/ogg">
                                                    Seu navegador não suporta o elemento de vídeo.
                                                </video>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="prayer-section">
            <h3>Oração pelo Nosso Relacionamento 🙏</h3>
            <p>Senhor, obrigado por nos abençoar com este amor.</p>
            <p>Que possamos sempre buscar Tua vontade em nossa união,</p>
            <p>crescendo juntos na fé e no amor verdadeiro.</p>
            <p>Que nosso relacionamento seja um testemunho do Teu amor.</p>
            <p>Abençoa nossos planos, nossos sonhos e nosso futuro.</p>
            <p class="amen">Amém! 🙏</p>
        </div>
    </div>

    <!-- Modal para visualizar imagens -->
    <div id="imageModal" class="image-modal" onclick="closeImageModal()">
        <span class="image-modal-close">&times;</span>
        <img class="image-modal-content" id="modalImage">
    </div>

    <script>
        // Ocultar mensagem flash após 5 segundos
        const flashMessage = document.getElementById('flashMessage');
        if (flashMessage) {
            setTimeout(() => {
                flashMessage.style.animation = 'slideOut 0.5s ease-in forwards';
                setTimeout(() => {
                    flashMessage.style.display = 'none';
                }, 500);
            }, 5000);
        }

        // Modal de imagens
        function openImageModal(src) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            modal.style.display = 'block';
            modalImg.src = src;
        }

        function closeImageModal() {
            document.getElementById('imageModal').style.display = 'none';
        }

        // Fechar modal com ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeImageModal();
            }
        });

        // Animação de bênçãos
        function startBlessingAnimation() {
            setInterval(() => {
                if (Math.random() > 0.8) {
                    const blessing = document.createElement('div');
                    blessing.className = 'blessing-animation';
                    blessing.innerHTML = '<span class="blessing">✝️</span>';
                    
                    blessing.style.left = Math.random() * window.innerWidth + 'px';
                    blessing.style.top = window.innerHeight + 'px';
                    
                    document.body.appendChild(blessing);
                    
                    setTimeout(() => {
                        if (document.body.contains(blessing)) {
                            document.body.removeChild(blessing);
                        }
                    }, 4000);
                }
            }, 3000);
        }

        // Animação INSANA de pétalas de rosas caindo
        function startRosePetalsRain() {
            const petalEmojis = ['🌹', '🌸', '💐', '🌺', '🌻'];
            const petalClasses = ['rose-petal-1', 'rose-petal-2', 'rose-petal-3', 'rose-petal-4', 'rose-petal-5'];
            const swayClasses = ['', 'sway-1', 'sway-2', 'sway-3'];
            
            function createPetal() {
                const petal = document.createElement('div');
                const randomEmoji = petalEmojis[Math.floor(Math.random() * petalEmojis.length)];
                const randomClass = petalClasses[Math.floor(Math.random() * petalClasses.length)];
                const randomSway = swayClasses[Math.floor(Math.random() * swayClasses.length)];
                
                petal.innerHTML = randomEmoji;
                petal.className = `rose-petal ${randomClass} ${randomSway}`;
                
                // Posição aleatória horizontal
                petal.style.left = Math.random() * (window.innerWidth + 100) + 'px';
                petal.style.top = '-100px';
                
                // Variação na velocidade e delay
                const animationDuration = 8 + Math.random() * 6; // 8-14 segundos
                const animationDelay = Math.random() * 2; // 0-2 segundos de delay
                
                petal.style.animationDuration = animationDuration + 's';
                petal.style.animationDelay = animationDelay + 's';
                
                document.body.appendChild(petal);
                
                // Remover a pétala após a animação
                setTimeout(() => {
                    if (document.body.contains(petal)) {
                        document.body.removeChild(petal);
                    }
                }, (animationDuration + animationDelay) * 1000);
            }
            
            // Criar pétalas constantemente
            setInterval(createPetal, 300); // Nova pétala a cada 300ms
            
            // Rajada inicial mais intensa
            for (let i = 0; i < 15; i++) {
                setTimeout(createPetal, i * 100);
            }
            
            // Rajadas periódicas mais intensas
            setInterval(() => {
                for (let i = 0; i < 8; i++) {
                    setTimeout(createPetal, i * 50);
                }
            }, 5000); // Rajada a cada 5 segundos
        }

        // Função para criar uma chuva super intensa (modo especial)
        function createRoseStorm() {
            for (let i = 0; i < 30; i++) {
                setTimeout(() => {
                    const petal = document.createElement('div');
                    const petalEmojis = ['🌹', '🌸', '💐', '🌺', '🌻', '💖', '💕', '💗'];
                    const randomEmoji = petalEmojis[Math.floor(Math.random() * petalEmojis.length)];
                    
                    petal.innerHTML = randomEmoji;
                    petal.className = 'rose-petal rose-petal-' + (Math.floor(Math.random() * 5) + 1);
                    petal.style.left = Math.random() * window.innerWidth + 'px';
                    petal.style.top = '-100px';
                    petal.style.fontSize = (15 + Math.random() * 15) + 'px';
                    
                    document.body.appendChild(petal);
                    
                    setTimeout(() => {
                        if (document.body.contains(petal)) {
                            document.body.removeChild(petal);
                        }
                    }, 12000);
                }, i * 100);
            }
        }

        // Tempestade de rosas a cada 30 segundos
        setInterval(createRoseStorm, 30000);

        // Animação de entrada das memórias
        function animateMemories() {
            const cards = document.querySelectorAll('.memory-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 200);
            });
        }

        // Pausar todos os vídeos quando um começar a tocar
        function setupVideoControls() {
            const videos = document.querySelectorAll('.memory-video');
            videos.forEach(video => {
                video.addEventListener('play', function() {
                    videos.forEach(otherVideo => {
                        if (otherVideo !== video) {
                            otherVideo.pause();
                        }
                    });
                });
            });
        }

        // Adicionar animação de slideOut e pulse ao CSS
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
            
            @keyframes pulse {
                0% { transform: translate(-50%, -50%) scale(0.8); opacity: 0; }
                50% { transform: translate(-50%, -50%) scale(1.1); opacity: 1; }
                100% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
            }
        `;
        document.head.appendChild(style);

        // Inicializar quando a página carregar
        window.addEventListener('load', () => {
            startBlessingAnimation();
            startRosePetalsRain(); // 🌹 CHUVA DE PÉTALAS ATIVADA! 🌹
            animateMemories();
            setupVideoControls();
        });

        // Easter egg: clique triplo para tempestade de rosas
        let clickCount = 0;
        document.addEventListener('click', () => {
            clickCount++;
            if (clickCount === 3) {
                createRoseStorm();
                clickCount = 0;
                
                // Mostrar mensagem especial
                const message = document.createElement('div');
                message.innerHTML = '🌹✨ TEMPESTADE DE AMOR ATIVADA! ✨🌹';
                message.style.cssText = `
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    background: linear-gradient(45deg, #ff69b4, #ffb6c1);
                    color: white;
                    padding: 20px 40px;
                    border-radius: 25px;
                    font-size: 18px;
                    font-weight: bold;
                    z-index: 10000;
                    box-shadow: 0 8px 25px rgba(255, 105, 180, 0.5);
                    animation: pulse 0.5s ease-in-out;
                `;
                document.body.appendChild(message);
                
                setTimeout(() => {
                    if (document.body.contains(message)) {
                        document.body.removeChild(message);
                    }
                }, 3000);
            }
            
            setTimeout(() => { clickCount = 0; }, 1000);
        });
    </script>
</body>
</html>