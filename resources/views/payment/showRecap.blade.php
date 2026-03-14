<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app_name', 'EasayPay')  }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-16 min-h-screen flex items-center">
        <div x-data="{loading: false, success: false }" class="mx-auto max-w-screen-xl px-4 2xl:px-0 w-full">
            <div class="mx-auto max-w-5xl">
                
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl text-center mb-6">Validation de paiement</h2>
                <div class="grow sm:mt-8 lg:mt-0 flex items-center justify-center">
                    <div x-show="!loading && !success" id="step-info" class="space-y-4 min-w- rounded-lg border border-gray-100 bg-gray-50 p-6 dark:border-gray-700 dark:bg-gray-800 w-130">
                        
                        <div class="space-y-2">
                            <dl class="flex items-center justify-between gap-4">
                                <dt class="text-base font-normal text-gray-500 dark:text-gray-400">{{ 'Nom' }}</dt>
                                <dd class="text-base font-medium text-gray-900 dark:text-white">{{ $donnees['nom'] }}</dd>
                            </dl>

                            <dl class="flex items-center justify-between gap-4">
                                <dt class="text-base font-normal text-gray-500 dark:text-gray-400">{{ 'Prénom' }}</dt>
                                <dd class="text-base font-medium text-gray-900 dark:text-white">{{ $donnees['prenom'] }}</dd>
                            </dl>

                            <dl class="flex items-center justify-between gap-4">
                                <dt class="text-base font-normal text-gray-500 dark:text-gray-400">{{ 'Email' }}</dt>
                                <dd class="text-base font-medium text-gray-900 dark:text-white">{{ $donnees['email'] }}</dd>
                            </dl>

                            <dl class="flex items-center justify-between gap-4">
                                <dt class="text-base font-normal text-gray-500 dark:text-gray-400">{{ 'Numéro' }}</dt>
                                <dd class="text-base font-medium text-gray-900 dark:text-white">{{ $donnees['numero'] }}</dd>
                            </dl>

                            <dl class="flex items-center justify-between gap-4">
                                <dt class="text-base font-normal text-gray-500 dark:text-gray-400">{{ 'Opérateur' }}</dt>
                                <dd class="text-base font-medium text-gray-900 dark:text-white">
                                            @if ($donnees['methode_paiement'] == 'Orange Money')
                                                <div class="flex items-center">
                                                    {{ "Orange" }}
                                                    <img class=" ml-4 w-8 h-8" src="{{ asset('images/orange.png') }}" alt="Orange">
                                                </div>
                                            @elseif ($donnees['methode_paiement'] == 'Mobile Money')
                                                <div class="flex items-center">    
                                                    {{ "MTN" }}
                                                    <img class=" ml-4 w-8 h-8" src="{{ asset('images/MTN.png') }}" alt="MTN">
                                                </div>
                                            @endif
                                </dd>
                            </dl>

                            <dl class="flex items-center justify-between gap-4">
                                <dt class="text-base font-normal text-gray-500 dark:text-gray-400">{{ 'Référence de produit' }}</dt>
                                <dd class="text-base font-medium text-gray-900 dark:text-white">{{ $donnees['ref_externe'] }}</dd>
                            </dl>
                        </div>

                        <dl class="flex items-center justify-between gap-4 border-t border-gray-200 pt-2 dark:border-gray-700">
                        <dt class="text-base font-bold text-gray-900 dark:text-white">{{ 'Montant' }}</dt>
                        <dd class="text-base font-bold text-green-600">{{ $donnees['montant'] }} XAF</dd>
                        </dl>
                        <form @submit.prevent="
                        loading = true;
                        fetch('{{ route('payment.process') }}', {
                            method: POST,
                            body: new FormData($event.target),
                            headers: {'X-Requested-With': 'XMLHttpRequest'}
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success){
                                //on lance le polling
                                startPolling(data.ref_externe);
                            }else{
                                loading = false;
                                alert(data.message);
                            }
                        })
                            .catch(() => {loading = false; alert('Erreur serveur'); })
                        ">
                            @csrf
                            @foreach ($donnees as $key => $value)
                                <input type="hidden" id="{{ $key }}" name="{{ $key }}" value="{{ $value }}">
                            @endforeach

                            <button type="submit" class="flex w-full items-center justify-center rounded-lg bg-green-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300">
                               Confirmer et Payer {{ $donnees['montant']}} XAF
                            </button>
                        </form>
                    </div>

                    <!-- ÉTAPE 2 : LE SPINNER (Visible uniquement quand loading est vrai) -->
                    <div x-show="loading" x-cloak class="flex flex-col items-center py-10">
                        <svg class="w-24 h-24 animate-spin fill-green-700" viewBox="0 0 24 24">
                            <!-- Ton SVG de spinner ici -->
                        </svg>
                        <p class="mt-4 text-gray-600 font-medium">Validation USSD en cours...</p>
                    </div>

                    <!-- ÉTAPE 3 : LE SUCCÈS (Visible quand success est vrai) -->
                    <div x-show="success" x-cloak class="text-center py-10 text-green-700">
                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold">Paiement Réussi !</h2>
                    </div>

                </div>
            </div>
        </div>
    </section>
</body>
</html>