<div>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-gray-800">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Subtasks</h2>
                @can('create', \App\Models\Subtask::class)
                    <a href="{{ route('subtasks.create') }}" wire:navigate
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Create Subtask
                    </a>
                @endcan
            </div>

            <div class="overflow-x-auto">
                @php($showActions = !auth()->user()->isAdmin())
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Name</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Task</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Project</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Status</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Assigned To</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Due Date</th>
                            @if ($showActions)
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                    Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($subtasks as $subtask)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $subtask->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $subtask->task->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $subtask->task->project->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @can('update', $subtask)
                                        <select wire:change="updateStatus({{ $subtask->id }}, $event.target.value)" class="px-2 py-1 text-xs font-semibold rounded-full border-0 cursor-pointer
                                                                @if($subtask->status === 'completed') bg-green-100 text-green-800
                                                                @elseif($subtask->status === 'in_progress') bg-blue-100 text-blue-800
                                                                @else bg-gray-100 text-gray-800
                                                                @endif">
                                            <option value="pending" {{ $subtask->status === 'pending' ? 'selected' : '' }}>Pending
                                            </option>
                                            <option value="in_progress" {{ $subtask->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="completed" {{ $subtask->status === 'completed' ? 'selected' : '' }}>
                                                Completed</option>
                                        </select>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                            @if($subtask->status === 'completed') bg-green-100 text-green-800
                                                            @elseif($subtask->status === 'in_progress') bg-blue-100 text-blue-800
                                                            @else bg-gray-100 text-gray-800
                                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $subtask->status)) }}
                                        </span>
                                    @endcan
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $subtask->assignedUser?->name ?? 'Unassigned' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $subtask->due_date?->format('M d, Y') ?? 'N/A' }}
                                </td>
                                @if ($showActions)
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @can('update', $subtask)
                                            <button wire:click="delete({{ $subtask->id }})" wire:confirm="Are you sure?"
                                                class="text-red-600 dark:text-red-400 hover:text-red-900">
                                                Delete
                                            </button>
                                        @endcan
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $showActions ? 7 : 6 }}"
                                    class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    No subtasks found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $subtasks->links() }}
            </div>
        </div>
    </div>
</div>