<script lang="ts">
    export let entries: any;
    export let user: any;

    console.log(user);
</script>

<h1 class="p-3 font-bold my-2">DiaryEntries</h1>
<ul>
    {#each entries ?? [] as entry}
        <li>
            {entry.title}
        </li>
    {/each}
</ul>