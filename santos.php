<?php
require_once 'config.php';

// Base de dados dos santos e suas frases
$santos = [
    'nossa_senhora_aparecida' => [
        'nome' => 'Nossa Senhora Aparecida',
        'festa' => '12 de Outubro',
        'padroeira' => 'Padroeira do Brasil',
        'frases' => [
            'Ó Maria, concebida sem pecado, rogai por nós que recorremos a Vós.',
            'Sob vossa proteção nos refugiamos, Santa Mãe de Deus.',
            'A verdadeira devoção a Maria nos leva sempre a Jesus.',
            'Maria é o caminho mais seguro para chegar a Jesus.',
            'Quem encontra Maria, encontra a paz que o mundo não pode dar.'
        ],
        'oracao' => 'Senhora Aparecida, Mãe querida, protegei nossa família e nosso amor. Intercedei por nós junto a vosso Filho Jesus.',
        'cor' => '#1e40af',
        'emoji' => '👸🏽',
        'imagem' => 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSIjMWU0MGFmIiByeD0iNTAiLz4KPHR0ZXh0IHg9IjUwIiB5PSI2MCIgZm9udC1zaXplPSI0MCIgdGV4dC1hbmNob3I9Im1pZGRsZSI+8J+RuPCfj708L3RleHQ+Cjwvc3ZnPgo='
    ],
    'sao_jose' => [
        'nome' => 'São José',
        'festa' => '19 de Março',
        'padroeira' => 'Padroeiro dos Trabalhadores e das Famílias',
        'frases' => [
            'São José, terror dos demônios, rogai por nós.',
            'Ó São José, pai adotivo de Jesus, protegei as famílias.',
            'São José, modelo de esposo e pai, ensinai-nos a amar.',
            'Com São José, Jesus crescia em sabedoria e graça.',
            'São José, guardião da Sagrada Família, protegei nosso lar.'
        ],
        'oracao' => 'São José, esposo castíssimo de Maria, protegei nosso relacionamento e nossa futura família.',
        'cor' => '#92400e',
        'emoji' => '👨‍🔧',
        'imagem' => 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSIjOTI0MDBlIiByeD0iNTAiLz4KPHR0ZXh0IHg9IjUwIiB5PSI2MCIgZm9udC1zaXplPSI0MCIgdGV4dC1hbmNob3I9Im1pZGRsZSI+8J+RqOKAjeKakeKYvzwvdGV4dD4KPC9zdmc+Cg=='
    ],
    'santo_antonio' => [
        'nome' => 'Santo Antônio',
        'festa' => '13 de Junho',
        'padroeira' => 'Santo Casamenteiro',
        'frases' => [
            'Santo Antônio, casamenteiro querido, uni os corações que se amam.',
            'As ações falam mais alto que as palavras.',
            'O amor verdadeiro não conhece medida, mas dá-se sem medida.',
            'Feliz aquele que pode amar sem reservas.',
            'Santo Antônio, que encontrais o que se perde, encontrai-nos o amor verdadeiro.'
        ],
        'oracao' => 'Santo Antônio, casamenteiro dos namorados, abençoai nosso namoro e preparai nossos corações para o matrimônio.',
        'cor' => '#dc2626',
        'emoji' => '💒',
        'imagem' => 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSIjZGMyNjI2IiByeD0iNTAiLz4KPHR0ZXh0IHg9IjUwIiB5PSI2MCIgZm9udC1zaXplPSI0MCIgdGV4dC1hbmNob3I9Im1pZGRsZSI+8J+SgjwvdGV4dD4KPC9zdmc+Cg=='
    ],
    'santa_rita' => [
        'nome' => 'Santa Rita de Cássia',
        'festa' => '22 de Maio',
        'padroeira' => 'Santa das Causas Impossíveis',
        'frases' => [
            'Nada é impossível para quem confia em Deus.',
            'A oração é a chave que abre o coração de Deus.',
            'Santa Rita, advogada dos impossíveis, intercedei por nós.',
            'O sofrimento aceito com amor torna-se redenção.',
            'Quem persevera na oração alcança todas as graças.'
        ],
        'oracao' => 'Santa Rita, advogada dos casos impossíveis, intercedei pelo nosso amor e por todas as nossas intenções.',
        'cor' => '#7c2d12',
        'emoji' => '🌹',
        'imagem' => 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSIjN2MyZDEyIiByeD0iNTAiLz4KPHR0ZXh0IHg9IjUwIiB5PSI2MCIgZm9udC1zaXplPSI0MCIgdGV4dC1hbmNob3I9Im1pZGRsZSI+8J+MuTwvdGV4dD4KPC9zdmc+Cg=='
    ],
    'sao_francisco' => [
        'nome' => 'São Francisco de Assis',
        'festa' => '4 de Outubro',
        'padroeira' => 'Padroeiro dos Animais e da Ecologia',
        'frases' => [
            'Senhor, fazei de mim um instrumento de vossa paz.',
            'É dando que se recebe.',
            'Comece fazendo o que é necessário, depois o que é possível.',
            'Onde houver ódio, que eu leve o amor.',
            'Pregai sempre o Evangelho e, se necessário, usai palavras.'
        ],
        'oracao' => 'São Francisco, ensinai-nos a amar com simplicidade e a buscar a paz em nosso relacionamento.',
        'cor' => '#92400e',
        'emoji' => '🕊️',
        'imagem' => 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSIjOTI0MDBlIiByeD0iNTAiLz4KPHR0ZXh0IHg9IjUwIiB5PSI2MCIgZm9udC1zaXplPSI0MCIgdGV4dC1hbmNob3I9Im1pZGRsZSI+8J+VijwvdGV4dD4KPC9zdmc+Cg=='
    ],
    'santa_teresinha' => [
        'nome' => 'Santa Teresinha do Menino Jesus',
        'festa' => '1º de Outubro',
        'padroeira' => 'Doutora da Igreja',
        'frases' => [
            'Farei chover rosas do céu.',
            'O que mais atrai o Bom Deus é a confiança.',
            'Quero passar meu céu fazendo o bem na terra.',
            'A vida é mais bela quando se tem confiança em Deus.',
            'O amor prova-se com obras, não com belas palavras.'
        ],
        'oracao' => 'Santa Teresinha, fazei chover rosas sobre nosso amor e intercedei por nós junto ao Sagrado Coração.',
        'cor' => '#ec4899',
        'emoji' => '🌺',
        'imagem' => 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSIjZWM0ODk5IiByeD0iNTAiLz4KPHR0ZXh0IHg9IjUwIiB5PSI2MCIgZm9udC1zaXplPSI0MCIgdGV4dC1hbmNob3I9Im1pZGRsZSI+8J+MujwvdGV4dD4KPC9zdmc+Cg=='
    ],
    'sao_pedro' => [
        'nome' => 'São Pedro',
        'festa' => '29 de Junho',
        'padroeira' => 'Primeiro Papa da Igreja',
        'frases' => [
            'Tu és o Cristo, o Filho do Deus vivo.',
            'Senhor, para onde iremos? Só tu tens palavras de vida eterna.',
            'Lançai sobre Ele toda a vossa ansiedade, porque Ele tem cuidado de vós.',
            'Sede sóbrios e vigilantes.',
            'O amor cobre a multidão dos pecados.'
        ],
        'oracao' => 'São Pedro, rocha da Igreja, fortalecei nossa fé e nosso amor em Cristo.',
        'cor' => '#1e40af',
        'emoji' => '🗝️',
        'imagem' => 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSIjMWU0MGFmIiByeD0iNTAiLz4KPHR0ZXh0IHg9IjUwIiB5PSI2MCIgZm9udC1zaXplPSI0MCIgdGV4dC1hbmNob3I9Im1pZGRsZSI+8J+XnTwvdGV4dD4KPC9zdmc+Cg=='
    ],
    'sao_paulo' => [
        'nome' => 'São Paulo',
        'festa' => '29 de Junho',
        'padroeira' => 'Apóstolo dos Gentios',
        'frases' => [
            'Tudo posso naquele que me fortalece.',
            'O amor é paciente, é bondoso, não tem inveja.',
            'Combati o bom combate, terminei a carreira, guardei a fé.',
            'Não sou eu quem vive, mas Cristo vive em mim.',
            'Onde abundou o pecado, superabundou a graça.'
        ],
        'oracao' => 'São Paulo, apóstolo do amor, ensinai-nos a viver em Cristo e a amar como Ele nos amou.',
        'cor' => '#7c2d12',
        'emoji' => '✝️',
        'imagem' => 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSIjN2MyZDEyIiByeD0iNTAiLz4KPHR0ZXh0IHg9IjUwIiB5PSI2MCIgZm9udC1zaXplPSI0MCIgdGV4dC1hbmNob3I9Im1pZGRsZSI+4p2e8J+PuzwvdGV4dD4KPC9zdmc+Cg=='
    ],
    'padre_pio' => [
        'nome' => 'Padre Pio de Pietrelcina',
        'festa' => '23 de Setembro',
        'padroeira' => 'Santo dos Estigmas e da Oração',
        'frases' => [
            'Reze, tenha esperança e não se preocupe.',
            'A oração é a melhor arma que temos.',
            'Permaneça perto de Jesus; Ele nunca o abandonará.',
            'Quanto mais você se aproxima de Jesus, mais paz encontrará.',
            'O amor de Deus é infinito e sempre nos perdoa.'
        ],
        'oracao' => 'Padre Pio, intercedei por nosso amor e que possamos sempre confiar na misericórdia divina.',
        'cor' => '#8b5a2b',
        'emoji' => '🛐',
        'imagem' => 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSIjOGI1YTJiIiByeD0iNTAiLz4KPHR0ZXh0IHg9IjUwIiB5PSI2MCIgZm9udC1zaXplPSI0MCIgdGV4dC1hbmNob3I9Im1pZGRsZSI+8J+QkDwvdGV4dD4KPC9zdmc+Cg=='
    ],
    'sao_carlos_acutis' => [
        'nome' => 'São Carlos Acutis',
        'festa' => '12 de Outubro',
        'padroeira' => 'Padroeiro da Internet e dos Jovens',
        'frases' => [
            'A Eucaristia é minha autoestrada para o céu.',
            'Todos nascem originais, mas muitos morrem como fotocópias.',
            'Quanto mais Eucaristia recebermos, mais nos tornaremos como Jesus.',
            'A tristeza é olhar para si mesmo; a felicidade é olhar para Deus.',
            'Nossa meta deve ser o infinito, não o finito.'
        ],
        'oracao' => 'São Carlos Acutis, ensinai-nos a viver com autenticidade e a usar a tecnologia para o bem.',
        'cor' => '#3b82f6',
        'emoji' => '💻',
        'imagem' => 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSIjM2I4MmY2IiByeD0iNTAiLz4KPHR0ZXh0IHg9IjUwIiB5PSI2MCIgZm9udC1zaXplPSI0MCIgdGV4dC1hbmNob3I9Im1pZGRsZSI+8J+SuzwvdGV4dD4KPC9zdmc+Cg=='
    ],
    'santa_catarina_siena' => [
        'nome' => 'Santa Catarina de Siena',
        'festa' => '29 de Abril',
        'padroeira' => 'Doutora da Igreja e Padroeira da Europa',
        'frases' => [
            'Seja quem Deus quer que você seja e incendiará o mundo.',
            'O amor transforma em si mesmo tudo o que ama.',
            'Nada grande foi feito sem muita dificuldade.',
            'Se você for o que deve ser, incendiará o mundo inteiro.',
            'O amor vence tudo, e nós devemos nos entregar ao amor.'
        ],
        'oracao' => 'Santa Catarina, ensinai-nos a ser corajosos no amor e a seguir a vontade de Deus.',
        'cor' => '#dc2626',
        'emoji' => '🔥',
        'imagem' => 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSIjZGMyNjI2IiByeD0iNTAiLz4KPHR0ZXh0IHg9IjUwIiB5PSI2MCIgZm9udC1zaXplPSI0MCIgdGV4dC1hbmNob3I9Im1pZGRsZSI+8J+UpTwvdGV4dD4KPC9zdmc+Cg=='
    ],
    'sao_josemaria_escriva' => [
        'nome' => 'São Josemaría Escrivá',
        'festa' => '26 de Junho',
        'padroeira' => 'Fundador do Opus Dei',
        'frases' => [
            'Busque a santidade no trabalho ordinário de cada dia.',
            'O amor se prova com obras, não com belos sentimentos.',
            'Deus não nos pede o êxito, mas a fidelidade.',
            'Não há santidade sem sacrifício.',
            'Busque a Deus no trabalho e encontrará Deus no amor.'
        ],
        'oracao' => 'São Josemaría, ensinai-nos a buscar a santidade em nosso amor cotidiano.',
        'cor' => '#059669',
        'emoji' => '⚒️',
        'imagem' => 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSIjMDU5NjY5IiByeD0iNTAiLz4KPHR0ZXh0IHg9IjUwIiB5PSI2MCIgZm9udC1zaXplPSI0MCIgdGV4dC1hbmNob3I9Im1pZGRsZSI+4pqi77iPPC90ZXh0Pgo8L3N2Zz4K'
    ],
    'sao_joao_vianney' => [
        'nome' => 'São João Maria Vianney',
        'festa' => '4 de Agosto',
        'padroeira' => 'Cura d\'Ars - Padroeiro dos Párocos',
        'frases' => [
            'O coração do homem é como uma pedra lançada num lago.',
            'A oração é para a alma o que a chuva é para a terra.',
            'Nosso Senhor ama-nos tanto que morreu por nós na cruz.',
            'Quando rezamos bem, todos os nossos atos se tornam oração.',
            'Meu Deus, eu Vos amo! Fazei que eu Vos ame cada vez mais!'
        ],
        'oracao' => 'São João Vianney, ensinai-nos a orar com o coração e a amar com pureza.',
        'cor' => '#7c3aed',
        'emoji' => '⛪',
        'imagem' => 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSIjN2MzYWVkIiByeD0iNTAiLz4KPHR0ZXh0IHg9IjUwIiB5PSI2MCIgZm9udC1zaXplPSI0MCIgdGV4dC1hbmNob3I9Im1pZGRsZSI+4pun77iPPC90ZXh0Pgo8L3N2Zz4K'
    ],
    'santa_faustina' => [
        'nome' => 'Santa Faustina Kowalska',
        'festa' => '5 de Outubro',
        'padroeira' => 'Apóstola da Divina Misericórdia',
        'frases' => [
            'Jesus, eu confio em Vós.',
            'A misericórdia de Deus é inesgotável.',
            'Onde há humildade, há também sabedoria.',
            'O amor de Deus é o único que pode encher o coração humano.',
            'Jesus me disse: Escreva que quanto mais alguém confia, mais recebe.'
        ],
        'oracao' => 'Santa Faustina, ensinai-nos a confiar na misericórdia divina e a amar com compaixão.',
        'cor' => '#06b6d4',
        'emoji' => '💙',
        'imagem' => 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSIjMDZiNmQ0IiByeD0iNTAiLz4KPHR0ZXh0IHg9IjUwIiB5PSI2MCIgZm9udC1zaXplPSI0MCIgdGV4dC1hbmNob3I9Im1pZGRsZSI+8J+SrzwvdGV4dD4KPC9zdmc+Cg=='
    ]
];

