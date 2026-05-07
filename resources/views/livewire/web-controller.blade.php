<!-- web-controller.blade.php (Livewire Component) -->
<div class="fixed bottom-4 right-4 z-50">
    <div class="flex justify-end">
        <!-- Settings Content Panel -->
        <div id="settings-content" class="hidden shadow-lg z-40 absolute text-center rounded-lg bottom-12">
            <div class="border border-zinc-300 dark:border-[#404040] rounded-lg overflow-hidden bg-white dark:bg-[#1E1E1E]">
                <div class="shadow-lg rounded-lg p-4">
                    <h3 class="md:text-lg text-md font-bold mb-2 text-green-800 text-center dark:text-[#6BA17E]">
                        Display Settings</h3>
                    <div class="flex flex-col gap-2">
                        <div class="flex md:flex-row flex-col gap-3 p-4 rounded-md dark:text-white">
                            <div class="flex justify-center items-center">
                                <span class="dark:text-white w-20 text-sm md:text-md">Text size:</span>
                            </div>
                            <div class="flex space-x-2 items-center">
                                <span class="text-sm">A</span>
                                <input type="range" id="text-size" class="w-40 h-2 bg-gray-300 rounded-full appearance-none cursor-pointer" min="0" max="12" value="0" style="accent-color: gray;">
                                <span class="text-xl">A</span>
                            </div>
                        </div>
                    </div>

                    <div class="">
                        <div class="flex md:flex-row flex-col gap-3 p-2 rounded-md dark:text-white transition-all duration-300">
                            <div class="flex justify-center items-center">
                                <span class="dark:text-white w-20 text-sm md:text-md">Mode:</span>
                            </div>
                            <label class="relative inline-flex items-center justify-center cursor-pointer transition-all duration-300">
                                <input type="checkbox" id="dark-mode-toggle" class="sr-only peer"
                                       x-model="darkMode">
                                <div id="light-mode" class="flex items-center bg-white border-2 border-gray-300 rounded-full p-2 shadow-md peer-checked:hidden transition-all duration-300">
                                    <span class="flex items-center justify-center text-[#F5F21F] rounded-full w-6 h-6 mr-2 bg-green-800">
                                        <i class="fas fa-sun"></i>
                                    </span>
                                    <span class="text-green-800 font-semibold text-sm md:text-md">Light mode</span>
                                </div>
                                <div id="dark-mode" class="hidden peer-checked:flex items-center bg-[#404040] border-2 border-gray-600 rounded-full p-2 shadow-md transition-all duration-300">
                                    <span class="text-white font-semibold text-sm md:text-md">Dark mode</span>
                                    <span class="flex items-center justify-center text-green-800 rounded-full w-6 h-6 ml-2 bg-[#F5F21F]">
                                        <i class="fas fa-moon"></i>
                                    </span>
                                </div>
                            </label>
                            <div class="flex ml-auto mt-1">
                                <div id="font-reset" class="flex justify-center items-center cursor-pointer p-2 text-white rounded-full shadow-lg hover:bg-gray-600 bg-[#166534] duration-300 relative">
                                    <i class="fa-solid fa-rotate-left text-sm"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Button -->
        <div id="web-settings" class="flex justify-center items-center cursor-pointer md:p-3 p-2 text-white rounded-full shadow-lg hover:bg-green-700 bg-[#6BA17E] duration-300 relative">
            <i class="fa-solid fa-cog md:text-xl text-md"></i>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:load', function () {
        // Toggle settings panel visibility
        document.getElementById('web-settings').addEventListener('click', () => {
            document.getElementById('settings-content').classList.toggle('hidden');
        });

        // Adjust font size dynamically
        document.getElementById('text-size').addEventListener('input', function () {
            const scale = 1 + (this.value / 10);
            document.body.style.fontSize = `${scale}em`;
            localStorage.setItem('font-size', `${scale}em`); // Save font size to localStorage
        });

        // Reset font size
        document.getElementById('font-reset').addEventListener('click', () => {
            document.getElementById('text-size').value = 0;
            document.body.style.fontSize = '';
            localStorage.removeItem('font-size'); // Remove font size from localStorage
        });
    });
</script>

