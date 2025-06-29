<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Informations du profil</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Prénom</label>
                    <p class="mt-1 text-sm text-gray-900">{{ auth()->user()->firstName }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nom</label>
                    <p class="mt-1 text-sm text-gray-900">{{ auth()->user()->lastName }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <p class="mt-1 text-sm text-gray-900">{{ auth()->user()->email }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Téléphone 1</label>
                    <p class="mt-1 text-sm text-gray-900">{{ auth()->user()->tel1 ?? 'Non renseigné' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Téléphone 2</label>
                    <p class="mt-1 text-sm text-gray-900">{{ auth()->user()->tel2 ?? 'Non renseigné' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Type d'utilisateur</label>
                    <p class="mt-1 text-sm text-gray-900">{{ auth()->user()->userType->title ?? 'Non défini' }}</p>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page> 