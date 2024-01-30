<script lang="ts">
    import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';
    import PrimaryButton from '$components/Buttons/PrimaryButton.svelte';
    import PageTitle from '$components/PageTitle.svelte';
    import route from '$vendor/tightenco/ziggy';

    export let errors: any, auth: any, ziggy: any;
    export let projects: any[];

</script>


<AuthenticatedLayout>
    <div>
        <PageTitle headTitle="Projects - FleaClock">
            Projects
            <PrimaryButton href={route('projects.create')}>
                Create Project
            </PrimaryButton>
        </PageTitle>
        {#if projects.length}
        <ul class="text-xl space-y-4 my-12">
            {#each projects as project}
            <li>
                <a href="/projects/{project.id}">{project.name}</a>
            </li>
            {/each}
        </ul>
        {:else}
            <p>No projects yet.</p>
            <PrimaryButton href={route('projects.create')}>
                Create your first project
            </PrimaryButton>
        {/if}
    </div>

    <slot />

</AuthenticatedLayout>