<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avis des Utilisateurs - Miniblog</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        
        .fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }
        
        .slide-up {
            animation: slideUp 0.5s ease-out forwards;
        }
        
        .pulse-glow {
            animation: pulseGlow 2s infinite;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(20px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes pulseGlow {
            0% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(99, 102, 241, 0); }
            100% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0); }
        }
        
        .rating-item {
            transition: all 0.3s ease;
        }
        
        .rating-item:hover {
            transform: translateX(5px);
        }
        
        .star-rating i {
            transition: all 0.2s ease;
        }
        
        .star-rating i:hover {
            transform: scale(1.2);
        }
        
        .avatar-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .header-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        /* Animation pour les éléments de liste avec délais */
        .rating-item:nth-child(1) { animation-delay: 0.1s; }
        .rating-item:nth-child(2) { animation-delay: 0.2s; }
        .rating-item:nth-child(3) { animation-delay: 0.3s; }
        .rating-item:nth-child(4) { animation-delay: 0.4s; }
        .rating-item:nth-child(5) { animation-delay: 0.5s; }
        
        /* Styles personnalisés pour la pagination */
        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .pagination li {
            margin: 4px;
        }
        
        .pagination li a,
        .pagination li span {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .pagination li a {
            background-color: white;
            color: #4f46e5;
            border: 1px solid #e2e8f0;
        }
        
        .pagination li a:hover {
            background-color: #4f46e5;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .pagination li span {
            background-color: #4f46e5;
            color: white;
            border: 1px solid #4f46e5;
        }
        
        /* Responsive adjustments */
        @media (max-width: 640px) {
            .pagination li {
                margin: 2px;
            }
            
            .pagination li a,
            .pagination li span {
                width: 36px;
                height: 36px;
                font-size: 0.875rem;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 py-10">
        <!-- En-tête avec moyenne globale -->
        <div class="header-gradient rounded-2xl shadow-xl p-6 mb-8 transform transition-all duration-300 hover:shadow-2xl fade-in">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="text-center md:text-left mb-6 md:mb-0">
                    <h2 class="text-3xl font-bold text-white mb-2">Avis des utilisateurs de Miniblog</h2>
                    <div class="flex items-center justify-center md:justify-start space-x-2 mb-2">
                        <!-- Étoiles pour la moyenne -->
                        <div class="flex star-rating">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($avg))
                                    <i class="fas fa-star text-yellow-300 text-2xl"></i>
                                @elseif($i - 0.5 <= $avg)
                                    <i class="fas fa-star-half-alt text-yellow-300 text-2xl"></i>
                                @else
                                    <i class="far fa-star text-yellow-200 text-2xl"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="text-white font-bold text-xl">{{ number_format($avg, 1) }}/5</span>
                    </div>
                    <center><p class="text-indigo-100 text-sm">
                        Avis total {{ $count }} {{ Str::plural('avis', $count) }}
                    </p></center>
                </div>
                
                <!-- Bouton de retour avec animation -->
                <a href="{{ url()->previous() }}" 
                   class="bg-white text-indigo-600 px-5 py-3 rounded-xl font-semibold shadow-md hover:bg-indigo-50 hover:shadow-lg transform transition-all duration-300 hover:-translate-y-1 flex items-center space-x-2 pulse-glow">
                    
                    <span>Retour</span>
                </a>
            </div>
        </div>

        <!-- Section des avis -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden slide-up">
            <!-- En-tête de la liste -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Tous les avis</h3>
                    <div class="mt-2 sm:mt-0">
                        <div class="relative">
                            <select class="block w-full sm:w-auto pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md bg-white border">
                                <option>Trier par: Plus récents</option>
                                <option>Trier par: Plus anciens</option>
                                <option>Trier par: Meilleures notes</option>
                                <option>Trier par: Moins bonnes notes</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Liste des avis -->
            <div class="divide-y divide-gray-100">
                @forelse($ratings as $rating)
                    <div class="p-6 hover:bg-gray-50 transition-all duration-300 rating-item slide-up">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-3">
                            <div class="flex items-center mb-2 md:mb-0">
                                <!-- Avatar utilisateur -->
                                <div class="w-10 h-10 rounded-full avatar-gradient flex items-center justify-center text-white font-bold mr-3 shadow-sm">
                                    {{ substr($rating->user->name ?? 'U', 0, 1) }}
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-800">{{ $rating->user->name ?? 'Utilisateur supprimé' }}</span>
                                    <p class="text-xs text-gray-500">
                                        Publié le {{ $rating->created_at->format('d/m/Y à H:i') }}
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Évaluation en étoiles -->
                            <div class="flex star-rating mt-2 md:mt-0">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $rating->stars)
                                        <i class="fas fa-star text-yellow-400"></i>
                                    @else
                                        <i class="far fa-star text-gray-300"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                        
                        <!-- Commentaire (si vous ajoutez cette fonctionnalité ultérieurement) -->
                        <!--
                        <p class="text-gray-700 mt-2">
                            {{ $rating->comment ?? 'Aucun commentaire' }}
                        </p>
                        -->
                    </div>
                @empty
                    <!-- État vide avec animation -->
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-400 mb-4 pulse-glow">
                            <i class="far fa-comment-alt text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-medium text-gray-500 mb-2">Aucun avis pour le moment</h3>
                        <p class="text-gray-400">Soyez le premier à laisser votre avis !</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination avec style amélioré -->
        @if($ratings->hasPages())
            <div class="mt-8 flex justify-center slide-up">
                <div class="bg-white rounded-xl shadow-md p-4">
                    {{ $ratings->links() }}
                </div>
            </div>
        @endif
    </div>

    <script>
        // Animation d'entrée pour les éléments de la liste
        document.addEventListener('DOMContentLoaded', function() {
            // Animation des étoiles au survol
            const stars = document.querySelectorAll('.star-rating i');
            stars.forEach(star => {
                star.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.2)';
                });
                
                star.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });
            
            // Animation des éléments de la liste
            const ratingItems = document.querySelectorAll('.rating-item');
            ratingItems.forEach((item, index) => {
                item.style.animationDelay = `${0.1 + (index * 0.1)}s`;
            });
        });
    </script>
</body>
</html>