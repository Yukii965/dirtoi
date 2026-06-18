<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GasyMarket — Photo d'identité</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- face-api.js pour la détection faciale --}}
    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(to bottom, rgba(44,26,14,0.92), rgba(44,26,14,0.85)),
                        url('/images/baobab-sunset.jpg') center/cover fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        /* Conteneur fluide : largeur 100% jusqu'à 340px max,
        on garde le ratio 340/380 via aspect-ratio pour ne jamais déformer */
        #video-container {
            position: relative;
            width: 100%;
            max-width: 340px;
            aspect-ratio: 340 / 380;
            margin: 0 auto;
        }
        #video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 1rem;
            transform: scaleX(-1); /* Miroir */
            display: block;
        }
        #canvas-overlay {
            position: absolute;
            top: 0; left: 0;
            width: 100%;
            height: 100%;
            border-radius: 1rem;
            transform: scaleX(-1);
        }
        /* Cadre ovale de guidage — en %, donc il suit automatiquement
        la taille réelle du conteneur sur tous les écrans */
        #face-guide {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 58%;
            height: 68%;
            border: 3px solid rgba(244,164,41,0.8);
            border-radius: 50%;
            pointer-events: none;
            transition: border-color 0.3s;
            box-shadow: 0 0 0 2000px rgba(0,0,0,0.4);
        }
        #preview-img {
            width: 100%;
            max-width: 340px;
            aspect-ratio: 340 / 380;
            object-fit: cover;
            border-radius: 1rem;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="max-w-md w-full mx-4">

        {{-- Logo --}}
        <div class="text-center mb-6">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-2"
                 style="background: linear-gradient(135deg, #F4A429, #E07B2A)">
                <span class="text-white font-black text-xl">G</span>
            </div>
            <h1 class="text-xl font-black" style="color: #F4A429; font-family: 'Playfair Display', serif">
                Photo d'identité
            </h1>
        </div>

        <div class="rounded-2xl p-6" style="background: rgba(44,26,14,0.92); border: 1px solid rgba(244,164,41,0.2)">

            {{-- État : caméra --}}
            <div id="camera-section">
                <p id="status-msg" class="text-center text-sm mb-4" style="color: rgba(253,246,236,0.7)">
                    ⏳ Chargement de la détection faciale...
                </p>

                <div id="video-container" class="mx-auto mb-4">
                    <video id="video" autoplay muted playsinline></video>
                    <canvas id="canvas-overlay"></canvas>
                    <div id="face-guide"></div>
                </div>

                <p class="text-xs text-center mb-4" style="color: rgba(253,246,236,0.4)">
                    📷 Placez votre visage dans le cadre — la photo sera prise automatiquement
                </p>

                {{-- Barre de progression détection --}}
                <div class="w-full h-2 rounded-full mb-4"
                     style="background: rgba(255,255,255,0.1)">
                    <div id="detection-bar" class="h-2 rounded-full transition-all duration-300"
                         style="width: 0%; background: #F4A429"></div>
                </div>
                <p id="detection-text" class="text-xs text-center"
                   style="color: rgba(253,246,236,0.5)">
                    En attente de détection...
                </p>
            </div>

            {{-- État : confirmation --}}
            <div id="confirm-section" class="hidden">
                <p class="text-center text-sm mb-4 text-green-400 font-bold">
                    ✅ Photo capturée ! Est-ce correct ?
                </p>

                <img id="preview-img" class="mx-auto mb-4" src="" alt="Photo capturée">

                <div class="flex gap-3">
                    <button onclick="retake()"
                            class="flex-1 py-3 rounded-xl font-bold text-sm transition"
                            style="background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color: #f87171">
                        🔄 Recommencer
                    </button>
                    <button onclick="confirmPhoto()"
                            class="flex-1 py-3 rounded-xl font-bold text-sm transition"
                            style="background: #F4A429; color: #2C1A0E">
                        ✅ Confirmer
                    </button>
                </div>
            </div>

        </div>

        {{-- Formulaire caché pour upload --}}
        <form id="upload-form" action="{{ route('register.photo') }}" method="POST" enctype="multipart/form-data" class="hidden">
            @csrf
            <input type="hidden" name="photo_data" id="photo-data">
        </form>

    </div>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas-overlay');
        const faceGuide = document.getElementById('face-guide');
        const statusMsg = document.getElementById('status-msg');
        const detectionBar = document.getElementById('detection-bar');
        const detectionText = document.getElementById('detection-text');
        const cameraSection = document.getElementById('camera-section');
        const confirmSection = document.getElementById('confirm-section');
        const previewImg = document.getElementById('preview-img');

        let detectionCount = 0;
        let captureCanvas = document.createElement('canvas');
        let detecting = true;

        // Chargement des modèles face-api.js
        async function loadModels() {
            const MODEL_URL = 'https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/weights';
            await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
            statusMsg.textContent = '📷 Caméra activée — placez votre visage dans le cadre';
            startCamera();
        }

        // Démarrage caméra
        async function startCamera() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'user', width: 340, height: 380 }
                });
                video.srcObject = stream;
                video.addEventListener('playing', () => {
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    detectFace();
                });
            } catch (err) {
                statusMsg.textContent = '❌ Accès caméra refusé. Veuillez autoriser l\'accès.';
                statusMsg.style.color = '#f87171';
            }
        }

        // Boucle de détection faciale
        async function detectFace() {
            if (!detecting) return;

            const detection = await faceapi.detectSingleFace(
                video,
                new faceapi.TinyFaceDetectorOptions({ inputSize: 160, scoreThreshold: 0.5 })
            );

            if (detection) {
                // Visage détecté
                faceGuide.classList.add('detected');
                detectionCount++;
                const progress = Math.min((detectionCount / 15) * 100, 100);
                detectionBar.style.width = progress + '%';
                detectionBar.style.background = '#4ade80';
                detectionText.textContent = `Visage détecté ! Restez immobile... (${Math.round(progress)}%)`;
                detectionText.style.color = '#4ade80';

                // Capture automatique après 15 détections consécutives
                if (detectionCount >= 15) {
                    detecting = false;
                    capturePhoto();
                    return;
                }
            } else {
                // Pas de visage
                faceGuide.classList.remove('detected');
                detectionCount = Math.max(0, detectionCount - 2);
                const progress = (detectionCount / 15) * 100;
                detectionBar.style.width = progress + '%';
                detectionBar.style.background = '#F4A429';
                detectionText.textContent = 'Placez votre visage dans le cadre ovale...';
                detectionText.style.color = 'rgba(253,246,236,0.5)';
            }

            // Continue la détection
            requestAnimationFrame(detectFace);
        }

        // Capture de la photo
        function capturePhoto() {
            captureCanvas.width = video.videoWidth;
            captureCanvas.height = video.videoHeight;
            const ctx = captureCanvas.getContext('2d');

            // Miroir (pour correspondre à l'affichage)
            ctx.translate(captureCanvas.width, 0);
            ctx.scale(-1, 1);
            ctx.drawImage(video, 0, 0);

            const dataUrl = captureCanvas.toDataURL('image/jpeg', 0.9);
            previewImg.src = dataUrl;
            document.getElementById('photo-data').value = dataUrl;

            // Afficher section confirmation
            cameraSection.classList.add('hidden');
            confirmSection.classList.remove('hidden');

            // Arrêter la caméra
            video.srcObject.getTracks().forEach(t => t.stop());
        }

        // Recommencer
        function retake() {
            detectionCount = 0;
            detecting = true;
            detectionBar.style.width = '0%';
            detectionBar.style.background = '#F4A429';
            detectionText.textContent = 'En attente de détection...';
            cameraSection.classList.remove('hidden');
            confirmSection.classList.add('hidden');
            startCamera();
            detectFace();
        }

        // Confirmer et envoyer
        function confirmPhoto() {
            document.getElementById('upload-form').submit();
        }

        // Démarrage
        loadModels();
    </script>
</body>
</html>