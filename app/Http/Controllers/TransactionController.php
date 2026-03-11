<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function showCheckout($ref_externe)
    {
    return view('payment.checkout', [
        "ref_externe" => $ref_externe
    ]);
    }

    public function showRecap(Request $request)
    {
        // 1. Validation des données saisies par l'utilisateur à "showCjeckout"
        $validated = $request->validate([
            'nom' => 'required|min:3|max:30',
            'prenom' => 'required|min:3|max:30',
            'email' => 'required|email',
            'montant' => 'required|numeric|min:100|max:500000',
            'numero' => ['required', 'numeric', 'regex:/^(64|65|66|67|68|69)[0-9]{7}$/'],
            //'methode_paiement' => 'required', 'in:Orange Money, Mobile Money',
        ], [
            'email.email' => 'L\'adresse email saisie n\'est pas valide.',
            'numero.regex' => 'Entrez un numéro à 9 chiffres valide.',
            'numero' => 'Entrez obligatoirement un numéro de téléphone'
            //'methode_paiement.in' => 'Veuillez sélectionner un opérateur.'
        ]);
        return view('payment.showRecap', ['donnees' => $validated]);
    }

}
