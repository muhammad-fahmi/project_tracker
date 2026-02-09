<div>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-gray-800">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6">Create New Subtask</h2>

            <form wire:submit="save">
                <div class="mb-4">
                    <label for="task_id" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                        Task *
                    </label>
                    <select wire:model="task_id" id="task_id"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600">
                        <option value="">Select a task</option>
                        @foreach($tasks as $task)
                            <option value="{{ $task->id }}">{{ $task->name }} ({{ $task->project->name }})</option>
                        @endforeach
                    </select>
                    @error('task_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                        Subtask Name *
                    </label>
                    <input type="text" wire:model="name" id="name"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                        Description
                    </label>
                    <textarea wire:model="description" id="description" rows="4"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600"></textarea>
                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="status" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                            Status *
                        </label>
                        <select wire:model="status" id="status"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600">
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                        @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="assigned_to" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                            Assign To
                        </label>
                        <select wire:model="assigned_to" id="assigned_to"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600">
                            <option value="">Select user</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('assigned_to') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="due_date" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                            Due Date
                        </label>
                        <input type="date" wire:model="due_date" id="due_date"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600">
                        @error('due_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Create Subtask
                    </button>
                    <a href="{{ route('subtasks.index') }}" wire:navigate
                        class="text-gray-600 dark:text-gray-400 hover:text-gray-900">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>