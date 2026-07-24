<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification de Document</title>
    <style>
        body { font-family: sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-2xl mx-auto px-4 py-12">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center">
            @if($valid)
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Document Authentique</h1>
                <p class="text-gray-600 mb-8">Ce document a été généré officiellement par MediNexus</p>
                
                <div class="bg-gray-50 rounded-lg p-6 text-left mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Détails du Document</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Code Unique:</span>
                            <span class="font-mono font-bold text-blue-600">{{ $document->document_id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Type:</span>
                            <span class="font-medium text-gray-900">{{ $document->type }}</span>
                        </div>
                        @if($document->patient)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Patient:</span>
                            <span class="font-medium text-gray-900">{{ $document->patient->nom }} {{ $document->patient->prenom }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-gray-600">Généré par:</span>
                            <span class="font-medium text-gray-900">{{ $document->user->full_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Rôle:</span>
                            <span class="font-medium text-gray-900">{{ $document->user_role }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Date de génération:</span>
                            <span class="font-medium text-gray-900">{{ $document->generated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Statut:</span>
                            <span class="font-medium text-green-600">{{ $document->status === 'valid' ? 'Valide' : 'Annulé' }}</span>
                        </div>
                    </div>
                </div>

                <div class="text-sm text-gray-500">
                    <p>ℹ️ Ce document a été vérifié via son code unique MediNexus.</p>
                    <p>La vérification garantit l'authenticité et l'origine du document.</p>
                </div>
            @else
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Document Non Valide</h1>
                <p class="text-gray-600 mb-8">Ce document a été annulé ou n'est plus valide</p>
                
                <div class="bg-red-50 rounded-lg p-6 text-left mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Détails du Document</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Code Unique:</span>
                            <span class="font-mono font-bold text-red-600">{{ $document->document_id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Type:</span>
                            <span class="font-medium text-gray-900">{{ $document->type }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Statut:</span>
                            <span class="font-medium text-red-600">{{ $document->status === 'valid' ? 'Valide' : 'Annulé' }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>
</html>