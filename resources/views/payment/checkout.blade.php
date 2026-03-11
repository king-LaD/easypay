<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'EasyPay') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <!-- 
    SECTION PRINCIPALE : Centrage total (Viewport height)
    - min-h-screen : occupe 100% de la hauteur de l'écran
    - flex items-center justify-center : centre le bloc au milieu
    -->
    <section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-16 min-h-screen flex items-center justify-center">
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0 w-full">
            <div class="mx-auto max-w-5xl text-center">
                
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl mb-8">Paiement</h2>

                <div class="mt-6 sm:mt-8 lg:flex lg:items-start lg:gap-12 lg:justify-center text-left">
                    <!-- 
                        FORMULAIRE DE PAIEMENT
                        - Action : Route 'payment.recap' via POST
                        - Validation : Gérée par le Controller (pas de 'required' HTML)
                    -->
                    <form action="{{ route('payment.recap') }}" method="POST" class="w-full rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:p-6 lg:max-w-xl lg:p-8">
                        @csrf
                        
                        <div class="mb-6 grid grid-cols-2 gap-4">

                            <!-- CHAMP : NOM -->
                            <div class="col-span-2 sm:col-span-1">
                                <label for="nom" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Nom complet*</label>
                                <input type="text" id="nom" name="nom" value="{{ old('nom') }}" 
                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 outline-none focus:border-green-600 focus:ring-1 focus:ring-green-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('nom') border-red-500 @enderror" 
                                    placeholder="Atangana" />
                                @error('nom') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- CHAMP : PRÉNOM -->
                            <div class="col-span-2 sm:col-span-1">
                                <label for="prenom" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Prénom*</label>
                                <input type="text" id="prenom" name="prenom" value="{{ old('prenom') }}" 
                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 outline-none focus:border-green-600 focus:ring-1 focus:ring-green-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('prenom') border-red-500 @enderror" 
                                    placeholder="Jean" />
                                @error('prenom') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- CHAMP : EMAIL -->
                            <div class="col-span-2">
                                <label for="email" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Adresse Email*</label>
                                <input type="text" id="email" name="email" value="{{ old('email') }}" 
                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 outline-none focus:border-green-600 focus:ring-1 focus:ring-green-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('email') border-red-500 @enderror" 
                                    placeholder="test@exemple.com" />
                                @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- CHAMP : NUMÉRO (AVEC INDICATIF) -->
                            <div class="col-span-2 sm:col-span-1">
                                <label for="numero" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Numéro de téléphone*</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 font-semibold text-sm border-r border-gray-300 pr-2">+237</span>
                                    </div>
                                    <input type="text" id="numero" name="numero" value="{{ old('numero') }}" 
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-16 text-sm text-gray-900 outline-none focus:border-green-600 focus:ring-1 focus:ring-green-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('numero') border-red-500 @enderror" 
                                        placeholder="699009900" />
                                </div>
                                @error('numero') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- CHAMP : MONTANT (AVEC XAF) -->
                            <div class="col-span-2 sm:col-span-1">
                                <label for="montant" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Montant*</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <span class="text-green-600 font-bold text-xs">XAF</span>
                                    </div>
                                    <input type="text" id="montant" name="montant" value="{{ old('montant') }}" 
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-12 text-sm text-gray-900 outline-none focus:border-green-600 focus:ring-1 focus:ring-green-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('montant') border-red-500 @enderror" 
                                        placeholder="3000" />
                                </div>
                                @error('montant') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- CHAMP : MÉTHODE DE PAIEMENT (SELECT) -->
                            <div class="col-span-2">
                                <label for="methode_paiement" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Méthode de paiement*</label>
                                <select id="methode_paiement" name="methode_paiement" 
                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 outline-none focus:border-green-600 focus:ring-1 focus:ring-green-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('methode_paiement') border-red-500 @enderror">
                                    <option value="">Sélectionnez un opérateur</option>
                                    <option value="Mobile Money" {{ old('methode_paiement') == 'Mobile Money' ? 'selected' : '' }}>Mobile Money (MTN)</option>
                                    <option value="Orange Money" {{ old('methode_paiement') == 'Orange Money' ? 'selected' : '' }}>Orange Money</option>
                                </select>
                                @error('methode_paiement') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                        </div>

                        <button type="submit" class="flex w-full items-center justify-center rounded-lg bg-green-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300">
                            Vérifier les informations
                        </button>
                    </form>
                </div>

                <p class="mt-6 text-center text-gray-500 dark:text-gray-400 sm:mt-8">
                    Paiement sécurisé par <a href="#" class="font-medium text-primary-700 underline dark:text-primary-500">EasyPay</a> pour <a href="#" class="font-medium text-primary-700 underline dark:text-primary-500">Groupe Commande</a>
                </p>
            </div>
        </div>
    </section>
</body>
</html>