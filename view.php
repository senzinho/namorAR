<?php
require_once 'config.php';
require_once 'Memory.php';

// Instanciar modelo
$memory = new Memory();

// Obter todas as memórias
$result = $memory->getAll();
$memories = $result['success'] ? $result['data'] : [];

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
            background: linear-gradient(135deg, #4a69bd 0%, #1e3c72 100%);
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
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 25px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #333;
        }

        .nav-btn:hover {
            background: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .nav-btn.active {
            background: linear-gradient(45deg, #ffd700, #ff6b35);
            color: white;
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
            background: linear-gradient(45deg, #2ecc71, #27ae60);
        }

        .flash-error {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
        }

        .flash-warning {
            background: linear-gradient(45deg, #f39c12, #e67e22);
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
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-size: 3em;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            margin-bottom: 15px;
            animation: fadeInUp 1s ease-out;
        }

        .header .subtitle {
            font-size: 1.3em;
            color: rgba(255, 255, 255, 0.9);
            font-style: italic;
            margin-bottom: 20px;
            animation: fadeInUp 1s ease-out 0.3s both;
        }

        .bible-verse {
            font-size: 1.1em;
            color: rgba(255, 255, 255, 0.95);
            padding: 20px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            border-left: 4px solid #ffd700;
            margin: 20px 0;
            animation: fadeInUp 1s ease-out 0.6s both;
        }

        .bible-verse .reference {
            font-size: 0.9em;
            color: rgba(255, 255, 255, 0.8);
            margin-top: 10px;
            text-align: right;
            font-style: italic;
        }

        .stats {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            color: white;
            backdrop-filter: blur(10px);
        }

        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #ffd700;
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
            background: linear-gradient(to bottom, #ffd700, #ff6b35, #ffd700);
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
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            position: relative;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .memory-content:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
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
            background: linear-gradient(45deg, #ffd700, #ff6b35);
            color: white;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 0.9em;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            z-index: 10;
        }

        .memory-title {
            font-size: 1.5em;
            color: #2c3e50;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .memory-description {
            color: #555;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .memory-verse {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            padding: 15px;
            border-radius: 10px;
            border-left: 4px solid #ffd700;
            font-style: italic;
            color: #444;
            margin: 15px 0;
        }

        .memory-verse .verse-reference {
            font-size: 0.85em;
            color: #666;
            margin-top: 8px;
            text-align: right;
        }

        .memory-photo {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 10px;
            margin: 15px 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .photo-placeholder {
            width: 100%;
            height: 200px;
            background: linear-gradient(45deg, #f8f9fa, #e9ecef);
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-style: italic;
            margin: 15px 0;
        }

        .empty-state {
            text-align: center;
            color: white;
            padding: 60px 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            backdrop-filter: blur(10px);
        }

        .empty-state h3 {
            font-size: 2em;
            margin-bottom: 20px;
            color: #ffd700;
        }

        .empty-state p {
            font-size: 1.2em;
            margin-bottom: 30px;
        }

        .empty-state .btn {
            display: inline-block;
            padding: 15px 30px;
            background: linear-gradient(45deg, #ffd700, #ff6b35);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .empty-state .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .prayer-section {
            text-align: center;
            margin: 60px 0;
            padding: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            backdrop-filter: blur(10px);
        }

        .prayer-section h3 {
            color: white;
            font-size: 1.8em;
            margin-bottom: 20px;
        }

        .prayer-section p {
            font-size: 1.2em;
            color: rgba(255, 255, 255, 0.9);
            font-style: italic;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .prayer-section .amen {
            font-size: 1.1em;
            color: #ffd700;
            font-weight: bold;
            margin-top: 20px;
        }

        .blessing-animation {
            position: fixed;
            pointer-events: none;
            z-index: 1000;
        }

        .blessing {
            color: #ffd700;
            font-size: 24px;
            animation: float 4s ease-in-out infinite;
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
                <div class="stat-number"><?php echo count($memories); ?></div>
                <div class="stat-label">Memórias</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count(array_filter($memories, fn($m) => !empty($m['photo']))); ?></div>
                <div class="stat-label">Fotos</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count(array_filter($memories, fn($m) => !empty($m['verse']))); ?></div>
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
                            
                            <?php if (!empty($memory['photo'])): ?>
                                <img src="<?php echo UPLOAD_DIR . htmlspecialchars($memory['photo']); ?>" 
                                     alt="<?php echo htmlspecialchars($memory['title']); ?>" 
                                     class="memory-photo"
                                     onerror="this.parentNode.innerHTML='<div class=\'photo-placeholder\'>📸 Imagem não encontrada</div>'">
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

        // Adicionar animação de slideOut ao CSS
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
        `;
        document.head.appendChild(style);

        // Inicializar quando a página carregar
        window.addEventListener('load', () => {
            startBlessingAnimation();
            animateMemories();
        });
    </script>
</body>
</html>