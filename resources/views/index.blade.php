<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnePass - Gestion des mots de passe</title>
    <link rel="stylesheet" href="{{ asset('build/assets/app.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    {{-- pour le cryptage --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>

</head>

<body class="bg-gray-100 min-h-screen">
    <header class="bg-slate-400 text-white p-4 shadow-lg">
        <div class="container mx-auto">
            <h1 class="text-2xl font-bold">OnePass</h1>
        </div>
    </header>

    <main class="container mx-auto p-4">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between my-2">
                <h2 class="text-xl font-bold mb-4">Mes mots de passe</h2>
                <button class="py-4 px-8 bg-slate-300 hover:bg-slate-200 border-0 rounded-lg" onclick="openAddModal()">
                    Ajouter Password
                </button>
            </div>

            <!-- ******************************************************************************************************************************** -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left">Service</th>
                            <th class="px-4 py-3 text-left">nom d'utilisateur</th>
                            <th class="px-4 py-3 text-left">Mot de passe</th>
                            <th class="px-4 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($passwords as $password)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <span>{{ $password->service }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">{{ $password->username }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">

                                        <span data-password="{{ $password->password }}"
                                            data-visible="false">••••••</span>
                                        <button type="button" onclick="togglePassword(this)"
                                            class="text-gray-500 hover:text-gray-700">
                                            <i class="far fa-eye"></i>
                                        </button>


                                    </div>
                                </td>


                                <td class="px-4 py-3 text-right flex">
                                    <button onclick="openEditModal()"
                                        class="edit-btn text-blue-500 hover:text-blue-600 px-2"
                                        data-id="{{ $password->id }}" data-username="{{ $password->username }}"
                                        data-service="{{ $password->service }}"
                                        data-password="{{ $password->password }}">
                                        <i class="fas fa-edit"></i>

                                    </button>
                                    <form action="{{ route('passwords.destroy', $password->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            onclick="confirm('Etes vous sur de supprimer ce password : {{ $password->service }}')"
                                            class="text-red-500 hover:text-red-600 px-2">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    {{-- ******************************************************************************************************************************** --}}
    <!-- Modal d'ajout  -->
    <div id="addModal" class="fixed inset-0 bg-gray-100 bg-opacity-50 flex items-center justify-center hidden">
        <div
            class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 transform transition-all duration-300 scale-100">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3 text-blue-600">
                        <i class="fas fa-add text-2xl"></i>
                        <h2 class="text-xl font-bold">Ajouter un mot de passe</h2>
                    </div>
                    <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <form id="passwordForm" class="space-y-6" method="POST">
                    @csrf
                    @method('POST')

                    <!-- Service -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-globe mr-2"></i>Service
                        </label>
                        <input type="text" id="service" name="service"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Ex: Gmail, Netflix, Amazon..." required>
                    </div>

                    <!-- Nom d'utilisateur -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-user mr-2"></i>Nom d'utilisateur
                        </label>
                        <input type="text" id="username" name="username"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Votre identifiant" required>
                    </div>

                    <!-- Mot de passe -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-lock mr-2"></i>Mot de passe
                        </label>
                        <div class="">
                            <input type="password" id="password"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent "
                                placeholder="Votre mot de passe" required>

                        </div>
                    </div>

                    <!-- input caché pour le mot de passe chiffré -->
                    <input type="hidden" id="encryptedPassword" name="password">

                    <!-- Boutons d'action -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <button type="submit"
                            class="flex-1 bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-check"></i>
                            <span>Enregistrer</span>
                        </button>
                        <button type="button" onclick="closeAddModal()"
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg transition-colors duration-200">
                            Annuler
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- ******************************************************************************************************************************** --}}
    <!-- Modal de modification -->
    <div class="fixed inset-0 bg-gray-100 bg-opacity-75 hidden" id="modal-backdrop"></div>
    <div class="fixed inset-0 z-10 hidden flex justify-center items-center" id="modal">
        <div
            class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 transform transition-all duration-300 scale-100">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3 text-blue-600">
                        <i class="fas fa-edit text-2xl"></i>
                        <h2 class="text-xl font-bold">Modifier le mot de passe</h2>
                    </div>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <form id="updatePasswordForm" method="POST" action="{{ route('passwords.update') }}"
                    class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="password_id" class="password_id">
                    <!-- Service -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Service</label>
                        <input type="text" name="service"
                            class="service w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Utilisateur -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom d'utilisateur</label>
                        <input type="text" name="username"
                            class="username w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Nouveau mot de passe -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
                        <div class="relative">
                            <input type="password" id="newPassword1" name="password"
                                class="password w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent pr-10">
                            <button type="button" onclick="showPassword(this)"
                                class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Champ caché pour le mot de passe chiffré -->
                    <input type="hidden" id="encryptedPasswordUpdate" name="password">

                    <!-- Boutons d'action -->
                    <button type="submit"
                        class="flex-1 bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2">
                        <i class="fas fa-check"></i>
                        <span>Enregistrer</span>
                    </button>

                </form>
            </div>
        </div>
    </div>
    {{-- ******************************************************************************************************************************** --}}

</body>

<script src="{{ asset('js/updateForm.js') }}"></script>
<script src="{{ asset('js/encryptPassword.js') }}"></script>



<script>
    function openAddModal() {
        document.getElementById('addModal').classList.remove('hidden');
    }

    function closeAddModal() {
        document.getElementById('addModal').classList.add('hidden');
    }

    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();
        closeEditModal();
    });
</script>
<script>
    // decrypt passwords : 
    const encryptionKey = "mySecretKey12345";

    function togglePassword(button) {
        let passwordSpan = button.previousElementSibling;
        let encryptedPassword = passwordSpan.getAttribute(
            "data-password");

        if (passwordSpan.dataset.visible === "false") {
            try {

                let bytes = CryptoJS.AES.decrypt(encryptedPassword, encryptionKey);
                let decryptedPassword = bytes.toString(CryptoJS.enc.Utf8);

                if (decryptedPassword) {
                    passwordSpan.textContent = decryptedPassword;
                    passwordSpan.dataset.visible = "true";
                    button.innerHTML = '<i class="far fa-eye-slash"></i>';
                } else {
                    console.error("Déchiffrement échoué : mot de passe invalide.");
                }
            } catch (error) {
                console.error("Erreur lors du déchiffrement :", error);
            }
        } else {
            passwordSpan.textContent = "••••••";
            passwordSpan.dataset.visible = "false";
            button.innerHTML = '<i class="far fa-eye"></i>';
        }
    }
</script>
<script>
    function showPassword(button) {
        let passwordInput = button.previousElementSibling;

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            button.innerHTML = '<i class="far fa-eye-slash"></i>';
        } else {
            passwordInput.type = "password";
            button.innerHTML = '<i class="far fa-eye"></i>';
        }
    }
</script>



</html>
