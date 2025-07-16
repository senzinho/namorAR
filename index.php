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

        .loading {
            text-align: center;
            color: white;
            font-size: 1.2em;
            padding: 40px;
        }

        .error {
            text-align: center;
            color: #ff6b6b;
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
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
        }
    </style>
</head>
<body>
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

        <div id="loading" class="loading">
            Carregando nossas memórias abençoadas... 🙏
        </div>

        <div id="error" class="error" style="display: none;">
            Não foi possível carregar as memórias. Verifique se o arquivo memories.json existe.
        </div>

        <div class="timeline" id="timeline" style="display: none;">
            <!-- Memórias serão carregadas aqui -->
        </div>

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
        let blessingInterval;

        async function loadMemories() {
            try {
                const response = await fetch('memories.json');
                if (!response.ok) {
                    throw new Error('Arquivo não encontrado');
                }
                const data = await response.json();
                displayMemories(data.memories || []);
            } catch (error) {
                console.error('Erro ao carregar memórias:', error);
                document.getElementById('loading').style.display = 'none';
                document.getElementById('error').style.display = 'block';
            }
        }

        function displayMemories(memories) {
            const timeline = document.getElementById('timeline');
            const loading = document.getElementById('loading');
            
            loading.style.display = 'none';
            timeline.style.display = 'block';
            timeline.innerHTML = '';

            if (memories.length === 0) {
                timeline.innerHTML = `
                    <div style="text-align: center; color: white; padding: 40px;">
                        <h3>Ainda não há memórias cadastradas 💝</h3>
                        <p>Use a página de edição para adicionar suas primeiras memórias!</p>
                        <a href="edit.html" class="nav-btn" style="display: inline-block; margin-top: 20px;">✏️ Começar a Editar</a>
                    </div>
                `;
                return;
            }

            memories.forEach((memory, index) => {
                const memoryCard = document.createElement('div');
                memoryCard.className = 'memory-card';
                
                let photoHtml = '';
                if (memory.photo && memory.photo.trim()) {
                    photoHtml = `<img src="uploads/${memory.photo}" alt="${memory.title}" class="memory-photo" onerror="this.style.display='none'">`;
                } else {
                    photoHtml = '<div class="photo-placeholder">📸 Momento abençoado por Deus</div>';
                }

                let verseHtml = '';
                if (memory.verse && memory.verse.trim()) {
                    verseHtml = `
                        <div class="memory-verse">
                            "${memory.verse}"
                            <div class="verse-reference">${memory.verseReference || 'Versículo especial'}</div>
                        </div>
                    `;
                }
                
                memoryCard.innerHTML = `
                    <div class="memory-date">${memory.date}</div>
                    <div class="memory-content">
                        <h3 class="memory-title">${memory.title}</h3>
                        <p class="memory-description">${memory.description}</p>
                        ${verseHtml}
                        ${photoHtml}
                    </div>
                `;
                
                timeline.appendChild(memoryCard);

                // Animação de entrada
                setTimeout(() => {
                    memoryCard.style.opacity = '0';
                    memoryCard.style.transform = 'translateY(30px)';
                    memoryCard.style.transition = 'all 0.5s ease';
                    
                    setTimeout(() => {
                        memoryCard.style.opacity = '1';
                        memoryCard.style.transform = 'translateY(0)';
                    }, index * 200);
                }, 100);
            });
        }

        // Bênçãos flutuando automaticamente
        function startBlessingAnimation() {
            blessingInterval = setInterval(() => {
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

        // Inicializar quando a página carregar
        window.addEventListener('load', () => {
            loadMemories();
            startBlessingAnimation();
        });
    </script>
</body>
</html>