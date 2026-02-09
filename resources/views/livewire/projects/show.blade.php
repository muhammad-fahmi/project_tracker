<div>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-gray-800">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6">Project Details: {{ $project->name }}
            </h2>

            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Status</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Created By</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            {{ $project->creator->name }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Start Date</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            {{ $project->start_date?->format('M d, Y') ?? 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">End Date</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            {{ $project->end_date?->format('M d, Y') ?? 'N/A' }}
                        </p>
                    </div>
                </div>
                @if($project->description)
                    <div class="mt-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Description</p>
                        <p class="text-gray-900 dark:text-gray-100">{{ $project->description }}</p>
                    </div>
                @endif
            </div>

            <div class="mb-4">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Tasks</h3>
                @if($tasks->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Name</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Assigned To</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Due Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($tasks as $task)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $task->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                        @if($task->status === 'completed') bg-green-100 text-green-800
                                                        @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800
                                                        @else bg-gray-100 text-gray-800
                                                        @endif">
                                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $task->assignedUser?->name ?? 'Unassigned' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $task->due_date?->format('M d, Y') ?? 'N/A' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">No tasks yet.</p>
                @endif
            </div>

            <div class="mt-6">
                <a href="{{ route('projects.index') }}" wire:navigate
                    class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
                    ← Back to Projects
                </a>
            </div>
        </div>
    </div>
</div>