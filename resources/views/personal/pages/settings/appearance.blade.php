<x-personal::settings-layout title="Pengaturan Tampilan">

    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 border-b pb-4 dark:border-gray-700">
            Pengaturan Tampilan
        </h2>

        {{-- Alpine scope untuk tema --}}
        <div x-data="{
                theme: localStorage.getItem('theme') || 'default',

                applyTheme(selectedTheme) {
                    console.log('Applying theme:', selectedTheme);

                    if (selectedTheme === 'dark') {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                    } else if (selectedTheme === 'light') {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                    } else {
                        localStorage.removeItem('theme');
                        if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }
                    }
                    this.theme = selectedTheme;
                },

                watchSystemTheme() {
                    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
                        if (this.theme === 'default') {
                            console.log('System theme changed, applying default...');
                            if (event.matches) {
                                document.documentElement.classList.add('dark');
                            } else {
                                document.documentElement.classList.remove('dark');
                            }
                        }
                    });
                }
             }"
             x-init="applyTheme(theme); watchSystemTheme()">

            <div class="space-y-4">
                <div>
                    <label for="theme_select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Pilih Tema
                    </label>
                    <select id="theme_select"
                            x-model="theme"
                            @change="applyTheme($event.target.value)"
                            class="block w-full max-w-xs px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                        <option value="light">Light</option>
                        <option value="dark">Dark</option>
                        <option value="default">Default Sistem</option>
                    </select>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Pilih tema tampilan aplikasi. "Default Sistem" akan mengikuti pengaturan perangkat Anda.
                    </p>
                </div>

                {{-- Preview tema (opsional) --}}
                <div class="mt-6 p-4 border border-gray-300 dark:border-gray-600 rounded-md">
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        <strong>Preview:</strong> Tema saat ini - 
                        <span x-text="theme === 'default' ? 'Default Sistem' : (theme === 'dark' ? 'Dark' : 'Light')" 
                              class="font-semibold text-blue-600 dark:text-blue-400"></span>
                    </p>
                </div>
            </div>
        </div>
    </div>

</x-personal::settings-layout>