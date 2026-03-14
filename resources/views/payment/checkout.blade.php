<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'EasyPay') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-16  flex items-center justify-center min-h-screen">
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0 w-full">
            <div class="mx-auto max-w-5xl text-center">
                
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl mb-8">Formulaire de paiement</h2>

                <div class="mt-6 sm:mt-8 lg:flex lg:items-start lg:gap-12 lg:justify-center text-left">
                    
                    <form action="{{ route('payment.recap') }}" method="POST" class="w-full rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:p-6 lg:max-w-xl lg:p-8">
                        @csrf
                        
                        <div class="mb-6 grid grid-cols-2 gap-4">
                            <div class="hidden">
                                <input type="hidden" name="ref_externe" id="ref_externe" value="{{ $ref_externe }}">
                            </div>

                            <!-- CHAMP NOM -->
                            <div 
                            x-data="{
                                nom: '{{ old('nom') }}',
                                hasError: {{ $errors->has('nom') ? 'true' : 'false' }},
                                get estValide(){
                                    return this.nom.length >= 3 && this.nom.length <= 30;
                                },
                            }"
                            class="col-span-2 sm:col-span-1">
                                <label for="nom" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Nom complet*</label>
                                <input type="text" id="firstname" name="nom" value="{{ old('nom') }}" 
                                x-model="nom"
                                x-on:input="if (estValide) {hasError=false}"
                                :class="{
                                    'border-red-500 bg-red-50 focus:ring-red-500': hasError && !estValide,
                                    'border-green-500 bg-green-50 focus:ring-green-500': estValide,
                                    'border-indigo-300 bg-gray-50 focus:ring-gray-300': !hasError && estValide,
                                }"
                                class="block w-full outline-none rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 outline-none focus:ring-1 dark:border-gray-600 dark:bg-gray-700 dark:text-white" 
                                placeholder="Atangana" />
                                <div x-show="hasError && !estValide">
                                    @error('nom') 
                                        <p class="mt-1 text-xs text-red-600">
                                            {{ $message }}
                                        </p> 
                                    @enderror
                                </div>
                            </div>

                            <!-- CHAMP PRÉNOM -->
                            <div class="col-span-2 sm:col-span-1">
                                <label for="prenom" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Prénom*</label>
                                <input type="text" id="prenom" name="prenom" value="{{ old('prenom') }}" 
                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 outline-none focus:border-indigo-700 focus:ring-1 focus:ring-indigo-700 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('prenom') border-red-500 @enderror" 
                                    placeholder="Jean" />
                                @error('prenom') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- CHAMP EMAIL -->
                            <div class="col-span-2">
                                <label for="email" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Adresse Email*</label>
                                <input type="text" id="email" name="email" value="{{ old('email') }}" 
                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 outline-none focus:border-indigo-700 focus:ring-1 focus:ring-indigo-700 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('email') border-red-500 @enderror" 
                                    placeholder="test@exemple.com" />
                                @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- CHAMP NUMÉRO -->
                            <div class="col-span-2 sm:col-span-1">
                                <label for="numero" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Numéro de téléphone*</label>
                                <div x-data="{
                                numero: '{{ old('numero') }}',
                                hasError: {{ $errors->has('numero') ? 'true' : 'false' }},

                                get estValide(){
                                    return /^6(40|5[1-9]|7[0-9]|8[0-9]|9[0-9])[0-9]{6}$/.test(this.numero)
                                },
                                get operator(){
                                    if (/^6(7[0-9]|8[0-3]|5[0-4])/.test(this.numero)) return 'Mobile Money';
                                    if (/^6(40|5[5-9]|8[6-9]|9[0-9])/.test(this.numero)) return 'Orange Money';
                                    return null;
                                }
                                }">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 font-semibold text-sm border-r border-gray-300 pr-2">
                                                +237
                                            </span>
                                        </div>
                                            <!-- CHAMP: METHODE DE PAIEMENT SYNC avec CHAMP NUMERO -->
                                            <input type="hidden" id="methode_paiement" name="methode_paiement" :value="operator">

                                        <input type="text" id="tel" name="numero" value="{{ old('numero') }}" 
                                        x-on:input="if(estValide) { hasError = false }"
                                        x-model="numero"
                                        maxlength="9"
                                        :class="{
                                            'border-red-500 bg-red-50 focus:ring-red-500': hasError && !estValide,
                                            'border-green-500 bg-green-50 focus:ring-green-500': estValide,
                                            'border-gray-300 bg-gray-50': !hasError && !estValide
                                        }"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-16 text-sm pr-12 text-gray-900 outline-none focus:ring-1 dark:border-gray-600 dark:bg-gray-700 dark:text-white " 
                                        placeholder="699009900" />
                                        <div class="absolute inset-y-0 right-3 flex items-center pl-3 pointer-events-none">
                                            <template x-if="operator === 'Orange Money'">
                                                <img class="w-8 h-8 object-contain" src="{{ asset('images/orange.png') }}" alt="Orange">
                                            </template>
                                            <template x-if="operator === 'Mobile Money'">
                                                <img class="w-8 h-8 object-contain" src="{{ asset('images/MTN.png') }}" alt="MTN">
                                            </template>
                                        </div>
                                    </div>
                                    <div x-show="hasError && !estValide" x-transition>
                                        @error('numero') 
                                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> 
                                        @enderror
                                    </div>
                                </div>
                                

                            </div>

                            <!-- CHAMP MONTANT -->
                            <div class="col-span-2 sm:col-span-1">
                                <label for="montant" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Montant*</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <span class="text-green-600 font-bold text-xs">XAF</span>
                                    </div>
                                    <input type="text" id="montant" name="montant" value="{{ old('montant') }}" 
                                    maxlength="6"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-12 text-sm text-gray-900 outline-none focus:border-indigo-700 focus:ring-1 focus:ring-indigo-700 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('montant') border-red-500 @enderror" 
                                        placeholder="3000" />
                                </div>
                                @error('montant') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- CHAMP MÉTHODE DE PAIEMENT (ancienne méthode: sélecteur. J'ai opté d'un synchro avec le numéro donc en fonction du numéro la méthode es attribuée de facon automatique) -->
                           {{-- <div class="col-span-2"> 
                                <label for="methode_paiement" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Méthode de paiement*</label>
                                <select id="methode_paiement" name="methode_paiement" 
                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 outline-none focus:border-indigo-700 focus:ring-1 focus:ring-indigo-700 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('methode_paiement') border-red-500 @enderror">
                                    <option value="">Sélectionnez un opérateur</option>
                                    <option value="Mobile Money" {{ old('methode_paiement') == 'Mobile Money' ? 'selected' : '' }}>Mobile Money (MTN)</option>
                                    <option value="Orange Money" {{ old('methode_paiement') == 'Orange Money' ? 'selected' : '' }}>Orange Money</option>
                                </select>
                                @error('methode_paiement') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>--}}

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