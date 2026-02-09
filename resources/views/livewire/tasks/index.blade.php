<div>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-gray-800">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Tasks</h2>
                @can('create', \App\Models\Task::class)
                    <a href="{{ route('tasks.create') }}" wire:navigate
                        class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        <span class="text-lg leading-none">+</span>
                        <span>Create Task</span>
                    </a>
                @endcan
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Name</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Project</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Status</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Priority</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Progress</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Assigned To</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Due Date</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($tasks as $task)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $task->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $task->project->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @can('update', $task)
                                        <select wire:change="updateStatus({{ $task->id }}, $event.target.value)" class="px-2 py-1 text-xs font-semibold rounded-full border-0 cursor-pointer
                                                                        @if($task->status === 'completed') bg-green-100 text-green-800
                                                                        @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800
                                                                        @else bg-gray-100 text-gray-800
                                                                        @endif">
                                            <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>Pending
                                            </option>
                                            <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In
                                                Progress</option>
                                            <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>
                                                Completed</option>
                                        </select>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                                    @if($task->status === 'completed') bg-green-100 text-green-800
                                                                    @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800
                                                                    @else bg-gray-100 text-gray-800
                                                                    @endif">
                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                        </span>
                                    @endcan
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($task->priority === 'high') bg-red-100 text-red-800
                                                    @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800
                                                    @else bg-green-100 text-green-800
                                                    @endif">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    @php
                                        $totalSubtasks = $task->subtasks->count();
                                        $completedSubtasks = $task->subtasks->where('status', 'completed')->count();
                                        $percentage = $totalSubtasks > 0 ? round(($completedSubtasks / $totalSubtasks) * 100) : 0;
                                    @endphp
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2 w-24">
                                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $percentage }}%">
                                            </div>
                                        </div>
                                        <span class="text-xs font-semibold">{{ $percentage }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $task->assignedUser?->name ?? 'Unassigned' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $task->due_date?->format('M d, Y') ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @can('update', $task)
                                        <button wire:click="delete({{ $task->id }})" wire:confirm="Are you sure?"
                                            class="text-red-600 dark:text-red-400 hover:text-red-900">
                                            Delete
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    No tasks found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $tasks->links() }}
            </div>
        </div>
    </div>
</div>