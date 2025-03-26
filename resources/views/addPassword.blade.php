<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnePass - Gestion des mots de passe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="{{ asset('build/assets/app.css') }}">
    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100 min-h-screen" x-data="passwordManager()">
    <!-- En-tête -->
    <header class="bg-slate-800 text-white p-4 shadow-lg">
        <div class="container mx-auto">
            <h1 class="text-2xl font-bold">OnePass</h1>
        </div>
    </header>

    <!-- Contenu principal -->
    <main class="container mx-auto p-4">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Mes mots de passe</h2>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left">Service</th>
                            <th class="px-4 py-3 text-left">Utilisateur</th>
                            <th class="px-4 py-3 text-left">Mot de passe</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <template x-for="password in passwords" :key="password.id">
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <img :src="password.icon" :alt="password.service" class="w-6 h-6">
                                        <span x-text="password.service"></span>
                                    </div>
                                </td>
                                <td class="px-4 py-3" x-text="password.username"></td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <span x-text="password.showPassword ? password.password : '••••••••'"></span>
                                        <button @click="password.showPassword = !password.showPassword"
                                                class="text-blue-500 hover:text-blue-600">
                                            <i class="far" :class="password.showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <button @click="openEditModal(password)" class="text-blue-500 hover:text-blue-600 px-2">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button @click="openDeleteModal(password)" class="text-red-500 hover:text-red-600 px-2">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal de suppression -->
    <div x-show="isDeleteModalOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-90"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-90"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
         @keydown.escape.window="isDeleteModalOpen = false">

        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4" @click.away="isDeleteModalOpen = false">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3 text-red-600">
                        <i class="fas fa-exclamation-triangle text-2xl"></i>
                        <h2 class="text-xl font-bold">Supprimer le mot de passe</h2>
                    </div>
                    <button @click="isDeleteModalOpen = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-red-700">
                                Êtes-vous sûr de vouloir supprimer ce mot de passe ? Cette action est irréversible.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button @click="deletePassword()"
                            class="flex-1 bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg transition-colors duration-200">
                        Supprimer définitivement
                    </button>
                    <button @click="isDeleteModalOpen = false"
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg transition-colors duration-200">
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de modification -->
    <div x-show="isEditModalOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-90"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-90"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
         @keydown.escape.window="isEditModalOpen = false">

        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4" @click.away="isEditModalOpen = false">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3 text-blue-600">
                        <i class="fas fa-edit text-2xl"></i>
                        <h2 class="text-xl font-bold">Modifier le mot de passe</h2>
                    </div>
                    <button @click="isEditModalOpen = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <form @submit.prevent="savePassword()">
                    <!-- Service -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Service</label>
                        <div class="flex items-center gap-3">
                            <img :src="editingPassword.icon" :alt="editingPassword.service" class="w-6 h-6">
                            <input type="text"
                                   x-model="editingPassword.service"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50"
                                   disabled>
                        </div>
                    </div>

                    <!-- Utilisateur -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom d'utilisateur</label>
                        <input type="email"
                               x-model="editingPassword.username"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Mot de passe actuel -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe actuel</label>
                        <div class="relative">
                            <input :type="showCurrentPassword ? 'text' : 'password'"
                                   x-model="editingPassword.currentPassword"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50"
                                   disabled>
                            <button type="button"
                                    @click="showCurrentPassword = !showCurrentPassword"
                                    class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <i class="far" :class="showCurrentPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Nouveau mot de passe -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
                        <div class="relative">
                            <input :type="showNewPassword ? 'text' : 'password'"
                                   x-model="editingPassword.newPassword"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Laissez vide pour garder le même mot de passe">
                            <button type="button"
                                    @click="showNewPassword = !showNewPassword"
                                    class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <i class="far" :class="showNewPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Générateur de mot de passe -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <button type="button"
                                @click="generatePassword()"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 w-full">
                            Générer un mot de passe sécurisé
                        </button>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="submit"
                                class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition-colors duration-200">
                            Enregistrer les modifications
                        </button>
                        <button type="button"
                                @click="isEditModalOpen = false"
                                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg transition-colors duration-200">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div x-show="toast.show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed bottom-4 right-4">
        <div :class="toast.type === 'success' ? 'bg-green-500' : 'bg-red-500'"
             class="text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2">
            <i class="fas" :class="toast.type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'"></i>
            <span x-text="toast.message"></span>
        </div>
    </div>

    <script>
        function passwordManager() {
            return {
                passwords: [
                    @foreach ($passwords as $password)


                    {
                        id: $password->service,
                        service: $password->service,
                        username: $password->username,
                        password: $password->password,
                        showPassword: false
                    },
                    @endforeach
                ],
                showCurrentPassword: false,
                showNewPassword: false,
                isDeleteModalOpen: false,
                isEditModalOpen: false,
                selectedPasswordId: null,
                editingPassword: {
                    id: null,
                    service: '',
                    icon: '',
                    username: '',
                    currentPassword: '',
                    newPassword: ''
                },
                toast: {
                    show: false,
                    message: '',
                    type: 'success'
                },

                openEditModal(password) {
                    this.editingPassword = {
                        id: password.id,
                        service: password.service,
                        icon: password.icon,
                        username: password.username,
                        currentPassword: password.password,
                        newPassword: ''
                    };
                    this.isEditModalOpen = true;
                },

                openDeleteModal(password) {
                    this.selectedPasswordId = password.id;
                    this.isDeleteModalOpen = true;
                },

                savePassword() {
                    const index = this.passwords.findIndex(p => p.id === this.editingPassword.id);
                    if (index !== -1) {
                        this.passwords[index] = {
                            ...this.passwords[index],
                            username: this.editingPassword.username,
                            password: this.editingPassword.newPassword || this.editingPassword.currentPassword
                        };
                    }
                    this.isEditModalOpen = false;
                    this.showToast('Modifications enregistrées avec succès');
                },

                deletePassword() {
                    this.passwords = this.passwords.filter(p => p.id !== this.selectedPasswordId);
                    this.isDeleteModalOpen = false;
                    this.showToast('Mot de passe supprimé avec succès');
                },

                generatePassword() {
                    const length = 16;
                    const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+";
                    let password = "";

                    for (let i = 0; i < length; i++) {
                        password += charset.charAt(Math.floor(Math.random() * charset.length));
                    }

                    this.editingPassword.newPassword = password;
                    this.showNewPassword = true;
                    this.showToast('Nouveau mot de passe généré');
                },

                showToast(message, type = 'success') {
                    this.toast.message = message;
                    this.toast.type = type;
                    this.toast.show = true;
                    setTimeout(() => {
                        this.toast.show = false;
                    }, 3000);
                }
            }
        }
    </script>
</body>

</html>
