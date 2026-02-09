<div>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-gray-800">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6">
                {{ $user ? 'Edit User' : 'Create User' }}
            </h2>

            <form wire:submit.prevent="save" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text" wire:model.lazy="name"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-blue-500 focus:ring-blue-500" />
                    @error('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                    <input type="email" wire:model.lazy="email"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-blue-500 focus:ring-blue-500" />
                    @error('email')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                    <select wire:model.lazy="role"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                        <option value="admin">Admin</option>
                        <option value="supervisor">Supervisor</option>
                        <option value="head">Head</option>
                        <option value="staff">Staff</option>
                    </select>
                    @error('role')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password
                        {{ $user ? '(leave blank to keep current)' : '' }}</label>
                    <input type="password" wire:model.lazy="password"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-blue-500 focus:ring-blue-500" />
                    @error('password')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm Password</label>
                    <input type="password" wire:model.lazy="password_confirmation"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-blue-500 focus:ring-blue-500" />
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        <span class="text-lg leading-none">{{ $user ? '✎' : '+' }}</span>
                        <span>{{ $user ? 'Update User' : 'Create User' }}</span>
                    </button>
                    <a href="{{ route('users.index') }}" wire:navigate
                        class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>