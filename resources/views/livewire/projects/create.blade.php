<div>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-gray-800">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6">Create New Project</h2>

            <form wire:submit="save">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                        Project Name *
                    </label>
                    <input type="text" wire:model="name" id="name"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600 leading-tight focus:outline-none focus:shadow-outline">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                        Description
                    </label>
                    <textarea wire:model="description" id="description" rows="4"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="status" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                            Status *
                        </label>
                        <select wire:model="status" id="status"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="on_hold">On Hold</option>
                        </select>
                        @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="start_date" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                            Start Date
                        </label>
                        <input type="date" wire:model="start_date" id="start_date"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600 leading-tight focus:outline-none focus:shadow-outline">
                        @error('start_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="end_date" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                            End Date
                        </label>
                        <input type="date" wire:model="end_date" id="end_date"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600 leading-tight focus:outline-none focus:shadow-outline">
                        @error('end_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        <span class="text-lg leading-none">💾</span>
                        <span>Create Project</span>
                    </button>
                    <a href="{{ route('projects.index') }}" wire:navigate
                        class="inline-flex items-center gap-2 bg-gray-500 hover:bg-gray-700 dark:bg-gray-600 dark:hover:bg-gray-800 text-white font-bold py-2 px-4 rounded">
                        <span class="text-lg leading-none">✕</span>
                        <span>Cancel</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>