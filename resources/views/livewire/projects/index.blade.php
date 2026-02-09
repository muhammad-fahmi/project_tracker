<div>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Projects</h2>
                @can('create', \App\Models\Project::class)
                    <a href="{{ route('projects.create') }}" wire:navigate
                        class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        <span class="text-lg leading-none">+</span>
                        <span>Create Project</span>
                    </a>
                @endcan
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                ID</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Name</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Progress</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Creator</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($projects as $project)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <a href="{{ route('projects.show', $project) }}" wire:navigate
                                        class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
                                        #{{ $project->id }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $project->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if ($project->status === 'completed') bg-green-100 text-green-800
                                            @elseif($project->status === 'in_progress') bg-blue-100 text-blue-800
                                            @elseif($project->status === 'on_hold') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    @php
                                        $totalTasks = $project->tasks->count();
                                        $completedTasks = $project->tasks->where('status', 'completed')->count();
                                        $percentage =
                                            $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                                    @endphp
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2 w-24">
                                            <div class="bg-blue-600 h-2 rounded-full"
                                                style="width: {{ $percentage }}%">
                                            </div>
                                        </div>
                                        <span class="text-xs font-semibold">{{ $percentage }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $project->creator->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @can('manage', $project)
                                        <button wire:click="delete({{ $project->id }})"
                                            wire:confirm="Are you sure you want to delete this project?"
                                            class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 ml-2">
                                            Delete
                                        </button>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-600 text-xs">
                                            View only
                                        </span>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    No projects found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $projects->links() }}
            </div>
        </div>
    </div>
</div>
