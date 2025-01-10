<script lang="ts">
    import PrimaryButton from '$components/Buttons/PrimaryButton.svelte';
    import SimpleTable from '$components/Tables/SimpleTable.svelte';
    import PageTitle from '$components/UI/PageTitle.svelte';
    import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';
    import route from '$vendor/tightenco/ziggy';
    import { router } from '@inertiajs/svelte';

    interface Props {
        projects: any[];
        children?: import('svelte').Snippet;
        auth: any;
    }

    let { projects, children, auth }: Props = $props();
</script>


<AuthenticatedLayout>
    <div class="p-8">
        <PageTitle headTitle="Projects - aterclock">
            Projects
            <PrimaryButton href={route('projects.create')}>
                Create Project
            </PrimaryButton>
        </PageTitle>
        {#if projects.length}
            <SimpleTable data={projects} title="Projects"  
                headers={[
                    { label: "Name", key: "name", route: "projects.show"},
                ]}
                actions={[
                    { label: "Edit", action: (item) => router.visit(route('projects.edit', item.id))},
                ]}
            />
        {:else}
            <p class="text-gray-600">No projects yet.</p>
            <PrimaryButton href={route('projects.create')} class="mt-4 bg-green-500 hover:bg-green-600 text-primary px-4 py-2 rounded shadow">
                Create your first project
            </PrimaryButton>
        {/if}
    </div>

    {@render children?.()}
</AuthenticatedLayout>
