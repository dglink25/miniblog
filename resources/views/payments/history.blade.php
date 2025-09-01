@extends('layouts.app')

@section('content')
<h2>Historique de mes paiements</h2>

<table class="table">
    <thead>
        <tr>
            <th>Référence</th>
            <th>Montant</th>
            <th>Objet</th>
            <th>Statut</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($payments as $payment)
            <tr>
                <td>{{ $payment->reference }}</td>
                <td>{{ $payment->amount }} CFA</td>
                <td>{{ $payment->purpose }}</td>
                <td>
                    @if($payment->status === 'success')
                        ✅ Réussi
                    @elseif($payment->status === 'cancelled')
                        ❌ Annulé
                    @else
                        ⚠️ Échec
                    @endif
                </td>
                <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
