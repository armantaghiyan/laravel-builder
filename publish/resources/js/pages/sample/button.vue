<script setup lang="ts">
import { ref } from 'vue';

const loading = ref(false);

function simulateLoad() {
    loading.value = true;
    setTimeout(() => (loading.value = false), 2000);
}
</script>

<template>
    <div class="p-8 space-y-10 bg-panel min-h-screen font-sans text-base text-gray-9">

        <!-- Solid variants -->
        <section class="space-y-3">
            <h2 class="text-xs font-semibold uppercase tracking-widest text-gray-3">Solid</h2>
            <div class="flex flex-wrap gap-3">
                <app-button variant="primary">Primary</app-button>
                <app-button variant="danger">Danger</app-button>
                <app-button variant="success">Success</app-button>
                <app-button variant="info">Info</app-button>
                <app-button variant="warning">Warning</app-button>
                <app-button variant="dark">Dark</app-button>
            </div>
        </section>

        <!-- Outline variants -->
        <section class="space-y-3">
            <h2 class="text-xs font-semibold uppercase tracking-widest text-gray-3">Outline</h2>
            <div class="flex flex-wrap gap-3">
                <app-button variant="primary" outline>Primary</app-button>
                <app-button variant="danger"  outline>Danger</app-button>
                <app-button variant="success" outline>Success</app-button>
                <app-button variant="info"    outline>Info</app-button>
                <app-button variant="warning" outline>Warning</app-button>
                <app-button variant="dark"    outline>Dark</app-button>
            </div>
        </section>

        <!-- Sizes -->
        <section class="space-y-3">
            <h2 class="text-xs font-semibold uppercase tracking-widest text-gray-3">Sizes</h2>
            <div class="flex flex-wrap items-center gap-3">
                <app-button variant="primary" size="sm">Small</app-button>
                <app-button variant="primary" size="md">Medium</app-button>
                <app-button variant="primary" size="lg">Large</app-button>
            </div>
        </section>

        <!-- Rounded pill -->
        <section class="space-y-3">
            <h2 class="text-xs font-semibold uppercase tracking-widest text-gray-3">Rounded (pill)</h2>
            <div class="flex flex-wrap gap-3">
                <app-button variant="primary" rounded>Primary</app-button>
                <app-button variant="success" rounded outline>Success</app-button>
                <app-button variant="danger"  rounded>Danger</app-button>
            </div>
        </section>

        <!-- With icons -->
        <section class="space-y-3">
            <h2 class="text-xs font-semibold uppercase tracking-widest text-gray-3">With Icons</h2>
            <div class="flex flex-wrap items-center gap-3">
                <!-- icon-left -->
                <app-button variant="primary">
                    <template #icon-left>
                        <!-- heroicons: plus -->
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4">
                            <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z"/>
                        </svg>
                    </template>
                    Add Item
                </app-button>

                <!-- icon-right -->
                <app-button variant="info" outline>
                    Details
                    <template #icon-right>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4">
                            <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                        </svg>
                    </template>
                </app-button>

                <!-- both icons -->
                <app-button variant="success">
                    <template #icon-left>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd"/>
                        </svg>
                    </template>
                    Confirm
                    <template #icon-right>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4">
                            <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                        </svg>
                    </template>
                </app-button>
            </div>
        </section>

        <!-- Icon-only -->
        <section class="space-y-3">
            <h2 class="text-xs font-semibold uppercase tracking-widest text-gray-3">Icon Only</h2>
            <div class="flex flex-wrap items-center gap-3">
                <app-button variant="primary" icon-only size="sm">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4">
                        <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z"/>
                    </svg>
                </app-button>
                <app-button variant="danger" icon-only>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4">
                        <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 0 0 6 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 1 0 .23 1.482l.149-.022.841 10.518A2.75 2.75 0 0 0 7.596 19h4.807a2.75 2.75 0 0 0 2.742-2.53l.841-10.52.149.023a.75.75 0 0 0 .23-1.482A41.03 41.03 0 0 0 14 4.193V3.75A2.75 2.75 0 0 0 11.25 1h-2.5ZM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4ZM8.58 7.72a.75.75 0 0 0-1.5.06l.3 7.5a.75.75 0 1 0 1.5-.06l-.3-7.5Zm4.34.06a.75.75 0 1 0-1.5-.06l-.3 7.5a.75.75 0 1 0 1.5.06l.3-7.5Z" clip-rule="evenodd"/>
                    </svg>
                </app-button>
                <app-button variant="success" icon-only rounded outline size="lg">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd"/>
                    </svg>
                </app-button>
            </div>
        </section>

        <!-- Loading state -->
        <section class="space-y-3">
            <h2 class="text-xs font-semibold uppercase tracking-widest text-gray-3">Loading</h2>
            <div class="flex flex-wrap gap-3">
                <app-button variant="primary" :loading="loading" @click="simulateLoad">
                    <template #icon-left>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4">
                            <path d="M3.105 2.288a.75.75 0 0 0-.826.95l1.414 4.926A1.5 1.5 0 0 0 5.135 9.25h6.115a.75.75 0 0 1 0 1.5H5.135a1.5 1.5 0 0 0-1.442 1.086l-1.414 4.926a.75.75 0 0 0 .826.95 28.897 28.897 0 0 0 15.293-7.154.75.75 0 0 0 0-1.115A28.897 28.897 0 0 0 3.105 2.288Z"/>
                        </svg>
                    </template>
                    {{ loading ? 'Sending…' : 'Send Request' }}
                </app-button>
                <app-button variant="danger" loading>Delete</app-button>
                <app-button variant="success" loading outline>Save</app-button>
            </div>
        </section>

        <!-- Disabled state -->
        <section class="space-y-3">
            <h2 class="text-xs font-semibold uppercase tracking-widest text-gray-3">Disabled</h2>
            <div class="flex flex-wrap gap-3">
                <app-button variant="primary" disabled>Primary</app-button>
                <app-button variant="danger"  disabled outline>Danger</app-button>
                <app-button variant="success" disabled>Success</app-button>
            </div>
        </section>

        <!-- Block -->
        <section class="space-y-3">
            <h2 class="text-xs font-semibold uppercase tracking-widest text-gray-3">Block (full width)</h2>
            <app-button variant="primary" block>
                <template #icon-left>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4">
                        <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z"/>
                    </svg>
                </template>
                Create New Project
            </app-button>
        </section>

    </div>
</template>
