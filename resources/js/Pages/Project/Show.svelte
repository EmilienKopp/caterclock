<script lang="ts">
    import MiniButton from "$components/Buttons/MiniButton.svelte";
    import AuthenticatedLayout from "$layouts/AuthenticatedLayout.svelte";
    import { fly } from "svelte/transition";
    
    
    export let project: any;
    export let auth: any;

    let tabs: string[] = ["Overview","Notes", "Tasks", "Reports"];
    let activeTab: string = "Overview";

    
</script>

<AuthenticatedLayout>
    <h1 class="w-full flex items-center justify-between">
        {project.name}
        {#if project.company}
            @<strong>{project.company.name}</strong>
        {:else if project.user_id == auth.user.id}
            <MiniButton href="/projects/{project.id}/edit">Edit</MiniButton>
        {/if}
    </h1>

    <div role="tablist" class="tabs tabs-bordered">
        {#each tabs as tab}
            <button on:click={() => activeTab = tab} role="tab" 
                class:tab-active={activeTab == tab} class="tab">
                {tab}
            </button>
        {/each}
    </div>

</AuthenticatedLayout>