// Processar formulário
$santoSelecionado = null;
$fraseDoDia = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $santoKey = $_POST['santo'] ?? '';
    if (isset($santos[$santoKey])) {
        $santoSelecionado = $santos[$santoKey];
        $fraseDoDia = $santoSelecionado['frases'][array_rand($santoSelecionado['frases'])];
    }
} elseif (isset($_GET['santo'])) {
    $santoKey = $_GET['santo'];
    if (isset($santos[$santoKey])) {
        $santoSelecionado = $santos[$santoKey];
        $fraseDoDia = $santoSelecionado['frases'][array_rand($santoSelecionado['frases'])];
    }
}

// Obter mensagem flash
$flashMessage = getFlashMessage();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Santos de Devoção 🙏</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .nav-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .nav-btn {
            padding: 12px 24px;
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
            padding: 40px 20px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .header h1 {
            font-size: 2.5em;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            margin-bottom: 10px;
        }

        .header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1em;
        }

        .santos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .santo-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 3px solid transparent;
        }

        .santo-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            border-color: rgba(255, 215, 0, 0.6);
        }

        .santo-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: white;
            font-weight: bold;
        }

        .santo-nome {
            font-size: 1.3em;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }

        .santo-festa {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 5px;
        }

        .santo-padroeira {
            color: #888;
            font-size: 0.8em;
            font-style: italic;
        }

        .frase-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            margin: 30px 0;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            border: 2px solid;
        }

        .santo-info {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .santo-info img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
        }

        .santo-details h2 {
            color: #333;
            font-size: 1.8em;
            margin-bottom: 10px;
        }

        .santo-details .festa {
            color: #666;
            font-size: 1.1em;
            margin-bottom: 5px;
        }

        .santo-details .padroeira {
            color: #888;
            font-style: italic;
        }

        .frase-destaque {
            font-size: 1.5em;
            color: #444;
            font-style: italic;
            line-height: 1.6;
            margin: 30px 0;
            padding: 25px;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 15px;
            border-left: 5px solid;
            position: relative;
        }

        .frase-destaque::before {
            content: '"';
            font-size: 3em;
            position: absolute;
            left: 15px;
            top: -5px;
            opacity: 0.3;
        }

        .frase-destaque::after {
            content: '"';
            font-size: 3em;
            position: absolute;
            right: 15px;
            bottom: -20px;
            opacity: 0.3;
        }

        .oracao-section {
            background: rgba(255, 255, 255, 0.1);
            padding: 25px;
            border-radius: 15px;
            margin-top: 30px;
            color: white;
        }

        .oracao-section h3 {
            margin-bottom: 15px;
            color: #ffd700;
        }

        .oracao-texto {
            font-style: italic;
            line-height: 1.6;
        }

        .todas-frases {
            margin-top: 30px;
            padding: 25px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
        }

        .todas-frases h3 {
            color: white;
            margin-bottom: 20px;
            text-align: center;
        }

        .frase-item {
            background: rgba(255, 255, 255, 0.9);
            padding: 15px;
            margin: 10px 0;
            border-radius: 10px;
            font-style: italic;
            color: #555;
            border-left: 4px solid;
        }

        .nova-frase-btn {
            background: linear-gradient(45deg, #ffd700, #ff6b35);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 20px 10px;
        }

        .nova-frase-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .voltar-btn {
            background: linear-gradient(45deg, #6366f1, #8b5cf6);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 20px 10px;
        }

        .voltar-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .blessing-animation {
            position: fixed;
            pointer-events: none;
            z-index: 1000;
            color: #ffd700;
            font-size: 24px;
            animation: float 4s ease-in-out infinite;
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

        .fade-in {
            animation: fadeInUp 0.8s ease-out;
        }

        @media (max-width: 768px) {
            .santos-grid {
                grid-template-columns: 1fr;
            }
            
            .header h1 {
                font-size: 2em;
            }
            
            .santo-info {
                flex-direction: column;
                text-align: center;
            }
            
            .frase-destaque {
                font-size: 1.2em;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav-bar">
            <a href="view.php" class="nav-btn">👀 Visualizar</a>
            <a href="edit.php" class="nav-btn">✏️ Editar</a>
            <a href="santos.php" class="nav-btn active">🙏 Santos</a>
        </div>

        <div class="header">
            <h1>Santos de Devoção 🙏</h1>
            <p>Encontre inspiração nas palavras dos santos católicos</p>
        </div>

        <?php if (!$santoSelecionado): ?>
            <div class="santos-grid">
                <?php foreach ($santos as $key => $santo): ?>
                    <div class="santo-card fade-in" onclick="selecionarSanto('<?php echo $key; ?>')">
                        <div class="santo-avatar" style="background: <?php echo $santo['cor']; ?>;">
                            <?php echo $santo['emoji']; ?>
                        </div>
                        <div class="santo-nome"><?php echo $santo['nome']; ?></div>
                        <div class="santo-festa">Festa: <?php echo $santo['festa']; ?></div>
                        <div class="santo-padroeira"><?php echo $santo['padroeira']; ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="frase-section fade-in" style="border-color: <?php echo $santoSelecionado['cor']; ?>;">
                <div class="santo-info">
                    <img src="<?php echo $santoSelecionado['imagem']; ?>" alt="<?php echo $santoSelecionado['nome']; ?>">
                    <div class="santo-details">
                        <h2><?php echo $santoSelecionado['nome']; ?></h2>
                        <div class="festa">Festa: <?php echo $santoSelecionado['festa']; ?></div>
                        <div class="padroeira"><?php echo $santoSelecionado['padroeira']; ?></div>
                    </div>
                </div>

                <div class="frase-destaque" style="border-left-color: <?php echo $santoSelecionado['cor']; ?>;">
                    <?php echo $fraseDoDia; ?>
                </div>

                <div style="text-align: center;">
                    <button class="nova-frase-btn" onclick="novaFrase()">✨ Nova Frase</button>
                    <button class="voltar-btn" onclick="voltarAoMenu()">← Voltar aos Santos</button>
                </div>

                <div class="oracao-section">
                    <h3>🙏 Oração</h3>
                    <div class="oracao-texto"><?php echo $santoSelecionado['oracao']; ?></div>
                </div>

                <div class="todas-frases">
                    <h3>💖 Todas as Frases</h3>
                    <?php foreach ($santoSelecionado['frases'] as $frase): ?>
                        <div class="frase-item" style="border-left-color: <?php echo $santoSelecionado['cor']; ?>;">
                            <?php echo $frase; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        let santoAtual = <?php echo $santoSelecionado ? "'" . array_search($santoSelecionado, $santos) . "'" : 'null'; ?>;
        let frasesDoSanto = <?php echo $santoSelecionado ? json_encode($santoSelecionado['frases']) : '[]'; ?>;

        function selecionarSanto(santoKey) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `<input type="hidden" name="santo" value="${santoKey}">`;
            document.body.appendChild(form);
            form.submit();
        }

        function novaFrase() {
            if (frasesDoSanto.length > 0) {
                const fraseAleatoria = frasesDoSanto[Math.floor(Math.random() * frasesDoSanto.length)];
                const fraseElement = document.querySelector('.frase-destaque');
                
                // Animação de fade out
                fraseElement.style.opacity = '0';
                fraseElement.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    fraseElement.textContent = fraseAleatoria;
                    // Animação de fade in
                    fraseElement.style.opacity = '1';
                    fraseElement.style.transform = 'translateY(0)';
                    
                    // Criar efeito de bênção
                    criarBencao();
                }, 300);
            }
        }

        function voltarAoMenu() {
            window.location.href = 'santos.php';
        }

        function criarBencao() {
            const emojis = ['✨', '🙏', '💖', '✝️', '🌟', '💫', '👼'];
            const emoji = emojis[Math.floor(Math.random() * emojis.length)];
            
            const blessing = document.createElement('div');
            blessing.className = 'blessing-animation';
            blessing.textContent = emoji;
            
            blessing.style.left = Math.random() * window.innerWidth + 'px';
            blessing.style.top = window.innerHeight + 'px';
            
            document.body.appendChild(blessing);
            
            setTimeout(() => {
                if (document.body.contains(blessing)) {
                    document.body.removeChild(blessing);
                }
            }, 4000);
        }

        function iniciarAnimacaoBencaos() {
            setInterval(() => {
                if (Math.random() > 0.85) {
                    criarBencao();
                }
            }, 3000);
        }

        // Adicionar transições suaves
        const style = document.createElement('style');
        style.textContent = `
            .frase-destaque {
                transition: all 0.3s ease;
            }
            
            .santo-card {
                animation: fadeInUp 0.6s ease-out;
            }
            
            .santo-card:nth-child(1) { animation-delay: 0.1s; }
            .santo-card:nth-child(2) { animation-delay: 0.2s; }
            .santo-card:nth-child(3) { animation-delay: 0.3s; }
            .santo-card:nth-child(4) { animation-delay: 0.4s; }
            .santo-card:nth-child(5) { animation-delay: 0.5s; }
            .santo-card:nth-child(6) { animation-delay: 0.6s; }
            .santo-card:nth-child(7) { animation-delay: 0.7s; }
            .santo-card:nth-child(8) { animation-delay: 0.8s; }
            .santo-card:nth-child(9) { animation-delay: 0.9s; }
            .santo-card:nth-child(10) { animation-delay: 1.0s; }
            .santo-card:nth-child(11) { animation-delay: 1.1s; }
            .santo-card:nth-child(12) { animation-delay: 1.2s; }
            .santo-card:nth-child(13) { animation-delay: 1.3s; }
            .santo-card:nth-child(14) { animation-delay: 1.4s; }
        `;
        document.head.appendChild(style);

        // Inicializar animações
        window.addEventListener('load', () => {
            iniciarAnimacaoBencaos();
            
            // Adicionar efeito hover nos cards
            document.querySelectorAll('.santo-card').forEach(card => {
                card.addEventListener('mouseenter', () => {
                    card.style.transform = 'translateY(-8px) scale(1.02)';
                });
                
                card.addEventListener('mouseleave', () => {
                    card.style.transform = 'translateY(0) scale(1)';
                });
            });
        });

        // Teclas de atalho
        document.addEventListener('keydown', (e) => {
            if (santoAtual) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    novaFrase();
                } else if (e.key === 'Escape') {
                    voltarAoMenu();
                }
            }
        });

        // Compartilhar frase (funcionalidade extra)
        function compartilharFrase() {
            const frase = document.querySelector('.frase-destaque').textContent;
            const santo = '<?php echo $santoSelecionado['nome'] ?? ''; ?>';
            
            if (navigator.share) {
                navigator.share({
                    title: `Frase de ${santo}`,
                    text: `"${frase}" - ${santo}`,
                    url: window.location.href
                });
            } else {
                // Fallback para copiar para clipboard
                const texto = `"${frase}" - ${santo}`;
                navigator.clipboard.writeText(texto).then(() => {
                    alert('Frase copiada para a área de transferência! 📋');
                });
            }
        }

        // Adicionar botão de compartilhar se houver santo selecionado
        <?php if ($santoSelecionado): ?>
        document.addEventListener('DOMContentLoaded', () => {
            const botaoCompartilhar = document.createElement('button');
            botaoCompartilhar.className = 'nova-frase-btn';
            botaoCompartilhar.innerHTML = '📱 Compartilhar';
            botaoCompartilhar.onclick = compartilharFrase;
            
            const container = document.querySelector('.frase-section div[style*="text-align: center"]');
            if (container) {
                container.appendChild(botaoCompartilhar);
            }
        });
        <?php endif; ?>
    </script>
</body>
</html>