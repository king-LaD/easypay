<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function showCheckout($ref_externe)
    {
    return view('payment.checkout', [
        'ref_externe' => $ref_externe
    ]);
    }

    public function showRecap(Request $request)
    {
        // 1. Validation des données saisies par l'utilisateur à "showCjeckout"
        $validated = $request->validate([
            'ref_externe' => 'required',
            'nom' => 'required|min:3|max:30',
            'prenom' => 'required|min:3|max:30',
            'email' => 'required|email',
            'montant' => 'required|numeric|min:100|max:500000',
            'numero' => ['required', 'regex:/^6(40|5[1-9]|7[0-9]|8[0-9]|9[0-9])[0-9]{6}$/'],
            'methode_paiement' => 'required',
        ], [
            
            'numero.regex' => 'Numéro Orange ou MTN invalide (9 chiffres commençant par 6).',
        ]);
        return view('payment.showRecap', ['donnees' => $validated]);
    }

    public function initiatePayment(Request $request)
    {
        // A. Récupération des données envoyées par le formulaire de validation
        $donnees = $request->all();

        // B. Initialisation de la transaction en base de données (Statut PENDING)
        $transaction = Transaction::updateOrCreate(
            ['ref_externe' => $donnees['ref_externe']],
            [
                'nom' => $donnees['nom'],
                'prenom' => $donnees['prenom'],
                'email' => $donnees['email'],
                'tel' => $donnees['numero'],
                'montant' => $donnees['montant'],
                'methode_paiement' => $donnees['methode_paiement'],
                'statut' => 'PENDING',
            ]
        );

        // C. Appel à l'API CamPay (Authentification Token)
        $auth = Http::post('https://demo.campay.net', [
            'app_id' => env('CAMPAY_APP_ID'),
            'app_secret' => env('CAMPAY_APP_SECRET'),
        ]);

        if ($auth->successful()) {
            $token = $auth->json()['token'];

            // D. Demande de collecte/Push USSD
            $collect = Http::withToken($token)->post('https://demo.campay.net', [
                'amount' => $transaction->montant,
                'currency' => 'XAF',
                'from' => $transaction->tel,
                'description' => "Paiement EasyPay - " . $transaction->ref_externe,
                'external_reference' => $transaction->ref_externe,
            ]);

            if ($collect->successful()) {
                // On enregistre l'ID CamPay pour le suivi
                $transaction->update(['campay_id' => $collect->json()['reference']]);

                // E. On renvoie du JSON pour que le JS affiche le SPINNER
                return response()->json([
                    'success' => true,
                    'ref_externe' => $transaction->ref_externe,
                    'message' => 'Veuillez confirmer sur votre téléphone.'
                ]);
            }
        }

        return response()->json(['success' => false, 'message' => 'Erreur CamPay']);
    }


}
